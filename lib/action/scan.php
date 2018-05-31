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
				$crawl->set_url = $url;
				$crawl->do();
				$result = $crawl->result();

				update_post_meta( $post_id, 'cgss_scan_result', $result );

				$this->render($result);
			}
		}



		public function render($result) {

			echo 'HTML';
		}
	}
} ?>