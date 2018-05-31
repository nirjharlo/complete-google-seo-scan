<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Perform fetch insight action
 */
if ( ! class_exists( 'CGSS_INSIGHT' ) ) {

	final class CGSS_INSIGHT {


		public function __construct() {
				
				$insight = $this->fetch();
				$result = $this->compile($insight);
				$this->save($result);
		}



		public function compile() {


		}



		public function fetch() {


		}



		public function save() {

			//DB insert function
		}
	}
} ?>