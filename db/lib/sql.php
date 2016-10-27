<?php
/**
 *
 * @package: onpage-seo-checker/db/lib/
 * on: 11.07.2016
 * @since 2.5
 * @called_in: ONPAGE_DB_INSTALL, ONPAGE_QUERY, ONSEOCHECK_DB_UNINSTALL
 *
 * Database SQL.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Define the base class for menu and settings
 *
 * 4 properties:
 * 1. $sql			(string)	The sql outputr
 * 2. $type			(string)	Type of SQL, name of function
 * 3. $table_name	(string)	DB table name
 * 4. $collate		(string)	DB collate parameter
 *
 */
if ( ! class_exists( 'ONSEOCHECK_SQL' ) ) {

	class ONSEOCHECK_SQL {



		public $sql;
		private $type;
		private $table_name;
		private $collate;



		public function __construct( $type, $table_name, $collate ) {

			$this->type = $type;
			$this->table_name = $table_name;
			$this->collate = $collate;
			$this->sql = false;

			$this->sql = call_user_func( array( $this, $this->type ), $this->table_name, $this->collate );
		}



		/**
		 *
		 * SQL query to create the main plugin table.
		 *
		 */
		private function onseocheck_general( $table_name, $collate ) {

			return "CREATE TABLE $table_name (
					ID mediumint(9) NOT NULL AUTO_INCREMENT,
					post_id mediumint(6) NOT NULL,
					responseCode mediumint(3) NOT NULL,
					post_type smallint(1) NOT NULL,
					last_crawled datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					first_detected datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					marks smallint(4) NOT NULL,
					score decimal(3,1) NOT NULL,
					UNIQUE KEY ID (ID) ) $collate;";
		}



		/**
		 *
		 * SQL query to create the main plugin table.
		 *
		 */
		private function onseocheck_scan( $table_name, $collate ) {

			return "CREATE TABLE $table_name (
					ID mediumint(9) NOT NULL AUTO_INCREMENT,
					post_id mediumint(6) NOT NULL,
					json_key varchar(256) NOT NULL,
					data text NOT NULL,
					UNIQUE KEY ID (ID) ) $collate;";
		}



		/**
		 *
		 * SQL query to create the compete table.
		 *
		 */
		private function onseocheck_compete( $table_name, $collate ) {

			return "CREATE TABLE $table_name (
					ID mediumint(9) NOT NULL AUTO_INCREMENT,
					post_id mediumint(6) NOT NULL,
					json_key varchar(256) NOT NULL,
					data text NOT NULL,
					UNIQUE KEY ID (ID) ) $collate;";
		}
	}
} ?>
