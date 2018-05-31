<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Perform compete action
 */
if ( ! class_exists( 'CGSS_COMPETE' ) ) {

	final class CGSS_COMPETE {


		public function __construct() {

			$post_id = intval($_GET['compete']);
			$url = get_permalink( $post_id );

			if (class_exists('CGSS_COMPETE_CRAWL')) {

				$compete = new CGSS_COMPETE_CRAWL();
				$compete->set_url = $url;
				$compete->do();
				$result = $compete->results();

				update_post_meta( $post_id, 'cgss_scan_result', $result );

				$this->render($result);
			}
		}



		public function render($result) {

			echo 'HTML';
		}
	}
} ?>