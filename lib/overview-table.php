<?php
/**
 * Implimentation of WordPress inbuilt functions for creating an extension of a default table class.
 *
 * $myPluginNameTable = new CGSS_OVERVIEW_TABLE();
 * $myPluginNameTable->prepare_items();
 * $myPluginNameTable->display();
 *
 */
if ( ! class_exists( 'CGSS_OVERVIEW_TABLE' ) ) {

	final class CGSS_OVERVIEW_TABLE extends WP_List_Table {



		public function __construct() {

			parent::__construct( [
				'singular' => __( 'Insight', 'cgss' ),
				'plural'   => __( 'Inssight', 'cgss' ),
				'ajax'     => false,
			] );
		}



		//fetch the data using custom named method function
		public static function get_insight() {

			global $wpdb;

			//Build the db query base
			$sql = "SELECT * FROM {$wpdb->prefix}cgss_insight";

			//get the data from database
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			return $result;
		}



		//If there is no data to show
		public function no_items() {

			_e( 'Database is empty. Please reactivate the plugin.', 'cgss' );
		}



		//How many rows are present there
		public static function record_count() {

			global $wpdb;

			//Build the db query base
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}cgss_insight";

			return $wpdb->get_var( $sql );
		}



		//Display columns content
		public function column_name( $item ) {

			$title = sprintf( '<strong>%s</strong>', $item['item'] );

			//Change the page instruction where you want to show it
			$actions = array();
			return $title . $this->row_actions( $actions );
		}



		//set coulmns name
		public function column_default( $item, $column_name ) {

			switch ( $column_name ) {

				case 'item':
					//This is the first column
					return $this->column_name( $item );
				case 'remark':
					return $item[ $column_name ];

				default:

					//Show the whole array for troubleshooting purposes
					return print_r( $item, true );
			}
		}



		//Columns callback
		public function get_columns() {

			$columns = array(
							'item'	=> __( 'Item', 'cgss' ),
							'remark'	=> __( 'Remark', 'cgss' ),
						);
			return $columns;
		}



		//Prapare the display variables for screen options
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();
			$this->items = self::get_insight();
		}
	}
} ?>
