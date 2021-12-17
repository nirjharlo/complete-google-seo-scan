<?php
namespace NirjharLo\Cgss;

if ( ! defined( 'ABSPATH' ) ) exit;


use \NirjharLo\Cgss\Lib\Script;

use \NirjharLo\Cgss\Lib\Action\Scan;
use \NirjharLo\Cgss\Lib\Action\Insight;

use \NirjharLo\Cgss\Src\Db;
use \NirjharLo\Cgss\Src\Install;
use \NirjharLo\Cgss\Src\Settings;


final class Loader {

	/**
	 * Plugin Instance.
	 *
	 * @var PLUGIN_BUILD the PLUGIN Instance
	 */
	protected static $instance;


	/**
	 * Main Plugin Instance.
	 *
	 * @return PLUGIN_BUILD
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function installation() {

			$install = new Install();
			$install->textDomin = 'cgss';
			$install->phpVerAllowed = '5.4';
			$install->pluginPageLinks = array(
											array(
												'slug' => home_url().'/wp-admin/admin.php?page=seo-scan',
												'label' => __( 'Dashboard', 'cgss' )
											),
										);
			$install->execute();
	}


	public function db_install() {

			$db = new Db();
			$db->table = 'cgss_insight';
			$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
						item varchar(256) NOT NULL,
						remark varchar(512) NOT NULL,
						UNIQUE KEY ID (ID)";
			$db->build();

			$insert_data = $this->insert_prelim_data();
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

		new Script();
	}



	//Include settings pages
	public function settings() {

		new Settings();
	}



	// Add custom insight action
	public function insight() {

		new Insight();
	}



	// Add custom insight action
	public function scan() {

		new Scan();
	}


	public function init() {

		register_activation_hook( CGSS_FILE, array( $this, 'db_install' ) );
		register_uninstall_hook( CGSS_FILE, array( 'BUILD', 'db_uninstall' ) ); //$this won't work here.

		add_action('init', array($this, 'installation'));

		$this->scripts();
		$this->settings();

		// Add custom actions, defined in settings
		add_action( 'cgss_scan', array( $this, 'scan' ) );
		add_action( 'cgss_fetch_insight', array( $this, 'insight' ) );
	}
} ?>
