<?php
/**
 *
 * @package: onpage-seo-checker/db/lib/
 * on: 12.07.2016
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
 * 1. $cap_name		(string)	Capability name
 * 2. $cap_option	(string)	Capability option table name
 * 3. $v_option		(string)	Version option table name
 * 4. $sql_names	(array)		Table names to be dropped
 *
 */
if ( ! class_exists( 'ONSEOCHECK_DB_UNINSTALL' ) ) {

	class ONSEOCHECK_DB_UNINSTALL {



		private $cap_name;
		private $cap_option;
		private $v_option;
		private $sql_name;



		public function __construct() {

			$this->cap_name = 'onseocheck_user';
			$this->cap_option = '_onseocheck_capability';
			$this->v_option = '_onseocheck_version';
			$this->sql_name = array(
									'onseocheck_general',
									'onseocheck_text',
									'onseocheck_link',
									'onseocheck_speed',
									'onseocheck_social',
									'onseocheck_crawl'
								);

			$this->delete_version();
			$this->delete_capaability();
			$this->delete_db_tables();
		}



		/**
		 *
		 * Remove user capability from all roles having this capability
		 *
		 */
		public function delete_capaability() {

			$roles = get_editable_roles();
			foreach( $roles as $key => $val ) {

				$role = get_role( $key );
				$role->remove_cap( $this->cap_name );
			}

			delete_option( $this->cap_option );
		}



		/**
		 *
		 * Delete version information
		 *
		 */
		public function delete_version() {

			delete_option( $this->v_option );
		}



		/**
		 *
		 * Define the necessary database tables
		 *
		 */
		public function delete_db_tables() {

			global $wpdb;

			foreach( $this->sql_names as $key ) {

				$table_name = $wpdb->prefix . $key;
echo $table_name;
				$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
			}
		}
	}
} ?>
