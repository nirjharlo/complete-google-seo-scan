<?php
/**
 Plugin Name: Complete Google SEO Scan
 Plugin URI: http://gogretel.com/
 Description: Find issues, check status and get fixes for Seo of individual pages and whole website.
 Version: 2.2
 Author: Gogretel
 Author URI: http://gogretel.com/
 Text Domain: cgss
 Domain Path: /assets/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html

 *
 * This plugin page works in following process:
 *
 * INITIATE --- /assets/ln
 *
 * BUILD
 *   |
 *   | - GET MESSAGES FOR JAVASCRIPT --- /help/message-object.php
 *
 * CALLBACKS
 *   |
 *   |
 *   |
 *   | - SCRIPTS --- /assets/css, /assets/js
 *   |
 *   |
 *   | | - OVERVIEW PAGE --- /user, /user/display
 *   | |   |
 *   | |   | --- FETCH FROM DB --- /db
 *   | |
 *   | |
 *   | | | - PLUGIN'S PAGE --- /user, /user/display
 *   | | |   |
 *   | | |   | --- FETCH FROM DB --- /db
 *   | | |
 *   | | |
 *   | | |   | --- INSERT INTO DB --- /db
 *   | | |   |
 *   | | | - AJAX HANDLE --- /core, /core/lib
 *   | |
 *   | |
 *   | |   | --- INSERT INTO DB --- /db
 *   | |   |
 *   | | - OVERVIEW AJAX HANDLE --- /core, /core/lib
 *   |
 *   |
 *   | - HELP TAB --- /help
 *
 * NOTE: All database calls (/db) in and out is kept only in this file (__FILE__)
 *
 */





/**
 *
 * INITIATE
 */

//define basic variables
defined( 'ABSPATH' ) or exit;
define( 'COMPLETE_GOOGLE_SEO_SCAN_VERSION', cgss_get_version() );
defined( 'COMPLETE_GOOGLE_SEO_SCAN_DEBUG' ) or define( 'COMPLETE_GOOGLE_SEO_SCAN_DEBUG', false );

//load plugin textdomain
add_action( 'plugins_loaded', 'cgss_textdomain_cb' );
function cgss_textdomain_cb() {
	load_plugin_textdomain( 'cgss', false, basename( dirname( __FILE__ ) ) . '/assets/ln/' );
}

//software version returning function
function cgss_get_version() {
	$version = "0.0.1";
	$plugin_file = file_get_contents( __FILE__ );
	preg_match( '#^\s*Version\:\s*(.*)$#im', $plugin_file, $matches );
	if ( ! empty( $matches[1] ) ) {
		$version = $matches[1];
	}
	return $version;
}

//define low php verson errors
if ( version_compare( phpversion(), '5.3', '<' ) ) {
	add_action( 'admin_notices', 'cgss_php_too_low' );
	function cgss_php_too_low() {
	?>
	<div id="message" class="updated notice notice-success is-dismissible below-h2">
		<p><?php echo __( 'Complete Google SEO Scan Plugin will not be activated because your PHP version', 'cgss' ) . ' ' . phpversion() . ' ' . __( 'is less than required 5.3. See more information:', 'cgss' ) . ' '; ?><a href="http://php.net/eol.php/" target="_blank">php.net/eol.php</a></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php _e( 'Dismiss', 'cgss' ); ?></span>
		</button>
	</div>
	<?php
	}
	return;
}

//add settings link to plugin page
add_filter( 'plugin_action_links', 'cgss_scan_page_link', 10, 2 );
function cgss_scan_page_link( $links, $file ) {
	static $this_plugin;

	//Capability Check
	if( ! current_user_can( 'install_plugins' ) )
		return $links;

	//create array shift links
	if ( ! $this_plugin ) {
		$this_plugin = plugin_basename(__FILE__);
	}
	if ( $file == $this_plugin ) {
		$shift_link = array( 
			'<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=seo-scan">' . __( 'Start Here', 'cgss' ) . '</a>',
		);
		foreach( $shift_link as $val ) {
			array_unshift( $links, $val );
		}
	}
	return $links;
}




/**
 *
 * BUILD
 */

