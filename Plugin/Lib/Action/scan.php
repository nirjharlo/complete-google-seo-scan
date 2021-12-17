<?php

namespace NirjharLo\Cgss\Lib\Action;

if ( ! defined( 'ABSPATH' ) ) exit;

use \NirjharLo\Cgss\Lib\Analysis\Crawl;

/**
 * Perform scan action
 */
	final class Scan {


		public function __construct() {

			$post_id = intval($_GET['scan']);
			$url = get_permalink( $post_id );

			$crawl = new Crawl();
			$crawl->url = esc_url_raw($url);
			$crawl->execute();
			$this->result = $crawl->result();

			update_post_meta( $post_id, 'cgss_scan_result', $this->result );

			$this->render();
		}


		//Display the score
		public function score_html() {

			$score = $this->result['score'];
			$social = $this->result['social'];
			$fb_share = $social['fb_share'];
			$fb_like = $social['fb_like'];

			$score_html =
			'<span>'.sprintf(__('Overall SEO score %d out of 10. Facebook shares: %d and likes: %d', 'cgss' ), $score, $fb_share, $fb_like ).'</span>';

			return $score_html;
		}


		// Display the snippets
		public function snippet_display() {

			$snippet = $this->result['snip'];
			$title = $snippet['title'];
			$desc = $snippet['desc'];

			$social_snippet = $this->result['social_tags'];
			$social_title = $social_snippet['title'];
			$social_desc = $social_snippet['description'];
			$social_image = $social_snippet['image'];

			$not_found = __('NOT_FOUND', 'cgss');
			$snippet_html =
			'<ul>
				<li><strong>'.__('Search snippet', 'cgss').':</strong>
					<ul>
						<li>'.sprintf(__('Title: %s', 'cgss'),($title ? $title : $not_found)).'</li>
						<li>'.sprintf(__('Description: %s', 'cgss'),($desc ? $desc : $not_found)).'</li>
					</ul>
				</li>
				<li><strong>'.__('Social snippet(OGP)', 'cgss').':</strong>
					<ul>
						<li>'.sprintf(__('Title: %s', 'cgss'),($social_title ? $social_title : $not_found)).'</li>
						<li>'.sprintf(__('Description: %s', 'cgss'),( $social_desc ? $social_desc : $not_found)).'</li>
						<li>'.sprintf(__('Image: %s', 'cgss'),( $social_image ? $social_image : $not_found)).'</li>
					</ul></li>
			</ul>';

			return $snippet_html;
		}


		// Display text and link data
		public function text_display() {

			$text = $this->result['text'];
			$keys = implode(', ', array_keys($text['keys']));
			$count = $text['count'];
			$ratio = $text['ratio'];

			$htags = $text['htags'];
			$headings = implode( ', ', $htags['names'] );

			$links = $text['links'];
			$link_count = $links['count'];
			$link_nofollow = $links['nofollow'];
			$link_external = $links['external'];

			$text_html =
			'<ul>
				<li>'.sprintf(__('Keywords: %s','cgss'), $keys).'</li>
				<li>'.sprintf(__('Number of words: %s','cgss'), $count).'</li>
				<li>'.sprintf(__('Text to html ratio: %s percent','cgss'), $ratio).'</li>
				<li>'.sprintf(__('Heading tags in text hierarchy: %s','cgss'), $headings).'</li>
				<li>'.sprintf(__('Total Links: %d, Nofollow Links %d, External Links: %d','cgss'), $link_count, $link_nofollow, $link_external).'</li>
			</ul>';

			return $text_html;
		}


		// Display design data
		public function design_display() {


			$design = $this->result['design'];

			$iframe = $design['iframe'];

			$tag_style = $design['tag_style'];
			$tag_style_count = $tag_style['count'];
			$tag_style_attributes = implode( ', ', $tag_style['tags'] );

			$nested_table = $design['nested_table'];
			$vport = $design['vport'];
			$media = $design['media'];
			$image = $design['image'];
			$image_count = $image['count'];
			$image_no_alt_count = $image['no_alt_count'];

			$design_html =
			'<ul>
				<li>'.sprintf(__('Total images: %d and images without alt tags: %d','cgss'), $image_count, $image_no_alt_count).'</li>
				<li>'.sprintf(__('iframe: %d','cgss'), $iframe).'</li>
				<li>'.sprintf(__('Nested Tables: %d','cgss'), $nested_table).'</li>
				<li>'.sprintf(__('Tags with style attribute: %s(%s)','cgss'), $tag_style_count, $tag_style_attributes).'</li>
				<li>'.sprintf(__('Mobile optimization: Viewport Tag: %d, @media Queries: %d','cgss'), $vport, $media).'</li>
			</ul>';

			return $design_html;
		}


		// Display crawl data
		public function crawl_display() {

			$crawl = $this->result['crawl'];

			$ssl = $crawl['ssl'];
			$dynamic = $crawl['dynamic'];
			$underscore = $crawl['underscore'];

			$ip = $crawl['ip'];
			$cano = $crawl['cano'];
			$if_mod = $crawl['if_mod'];
			$alive = $crawl['alive'];
			$robot = $crawl['meta_robot'];

			$on = __( 'on', 'cgss' );
			$off = __( 'off', 'cgss' );

			$crawl_html =
			'<ul>
				<li>'.sprintf(__('Your URL parameters: SSL security: %s, Static url: %s, underscores in Url: %s','cgss'), (!$ssl ? $off : $on ), (!$dynamic ? $off : $on ), (!$underscore ? $off : $on )).'</li>
				<li>'.sprintf(__('If modified since header: %s','cgss'), ($if_mod == 0 ? $off : $on )).'</li>
				<li>'.sprintf(__('Canonical Url: %s','cgss'), ($cano == 0 ? $off : $on )).'</li>
				<li>'.sprintf(__('Meta Robot: %s','cgss'), (!$robot['ok'] ? $off : $on )).'</li>
				<li>'.sprintf(__('IP Forward: %s','cgss'), ($ip ? $ip : $off )).'</li>
				<li>'.sprintf(__('Keep alive connection: %s','cgss'), ($alive == 0 ? $off : $on )).'</li>
			</ul>';

			return $crawl_html;
		}


		// Display speed data
		public function speed_display() {

			$speed = $this->result['speed'];

			$res_time = $speed['res_time'];
			$down_time = $speed['down_time'];
			$gzip = $speed['gzip'];
			$cache = $speed['cache'];
			$css = $speed['css'];
			$js = $speed['js'];

			$on = __( 'on', 'cgss' );
			$off = __( 'off', 'cgss' );

			$speed_html =
			'<ul>
				<li>'. sprintf( __( 'Header response time %s s and page downloading time %s s', 'cgss' ), $res_time, $down_time ). '</li>
				<li>'.sprintf(__('gZip compression: %s','cgss'), ($gzip ? $off : $on )).'</li>
				<li>'.sprintf(__('Browser caching: %s','cgss'), ($cache ? $off : $on )).'</li>
				<li>'.sprintf(__('Requests made by CSS is %s and by JS is %s','cgss'), $css['count'], $js['count']).'</li>
				<li>'.sprintf(__('Resources can be compressed by %s kb for CSS and %s kb for JS','cgss'), $css['compress_num'], $js['compress_num']).'</li>
			</ul>';

			return $speed_html;
		}


		// Render the HTML
		public function render() {

			$this->score_html	= $this->score_html();
			$this->snippets_html	= $this->snippet_display();
			$this->text_html = $this->text_display();
			$this->design_html = $this->design_display();
			$this->crawl_html = $this->crawl_display();
			$this->speed_html = $this->speed_display();

			$this->box( null, null, $this->score_html );
			$this->box( __( 'Snippets', 'cgss' ), $this->dashicon('align-none'), $this->snippets_html );
			$this->box( __( 'Text & Links', 'cgss' ), $this->dashicon('text'), $this->text_html );
			$this->box( __( 'Design', 'cgss' ), $this->dashicon('smartphone'), $this->design_html );
			$this->box( __( 'Crawl', 'cgss' ), $this->dashicon('randomize'), $this->crawl_html );
			$this->box( __( 'Speed', 'cgss' ), $this->dashicon('clock'), $this->speed_html );
		}


		public function box($title, $icon, $desc) {

			echo
			'<div class="postbox">
				<div class="inside">
					<div class="main">' .
						'<h4>' . $icon . ' ' . $title . '</h4>' .
						$desc .
					'</div>
				</div>
			</div>';
		}


		public function dashicon($icon) {

			return '<span class="dashicons dashicons-'.$icon.'"></span>';
		}
	} ?>
