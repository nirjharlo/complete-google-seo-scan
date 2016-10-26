<?php
/**
 * @/core/ajax-handle.php
 * on: 23.06.2015
 * @since 2.0
 *
 * Ajax call handle for main seo scan.
 *
 * 8 step process flow:
 * 1. INITIATE
 * 2. CHECKS
 * 3. DOWNLOAD & BUILD
 * 4. DATA FETCH
 * 5. TEXT TREATMENT
 * 6. SOCIAL COUNTING
 * 7. RESULTS
 * 8. SCORE & OUTPUT
 */

//Following DocBlock line is for development purpose only. Don't touch it.
//include_once( $_SERVER['DOCUMENT_ROOT'] . '/gretel/wp-config.php' );





/**
 *
 * INITIATE
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
require_once( 'lib/social-tags-object.php' );
require_once( 'lib/design-object.php' );





/**
 *
 * CHECKS
 */

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
if ( ! is_string( $_POST['id'] ) ) {
	$post_id = intval( $_POST['id'] );
} else {
	$post_id = $_POST['id'];
}

//Analyze the header type and get basic data.
$res_time_start = microtime(true);
$header = get_headers( $url, 1 );
$res_time_end = microtime(true);
$hresponse = $header[0];

//Check if headers are ok or not
$err = new CGSS_HEADERS_ERROR( $hresponse );
$head_check = $err->check();
if ( $head_check ) {
	echo json_encode( array( 'ping' => 'false',	'val' => __( 'Server Issue', 'cgss' ) . ': ' . $hresponse ), JSON_FORCE_OBJECT );
	wp_die();
}





/**
 *
 * DOWNLOAD & BUILD
 */

// Fetch the webpage content and get markup errors too. Not using CURL function library of php,
// because it may or may not be supported in each server. Also calculate download time.
$down_time_start = microtime(true);
$body = file_get_contents( $url, FILE_USE_INCLUDE_PATH );
$down_time_end = microtime(true);

//Stop execution if body can't be downloaded
if ( ! $body ) {
	echo json_encode( array( 'ping' => 'false', 'val' => __( 'Can not Download the Webpage', 'cgss' ) ), JSON_FORCE_OBJECT );
	wp_die();
}
//Get size of body in kb
$body_size = mb_strlen( $body, '8bit' );

//Create document object model
$dom = new DOMDocument;
libxml_use_internal_errors( true );
$dom->loadHTML( $body );





/**
 *
 * DATA FETCH
 */

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
if ( $meta_robot ) {
	$last_meta_robot = $meta_robot[count($meta_robot) - 1];
	$meta_robot_val = sanitize_text_field( $last_meta_robot );
	$meta_robot_ok = 1;
} else {
	$meta_robot_val = false;
	$meta_robot_ok = 0;
}

//Get Canonical link tag
$canonical_link = new CGSS_FETCH( $dom, 'link', array( 'att' => 'rel',
													'val' => 'canonical',
													'get_att' => 'href' ), null );
$canonical = $canonical_link->tag();
$Canonical_last = $canonical[count($canonical) - 1];
$Canonical_val = esc_url_raw( $Canonical_last );
$cano_show = 0;
if ( $Canonical_val and $Canonical_val != '' ) {
	$cano_show = 1;
}

//Get meta viewport meta tag
$vport = new CGSS_FETCH( $dom, 'meta', array( 'att' => 'name',
												'val' => 'viewport',
												'get_att' => 'content' ), null );
$vport_arr = $vport->tag();
$viewport_val = $vport_arr[count($vport_arr) - 1];
if ( $viewport_val ) {
	$viewport = 1;
} else {
	$viewport = 0;
}

//There are lots of social tags including social media and twitter v card.
$social_tags = new CGSS_SOCIAL_TAGS( $dom );
$ogp = $social_tags->ogp();

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
$link_anchors_content = implode( ' ', $link_anchors );

//Fetch images with all attributes: src, width, height and alt. Define proper format to export them.
$images_fetch = new CGSS_FETCH( $dom, 'img', null, array( 'src', 'alt' ) );
$images = new CGSS_FORMAT_IMAGES( $images_fetch->atts(), $ssl );
$get_image = $images->output();
$image_output = array();
$alt_collect = array();
$no_alt_src_collect = array();
if ( ! empty( $get_image ) ) {
	foreach( $get_image as $val ) {
		$pure_src = esc_url_raw( $val['src'] );
		$pure_alt = sanitize_text_field( $val['alt'] );
		if ( $pure_alt and strlen( $pure_alt ) > 0 ) {
			$alt_collect[] = $pure_alt;
		} else {
			$no_alt_src_collect[] = $pure_src;
		}
	}
	$alt_collect = implode( ' ', $alt_collect );
	$no_alt_src_collect = implode( ', ', $no_alt_src_collect );
}
if ( strlen( $alt_collect ) == 0 ) {
	$alt_collect = '';
}
if ( strlen( $no_alt_src_collect ) == 0 ) {
	$no_alt_src_collect = '';
}
$count_img = count( $get_image );

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
if ( ! $css_list and ! is_array( $css_list ) ) {
	$css_list = false;
}