//add tools-submenu page
add_action( 'admin_menu', 'cgss_overview_page' );
function cgss_overview_page() {

	//Set user capebilities
	if ( ! current_user_can( 'publish_posts' ) )
		return;

	//Instantiate compiled classes
	$post_types = get_post_types( array( 'public' => true, ), 'names' );
	unset( $post_types['attachment'] );

	//get post types to show
	$remove = get_option('cgss_screen_option_post_types');
	if ( ! $remove ) {
		$remove = $post_types;
	}

	global $cgss_scan_admin;
	$cgss_scan_admin = add_menu_page( __( 'Seo Scan Overview', 'cgss' ), __( 'Seo Scan', 'cgss' ), 'manage_options', 'seo-scan', 'cgss_overview_page_content', 'dashicons-search', '150' );
	add_action( 'load-' . $cgss_scan_admin, 'cgss_add_help_tab' );
	add_action( 'load-' . $cgss_scan_admin, "cgss_overview_add_screen_options" );
	add_action( 'admin_print_scripts-' . $cgss_scan_admin, 'cgss_admin_overview_script' );
	if ( $post_types and is_array( $post_types ) and count( $post_types ) > 0 ) {
		foreach ( $post_types as $type ) {
			$count_posts = wp_count_posts( $type );
			if ( $count_posts->publish > 0 ) {
				if ( in_array( $type, $remove ) ) {
					$type_name = ucwords( get_post_type_object( $type )->labels->singular_name );
					$each_submenu_page = add_submenu_page( 'seo-scan', $type_name . ' ' . __( 'Seo Scan', 'cgss' ), $type_name, 'manage_options', 'seo-scan-' . $type, 'cgss_scan_page_content' );
					add_action( 'load-' . $each_submenu_page, 'cgss_add_help_tab' );
					add_action( 'load-' . $each_submenu_page, "cgss_add_screen_options" );
					add_action( 'admin_print_scripts-' . $each_submenu_page, 'cgss_admin_base_script' );
				}
			}
		}
	}
}

//admin script callback function. Add styles and javascripts to menu pages
function cgss_admin_overview_script() {
	wp_enqueue_script( 'cgss-admin-callbacks', plugins_url() . '/complete-google-seo-scan/assets/js/callbacks.js', array( 'jquery' ), '', false );
	wp_enqueue_script( 'cgss-admin-design', plugins_url() . '/complete-google-seo-scan/assets/js/design.js', array( 'jquery', 'cgss-admin-callbacks' ), '', false );
	wp_enqueue_script( 'cgss-admin-server', plugins_url() . '/complete-google-seo-scan/assets/js/server.js', array( 'jquery', 'cgss-admin-callbacks' ), '', false );
	wp_enqueue_script( 'cgss-admin-scan', plugins_url() . '/complete-google-seo-scan/assets/js/scan.js', array( 'jquery', 'cgss-admin-callbacks' ), '', false );
	wp_enqueue_script( 'cgss-admin-intel', plugins_url() . '/complete-google-seo-scan/assets/js/intel.js', array( 'jquery', 'cgss-admin-callbacks' ), '', false );
	wp_enqueue_script( 'cgss-admin-overview-script', plugins_url() . '/complete-google-seo-scan/assets/js/cgss-overview-script.js', array( 'jquery', 'cgss-admin-callbacks', 'cgss-admin-design', 'cgss-admin-scan', 'cgss-admin-intel' ), '', false );
	wp_enqueue_style( 'cgss-admin-grid-style', plugins_url() . '/complete-google-seo-scan/assets/css/grid.css', '', '2.0', 'all' );
	wp_enqueue_style( 'cgss-admin-advanced-style', plugins_url() . '/complete-google-seo-scan/assets/css/cgss-style.css', '', '2.0', 'all' );
	wp_enqueue_style( 'cgss-admin-loading-style', plugins_url() . '/complete-google-seo-scan/assets/css/loading-style.css', '', '2.0', 'all' );
	wp_enqueue_style( 'cgss-admin-snippet-style', plugins_url() . '/complete-google-seo-scan/assets/css/snippet.css', '', '2.0', 'all' );

	//export messages to overview page javascript, It's done for localization
	require_once('help/message-object.php');
	$msg = new CGSS_MSG();
	$overview_msg = $msg->overview();
	$html_msg = $msg->html();
	$intel_msg = $msg->intel();

	// in JavaScript, object properties are accessed as ajax_object.ajax_url
	wp_localize_script( 'cgss-admin-overview-script', 'overview_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_msg' => $overview_msg, 'ajax_html' => $html_msg, 'ajax_intel' => $intel_msg ) );
}

