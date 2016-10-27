<?php
/**
 *
 * @package: onpage-seo-checker/admin/
 * on: 16.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Add admin manipulation objects. Equilavant to Controllor in MVC.
 * Hooking into admin page.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 1 Property:
 * $admin_path		(string)	Definition of path
 *
 */
if ( ! class_exists( 'ONSEOCHECK_ADMIN' ) ) {

	final class ONSEOCHECK_ADMIN {



		private $admin_path;



		public function __construct() {

			$this->admin_path = ONSEOCHECK_ADMIN_PATH . 'lib/';

			$this->dependencies();
		}



		/**
		 *
		 * Add following 1 scripts with classes in it:
		 *
		 * 1. Define settings page object
		 * 2. Define help page object
		 *
		 */
		public function dependencies() {

			require_once( $this->admin_path . 'settings.php' );
			require_once( $this->admin_path . 'scripts.php' );
		}
	}
} ?>
