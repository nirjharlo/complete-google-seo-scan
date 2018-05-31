<?php
/**
 * Implimentation of WordPress inbuilt functions for creating an extension of a default table class.
 * 
 * $myPluginNameTable = new CGSS_TABLE();
 * $myPluginNameTable->prepare_items();
 * $myPluginNameTable->display();
 *
 */
if ( ! class_exists( 'CGSS_TABLE' ) ) {

	final class CGSS_TABLE extends WP_List_Table {


		public function __construct() {

			parent::__construct( [
				'singular' => __( 'Post', 'cgss' ),
				'plural'   => __( 'Posts', 'cgss' ),
				'ajax'     => false,
			] );
		}



		//fetch the data using custom named method function
		public static function get_posts( $per_page = 10, $page_number = 1 ) {

			global $wpdb;

			$page = isset($_GET['page']) ? substr($_GET['page'], 9) : 'post';

			//Build the db query base
			$sql = "SELECT * FROM {$wpdb->prefix}posts";
			$sql .= " QUERIES WHERE post_status='publish' AND post_type='$page'";

			//Set filters in the query using $_REQUEST
			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

			//get the data from database
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );

			// FETCH POST META DATA AND MERGE IT WITH RESULTS
			$result = SELF::get_post_meta_data($result);

			return $result;
		}



		//If there is no data to show
		public function no_items() {

			_e( 'No Published Posts are available.', 'cgss' );
		}



		//How many rows are present there
		public static function record_count() {

			global $wpdb;

			//Take pivotal from URL
			$page = isset($_GET['page']) ? substr($_GET['page'], 9) : 'post';

			//Build the db query base
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}posts";
			$sql .= " QUERIES WHERE post_status='publish' AND post_type='$page'";

			return $wpdb->get_var( $sql );
		}



		//Display columns content
		public function column_name( $item ) {

			$title = sprintf( '<strong>%s</strong>', $item['post_title'] );

			//Change the page instruction where you want to show it
			$actions = array(
					'scan' => sprintf( '<a href="?page='.$_GET['page'].'&scan='.$item['ID'].'" target="_blank">%s</a>', __( 'Scan', 'cgss' ) ),
					'compete' => sprintf( '<a href="?page='.$_GET['page'].'&compete='.$item['ID'].'" target="_blank">%s</a>', __( 'Compete', 'cgss' ) )
					);
			return $title . $this->row_actions( $actions );
		}



		//set coulmns name
		public function column_default( $item, $column_name ) {

			switch ( $column_name ) {

				case 'post_title':
					//This is the first column
					return $this->column_name( $item );
				case 'focus':
				case 'word':
				case 'link':
				case 'image':
				case 'share':
					return $item[ $column_name ];
				case 'time':
					return $item[ $column_name ];
				default:
					//Show the whole array for troubleshooting purposes
					return print_r( $item, true );
			}
		}



		//Columns callback
		public function get_columns() {

			$columns = array(
							'post_title'	=> __( 'Post', 'cgss' ),
							'focus'	=> __( 'Focus', 'cgss' ),
							'word'	=> __( 'Words', 'cgss' ),
							'link'	=> __( 'Links', 'cgss' ),
							'image'	=> __( 'Images', 'cgss' ),
							'share'	=> __( 'Shares', 'cgss' ),
							'time'	=> __( 'Time(ms)', 'cgss' ),
							
						);
			return $columns;
		}



		//Decide columns to be sortable by array input
		public function get_sortable_columns() {

			$sortable_columns = array(
				'post_title' => array( 'post_title', true ),
			);
			return $sortable_columns;
		}



		//Prapare the display variables for screen options
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();

			/** Process bulk action */
			$per_page     = $this->get_items_per_page( 'post_per_page', 5 );
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();
			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			) );

			$this->items = self::get_posts( $per_page, $current_page );
		}



		public static function get_post_meta_data($result) {

			$IDs = array_column($result, 'ID');
			$titles = array_column($result, 'post_title');

			$empty_metas = array(
							'text' => array(
										'count' => '--',
										'top_key' => '--',
										'links' => array( 'num' => '--' ),
										),
							'design' => array(
										'image' => array( 'count' => '--' )
										),
							'social' => array('num' => '--' ),
							'speed' => array( 'res_time' => '--' ),
							);

			$metas = array();
			foreach ($IDs as $post_id) {
				$meta = get_post_meta( $post_id, 'cgss_scan_result', true );
				$metas[] = is_array($meta) ? $meta : $empty_metas;
			}
			$text = array_column($metas, 'text');
			$words = array_column($text, 'count');
			$focus = array_column($text, 'top_key');
			$link = array_column($text, 'links');
			$link_count = array_column($link, 'num');

			$design = array_column($metas, 'design');
			$image = array_column($design, 'image');
			$image_count = array_column($image, 'count');

			$social = array_column($metas, 'social');
			$share = array_column($social, 'num');

			$speed = array_column($metas, 'speed');
			$res_time = array_column($speed, 'res_time');

			$result = array();
			foreach ($IDs as $key => $ID) {
				$temp = array();
				$temp['ID'] = $ID;
				$temp['post_title'] = $titles[$key];
				$temp['focus'] = $focus[$key];
				$temp['word'] = $words[$key];
				$temp['link'] = $link_count[$key];
				$temp['image'] = $image_count[$key];
				$temp['share'] = $share[$key];
				$temp['time'] = $res_time[$key];

				$result[] = $temp;
			}
			return $result;
		}
	}
} ?>