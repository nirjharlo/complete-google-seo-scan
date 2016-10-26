<?php
/**
 * @/core/lib/overview-ajax.php
 * on: 16.07.2015
 * @since 2.1
 *
 * Ajax call handle for server and design seo scan.
 *
 * 2 step process flow:
 * 1. INITIATE
 * 2. CHECKS
 */

//Check if it's a valid request if it contains server or it has a required value.
if ( ! isset( $_POST['type'] ) ) {
	echo json_encode( array( 'ping' => 'false', 'val' => __( 'Invalid Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
	wp_die();
}

$request_type = $_POST['type'];

//If scan type is not set to any proper value, stop scan.
if ( $request_type != 'scan' and $request_type != 'server' and $request_type != 'design' and $request_type != 'intel' ) {
	echo json_encode( array( 'ping' => 'false', 'val' => __( 'Improper Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
	wp_die();
}

//Normal archive scan
if ( $request_type == 'scan' ) {

	//require front end library to execute this function
	require_once( 'ajax-handle.php' );
}

//Server seo scan
if ( $request_type == 'server' ) {

	//Call the custom function library in. Independent objects
	require_once( 'lib/url-object.php' );
	require_once( 'lib/error-object.php' );
	require_once( 'lib/server-object.php' );
	require_once( 'lib/header-object.php' );

	//Get the url of main WordPress directory
	$url = site_url();

	//Get headers
	$time_start = microtime(true);
	$header = get_headers( $url, 1 );
	$time_end = microtime(true);

	//if fails to call headers
	if ( ! $header ) {
		echo json_encode( array( 'ping' => 'false',	'val' => __( 'Network Error', 'cgss' ) ), JSON_FORCE_OBJECT );
		wp_die();
	}

	$hresponse = $header[0];

	//Check if headers are ok or not
	$err = new CGSS_HEADERS_ERROR( $hresponse );
	$head_check = $err->check();
	if ( $head_check ) {
		echo json_encode( array( 'ping' => 'false',	'val' => __( 'Failed, Server Issue', 'cgss' ) . ': ' . $hresponse ), JSON_FORCE_OBJECT );
		wp_die();
	}

	$output_data = new CGSS_HEADER_CHK( $url, $header, $time_start, $time_end );
	$output_result = $output_data->result();
	echo json_encode( $output_result, JSON_FORCE_OBJECT );
}

//Design seo scan
if ( $request_type == 'design' ) {

	//Check for css and js is present or not.
	if ( ! isset( $_POST['css'] ) and ! isset( $_POST['js'] ) ) {
		echo json_encode( array( 'ping' => 'false', 'val' => __( 'Improper Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
		wp_die();
	}

	//get enqueued scripts and styles
	$css_arr = $_POST['css'];
	$js_arr = $_POST['js'];

	//Check for css and js is properly formatted.
	if ( ! is_array( $css_arr ) or ! is_array( $js_arr ) ) {
		echo json_encode( array( 'ping' => 'false', 'val' => __( 'Improper Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
		wp_die();
	}

	$css_count = count( $css_arr );
	$js_count = count( $js_arr );

	//Check for css and js is present.
	if ( $css_count == 0 and $js_count == 0 ) {
		echo json_encode( array( 'ping' => 'false', 'val' => __( 'No Files Found', 'cgss' ) ), JSON_FORCE_OBJECT ); 
		wp_die();
	}

	//include error object
	require_once( 'lib/error-object.php' );
	require_once( 'lib/design-object.php' );

	//Check for css and js is present.
	$css_calls = new CGSS_DESIGN( $css_arr );
	$css = $css_calls->analyze();
	$css_result = array(
					'css_num' => $css['num'],
					'css_size' => $css['size'],
					'css_import' => $css['import'],
					'css_media' => $css['media'],
					'css_compress_num' => $css['compress_num'],
					'css_compress_size' => $css['compress_size'],
				);

	//Check for css and js is present.
	$js_calls = new CGSS_DESIGN( $js_arr );
	$js = $js_calls->analyze();
	$js_result = array(
					'js_num' => $js['num'],
					'js_size' => $js['size'],
					'js_compress_num' => $js['compress_num'],
					'js_compress_size' => $js['compress_size'],
				);

	$output_result = array_merge( $css_result, $js_result );
	echo json_encode( $output_result, JSON_FORCE_OBJECT );
}

//Fetch intel
if ( $request_type == 'intel' ) {

	//Check for css and js is present or not.
	if ( ! isset( $_POST['fetch'] ) ) {
		echo json_encode( array( 'ping' => 'false', 'val' => __( 'Improper Request', 'cgss' ) ), JSON_FORCE_OBJECT ); 
		wp_die();
	}

	$id = $_POST['fetch'];

	//Call the custom db fetch function
	$time_now = current_time( 'timestamp' );
	$data = new CGSS_GET_DB( 'cgss_scan_result', $id, $time_now );
	$intel = $data->intel();

	$output_result = array( 'ping' => 'ok', 'score' => $intel['marks'], 'share' => $intel['share'], 'stags' => $intel['stags'], 'time' => $intel['time'], 'url' => $intel['url'], 'mobile' => $intel['mobile'], 'image' => $intel['image'], 'keyword' => $intel['keyword'], 'links' => $intel['links'], 'words' => $intel['words'], 'ratio' => $intel['ratio'] );
	echo json_encode( $output_result, JSON_FORCE_OBJECT );
}
?>
