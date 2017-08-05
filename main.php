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
 * 6. $notice		(obj)		notice object
 *
 */
if ( ! class_exists( 'ONPAGE_SEO_CHECKER' ) ) {

	final class ONPAGE_SEO_CHECKER {


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
		/**
		 * [__construct ONPAGE_SEO_CHECKER constructure]
		 */
		public function __construct() {


			$this->table = array(
							'onseocheck_general',
							'onseocheck_scan',
							'onseocheck_compete',
							);

			$this->helpers();

			register_activation_hook( ONSEOCHECK_FILE, array( $this, 'db_install' ) );
			register_uninstall_hook( ONSEOCHECK_FILE, array( 'ONPAGE_SEO_CHECKER', 'db_uninstall' ) );

			$this->notice = new ONSEOCHECK_NOTICE();

			add_action( 'init', array( $this, 'installation' ) );
			add_action( 'init', array( $this, 'functionality' ) );
		}


		/**
		 * [helpers require modules]
		 * 
		 */
		public function helpers() {

			require_once ('helper/db.php');


			require_once( 'helper/notice.php' );
			require_once( 'helper/help.php' );
			require_once( 'helper/tabs.php' );


			require_once ('helper/scan.php');


			require_once ('helper/settings.php');
			require_once ('helper/scripts.php');			

		}


		/**
		 *
		 * 1. Add settings page and then add the scripts
		 * 2. Add styles, css and js to the settings page
		 * 3. Build and handle AJAX for scan
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
		 *
		 */
		public function db_uninstall() {

			//Important table name declarition
			$tableName = array(
							'onseocheck_general',
							'onseocheck_scan',
							'onseocheck_compete',
							);

			global $wpdb;
			foreach ($$tableName as $value) {
				$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}$value" );
			}
			$optionNames = array(
								'_onseocheck_total_db_exist'
							);
			foreach ($optionNames as $value) {
				delete_option($value);
			}
		}


		/**
		* [db_install install the database vars for pages and settings]
		* 
		*/
		public function db_install() {

			
			foreach ($this->table as $tName) {

				$db = new ONSEOCHECK_DB();
				$db->table = $tName;

				switch ($tName) {
					case 'onseocheck_general':
						$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
									post_id mediumint(6) NOT NULL,
									responseCode mediumint(3) NOT NULL,
									post_type smallint(1) NOT NULL,
									last_crawled datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
									first_detected datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
									marks smallint(4) NOT NULL,
									score decimal(3,1) NOT NULL,
									UNIQUE KEY ID (ID)";
						break;
					case 'onseocheck_scan':
						$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
									post_id mediumint(6) NOT NULL,
									json_key varchar(256) NOT NULL,
									data text NOT NULL,
									UNIQUE KEY ID (ID)";
						break;
					case 'onseocheck_compete':
						$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
									post_id mediumint(6) NOT NULL,
									json_key varchar(256) NOT NULL,
									data text NOT NULL,
									UNIQUE KEY ID (ID)";
						break;

				}
				$db->build();
				if (get_option('_onseocheck_total_db_exist') == '0') {
					add_action( 'admin_notices', 'dbErrorMsg' );
				}
			}
		}


		//Notice of DB
		public function dbErrorMsg() { ?>

			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'Database table Not installed correctly.', 'onseocheck' ); ?></p>
 			</div>
			<?php
		}
	}
} ?>
