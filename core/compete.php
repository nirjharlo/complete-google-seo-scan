<?php
/**
 * @/core/compete.php
 * on: 24.08.2015
 * @since 2.3
 *
 * Ajax call handle for main seo scan.
 */

//Check if it's a valid request if it contains server or it has a required value.
if ( ! isset( $_POST['type'] ) ) {
	echo json_encode( array( 'ping' => 'false', 'val' => __( 'Invalid Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
	wp_die();
}

$request_type = $_POST['type'];

//If scan type is not set to any proper value, stop scan.
if ( $request_type != 'compete' and $request_type != 'fetch' and $request_type != 'save' ) {
	echo json_encode( array( 'ping' => 'false', 'val' => __( 'Improper Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
	wp_die();
}


//Normal archive scan
if ( $request_type == 'compete' ) {

	//Check if it's a valid request. It will be delivered to json.
	if ( ! isset( $_POST['key'] ) or ! isset( $_POST['url'] ) or ! isset( $_POST['id'] ) ) {
		echo json_encode( array( 'ping' => 'false', 'val' => __( 'Invalid Request', 'cgss' ) ), JSON_FORCE_OBJECT );
		wp_die();
	}

	//see if xtended plugin is installed or not
	function cgss_core_plugin_xtend() {
		$xtend = false;
		$all_plugins = get_option('active_plugins');
		if ( $all_plugins ) {
			foreach ( $all_plugins as $key => $plug ) {
				if ( $plug == 'xtend-complete-google-seo-scan/xtend-complete-google-seo-scan.php' ) {
					$xtend = true;
				}
			}
		}
		return $xtend;
	}

	//if extension is not present
	$output_result = array( 'ping' => 'free' );

	//PUT PREMIUM HERE
	$xtend = cgss_core_plugin_xtend();
	if ( $xtend != false ) {
		require_once( __DIR__ . '/../../xtend-complete-google-seo-scan/core.php' );
	}

	//Export result to front end.
	echo json_encode( $output_result, JSON_FORCE_OBJECT );
}

//if a save data request comes
if ( $request_type == 'save' ) {

	//Check if it's a valid request. It will be delivered to json.
	if ( ! isset( $_POST['id'] ) or ! isset( $_POST['comp_key'] ) or ! isset( $_POST['comp_url'] ) ) {
		echo json_encode( array( 'ping' => 'false', 'val' => __( 'Invalid Request', 'cgss' ) ), JSON_FORCE_OBJECT );
		wp_die();
	}

	$output_result = array(
						'id' => $_POST['id'],
						'comp_key' => $_POST['comp_key'],
						'comp_url' => $_POST['comp_url'],
						'ssl' => $_POST['ssl'],
						'mobile' => $_POST['mobile'],
						'words' => $_POST['words'],
						'links' => $_POST['links'],
						'links_ext' => $_POST['links_ext'],
						'links_nof' => $_POST['links_nof'],
						'thr' => $_POST['thr'],
						'images' => $_POST['images'],
						'speed' => $_POST['speed'],
						'key_count' => $_POST['key_count'],
						'key_per' => $_POST['key_per'],
						'gplus' => $_POST['gplus'],
						'fb' => $_POST['fb'],
						'tw' => $_POST['tw'],
						'domain' => $_POST['domain'],
						'title' => $_POST['title'],
						'url' => $_POST['url'],
						'desc' => $_POST['desc'],
						'alt' => $_POST['alt'],
						'anch' => $_POST['anch'],
						'plain' => $_POST['plain'],
						'bold' => $_POST['bold'],
					);
	//continue to save it on main plugin page
}

//if a save data request comes
if ( $request_type == 'fetch' ) {

	//Check if it's a valid request. It will be delivered to json.
	if ( ! isset( $_POST['find_post_id'] ) ) {
		echo json_encode( array( 'ping' => 'false', 'val' => __( 'Invalid Request', 'cgss' ) ), JSON_FORCE_OBJECT );
		wp_die();
	}

	$post_id = $_POST['find_post_id'];
	//continue to fetch data on main plugin page
} ?>
