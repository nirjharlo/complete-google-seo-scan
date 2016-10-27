<?php
/**
 *
 * @package: onpage-seo-checker/user/
 * on: 12.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Add user manipulation objects. Equilavant to View in MVC.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 2 Properties:
 * $user_path		(string)	Definition of path
 * $display_path	(string)	Definition of path
 *
 */
if ( ! class_exists( 'ONSEOCHECK_USER' ) ) {

	class ONSEOCHECK_USER {



		private $user_path;
		private $display_path;



		public function __construct() {

			$this->user_path = ONSEOCHECK_USER_PATH . 'lib/';
			$this->display_path = ONSEOCHECK_USER_PATH . 'lib/display/';

			$this->dependencies();
		}



		/**
		 *
		 * Add following 1 scripts with classes in it:
		 *
		 * 1. Define noticees object
		 * 2. Define help page object
		 *
		 */
		public function dependencies() {

			require_once( $this->user_path . 'notice.php' );
			require_once( $this->user_path . 'help.php' );

			require_once( $this->display_path . 'tabs.php' );
			//require_once( $this->display_path . 'check.php' );
		}
	}
} ?>
