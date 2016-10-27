<?php
/**
 *
 * @package: onpage_seo_checker/
 * on: 11.07.2016
 * @since 2.5
 * @called_in: DIRECTLY
 *
 * Main plugin object.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Mother object to define OnPage Seo Checker plugin.
 *
 * 4 properties:
 * 1. $db_path		(string)	db.php path
 * 2. $core_path	(string)	core.php path
 * 3. $user_path	(string)	user.php path
 * 4. $admin_path	(string)	admin.php path
 * 5. $db_exist		(string)	db installed
 * 6. $notice		(obj)		notice object
 *
 */
if ( ! class_exists( 'ONPAGE_SEO_CHECKER' ) ) {

	final class ONPAGE_SEO_CHECKER {


		private $db_path;
		private $core_path;
		private $user_path;
		private $admin_path;
		private $db_exist;
		private $notice;



		/**
		 *
		 * Initiate the plugin, here saequence matters. Following is the process
		 * 1. Declare the dependency paths, Then define the dependencies
		 * 2. Register installation and uninstallation functions on database
		 * 3. If database tables are not found, show database failure notice
		 * 4. Execute WordPress actions
		 *
		 */
		public function __construct() {

			$this->db_path = ONSEOCHECK_DB_PATH . 'db.php';
			$this->core_path = ONSEOCHECK_CORE_PATH . 'core.php';
			$this->user_path = ONSEOCHECK_USER_PATH . 'user.php';
			$this->admin_path = ONSEOCHECK_ADMIN_PATH . 'admin.php';
			$this->dependencies( $this->db_path, $this->core_path, $this->admin_path, $this->user_path );

			register_activation_hook( ONSEOCHECK_FILE, array( $this, 'db_install' ) );
			register_uninstall_hook( ONSEOCHECK_FILE, array( 'ONPAGE_SEO_CHECKER', 'db_uninstall' ) );

			$this->notice = new ONSEOCHECK_NOTICE();
			$this->db_exist = get_option( '_onseocheck_total_db_exist' );

			if ( $this->db_exist != 3 ) { echo $this->notice->db_fail; return; }

			add_action( 'init', array( $this, 'installation' ) );
			add_action( 'init', array( $this, 'functionality' ) );
		}



		/**
		 *
		 * 1. Add settings page and then add the scripts
		 * 2. Add styles, css and js to the settings page
		 * 3. Build and handle AJAX for scan
		 *
		 * Calls in /user and /core objects in the included classes
		 *
		 */
		public function functionality() {

			if ( class_exists( 'ONSEOCHECK_SETTINGS' ) ) new ONSEOCHECK_SETTINGS();
			if ( class_exists( 'ONSEOCHECK_SCRIPTS' ) ) new ONSEOCHECK_SCRIPTS();
			if ( class_exists( 'ONSEOCHECK_HANDLE' ) ) new ONSEOCHECK_HANDLE();
		}



		/**
		 *
		 * Install the database vars for settings
		 *
		 */
		public function installation() {

			if ( class_exists( 'ONSEOCHECK_INSTALL' ) ) new ONSEOCHECK_INSTALL();
		}



		/**
		 *
		 * Uninstall the database vars
		 * Defined_in: onpage-seo-checker/db/lib/uninstall.php
		 *
		 */
		public function db_uninstall() {

			if ( class_exists( 'ONSEOCHECK_DB_UNINSTALL' ) ) new ONSEOCHECK_DB_UNINSTALL();
		}



		/**
		 *
		 * Install the database vars for pages and settings
		 * Defined_in: onpage-seo-checker/db/lib/install.php
		 *
		 */
		public function db_install() {

			if ( class_exists( 'ONSEOCHECK_DB_INSTALL' ) ) new ONSEOCHECK_DB_INSTALL();
		}



		/**
		 *
		 * Call dependent objets into main object
		 * Following 4 front objects are called for this.
		 *
		 * Defined_in: onpage-seo-checker/db/db.php
		 * onpage-seo-checker/core/core.php
		 * onpage-seo-checker/user/user.php
		 * onpage-seo-checker/admin/admin.php
		 *
		 */
		private function dependencies( $db, $core, $admin, $user ) {

			require_once( $db );
			require_once( $core );
			require_once( $admin );
			require_once( $user );

			if ( class_exists( 'ONSEOCHECK_DB' ) ) new ONSEOCHECK_DB();
			if ( class_exists( 'ONSEOCHECK_USER' ) ) new ONSEOCHECK_USER();
			if ( class_exists( 'ONSEOCHECK_CORE' ) ) new ONSEOCHECK_CORE();
			if ( class_exists( 'ONSEOCHECK_ADMIN' ) ) new ONSEOCHECK_ADMIN();
		}
	}
} ?>
