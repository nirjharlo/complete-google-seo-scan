<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//Main plugin object to define the plugin
if ( ! class_exists( 'CGSS_BUILD' ) ) {
	
	final class CGSS_BUILD {



		public function installation() {

			if (class_exists('CGSS_INSTALL')) {

				$install = new CGSS_INSTALL();
				$install->textDomin = 'cgss';
				$install->phpVerAllowed = '5.4';
				$install->pluginPageLinks = array(
												array(
													'slug' => home_url().'/wp-admin/admin.php?page=seo-scan',
													'label' => __( 'Dashboard', 'cgss' )
												),
											);
				$install->do();
			}
		}



		public function db_install() {

			if ( class_exists( 'CGSS_DB' ) ) {
				$db = new CGSS_DB();
				$db->table = 'cgss_insight';
				$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
							item varchar(256) NOT NULL,
							remark varchar(512) NOT NULL,
							UNIQUE KEY ID (ID)";
				$db->build();
			}
		}



		public function db_uninstall() {

			$tableName = 'cgss_insight';

			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}$tableName" );
		}



		//Include scripts
		public function scripts() {

			if ( class_exists( 'CGSS_SCRIPT' ) ) new CGSS_SCRIPT();
		}



		//Include settings pages
		public function settings() {

			if ( class_exists( 'CGSS_SETTINGS' ) ) new CGSS_SETTINGS();
		}



		//Add customization files
		public function customization() {

			require_once ('src/db.php');
			require_once ('src/install.php');
			require_once ('src/settings.php');
		}



		//Call the dependency files
		public function helpers() {

			if ( ! class_exists( 'WP_List_Table' ) ) {
    			require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
			}

			require_once ('lib/table.php');
			require_once ('lib/overview-table.php');
			require_once ('lib/ajax.php');
			require_once ('lib/script.php');
		}



		public function __construct() {

			$this->helpers();
			$this->customization();

			register_activation_hook( CGSS_FILE, array( $this, 'db_install' ) );
			register_uninstall_hook( CGSS_FILE, array( 'CGSS_BUILD', 'db_uninstall' ) ); //$this won't work here.

			add_action('init', array($this, 'installation'));

			$this->scripts();

			$this->settings();
		}
	}
} ?>