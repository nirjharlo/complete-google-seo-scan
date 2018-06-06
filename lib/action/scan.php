<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Perform scan action
 */
if ( ! class_exists( 'CGSS_SCAN' ) ) {

	final class CGSS_SCAN {


		public function __construct() {

			$post_id = intval($_GET['scan']);
			$url = get_permalink( $post_id );

			if (class_exists('CGSS_CRAWL')) {

				$crawl = new CGSS_CRAWL();
				$crawl->url = esc_url_raw($url);
				$crawl->do();
				$result = $crawl->result();

				update_post_meta( $post_id, 'cgss_scan_result', $result );

				$this->render($result);
			}
		}


		//Display the score
		public function score_html() {

			$score_html = '';

			return $score_html;
		}


		// Display the snippets
		public function snippet_display() {

			$snippet_html = '';

			return $snippet_html;
		}


		// Display text and link data
		public function text_display() {

			$text_html = '';

			return $text_html;
		}


		// Display design data
		public function design_display() {


			$design_html = '';

			return $design_html;
		}


		// Display crawl data
		public function crawl_display() {

			$crawl_html = '';

			return $crawl_html;
		}


		// Display speed data
		public function speed_display() {

			$speed_html = '';

			return $speed_html;
		}


		// Render the HTML
		public function render($result) {

			$this->score	= $this->score_html();
			$this->snippets	= $this->snippet_display();
			$this->text = $this->text_display();
			$this->design = $this->design_display();
			$this->crawl = $this->crawl_display();
			$this->speed = $this->speed_display();

			$this->box( null, null, $this->score );
			$this->box( __( 'Snippets', 'cgss' ), $this->dashicon('align-none'), $this->snippets );
			$this->box( __( 'Text & Links', 'cgss' ), $this->dashicon('text'), $this->text );
			$this->box( __( 'Design', 'cgss' ), $this->dashicon('smartphone'), $this->design );
			$this->box( __( 'Crawl', 'cgss' ), $this->dashicon('randomize'), $this->crawl );
			$this->box( __( 'Speed', 'cgss' ), $this->dashicon('clock'), $this->speed );
		}


		public function box($title, $icon, $desc) {

			echo 
			'<div class="postbox">
				<div class="inside">
					<div class="main">' .
						'<h3>' . $icon . ' ' . $title . '</h3>' .
						$desc .
					'</div>
				</div>
			</div>';
		}


		public function dashicon($icon) {

			return '<span class="dashicons dashicons-'.$icon.'"></span>';
		}
	}
} ?>