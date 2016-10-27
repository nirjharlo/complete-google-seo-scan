<?php
/**
 *
 * @package: onpage-seo-checker/db/
 * on: 12.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Add database manipulation objects.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 1 Property:
 * $db_path		(string)	Definition of path
 *
 */
if ( ! class_exists( 'ONSEOCHECK_DB' ) ) {

	final class ONSEOCHECK_DB {



		private $db_path;



		public function __construct() {

			$this->db_path = ONSEOCHECK_DB_PATH . 'lib/';

			$this->dependencies();
		}



		/**
		 *
		 * Add following 4 scripts with classes in it:
		 *
		 * 1. Define the MYSQL queries
		 * 2. Define the queries, using SQL above
		 * 3. Installation of basic db variables
		 * 4. Uninstallation of all db variables
		 *
		 */
		public function dependencies() {

			require_once( $this->db_path . 'sql.php' );
			require_once( $this->db_path . 'query.php' );
			require_once( $this->db_path . 'install.php' );
			require_once( $this->db_path . 'uninstall.php' );
		}
	}
} ?>
