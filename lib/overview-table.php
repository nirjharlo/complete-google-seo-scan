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
				'singular' => __( 'Insight', 'textdomain' ),
				'plural'   => __( 'Inssight', 'textdomain' ),
				'ajax'     => false,
			] );
		}



		//fetch the data using custom named method function
		public static function get_insight( $per_page = 5, $page_number = 1 ) {

			global $wpdb;

			//Build the db query base
			$sql = "SELECT * FROM {$wpdb->prefix}cgss_insight";

			//Set filters in the query using $_REQUEST
			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

			//get the data from database
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			return $result;
		}



		//If there is no data to show
		public function no_items() {

			_e( 'No insight available yet. Click the button on top to fetch insight.', 'cgss' );
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
							'item'	=> __( 'Item', 'textdomain' ),
							'remark'	=> __( 'Remark', 'textdomain' ),
						);
			return $columns;
		}



		//Prapare the display variables for screen options
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();

			/** Process bulk action */
			$this->process_bulk_action();
			$per_page     = $this->get_items_per_page( 'item_per_page', 5 );
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();
			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			) );

			$this->items = self::get_insight( $per_page, $current_page );
		}
	}
} ?>