$script = new CGSS_FETCH( $dom, 'script', null, array( 'src' ) );
//Must place following line of code before using CGSS_TEXT_TREATMENT object. Otherwise it'll break.
//Because, that class strips all style and script tags from the content.
$script_atts = $script->atts();
if ( ! $script_atts['src'] or ! is_array( $script_atts['src'] ) ) {
	$script_atts['src'] = false;
}

//Get content tags for tag analysis, nested tables and heading tags. Get all tags from the body
$all_tags = array( 'a', 'abbr', 'acronym', 'address', 'applet', 'area', 'b', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blockquote', 'blink', 'body', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'dd', 'dfn', 'del', 'dir', 'dl', 'div', 'dt', 'embed', 'em', 'fieldset', 'font', 'from', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'iframe', 'img', 'input', 'ins', 'isindex', 'i', 'kbd', 'label', 'legend', 'li', 'link', 'marquee', 'menu', 'meta', 'noframe', 'noscript', 'optgroup', 'option', 'ol', 'p', 'pre', 'q', 's', 'samp', 'script', 'select', 'small', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'td', 'th', 'tr', 'tbody', 'textarea', 'tfoot', 'thead', 'title', 'tt', 'u', 'ul', 'var' );
$tg_collect_val = array();
foreach( $all_tags as $val ) {
	$tagstyle = new CGSS_FETCH( $dom, $val, array(
												'att' => null,
												'val' => null,
												'get_att' => 'style', ), null );
	if ( $tagstyle->tag() ) {
		$tg_collect_val[] = $val;
	}
}
if ( ! empty( $tg_collect_val ) ) {
	$tg_count = array_count_values( $tg_collect_val );
	$tg_collect = implode( ', ', $tg_collect_val );
} else {
	$tg_count = 0;
	$tg_collect = false;
}

//Heading tags fetch
$all_headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$headings = array();
foreach ( $all_headings as $key ) {
	$head = new CGSS_FETCH( $dom, $key, null, null );
	$head_tag = $head->tag();
	$htags = '';
	if ( $head_tag ) {
		foreach ( $head_tag as $val ) {
			$htags .= ' ' . sanitize_text_field( $val );
		}
	}
	$headings[$key] = trim( $htags );
}
$headings = array_filter( $headings );
$headings_tags = array_keys( $headings );
$headings_content = implode( ' ', $headings );

//Fetch tables and check for nested tables. No special class is being used, because of small code.
$table_obj = $dom->getElementsByTagName( 'table' );
$nest_table = 0;
if ( $table_obj ) {
	foreach ( $table_obj as $obj ) {
		$nest_table_val = $obj->getElementsByTagName( 'table' );
		if ( $nest_table_val ) {
			$nest_table += 1;
		}
	}
}





/**
 *
 * TEXT TREATMENT
 */

//Get all text using xpath technique. Then get size in kb. Compare text and html.
$text = new CGSS_TEXT_TREATMENT( $dom, $body_size );
$words = $text->words();
$size = $text->size();
$text_size = round( ( $size / 1024 ), 1 );
$ratio = $text->ratio();
$words_count = count( $words );


//keywords analysis
$text_content = $text->text();
$words_input = $text->words();
if ( $text_content and $words_input ) {
	$keys = new CGSS_KEYWORDS( $words_input, $text_content );
	$keys_out = $keys->output();
} else {
	$keys_out = false;
}

//FInd out top keys
$top_key_arr = array_keys( $keys_out );
foreach ( $top_key_arr as $key ) {
	if ( substr_count( $last_title_val, $key ) > 0 or substr_count( $last_desc_val, $key ) > 0 or substr_count( $url, $key ) > 0 or substr_count( $url, implode( '-', explode( ' ', $key ) ) ) > 0 ) {
		$top_key = $key;
		break;
	}
}
if ( ! $top_key ) {
	if ( $top_key_arr and array_key_exists( 0, $top_key_arr ) ) {
		$top_key = $top_key_arr[0];
	}
}
if ( ! $top_key ) {
	$top_key = false;
}




/**
 *
 * SOCIAL COUNTING
 */

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





//get server results, if not for competative
if ( $post_id and $post_id != ' ' ) {
	$server_data = new CGSS_GET_SERVER();
	$server = $server_data->fetch();
} else {
	$server = array(
					'www' => false,
					'ip' => false,
					'gzip' => false,
					'cache' => false,
					'if_mod' => false,
					'time_val' => false,
				);
}

$www = $server['www'];
if ( array_key_exists( 'ip', $server ) ) {
	$ip = 1;
	$ip_addr = $server['ip'];
} else {
	$ip = 0;
	$ip_addr = false;
}
$gzip = $server['gzip'];
$cache = $server['cache'];
$if_mod = $server['if_mod'];
$res_time = $server['time_val'];


//get design results, if not for competative
if ( $post_id and $post_id != ' ' ) {
	$design_data = new CGSS_GET_DESIGN();
	$design = $design_data->fetch();
} else {
	$design = array(
					'css_num' => false,
					'js_num' => false,
					'css_import' => false,
					'css_size' => false,
					'js_size' => false,
					'css_media' => false,
					'css_compress_num' => false,
					'js_compress_num' => false,
					'css_compress_size' => false,
					'js_compress_size' => false,
				);
}

$css_num = $design['css_num'];
$js_num = $design['js_num'];
$css_import = $design['css_import'];
$css_size = $design['css_size'];
$js_size = $design['js_size'];
$css_media_num = $design['css_media'];
$css_compress_num = $design['css_compress_num'];
$js_compress_num = $design['js_compress_num'];
$css_compress_size = $design['css_compress_size'];
$js_compress_size = $design['js_compress_size'];
if ( $css_media_num and $css_media_num > 0 ) {
	$css_media = 1;
} else {
	$css_media = 0;
}


//Define ultimate result
$down_time = round( ( $down_time_end - $down_time_start ), 3 ) * 1000;
$res_time = round( ( $res_time_end - $res_time_start ), 3 ) * 1000;


/**
 *
 * RESULTS
 */

$result = array(
	'ping' => 'valid',
	'id' => $post_id,
	'domain' => $domain,
	'score' => null,
	'marks' => null,
	'time' => null,
	'time_now' => null,
	'snip' => array(
				'title' => $last_title_val,
				'url' => $url,
				'desc' => $last_desc_val,
	),
	'social' => array(
					'num' => $gplus + $twitter + $fb_share,
					'gplus' => $gplus,
					'twitter' => $twitter,
					'fb_share' => $fb_share,
					'fb_like' => $fb_like,
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
								'anchors' => $link_anchors_content,
							),
				'htags' => array(
								'names' => $headings_tags,
								'content' => $headings_content,
							),
				'keys' => $keys_out,
				'top_key' => $top_key,
	),
	'design' => array(
					'iframe' => $iframe_output,
					'image' => array(
									'count' => $count_img,
									'alt' => $alt_collect,
									'no_alt_src' => $no_alt_src_collect,
								),
					'nested_table' => $nest_table,
					'tag_style' => array(
										'num' => $tg_count,
										'list' => $tg_collect,
									),
					'vport' => $viewport,
					'media' => array(
									'ok' => $css_media,
									'num' => $css_media_num,
								),
				),
	'crawl' => array(
					'val' => $url,
					'ssl' => $ssl,
					'dynamic' => $dynamic,
					'underscore' => $underscore,
					'ip' => array(
								'ok' => $ip,
								'val' => $ip_addr,
							),
					'www' => $www,
					'cano' => $cano_show,
					'if_mod' => $if_mod,
					'meta_robot' => array(
										'ok' => $meta_robot_ok,
										'val' => $meta_robot_val,
									),
				),
	'speed' => array(
					'res_time' => $res_time,
					'down_time' => $down_time,
					'gzip' => $gzip,
					'cache' => $cache,
					'css' => array(
								'num' => $css_num + $css_import,
								'size' => $css_size,
							),
					'js' => array(
								'num' => $js_num,
								'size' => $js_size,
							),
					'comp' => array(
									'css' => array(
												'num' => $css_compress_num,
												'size' => $css_compress_size,
												),
									'js' => array(
												'num' => $js_compress_num,
												'size' => $js_compress_size,
												),
								),
				),
	'social_tags' => array(
						'ogp' => array(
									'title' => $ogp['title'],
									'desc' => $ogp['desc'],
									'url' => $ogp['url'],
									'img' => $ogp['img'],
									),
					),
);





/**
 *
 * SCORE & OUTPUT
 */

//Analysis of score
$score = new CGSS_SCORE( $result );
$rate = $score->calculate();
$exact = $score->exact();

//Before end of time
$time_end = microtime(true);

//Create new part of result, and add it to basic result
$add_result = array(
	'score' => $rate,
	'marks' => $exact,
	'time' => round( ( $time_end - $time_start ), 3 ),
);
$output_result = array_replace( $result, $add_result ); ?>
