<?php
/**
 * @/core/lib/do-db.php
 * on: 17.06.2015
 * @since 2.0
 *
 * An object for treating text by using methods of xpath and generating array of words along with
 * text to html ration interms of bits size.
 *
 * 1 properties.
 * @prop array $post_id with Ids of post
 * @prop array $data input data
 */
class CGSS_DO_DB {

	//generate individual words from text content
	public function save( $post_id, $data, $option_name ) {
		$save = false;
		$store = $data;
		if ( $post_id and $store ) {
			$pure_store = sanitize_meta ( $option_name, $store, 'post' );
			if ( ! update_post_meta ( $post_id, $option_name, $pure_store ) ) {
				$save = add_post_meta( $post_id, $option_name, $pure_store, true );
			} else {
				$save = update_post_meta ( $post_id, $option_name, $pure_store );
			}
		}
		if ( $save ) {
			return 'done';
		} else {
			return false;
		}
	}

	//generate individual words from text content
	public function xtra_save_option( $option_name, $data ) {
		$save = false;
		$store = $data;
		if ( $option_name and $store ) {
			$pure_store = sanitize_meta( $option_name, $store, 'post' );
			if ( ! update_option( $option_name, $pure_store, 'yes' ) ) {
				$save = add_option( $option_name, $pure_store, '', 'yes' );
			} else {
				$save = update_option( $option_name, $pure_store, 'yes' );
			}
		}
		if ( $save ) {
			return 'done';
		} else {
			return false;
		}
	}

	//Special function for updating option names used for category and tag scan result store.
	public function update_cgss_unique_ids( $name, $data ) {
		if ( $data ) {
			$option_list = get_option( $name );
			if ( $option_list and is_array( $option_list ) ) {
				$data_into = array_merge( $option_list, array( $data ) );
				$data_in = array_unique( $data_into, SORT_STRING );
			} else {
				$data_in = array( $data );
			}
			if ( ! update_option( $name, $data_in, 'yes' ) ) {
				add_option( $name, $data_in, '', 'yes' );
			} else {
				update_option( $name, $data_in, 'yes' );
			}
		}
	}
}
?>