//ajax callback hook for overview page
add_action( 'wp_ajax_cgss_overview', 'cgss_overview_callback' );

//admin script callback function. Add styles and javascripts to menu pages
function cgss_admin_base_script() {
	wp_enqueue_script( 'cgss-admin-base-callbacks', plugins_url() . '/complete-google-seo-scan/assets/js/callbacks.js', array( 'jquery' ), '', false );
	wp_enqueue_script( 'cgss-admin-base-scan', plugins_url() . '/complete-google-seo-scan/assets/js/scan.js', array( 'jquery' ), '', false );
	wp_enqueue_script( 'cgss-admin-base-script', plugins_url() . '/complete-google-seo-scan/assets/js/cgss-scan-script.js', array( 'jquery', 'cgss-admin-base-callbacks', 'cgss-admin-base-scan' ), '', false );
	wp_enqueue_style( 'cgss-admin-grid-style', plugins_url() . '/complete-google-seo-scan/assets/css/grid.css', '', '2.0', 'all' );
	wp_enqueue_style( 'cgss-admin-advanced-style', plugins_url() . '/complete-google-seo-scan/assets/css/cgss-style.css', '', '2.0', 'all' );
	wp_enqueue_style( 'cgss-admin-loading-style', plugins_url() . '/complete-google-seo-scan/assets/css/loading-style.css', '', '2.0', 'all' );
	wp_enqueue_style( 'cgss-admin-snippet-style', plugins_url() . '/complete-google-seo-scan/assets/css/snippet.css', '', '2.0', 'all' );

	//export messages to scan page javascript for localization
	require_once('help/message-object.php');
	$msg = new CGSS_MSG();
	$scan_msg = $msg->scan();
	$html_msg = $msg->html();

	// in JavaScript, object properties are accessed as ajax_object.ajax_url
	wp_localize_script( 'cgss-admin-base-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_msg' => $scan_msg, 'ajax_html' => $html_msg ) );
}

//ajax callback hook for post types
add_action( 'wp_ajax_cgss_core', 'cgss_core_callback' );


//add screen options
function cgss_add_screen_options() {

	$screen = get_current_screen();
	//stop for non conditions
	if ( ! is_object( $screen ) )
		return;

	$post_types = get_post_types( array( 'public' => true, ), 'names' );
	$chk = false;
	foreach ( $post_types as $type ) {
		if ( $screen->id == 'seo-scan_page_seo-scan-' . $type ) {
			$chk = true;
		}
	}
	if ( ! $chk )
		return;

	$args = array(
			'label' => __('For each post types, number of webpages per "Seo Status" page', 'cgss'),
			'default' => 20,
			'option' => 'cgss_per_page'
	);
	add_screen_option( 'per_page', $args );
}

//Save the data from screen options
add_filter( 'set-screen-option', 'cgss_set_screen_option', 10, 3 );
function cgss_set_screen_option( $status, $option, $value ) {
	if ( 'cgss_per_page' == $option ) return $value;
}

//create post type checkbox option
function cgss_overview_add_screen_options() {

	$screen = get_current_screen();
	if ( $screen->id != 'toplevel_page_seo-scan' )
		return;

	add_filter('screen_layout_columns', 'display_overview_screen_option');
	$screen->add_option('my_option');
}

//display screen option content
function display_overview_screen_option() {

	$post_types = get_post_types( array( 'public' => true, ), 'names' );
	unset( $post_types['attachment'] );
	?>
	<div id="screen-options-wrap" class="hidden" tabindex="-1" aria-label="Screen Options Tab">
		<h5><?php _e( 'Choose Post types to show', 'cgss' ); ?></h5>
		<form name="my_option_form" method="post">
			<div class="metabox-prefs">
				<?php $remove = get_option('cgss_screen_option_post_types');
					if ( ! $remove ) {
						$remove = $post_types;
					}
					$names = '';
					if ( $post_types and is_array( $post_types ) and count( $post_types ) > 0 ) :
					foreach ( $post_types as $type ) : ?>
						<label for="author-hide">
							<input type="checkbox" class="hide-column-tog" name="cgss-post-type-option-<?php echo $type; ?>" value="<?php echo $type; ?>" <?php echo ( in_array( $type, $remove ) ? 'checked="checked"' : '' ); ?>> <?php echo ucwords( get_post_type_object( $type )->labels->singular_name ); ?>
						</label>
				<?php $names .= 'cgss-post-type-option-' . $type . ', ';
				endforeach; endif; ?>
				<input type="hidden" name="overview_screen_option_names" value="<?php echo $names; ?>" />
				<input type="submit" name="overview_screen_option_submit" class="button" value="Apply" />
			</div>
		</form>
	</div>
<?php
}

