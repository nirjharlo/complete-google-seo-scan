<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//Main plugin object to define the plugin
if ( ! class_exists( 'CGSS_BUILD' ) ) {
	
	final class CGSS_BUILD {



		public function installation() {

			/**
			*
			* Plugin installation
			*
			if (class_exists('CGSS_INSTALL')) {

				$install = new CGSS_INSTALL();
				$install->textDomin = 'textdomain';
				$install->phpVerAllowed = '';
				$install->pluginPageLinks = array(
												array(
													'slug' => '',
													'label' => ''
												),
											);
				$install->do();
			}
			*
			*/
		}



		public function db_install() {

			/**
			*
			* Install DB options
			*
			$options = array(
							array( 'option_name', '__value__' ),
						);
			foreach ($options as $value) {
				update_option( $value[0], $value[1] );
			}
			*
			*/
		}



		public function db_uninstall() {

			/**
			*
			$options = array(
								'_plugin_db_exist'
							);
			foreach ($options as $value) {
				delete_option($value);
			}
			*
			*/
		}



		//Include settings pages
		public function settings() {

			if ( class_exists( 'CGSS_SETTINGS' ) ) new CGSS_SETTINGS();
		}



		//Add customization files
		public function customization() {

			require_once ('src/install.php');
			require_once ('src/settings.php');
		}



		//Call the dependency files
		public function helpers() {

			require_once ('lib/table.php');
			require_once ('lib/ajax.php');
			require_once ('lib/script.php');
		}



		public function __construct() {

			$this->helpers();
			$this->customization();

			register_activation_hook( PLUGIN_FILE, array( $this, 'db_install' ) );
			register_uninstall_hook( PLUGIN_FILE, array( 'CGSS_BUILD', 'db_uninstall' ) ); //$this won't work here.

			add_action('init', array($this, 'installation'));
			add_action('init', array($this, 'functionality'));

			$this->settings();
		}
	}
} ?>