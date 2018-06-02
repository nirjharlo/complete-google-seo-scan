<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Crawls an URL
 */
if ( ! class_exists( 'CGSS_CRAWL' ) ) {


	final class CGSS_CRAWL {


		public $url;


		// Execute the crawl
		public function do() {

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
					$this->social_tags = $this->social_tags();

				}
			}
		}



		// Output the result
		public function result() {

			$result = array();

			$result['domain'] = $this->parsed_url['domain'];
			$result['score'] = null;
			$result['marks'] = null;
			$result['time'] = null;
			$result['time_now'] = null;
			$result['snip'] = array(
								'title' => $this->title,
								'url' => $this->parsed_url['val'],
								'desc' => $this->desc,
								);
			$result['social'] = array(
									'num' => $gplus + $twitter + $fb_share,
									'gplus' => $gplus,
									'twitter' => $twitter,
									'fb_share' => $fb_share,
									'fb_like' => $fb_like,
								);
			$result['text'] = array(
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
							);
			$result['design'] = array(
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
									'vport' => $this->viewport['ok'],
									'media' => array(
												'ok' => $css_media,
												'num' => $css_media_num,
												),
								);
			$result['crawl'] = array(
									'val' => $this->parsed_url['url'],
									'ssl' => $this->parsed_url['ssl'],
									'dynamic' => $this->parsed_url['dynamic'],
									'underscore' => $underscore,
									'ip' => array(
											'ok' => $ip,
											'val' => $ip_addr,
											),
									'www' => $www,
									'cano' => $this->canonical['ok'],
									'if_mod' => $if_mod,
									'meta_robot' => $this->robot,
								);
			$result['speed'] = array(
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
								);
			$result['social_tags'] = array(
										'ogp' => array(
													'title' => $this->social_tags['title'],
													'desc' => $this->social_tags['description'],
													'url' => $this->social_tags['url'],
													'img' => $this->social_tags['image'],
												),
										);

			return $result;
		}



		// Get the OGP and Twitter card tags
		public function social_tags() {

			$tags = array('title', 'description', 'url', 'image');
			$social_tag_val = array();
			foreach( $tags as $value ) {

				$social_tag = new CGSS_FETCH();
				$social_tag->dom = $this->dom;
				$social_tag->tag = 'meta';
				$social_tag->specify = array('att' => 'property', 'val' => 'og:'.$value, 'get_att' => 'content');

				$social_tag_val[$value] = $social_tag->tag();
			}
		}



		// Get viewport tag
		public function get_viewport() {

			$meta_viewport = array();

			$viewport = new CGSS_FETCH();
			$viewport->dom = $this->dom;
			$viewport->tag = 'meta';
			$viewport->specify = array( 'att' => 'name', 'val' => 'viewport', 'get_att' => 'content' );

			$meta_viewport['val'] = esc_url_raw($viewport->tag());
			$meta_viewport['ok'] = ( $meta_viewport['val'] && $meta_viewport['val'] != '' ) ? 1 : 0;

			return $meta_viewport;
		}



		// Get meta canonical
		public function get_canonical() {

			$meta_canonical = array();

			$canonical = new CGSS_FETCH();
			$canonical->dom = $this->dom;
			$canonical->tag = 'meta';
			$canonical->specify = array( 'att' => 'rel', 'val' => 'canonical', 'get_att' => 'href' );

			$meta_canonical['val'] = esc_url_raw($canonical->tag());
			$meta_canonical['ok'] = ( $meta_canonical['val'] && $meta_canonical['val'] != '' ) ? 1 : 0;

			return $meta_canonical;
		}



		// Get meta robots
		public function get_robot() {

			$meta_robot = array();

			$robot = new CGSS_FETCH();
			$robot->dom = $this->dom;
			$robot->tag = 'meta';
			$robot->specify = array( 'att' => 'name', 'val' => 'robots', 'get_att' => 'content' );

			$meta_robot['val'] = $robot->tag();
			$meta_robot['ok'] = $meta_robot['val'] ? 1 : 0;

			return $meta_robot;
		}



		// Get page description
		public function get_desc() {

			$description = new CGSS_FETCH();
			$description->dom = $this->dom;
			$description->tag = 'meta';
			$description->specify = array( 'att' => 'name', 'val' => 'description', 'get_att' => 'content' );

			return $description->tag();
		}



		// Get the page title
		public function get_title() {

			$title_fetch = new CGSS_FETCH();
			$title_fetch->dom = $this->dom;
			$title_fetch->tag = 'title';

			return $title_fetch->tag();
		}


		// Get the body
		public function get_body() {

			$response = array();

			$start = microtime(true);
			$body = file_get_contents( $this->url, FILE_USE_INCLUDE_PATH );
			$end = microtime(true);

			$response['ok'] = !$body ? false : true;
			$response['time'] = round( ( $end - $start ), 3 ) * 1000;
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

			$response['time'] = round( ( $end - $start ), 3 ) * 1000;
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
	}
} ?>