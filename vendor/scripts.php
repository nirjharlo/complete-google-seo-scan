<?php
/**
 *
 * @package: onpage-seo-checker/admin/
 * on: 16.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Add admin manipulation objects. Equilavant to Controllor in MVC.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 */
if ( ! class_exists( 'ONSEOCHECK_SCRIPTS' ) ) {

	final class ONSEOCHECK_SCRIPTS {



		public function __construct() {

			add_action( 'admin_head', array( $this, 'shamsher' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		}



		/**
		 *
		 * Table css output for checks page
		 *
		 */
		public function shamsher() {

			if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'onseocheck' ) return;

			$table_css = '<style type="text/css">
							.wp-list-table .column-URL { width: 45%; }
							.wp-list-table .column-last_crawl { width: 22.5%; }
							.wp-list-table .column-first_crawl { width: 22.5%; }
							.wp-list-table .column-error_code { width: 10%; }
						</style>';

			return $table_css;
		}



		/**
		 *
		 * Enter scripts into pages
		 *
		 */
		public function scripts() {

			if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'onseocheck' ) return;

			wp_enqueue_script( 'js', ONSEOCHECK_JS_PATH . 'ui.js', array('jquery') );
			wp_enqueue_style( 'css', ONSEOCHECK_CSS_PATH . 'css.css' );
		}
	}
} ?>
