<?php
/**
 * @/core/lib/ajax-handle.php
 * on: 23.06.2015
 * Object to get tag values and tag attributes with different methods. Return values are in array()
 * with numeric indexes.
 *
 * 4 step process:
 * 1. Start timing. Initiate objects, header check and body download.
 * 2. Fetch all required tags for content and attributes. Analysis of tags.
 * 3. Generate text content and keywords.
 * 4. Get social media counts.
 * 5. Define result array without score and time.
 * 6. Calculate score. Stop timing. Reconfigure the result with time and score.
 * 7. Save result as an array. Output the result as a Json object.
 */

//Before time starts
$time_start = microtime(true);

//Call the custom function library in. Independent objects
require_once( 'lib/url-object.php' );
require_once( 'lib/error-object.php' );
require_once( 'lib/tag-object.php' );
require_once( 'lib/social-object.php' );
require_once( 'lib/text-object.php' );
require_once( 'lib/keywords-object.php' );
require_once( 'lib/score-object.php' );

//Dependent objects. Must be placed in sequesnce.
require_once( 'lib/link-object.php' );
require_once( 'lib/image-object.php' );





//Check if it's a valid request if it contains url and post id. Post id won't be used in data
//analysis process. It will be delivered to json.
if ( ! isset( $_POST['url'] ) or ! isset( $_POST['id'] ) ) {
	echo json_encode( array( 'ping' => 'false', 'val' => __( 'Invalid Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
	wp_die();
}


//Analyze url to get the domain name.
$url = esc_url_raw( $_POST['url'] );
$cut_url = new CGSS_URL( $url, false );
$domain = $cut_url->domain();
$ssl = $cut_url->get_ssl();
$dynamic = $cut_url->dynamic();
$underscore = $cut_url->underscore();


//Declare post id, make sure it is or it becomes an integer.
$post_id = intval( $_POST['id'] );


//Analyze the header type and get basic data.
$header = get_headers( $url, 1 );
$hresponse = $header[0];


//Check if headers are ok or not
$err = new CGSS_HEADERS_ERROR( $hresponse );
$head_check = $err->check();
if ( $head_check ) {
	echo json_encode( array( 'ping' => 'error',	'val' => __( 'Got from Server', 'cgss' ) . ': ' . $hresponse ), JSON_FORCE_OBJECT );
	wp_die();
}


// Fetch the webpage content and get markup errors too. Not using CURL function library of php,
// because it may or may not be supported in each server. Also calculate download time.
$down_time_start = microtime(true);
$body = file_get_contents( $url, FILE_USE_INCLUDE_PATH );
$down_time_end = microtime(true);


//Stop execution if body can't be downloaded
if ( ! $body ) {
	echo json_encode( array( 'ping' => 'nobody', 'val' => __( 'Can not Download the Webpage', 'cgss' ) ), JSON_FORCE_OBJECT );
	wp_die();
}


//Get size of body in kb
$body_size = mb_strlen( $body, '8bit' );


//Create document object model
$dom = new DOMDocument;
libxml_use_internal_errors( true );
$dom->loadHTML( $body );


//Create an array with error line and value.
$errors = array();
$libxml_errs = libxml_get_errors();
if ( $libxml_errs ) {
	foreach ( $libxml_errs as $val ) {
		$err_line_val = $val->line;
		$err_msg_val = $val->message;
		$err_line = intval( $err_line_val );
		$err_msg = sanitize_text_field( $err_msg_val );
		$errors[] = $err_line . ': ' . $err_msg;
		libxml_clear_errors();
	}
}
$error_count = count( $errors );




//Get title tag value
$title = new CGSS_FETCH( $dom, 'title', null, null );
$title_val = $title->tag();
$last_title = $title_val[count($title_val) - 1];
$last_title_val = sanitize_text_field( $last_title );


//Get description meta tag
$description = new CGSS_FETCH( $dom, 'meta', array( 'att' => 'name',
													'val' => 'description',
													'get_att' => 'content' ), null );
$desc_val = $description->tag();
$last_desc = $desc_val[count($desc_val) - 1];
$last_desc_val = sanitize_text_field( $last_desc );


//Get robots meta tag value
$mrobo = new CGSS_FETCH( $dom, 'meta', array( 'att' => 'name',
												'val' => 'robots',
												'get_att' => 'content' ), null );
$meta_robot = $mrobo->tag();
$last_meta_robot = $meta_robot[count($meta_robot) - 1];
$meta_robot_val = sanitize_text_field( $last_meta_robot );


//Get Canonical link tag
$canonical_link = new CGSS_FETCH( $dom, 'link', array( 'att' => 'rel',
													'val' => 'canonical',
													'get_att' => 'href' ), null );
$canonical = $canonical_link->tag();
$Canonical_last = $canonical[count($canonical) - 1];
$Canonical_val = esc_url_raw( $Canonical_last );


//Get meta viewport meta tag
$vport = new CGSS_FETCH( $dom, 'meta', array( 'att' => 'name',
												'val' => 'viewport',
												'get_att' => 'content' ), null );
$vport_arr = $vport->tag();
$vport_val = $vport_arr[count($vport_arr) - 1];
$viewport = sanitize_text_field( $vport_val );


//There are lots of social tags including social media and twitter v card.
$social_tags = array(
			array(
				'att' => 'property',
				'val' => 'og:title',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:description',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:url',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:image',
				'get_att' => 'content',
			),
        );
$stag = array();
foreach( $social_tags as $spec ) {
	$get_stag = new CGSS_FETCH( $dom, 'meta', $spec, null );
	$key = $spec['val'];
	$stag[$key] = $get_stag->tag();
}
$stag_og_title = $stag['og:title'];
$stag_og_desc = $stag['og:description'];
$stag_og_url = $stag['og:url'];
$stag_og_img = $stag['og:image'];
$og_title_val = $stag_og_title[count($stag_og_title) - 1];
$og_desc_val = $stag_og_desc[count($stag_og_desc) - 1];
$og_url_val = $stag_og_url[count($stag_og_url) - 1];
$og_img_val = $stag_og_img[count($stag_og_img) - 1];
$og_title = sanitize_text_field( $og_title_val );
$og_desc = sanitize_text_field( $og_desc_val );
$og_url = esc_url_raw( $og_url_val );
$og_img = esc_url_raw( $og_img_val );


//Links analysis. Get internal, nofollow and no-text links
$link_fetch = new CGSS_FETCH( $dom, 'a', null, array( 'rel', 'href' ) );
$link_atts = $link_fetch->atts();
$link_anchors_raw = $link_fetch->tag();
$links = new CGSS_FORMAT_LINKS( $domain, $link_atts, $link_anchors_raw );
$link_num = $links->count();
$link_int = $links->internal();
$link_ext = $link_num - $link_int;
$link_nofollow = $links->nofollow();
$link_no_text = $links->no_text();
$anchs = $links->anchors();
$link_anchors = array();
if ( ! empty( $anchs ) ) {
	foreach( $anchs as $val ) {
		$link_anchors[] = sanitize_text_field( $val );
	}
}


//Fetch images with all attributes: src, width, height and alt. Define proper format to export them.
$images_fetch = new CGSS_FETCH( $dom, 'img', null, array( 'src', 'width', 'height', 'alt' ) );
$images = new CGSS_FORMAT_IMAGES( $images_fetch->atts(), $ssl );
$get_image = $images->output();
$image_output = array();
if ( ! empty( $get_image ) ) {
	foreach( $get_image as $val ) {
		$pure_src = esc_url_raw( $val['src'] );
		$pure_width = intval( $val['width'] );
		$pure_height = intval( $val['height'] );
		$pure_alt = sanitize_text_field( $val['alt'] );
		$image_output[] = array(
								'src' => $pure_src,
								'width' => $pure_width,
								'height' => $pure_height,
								'alt' => $pure_alt,
							);
	}
}
$count_img = count( $image_output );


//Iframe fetch
$iframe = new CGSS_FETCH( $dom, 'iframe', array( 'att' => null,
											'val' => null,
											'get_att' => 'src' ), null );
$get_iframe = $iframe->tag();
$iframe_output = array();
if ( ! empty( $get_iframe ) ) {
	foreach( $get_iframe as $val ) {
		$iframe_output[] = esc_url_raw( $val );
	}
}


//Get css and js calls, required to load document. Can't use same function to extract js simillar to
//css, because not all webpages have script attribute like: type='text/javascript'
$css = new CGSS_FETCH( $dom, 'link', array( 'att' => 'rel',
											'val' => 'stylesheet',
											'get_att' => 'href' ), null );
$css_list = $css->tag();
if ( is_array( $css_list ) ) {
	$css_num = count( array_values( array_filter( $css_list ) ) );
} else {
	$css_num = 0;
}


$script = new CGSS_FETCH( $dom, 'script', null, array( 'src' ) );
//Must place following line of code before using CGSS_TEXT_TREATMENT object. Otherwise it'll break.
//Because, that class strips all style and script tags from the content.
$script_atts = $script->atts();
$script_num = count( array_values( array_filter( $script_atts['src'] ) ) );


//Get content tags for tag analysis, nested tables and heading tags. Get all tags from the body
$all_tags = array( 'a', 'abbr', 'acronym', 'address', 'applet', 'area', 'b', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blockquote', 'blink', 'body', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'dd', 'dfn', 'del', 'dir', 'dl', 'div', 'dt', 'embed', 'em', 'fieldset', 'font', 'from', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'img', 'input', 'ins', 'isindex', 'i', 'kbd', 'label', 'legend', 'li', 'link', 'marquee', 'menu', 'meta', 'noframe', 'noscript', 'optgroup', 'option', 'ol', 'p', 'pre', 'q', 's', 'samp', 'script', 'select', 'small', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'td', 'th', 'tr', 'tbody', 'textarea', 'tfoot', 'thead', 'title', 'tt', 'u', 'ul', 'var' );
$tg_collect = array();
foreach( $all_tags as $val ) {
	$tagstyle = new CGSS_FETCH( $dom, $val, array(
												'att' => null,
												'val' => null,
												'get_att' => 'style', ), null );
	if ( $tagstyle->tag() ) {
		$tg_collect[] = $val;
	}
}
if ( $tg_collect ) {
	$tg_count = array_count_values( $tg_collect );
} else {
	$tg_count = 0;
}


//Heading tags fetch
$all_headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$headings = array();
foreach ( $all_headings as $key ) {
	$head = new CGSS_FETCH( $dom, $key, null, null );
	$htags = array();
	if ( $head->tag() ) {
		foreach ( $head->tag() as $val ) {
			$htags[] = sanitize_text_field( $val );
		}
	}
	$headings[$key] = $htags;
}


//Fetch tables and check for nested tables. No special class is being used, because of small code.
$table_obj = $dom->getElementsByTagName( 'table' );
$nest_table = false;
if ( $table_obj ) {
	foreach ( $table_obj as $obj ) {
		$nest_table_val = $obj->getElementsByTagName( 'table' );
		if ( $nest_table_val ) {
			$nest_table = 1;
			break;
		}
	}
}
if ( $nest_table != 1 ) {
	$nest_table = 0;
}





//Get all text using xpath technique. Then get size in kb. Compare text and html.
$text = new CGSS_TEXT_TREATMENT( $dom, $body_size );
$words = $text->words();
$size = $text->size();
$text_size = round( ( $size / 1024 ), 1 );
$ratio = $text->ratio();
$words_count = count( $words );


//keywords analysis
$words_input = $text->words();
if ( $words_input ) {
	$keys = new CGSS_KEYWORDS( $words_input );
	$keys_out = $keys->output();
} else {
	$keys_out = array( 1 => array( 0 => array() ) );
}




//social media counts
$social = new CGSS_SOCIAL( $url );
$gplus_count = $social->gplus();
$twitter_count = $social->twitter();
$facebook = $social->fb();
$fb_share_count = $facebook['share'];
$fb_like_count = $facebook['like'];
$gplus = intval( $gplus_count );
$twitter = intval( $twitter_count );
$fb_share = intval( $fb_share_count );
$fb_like = intval( $fb_like_count );






//Define ultimate result
$down_time = round( ( $down_time_end - $down_time_start ), 0 );
$result = array(
	'ping' => 'valid',
	'id' => $post_id,
	'score' => null,
	'marks' => null,
	'time' => null,
	'time_now' => null,
	'over' => array(
				'url_prop' => array(
								'ssl' => $ssl,
								'dynamic' => $dynamic,
								'underscore' => $underscore,
								),
				'social' => array(
							'num' => $gplus + $twitter + $fb_share,
							'gplus' => $gplus,
							'twitter' => $twitter,
							'fb_share' => $fb_share,
							'fb_like' => $fb_like,
				),
				'cano' => $Canonical_val,
				'meta_robot' => $meta_robot_val,
	),
	'snip' => array(
				'title' => $last_title_val,
				'url' => $url,
				'desc' => $last_desc_val,
	),
	'text' => array(
				'count' => $words_count,
				'size' => $text_size,
				'ratio' => $ratio,
				'links' => array(
							'num' => $link_num,
							'nofollow' => $link_nofollow,
							'external' => $link_ext,
							'no_text' => $link_no_text,
							'anchors' => $link_anchors,
				),
				'htags' => $headings,
				'keys' => $keys_out,
	),
	'media' => array(
				'iframe' => $iframe_output,
				'image' => $image_output,
	),
	'usb' => array(
				'down_time' => $down_time,
				'nested_table' => $nest_table,
				'tag_style' => $tg_count,
				'code_errors' => array(
									'num' => $error_count,
									'val' => $errors,
				),
				'http_req' => array(
								'num' => $css_num + $script_num + $count_img,
								'css' => $css_num,
								'js' => $script_num,
								'img' => $count_img,
				),
				'vport' => $viewport,
				'social_tags' => array(
									'title' => $og_title,
									'desc' => $og_desc,
									'url' => $og_url,
									'img' => $og_img,
				),
	),
);





//Analysis of score
$score = new CGSS_SCORE( $result );
$marks = $score->calculate();
$exact = $score->exact();

//Before end of time
$time_end = microtime(true);

//Create new part of result, and add it to basic result
$add_result = array(
	'score' => $marks,
	'marks' => $exact,
	'time' => round( ( $time_end - $time_start ), 0 ),
);
$output_result = array_replace( $result, $add_result );

//Export result to front end.
echo json_encode( $output_result, JSON_FORCE_OBJECT ); ?>
