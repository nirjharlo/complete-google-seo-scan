<?php
/**
 *
 * @package: onpage-seo-checker/user/lib/display/
 * on: 10.08.2016
 * @since 2.5
 * @called_in: ONPAGE_SETTINGS
 *
 * Add user display objects.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 1 Property:
 * $user_path		(string)	Definition of path
 *
 */
if ( ! class_exists( 'ONSEOCHECK_CHECK' ) ) {

	class ONSEOCHECK_CHECK extends WP_List_Table {

		

		public function __construct() {

			parent::__construct( [
				'singular' => __( 'URL', 'onseocheck' ),
				'plural'   => __( 'URLs', 'onseocheck' ),
				'ajax'     => false,
			] );
		}



		/**
		 *
		 * Fetch the data
		 *
		 */
		public static function get_check( $per_page = 5, $page_number = 1 ) {

			//SHow this in specific page
			if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'onseocheck' ) return;
			$tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : '0' );
			$type = ( isset( $_GET['type'] ) ? $_GET['type'] : false );

			global $wpdb;

			$sql = "SELECT * FROM {$wpdb->prefix}posts";

			//Following line is important
			$sql .= ( $type ? " WHERE post_type = '$type'" : '' );

			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}

			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			return $result;
		}



		/**
		 *
		 * Delete individual data
		 *
		 */
		public static function delete_url( $id ) {

			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}posts", array( 'ID' => $id ), array( '%s' ) );
		}



		/**
		 *
		 * No data to show
		 *
		 */
		public function no_items() {
			_e( 'No URLs Added yet.', 'onseocheck' );
		}



		/**
		 *
		 * How many rows are present there
		 *
		 */
		public static function record_count() {

			$tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : '0' );
			$type = ( isset( $_GET['type'] ) ? $_GET['type'] : false );

			global $wpdb;
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}posts";

			//Following line is important
			$sql .= ( $type ? " AND post_type = '$type'" : '' );

			return $wpdb->get_var( $sql );
		}



		/**
		 *
		 * Column display
		 *
		 */
		public function column_name( $item ) {
			$delete_nonce = wp_create_nonce( 'delete_url' );
			$title = sprintf( '<strong>%s</strong>', $item['post_title'] );
			$actions = array(
					'delete' => sprintf( '<a href="?page=%s&action=%s&onseocheck=%s&_wpnonce=%s">%s</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce, __( 'Delete', 'onseocheck' ) )
					);
			return $title . $this->row_actions( $actions );
		}



		/**
		 *
		 * set coulmns name
		 *
		 */
		public function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'post_title':
					return $this->column_name( $item );
				case 'last_crawled':
				case 'first_detected':
				case 'responseCode':
				return $item[ $column_name ];
			default:
				//Show the whole array for troubleshooting purposes
				return print_r( $item, true );
			}
		}



		/**
		 *
		 * Set checkboxes to delete
		 *
		 */
		public function column_cb( $item ) {
			return sprintf( '<input type="checkbox" name="bulk-select[]" value="%s" />', $item['ID'] );
		}



		/**
		 *
		 * columns callback
		 *
		 */
		public function get_columns() {
			$columns = array(
							'cb'		=> '<input type="checkbox" />',
							'post_title'	=> __( 'URL', 'onseocheck' ),
							'last_crawled'	=> __( 'Last Crawled', 'onseocheck' ),
							'first_detected'	=> __( 'First Detected', 'onseocheck' ),
							'responseCode'		=> __( 'Response Code', 'onseocheck' ),
						);
			return $columns;
		}



		/**
		 *
		 * decide columns
		 *
		 */
		public function get_sortable_columns() {
			$sortable_columns = array(
				'post_title' => array( 'URL', true ),
				'last_crawled' => array( 'last_crawled', false ),
				'first_detected' => array( 'first_detected', false ),
				'responseCode' => array( 'responseCode', false ),
			);
			return $sortable_columns;
		}



		/**
		 *
		 * determine bulk actions
		 *
		 */
		public function get_bulk_actions() {
			$actions = array(
						'bulk-delete' => 'Delete',
						);
			return $actions;
		}



		/**
		 *
		 * prapare the display variables
		 *
		 */
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();

			/** Process bulk action */
			$this->process_bulk_action();
			$per_page     = $this->get_items_per_page( 'console_logs_per_page', 5 );
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();
			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			) );

			$this->items = self::get_check( $per_page, $current_page );
		}



		/**
		 *
		 * process bulk action
		 *
		 */
		public function process_bulk_action() {

			//Detect when a bulk action is being triggered...
			if ( 'delete' === $this->current_action() ) {

				// In our file that handles the request, verify the nonce.
				$nonce = esc_attr( $_REQUEST['_wpnonce'] );

				if ( ! wp_verify_nonce( $nonce, 'delete_url' ) ) {
					die( 'Go get a life script kiddies' );
				} else {
					self::delete_url( absint( $_GET['onseocheck'] ) );
				}
			}

			// If the delete bulk action is triggered
			if ( isset( $_POST['action'] ) ) {
				if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) ) {
					$delete_ids = esc_sql( $_POST['bulk-select'] );
					foreach ( $delete_ids as $id ) {
						self::delete_url( $id );
					}
				}
			}
		}
	}
} ?>
