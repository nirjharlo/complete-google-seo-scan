<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
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
?>
