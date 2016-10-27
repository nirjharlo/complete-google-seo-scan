<?php
/**
 *
 * @package: onpage-seo-checker/core/
 * on: 16.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Add Core manipulation objects. Equilavant to Model in MVC.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 1 Property:
 * $core_path		(string)	Definition of path
 *
 */
if ( ! class_exists( 'ONSEOCHECK_CORE' ) ) {

	final class ONSEOCHECK_CORE {



		private $core_path;



		public function __construct() {

			$this->core_path = ONSEOCHECK_CORE_PATH . 'lib/';

			$this->dependencies();
		}



		/**
		 *
		 * Add following 1 scripts with classes in it:
		 *
		 * 1. Define scan object
		 *
		 */
		public function dependencies() {

			require_once( $this->core_path . 'scan.php' );
		}
	}
} ?>
