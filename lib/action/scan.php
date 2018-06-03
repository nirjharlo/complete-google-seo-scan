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



		public function render($result) {

			$this->box( 'content', __( 'Text & Links', 'cgss' ), $this->dashicon('text'), 'content', 'width: 49.5%' );
		}


		public function box($id, $icon, $title, $desc, $style) {

			echo '<div id="dashboard_right_now" class="postbox" style="'.$style.'">
				<div class="handlediv handlediv-' . $id . '">' . $icon . '</div>
					<h3 id="hndle-' . $id . '" class="hndle ui-sortable-handle" title="' . __( 'Click to toggle', 'cgss' ) . '"><span>' . $title . '</span></h3>
					<div id="inside-' . $id . '" class="inside">
						<div class="main">' .
							$desc .
						'</div>
					</div>
				</div>';
		}


		public function dashicon($icon) {

			echo '<span class="dashicon dashicon-'.$icon.'"></span>';
		}
	}
} ?>