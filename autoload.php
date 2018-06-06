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

				$insert_data = $this->insert_prelim_data();
			}
		}


		public function db_uninstall() {

			$tableName = 'cgss_insight';

			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}$tableName" );
		}


		public function insert_prelim_data() {

			global $wpdb;

			$result = $wpdb->get_results("SELECT * from {$wpdb->prefix}cgss_insight");
    		if(count($result) == 0) {

			$init_insight = array(
					__('Score','cgss'),
					__('Snippet','cgss'),
					__('Text','cgss'),
					__('Links','cgss'),
					__('Keywords','cgss'),
					__('Images','cgss'),
					__('Responsive','cgss'),
					__('Speed','cgss'),
					__('Social','cgss')
				);
			$no_data = __( 'No scan reports are available yet', 'cgss' );
			foreach ($init_insight as $key => $value) {
				$sql = $wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}cgss_insight ( ID, item, remark ) VALUES ( %d, %s, %s )", ($key+1), $value, $no_data
					);
				$query = $wpdb->query($sql);
			}
			}
		}


		//Include scripts
		public function scripts() {

			if ( class_exists( 'CGSS_SCRIPT' ) ) new CGSS_SCRIPT();
		}



		//Include settings pages
		public function settings() {

			if ( class_exists( 'CGSS_SETTINGS' ) ) new CGSS_SETTINGS();
		}



		// Add custom insight action
		public function insight() {

			if ( class_exists( 'CGSS_INSIGHT' ) ) new CGSS_INSIGHT();
		}



		// Add custom insight action
		public function compete() {

			if ( class_exists( 'CGSS_COMPETE' ) ) new CGSS_COMPETE();
		}



		// Add custom insight action
		public function scan() {

			if ( class_exists( 'CGSS_SCAN' ) ) new CGSS_SCAN();
		}



		//Add files for crawling as external resource
		public function vendor() {

			require_once ('vendor/lib/tags.php');
			require_once ('vendor/lib/text.php');
			require_once ('vendor/lib/keywords.php');
			require_once ('vendor/lib/social.php');
			require_once ('vendor/lib/server.php');
			require_once ('vendor/lib/design.php');
			require_once ('vendor/lib/score.php');
			require_once ('vendor/crawl.php');
		}



		//Add functionality files
		public function functionality() {

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
			require_once ('lib/script.php');

			require_once ('lib/action/scan.php');
			require_once ('lib/action/compete.php');
			require_once ('lib/action/insight.php');
		}



		public function __construct() {

			$this->vendor();
			$this->helpers();
			$this->functionality();

			register_activation_hook( CGSS_FILE, array( $this, 'db_install' ) );
			register_uninstall_hook( CGSS_FILE, array( 'CGSS_BUILD', 'db_uninstall' ) ); //$this won't work here.

			add_action('init', array($this, 'installation'));

			$this->scripts();

			$this->settings();

			// Add custom actions, defined in settings
			add_action( 'cgss_scan', array( $this, 'scan' ) );
			add_action( 'cgss_compete', array( $this, 'compete' ) );
			add_action( 'cgss_fetch_insight', array( $this, 'insight' ) );
		}
	}
} ?>