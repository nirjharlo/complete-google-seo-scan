<?php
namespace NirjharLo\Cgss\Lib\Analysis;

if ( ! defined( 'ABSPATH' ) ) exit;

use \NirjharLo\Cgss\Lib\Analysis\Lib\Tags;
use \NirjharLo\Cgss\Lib\Analysis\Lib\Text;
use \NirjharLo\Cgss\Lib\Analysis\Lib\Keywords;
use \NirjharLo\Cgss\Lib\Analysis\Lib\Social;
use \NirjharLo\Cgss\Lib\Analysis\Lib\Server;
use \NirjharLo\Cgss\Lib\Analysis\Lib\Design;
use \NirjharLo\Cgss\Lib\Analysis\Lib\Score;

use \DOMDocument;

/**
 * Crawls an URL
 */
	final class Crawl {


		public $url;


		// Execute the crawl
		public function execute() {

			$tic = microtime(true);

			$this->header = $this->header_check();
			$ok = $this->header['ok'];
			if (!$ok) {

			 	$this->no_header_scan();

			} else {

				$this->parsed_url = $this->parse_url();
				$this->body = $this->get_body();
				$ok = $this->body['ok'];
				if (!$ok) {

			 		$this->no_body_scan();

				} else {

					// Prepare the DOM
					$this->dom = new DOMDocument;
					libxml_use_internal_errors( true );
					$this->dom->loadHTML( $this->body['body'] );

					// Parse the DOM
					$this->title = $this->get_title();
					$this->desc = $this->get_desc();
					$this->robot = $this->get_robot();
					$this->canonical = $this->get_canonical();
					$this->viewport = $this->get_viewport();
					$this->social_tags = $this->get_social_tags();
					$this->links = $this->get_links();
					$this->images = $this->get_images();
					$this->iframe = $this->get_iframes();
					$this->tag_css = $this->get_tag_css();
					$this->headings = $this->get_headings();
					$this->table = $this->get_nested_table();

					$this->text = $this->get_text_vars();
					$this->keywords = $this->get_keywords();

					$this->social_data = $this->get_social_data();

					$this->server = $this->get_server();

					$this->css = $this->get_style();
					$this->js = $this->get_javascript();

					$this->score = $this->get_score();
				}
			}

			$toc = microtime(true);

			$this->time = round( ( $toc - $tic ), 3 );
		}


		// Return snippet data
		public function return_snippet() {

			return array(
				'title' => $this->title,
				'url' => $this->parsed_url['val'],
				'desc' => $this->desc
				);
		}


		// Return text data
		public function return_text() {

			return array(
				'count' => $this->text['count'],
				'size' => $this->text['size'],
				'ratio' => $this->text['ratio'],
				'keys' => $this->keywords['value'],
				'top_key' => $this->keywords['top'],
				'links' => $this->links,
				'htags' => $this->headings
				);
		}


		//Return design data
		public function return_design() {

			return array(
				'iframe' => $this->iframe,
				'image' => $this->images,
				'nested_table' => $this->table,
				'tag_style' => $this->tag_css,
				'vport' => $this->viewport,
				'media' => $this->css['media']
				);
		}


		//Return crawl data
		public function return_crawl() {

			return array(
				'val' => $this->parsed_url['val'],
				'ssl' => $this->parsed_url['ssl'],
				'dynamic' => $this->parsed_url['dynamic'],
				'underscore' => $this->parsed_url['underscore'],
				'ip' => $this->server['ip'],
				'cano' => $this->canonical['ok'],
				'if_mod' => $this->server['if_mod'],
				'alive' => $this->server['alive'],
				'meta_robot' => $this->robot
				);
		}


		//Return speed data
		public function return_speed() {

			return array(
				'res_time' => $this->header['time'],
				'down_time' => $this->body['time'],
				'gzip' => $this->server['gzip'],
				'cache' => $this->server['cache'],
				'css' => $this->css,
				'js' => $this->js
				);
		}


		// Output the result
		public function result() {

			$result = array();

			$result['score'] = $this->get_score();
			$result['domain'] = $this->parsed_url['domain'];
			$result['time'] = $this->time;
			$result['snip'] = $this->return_snippet();
			$result['social'] = $this->social_data;
			$result['text'] = $this->return_text();
			$result['design'] = $this->return_design();
			$result['crawl'] = $this->return_crawl();
			$result['speed'] = $this->return_speed();
			$result['social_tags'] = $this->social_tags;

			return $result;
		}


		//Calculate score
		public function get_score() {

			$score = new Score();
			$score->snippet = $this->return_snippet();
			$score->text = $this->return_text();
			$score->design = $this->return_design();
			$score->crawl = $this->return_crawl();
			$score->speed = $this->return_speed();

			$score_obtained = $score->calculate();
			return $score_obtained;
		}


		// Analyze the JS
		public function get_javascript() {

			$js = new Tags();
			$js->dom = $this->dom;
			$js->tag = 'script';
			$js->atts = array( 'src' );

			$js_list = $js->tag();

			$js_data = new Design();
			$js_data->js_url = $js_list;
			$js_details = $js_data->analyze_js();

			return $js_details;
		}



		//Get the Stylesheets
		public function get_style() {

			$css = new Tags();
			$css->dom = $this->dom;
			$css->tag = 'link';
			$css->specify = array( 'att' => 'rel', 'val' => 'stylesheet', 'get_att' => 'href' );

			$css_list = $css->tag();

			$css_data = new Design();
			$css_data->css_url = $css_list;
			$css_details = $css_data->analyze_css();

			return $css_details;
		}


		//Get server data
		public function get_server() {

			$server = new Server();
			$server->header = $this->header;
			$server->domain = $this->parsed_url['domain'];

			$server_data = array();
			$server_data['gzip'] = $server->gzip();
			$server_data['cache'] = $server->cache();
			$server_data['if_mod'] = $server->if_mod();
			$server_data['alive'] = $server->if_mod();
			$server_data['ip'] = $server->IP();

			return $server_data;
		}


		//Get social data from 3rd party API
		public function get_social_data() {

			$social = new Social();
			$social->url = $this->url;

			$facebook = $social->fb();
			$social_data['fb_share'] = $facebook['share'];
			$social_data['fb_like'] = $facebook['like'];

			return $social_data;
		}


		// Get keywords data from text
		public function get_keywords() {

			$keys = new Keywords();
			$keys->words = $this->text['words'];
			$keys->text = $this->text['text'];

			$keys_data = array();
			$keys_data['value'] = $keys->output();
			$keywords = array_keys($keys_data['value']);
			$keys_data['top'] = $keywords[0];

			return $keys_data;
		}


		//Get text from dom
		public function get_text_vars() {

			$text = new Text();
			$text->dom =  $this->dom;
			$text->body_size = $this->body['size'];
			$text->execute();
			$text_string = $text->text();
			$words = $text->words();

			$text_data = array();

			$words = $text->words();
			$text_data['words'] = $words;
			$text_data['text'] = $text_string;
			$text_data['count'] = count( $words );
			$size = $text->size();
			$text_data['size'] = round( ( $size / 1024 ), 1 );
			$text_data['ratio'] = $text->ratio();

			return $text_data;
		}


		//Get nested table
		public function get_nested_table() {

			$table = $this->dom->getElementsByTagName( 'table' );

			$nested_table_count = 0;
			if ( $table ) {
				foreach ( $table as $obj ) {
					$nested_table = $obj->getElementsByTagName( 'table' );
					$nested_table_count = ( $nest_table ? $nested_table_count + 1 : $nested_table_count );
				}
			}

			return $nested_table_count;
		}


		//Get heading tags
		public function get_headings() {

			$all_headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

			$headings = array();
			foreach ( $all_headings as $value ) {

				$head = new Tags();
				$head->dom = $this->dom;
				$head->tag = $value;
				$head_tag = $head->tag();

				$headings[$value] = $head_tag;
			}

			$headings_filtered = array_filter( $headings );

			$heading = array();
			$heading['names'] = array_keys( $headings_filtered );
			$heading['content'] = $headings_filtered;

			return $heading;
		}


		//Get style attribute in tags
		public function get_tag_css() {

			$tags = array(
						'a', 'abbr', 'acronym', 'address', 'applet', 'area',
						'b', 'base', 'basefont', 'bdo', 'bgsound', 'big', 'blockquote', 'blink', 'body', 'br', 'button',
						'caption', 'center', 'cite', 'code', 'col', 'colgroup',
						'dd', 'dfn', 'del', 'dir', 'dl', 'div', 'dt',
						'embed', 'em',
						'fieldset', 'font', 'from', 'frame', 'frameset',
						'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html',
						'iframe', 'img', 'input', 'ins', 'isindex', 'i',
						'kbd',
						'label', 'legend', 'li', 'link',
						'marquee', 'menu', 'meta',
						'noframe', 'noscript',
						'optgroup', 'option', 'ol',
						'p', 'pre',
						'q',
						's', 'samp', 'script', 'select', 'small', 'span', 'strike', 'strong', 'style', 'sub', 'sup',
						'table', 'td', 'th', 'tr', 'tbody', 'textarea', 'tfoot', 'thead', 'title', 'tt',
						'u', 'ul',
						'var'
					);

			$tag_style_value = array();
			foreach( $tags as $value ) {

				$tag = new Tags();
				$tag->dom = $this->dom;
				$tag->tag = $value;
				$tag->specify =  array( 'att' => null, 'val' => null, 'get_att' => 'style', );

				$tag_style = $tag->tag();
				$tag_style_value[] = ( $tag_style ? $value : false );
			}

			$tag_style_filtered = array_filter($tag_style_value);

			$tag_css['count'] = count($tag_style_filtered);
			$tag_css['tags'] = $tag_style_filtered;

			return $tag_css;
		}


		//Get the iframes
		public function get_iframes() {

			$iframes = new Tags();
			$iframes->dom = $this->dom;
			$iframes->tag = 'iframe';
			$iframes->specify = array( 'att' => null, 'val' => null, 'get_att' => 'src' );

			$get_iframe = $iframes->tag();

			return $get_iframe;
		}


		//Get the images
		public function get_images() {

			$images = new Tags();
			$images->dom = $this->dom;
			$images->tag = 'img';
			$images->atts = array( 'src', 'alt' );

			$attributes = $images->atts();

			$alts = $attributes['alt'];
			$srcs = $attributes['src'];
			$pure_alt = array_filter($alts);

			$image_data = array();
			$image_data['count'] = count($srcs);
			$image_data['alt_value'] = $pure_alt;
			$image_data['no_alt_count'] = count($srcs) - count($pure_alt);

			return $image_data;
		}


		// Get the links
		public function get_links() {

			$links = new Tags();
			$links->dom = $this->dom;
			$links->tag = 'a';
			$links->atts = array( 'rel', 'href' );

			$attributes = $links->atts();
			$anchors = $links->tag();

			$rels = $attributes['rel'];
			$hrefs = $attributes['href'];

			$domain = $this->parsed_url['domain'];

			$link_num = 0;
			$ext_link_num = 0;
			foreach ($hrefs as $value) {
				if (filter_var($value, FILTER_VALIDATE_URL)) {
					$link_num = $link_num + 1;

					if( parse_url($value, PHP_URL_HOST) == $domain ) {
						$ext_link_num = $ext_link_num + 1;
					}
				}
			}

			$nofollow_link_num = 0;
			foreach ($rels as $value) {
				if( strpos($value, 'nofollow') !== false ) {
					$nofollow_link_num = $nofollow_link_num +1;
				}
			}

			$no_txt_link_num = count($anchors) - count(array_filter($anchors));
			$anchor_text = implode(' ', $anchors);

			$link_data = array();
			$link_data['count'] = $link_num;
			$link_data['nofollow'] = $nofollow_link_num;
			$link_data['external'] = $link_num - $ext_link_num;
			$link_data['no_text'] = $no_txt_link_num;
			$link_data['anchors'] = $anchor_text;

			return $link_data;
		}



		// Get the OGP and Twitter card tags
		public function get_social_tags() {

			$tags = array('title', 'description', 'url', 'image');
			$social_tag_val = array();
			foreach( $tags as $value ) {

				$social_tag = new Tags();
				$social_tag->dom = $this->dom;
				$social_tag->tag = 'meta';
				$social_tag->specify = array('att' => 'property', 'val' => 'og:'.$value, 'get_att' => 'content');
				$social_tag_fetch = $social_tag->tag();

				$social_tag_val[$value] = ( $social_tag_fetch ? array_pop($social_tag_fetch) : false );
			}

			return $social_tag_val;
		}


		// Get viewport tag
		public function get_viewport() {

			$viewport = new Tags();
			$viewport->dom = $this->dom;
			$viewport->tag = 'meta';
			$viewport->specify = array( 'att' => 'name', 'val' => 'viewport', 'get_att' => 'content' );
			$viewport_tag = $viewport->tag();

			$meta_viewport_val = ( $viewport_tag ? array_pop($viewport_tag) : false );
			$meta_viewport = ( $meta_viewport_val && $meta_viewport_val != '' ) ? 1 : 0;

			return $meta_viewport;
		}



		// Get meta canonical
		public function get_canonical() {

			$meta_canonical = array();

			$canonical = new Tags();
			$canonical->dom = $this->dom;
			$canonical->tag = 'meta';
			$canonical->specify = array( 'att' => 'rel', 'val' => 'canonical', 'get_att' => 'href' );
			$canonical_tag = $canonical->tag();

			$meta_canonical['val'] = ( $canonical_tag ? esc_url_raw(array_pop($canonical_tag)) : false );
			$meta_canonical['ok'] = ( $meta_canonical['val'] && $meta_canonical['val'] != '' ) ? 1 : 0;

			return $meta_canonical;
		}



		// Get meta robots
		public function get_robot() {

			$meta_robot = array();

			$robot = new Tags();
			$robot->dom = $this->dom;
			$robot->tag = 'meta';
			$robot->specify = array( 'att' => 'name', 'val' => 'robots', 'get_att' => 'content' );
			$robot_tag = $robot->tag();

			$meta_robot['val'] = ( $robot_tag ? array_pop($robot_tag) : false );
			$meta_robot['ok'] = $meta_robot['val'] && strpos($meta_robot['val'], 'index') !== false && strpos($meta_robot['val'], 'follow') !== false ? 1 : 0;

			return $meta_robot;
		}



		// Get page description
		public function get_desc() {

			$description = new Tags();
			$description->dom = $this->dom;
			$description->tag = 'meta';
			$description->specify = array( 'att' => 'name', 'val' => 'description', 'get_att' => 'content' );
			$desc = $description->tag();

			return ( $desc ? array_pop($desc) : false );
		}



		// Get the page title
		public function get_title() {

			$title_fetch = new Tags();
			$title_fetch->dom = $this->dom;
			$title_fetch->tag = 'title';
			$title = $title_fetch->tag();

			return ( $title ? array_pop($title) : false );
		}


		// Get the body
		public function get_body() {

			$response = array();

			$start = microtime(true);
			$body = @file_get_contents( $this->url, FILE_USE_INCLUDE_PATH );
			$end = microtime(true);

			$response['ok'] = empty($body) ? false : true;
			$response['time'] = round( ( $end - $start ), 3 );
			$response['body'] = $body;
			$response['size'] = mb_strlen( $body, '8bit' );

			return $response;
		}



		// Check the header
		public function header_check() {

			$response = array();

			$start = microtime(true);
			$header = get_headers( $this->url, 1 );
			$end = microtime(true);

			$response['time'] = round( ( $end - $start ), 3 );
			$response['response'] = $header[0];
			$response['ok'] = (strchr($header[0], '200') != false) ? true : false;

			return  $response;
		}



		// Parse the URL
		public function parse_url() {

			$parse = array();
			$parsed_url = parse_url($this->url);

			$parse['val'] = $this->url;
			$parse['domain'] = $parsed_url['host'];
			$parse['ssl'] = $parsed_url['scheme'] == 'http' ? false : true;
			$parse['dynamic'] = array_key_exists('query', $parsed_url) ? true : false;
			$parse['underscore'] = ( strpos($this->url, '_') !== false ) ? true : false;

			return $parse;
		}


		// No scan possible notice
		public function no_body_scan() { ?>

			<div class="notice notice-error">
        		<p><?php _e( 'The page could not be downloaded. Try again', 'cgss' ); ?></p>
			</div>
    	<?PHP
		}


		// No scan possible notice
		public function no_header_scan() { ?>

			<div class="notice notice-error">
        		<p><?php _e( 'The header returned is not Ok. Try again', 'cgss' ); ?></p>
			</div>
    	<?PHP
		}
	} ?>