//save overview screen options data
add_action('wp_loaded', 'save_overview_screen_option');
function save_overview_screen_option() {

	if ( isset( $_POST['overview_screen_option_submit'] ) ) {
		$values = array();
		if ( isset( $_POST['overview_screen_option_names'] ) ) {
			$names = explode( ', ', $_POST['overview_screen_option_names'] );
			foreach ( $names as $val ) {
				if ( isset( $_POST[$val] ) ) {
					$values[] = $_POST[$val];
				}
			}
		}

		//Save reult to database
		require_once( 'db/do-db.php' );
		$data = new CGSS_DO_DB();
		$data_save = $data->xtra_save_option( 'cgss_screen_option_post_types', $values );
	}
}





/**
 *
 * CALLBACKS
 */

//tools submenu page callback
function cgss_overview_page_content() {

	//Get data from database and utilize it
	require_once( 'db/get-db.php' );

	//require front end library to execute this function
	require_once( 'user/front-end.php' );

	//output the html
	require_once('user/display/overview-display.php');
}

//tools submenu page callback
function cgss_scan_page_content() {

	//Get data from database and utilize it
	require_once( 'db/get-db.php' );

	//require front end library to execute this function
	require_once( 'user/front-end.php' );

	//output the html
	require_once('user/display/scan-display.php');
}

//Scan process ajax callback
function cgss_core_callback() {

	//get server and design reult to database
	require_once( 'db/get-db.php' );

	//require front end library to execute this function
	require_once( 'core/ajax-handle.php' );

	//Save reult to database
	require_once( 'db/do-db.php' );

	//add current time for better user access, using wordpress time function. Following variable
	//will return time in GMT, because if server location is changed, user won't be affected.
	$time = current_time( 'timestamp' );
	$time_arr = array(
		'time_now' => $time,
	);
	$complete_result = array_replace( $output_result, $time_arr );

	$data = new CGSS_DO_DB();
	$data_save = $data->save( $complete_result['id'], $complete_result, 'cgss_scan_result' );

	//important to store the option names, to be deleted upon uninstall
	$data_option_name_save = $data->update_cgss_unique_ids( 'cgss_seo_option_ids', $output_result['id'] );
	wp_die();
}

//Scan process ajax callback
function cgss_overview_callback() {

	//get server and design reult to database
	require_once( 'db/get-db.php' );

	//require front end library to execute this function
	require_once( 'core/overview-ajax.php' );

	//Save reult to database
	require_once( 'db/do-db.php' );

	//Save data for different situations
	if ( $output_result ) {

		$data = new CGSS_DO_DB();
		if ( $request_type == 'scan' ) {
			$time = current_time( 'timestamp' );
			$complete_result = array_replace( $output_result, array( 'time_now' => $time, ) );
			$data_save = $data->xtra_save_option( $complete_result['id'], $complete_result );

			//important to store the option names, to be deleted upon uninstall
			$data_option_name_save = $data->update_cgss_unique_ids( 'cgss_seo_option_names', $output_result['id'] );
		} elseif ( $request_type == 'server' ) {
			$data_save = $data->xtra_save_option( 'cgss_server_seo_data', $output_result );
		} elseif ( $request_type == 'design' ) {
			$data_save = $data->xtra_save_option( 'cgss_design_seo_data', $output_result );
		} elseif ( $request_type == 'intel' ) {
			$data_save = $data->xtra_save_option( 'cgss_intel_seo_data', $output_result );
		}
	}
	wp_die();
}

//Create help tab function for seo overview page
function cgss_add_help_tab() {

	global $cgss_scan_admin;
	$screen = get_current_screen();

	//get help documentation content
	require_once( 'help/plugin-help-object.php' );
	$help = new CGSS_HELP();
	$help_data = $help->data();
	$help_links = $help->links();

	// Add help docs
	foreach ( $help_data as $key ) {
		$screen->add_help_tab( $key );
	}

	// Add help links
	$screen->set_help_sidebar( $help_links );
} ?>
