<?php
/**
 * Add scripts to the plugin. CSS and JS.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'CGSS_SCRIPT' ) ) {

	final class CGSS_SCRIPT {


		public function __construct() {

			add_action( 'admin_head', array( $this, 'data_table_css' ) );
		}



		// Table css for settings page data tables
		public function data_table_css() {

			$table_css = '<style type="text/css">
							.wp-list-table .column-post_title { width: 42.5%; }
							.wp-list-table .column-focus { width: 15%; }
							.wp-list-table .column-word { width: 7.5%; }
							.wp-list-table .column-column-link { width: 7.5%; }
							.wp-list-table .column-column-image { width: 7.5%; }
							.wp-list-table .column-column-share { width: 7.5%; }
							.wp-list-table .column-column-time { width: 12.5%; }
						</style>';

			$overview_table_css = '<style type="text/css">
							.wp-list-table .column-item { width: 20%; }
							.wp-list-table .column-remark { width: 80%; }
						</style>';

			echo $table_css;
			echo $overview_table_css;
		}
	}
} ?>