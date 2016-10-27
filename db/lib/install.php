<?php
/**
 *
 * @package: onpage-seo-checker/db/lib/
 * on: 11.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Initiate database upon activation.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Define the base class for menu and settings
 *
 * 4 properties:
 * 1. $sql_names	(array)		Call SQL queries
 * 2. $cap_name		(string)	Capability name
 * 3. $cap_option	(string)	Capability option table name
 * 4. $v_option		(string)	Version option table name
 * 5. $version		(dec)		Current version number
 * 6. $up_path		(string)	upgrade.php path to create table
 *
 */
if ( ! class_exists( 'ONSEOCHECK_DB_INSTALL' ) ) {

	class ONSEOCHECK_DB_INSTALL {



		private $sql_name;
		private $cap_name;
		private $cap_option;
		private $v_option;
		private $version;
		private $up_path;



		public function __construct() {

			$this->sql_names = array(
								'onseocheck_general',
								'onseocheck_scan',
								'onseocheck_compete',
								);
			$this->cap_name = 'onseocheck_user';
			$this->cap_option = '_onseocheck_capability';
			$this->v_option = '_onseocheck_version';
			$this->version = 2.5;
			$this->up_path = ABSPATH . 'wp-admin/includes/upgrade.php';

			$this->build_db_table();
			$this->build_capaability();
			$this->build_version();
		}



		/**
		 *
		 * Add user capaability
		 *
		 */
		private function build_capaability() {

			update_option( $this->cap_option, $this->cap_name );

			$role = get_role( 'administrator' );
			$role->add_cap( $this->cap_name );
		}



		/**
		 *
		 * update version information
		 *
		 */
		private function build_version() {

			if ( get_option( $this->v_option ) != $this->version ) {
				update_option( $this->v_option, $this->version );
			}
		}



		/**
		 *
		 * Define the necessary database tables
		 *
		 */
		private function build_db_table() {

			global $wpdb;

			$wpdb->hide_errors();

			foreach( $this->sql_names as $key ) {

				$this->table_name = $wpdb->prefix . $key;
				if ( $wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") != $this->table_name ) {

					$this->db = new ONSEOCHECK_SQL( $key, $this->table_name, $this->collate() );
					$sql = $this->db->sql;
					dbDelta( $sql );
				}
			}
		}



		/**
		 *
		 * Define the variables for db table creation
		 *
		 */
		private function collate() {

			global $wpdb;

			$collate = '';
		    if ( $wpdb->has_cap( 'collation' ) ) {
				if( ! empty($wpdb->charset ) )
					$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
				if( ! empty($wpdb->collate ) )
					$collate .= " COLLATE $wpdb->collate";
		    }

    		require_once( $this->up_path );

			return $collate;
		}



		/**
		 *
		 * Check options and tables and output the info to check if db install is successful
		 *
		 */
		public function __destruct() {

			global $wpdb;

			$output = array();
			foreach( $this->sql_names as $key ) {

				$this->table_name = $wpdb->prefix . $key;
				if ( $wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") == $this->table_name ) {
					$output[] = 1;
				}
			}

			update_option( '_onseocheck_total_db_exist', count( $output ) );
		}
	}
} ?>
