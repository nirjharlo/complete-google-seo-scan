<?php
/**
 * @/uninstall.php
 * on: 13.06.2015
 * @since 2.0.3
 *
 * Object to get tag values and tag attributes with different methods. Return values are in array()
 * with numeric indexes.
 */
// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

//delete post meta data if exists at all
$post_types = get_post_types( '', 'names' );
foreach( $post_types as $val ) {
	$get_posts = array( 'posts_per_page' => -1, 'post_type' => $val );
	$post_data = get_posts( $get_posts );
	foreach ( $post_data as $key ) {
		if ( get_post_meta( $key->ID, 'cgss_scan_result', true ) ) {
			delete_post_meta( $key->ID, 'cgss_scan_result' );
		}
	}
}

//delete non-post specific options data
$option_names = get_option( 'cgss_seo_option_names' );
$del_arr = array( 'cgss_seo_option_ids', 'cgss_server_seo_data', 'cgss_design_seo_data', 'cgss_seo_option_names', 'cgss_screen_option_post_types' );
if ( $option_names ) {
	$del_arr = array_merge( $option_names, $del_arr );
}
foreach( $del_arr as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}
?>
