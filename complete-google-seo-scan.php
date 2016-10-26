<?php
/**
 Plugin Name: Complete Google SEO Scan
 Plugin URI: http://gogretel.com/
 Description: It's name speaks for itself. Find issues, check status and get fixes for seo of individual pages and whole website.
 Version: 2.0.3
 Author: Nirjhar Lo
 Author URI: http://gogretel.com/about/ 
 Text Domain: cgss
 Domain Path: /user/assets/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html

/**
 * This plugin page works in following process:
 * 1. Setup the plugin for errors, setup links
 * 2. Set up management page, scripts actions and ajax callback
 * 3. Define callbacks for
 * 		3.1. Help tab function
 * 		3.2. Management page display function
 * 		3.3. Ajax handling core function
 */


//define basic variables
defined( 'ABSPATH' ) or exit;
define( 'COMPLETE_GOOGLE_SEO_SCAN_VERSION', cgss_get_version() );
defined( 'COMPLETE_GOOGLE_SEO_SCAN_DEBUG' ) or define( 'COMPLETE_GOOGLE_SEO_SCAN_DEBUG', false );

//load plugin textdomain
add_action( 'plugins_loaded', 'cgss_textdomain_cb' );
function cgss_textdomain_cb() {
	load_plugin_textdomain( 'cgss', false, basename( dirname( __FILE__ ) ) . '/user/assets/ln/' );
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
			'<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/tools.php?page=seo-overview">' . __( 'Scan Now', 'cgss' ) . '</a>',
		);
		foreach( $shift_link as $val ) {
			array_unshift( $links, $val );
		}
	}
	return $links;
}





//add tools-submenu page
add_action( 'admin_menu', 'cgss_overview_page' );
function cgss_overview_page() {

	//Set user capebilities
	if ( ! current_user_can( 'publish_posts' ) )
		return;

	global $cgss_scan_admin;
	$cgss_scan_admin = add_management_page( __( 'Seo Scan', 'cgss' ), __( 'Seo Scan', 'cgss' ), 'manage_options', 'seo-overview', 'cgss_overview_page_content' );
	add_action( 'admin_print_scripts-' . $cgss_scan_admin, 'cgss_admin_base_script' );
	add_action( 'load-' . $cgss_scan_admin, 'cgss_add_help_tab' );
}

//ajax callback hook
add_action( 'wp_ajax_cgss_core', 'cgss_core_callback' );





//admin script callback function. Add styles and javascripts to menu pages
function cgss_admin_base_script() {
	wp_enqueue_script( 'cgss-tooltip-script', plugins_url() . '/complete-google-seo-scan/user/assets/bootstrap-tooltip.min.js', array( 'jquery' ), '', false );
	wp_enqueue_script( 'cgss-admin-base-script', plugins_url() . '/complete-google-seo-scan/user/assets/cgss-script.js', array( 'jquery' ), '', false );
	wp_enqueue_style( 'cgss-admin-advanced-style', plugins_url() . '/complete-google-seo-scan/user/assets/cgss-style.css', '', '2.0', 'all' );

	// in JavaScript, object properties are accessed as ajax_object.ajax_url
	wp_localize_script( 'cgss-admin-base-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

//Create help tab function for seo overview page
function cgss_add_help_tab () {

	global $cgss_scan_admin;
	$screen = get_current_screen();

	//get help documentation content
	require_once( 'user/lib/help-object.php' );
	$help = new CGSS_HELP();
	$help_data = $help->data();
	$help_link = $help->links();

	// Add help docs
	foreach ( $help_data as $key ) {
		$screen->add_help_tab( $key );
	}

	// Add help links
	$screen->set_help_sidebar( $help_link );
}


//tools submenu page callback
function cgss_overview_page_content() {

	//Get time now. It will be fixed for the season since user isn't expected to stay in this page
	//more than a day in a single season. Filtering posts based on time won't show much variation
	//as minimum input is 1 day.

	//Get data from database and utilize it
	require_once( 'db/get-db.php' );
	function scan_data() {
		$time = current_time( 'timestamp' );
		$data_from_db = new CGSS_GET_DB( 'cgss_scan_result', $time );
		$scan_data = $data_from_db->fetch();
		$avg_time = $data_from_db->avg_scan_time();
		return array( $scan_data, $avg_time );
	}
	//require front end library to execute this function
	require_once( 'user/front-end.php' );

	//Instantiate compiled classes
	$btns = new cgss_group_btn();
	$title = new cgss_do_title();
	$dpd = new cgss_do_dpd();
	$rtable = new cgss_do_table();
	$ok = '<span class="dashicons dashicons-yes success-icon"></span>';
	$flag = '<span class="dashicons dashicons-flag warning-icon"></span>';
	$no = '<span class="dashicons dashicons-no-alt danger-icon"></span>';
?>
	<!--Begin body markup for scan form, scan process and scan report display sections-->
	<div class="wrap about-wrap">
		<?php echo $title->top(); ?>
		<div id="loadingProgressG">
			<div id="loadingProgressG_1" class="loadingProgressG"></div>
		</div>
		<div class="clear"></div>
		<!--Scan Process Area-->
		<div id="message" class="notice notice-failure">
			<p><span id="ShowMessage"></span><span class="message-dismiss alignright"><span class="dashicons dashicons-dismiss danger-icon"></span></span></p>
		</div>
		<div id="<?php $scan_data = scan_data(); echo $scan_data[1]; ?>" class="scan-process">
			<?php echo $rtable->process(); ?>
		</div>
		<!--Scan Form Section-->
		<div class="scan-form">
			<div class="tablenav top">
				<div class="alignleft">
					<?php echo $dpd->types(); ?>
				</div>
				<div class="alignright">
					<span id="UnFilterAlert" class="hide"><?php _e( 'No Match Found', 'cgss' ); ?></span>
					<span id="FilterAlert" class="hide"><?php _e( 'All Pages Matched', 'cgss' ); ?></span>
					<?php echo $dpd->filter(); ?>
				</div>
			</div>
			<form id="scanlink">
				<?php $post_type_list = $dpd->post_types();
				foreach ( $post_type_list as $type ) :
					$table = new cgss_form_table( $type );
					echo $table->form();
				endforeach; ?>
			</form>
		</div>
		<!--Scan Report Section-->
		<div class="scan-report">
			<div class="theme-overlay">
				<div class="theme-overlay active">
					<div class="theme-backdrop"></div>
					<div class="theme-wrap">
						<div class="theme-header">
							<button id="ReportClose" class="close dashicons dashicons-no">
								<span class="screen-reader-text"></span>
							</button>
						</div>
						<div class="theme-about printable">
							<div class="grid-container">
								<div class="row">
									<div class="col-3">
										<div class="row">
											<div class="score-pie">
												<h1 id="ShowMarks"></h1>
												<span class="total-marks">100</span>
											</div>
										</div>
										<div class="row">
											<div class="clear"></div>
											<div class="score-stars">
												<span id="ScoreStarts"></span>
											</div>
											<div class="clear"></div>
											<div class="row social">
												<span class="facebook"><span class="dashicons dashicons-facebook"></span> <span id="FbShare"></span></span>
												<span class="gplus"><span class="dashicons dashicons-googleplus"></span> <span id="GplusCount"></span></span>
												<span class="twitter"><span class="dashicons dashicons-twitter"></span> <span id="TweeetCount"></span></span>
												<br /><br />
												<span id="Fbmsg"><?php _e( 'Shares', 'cgss' ); ?></span>
												<span id="GplusMsg">+1 s</span>
												<span id="TwMsg"><?php _e( 'Tweets', 'cgss' ); ?></span>
											</div>
										</div>
									</div>
									<div class="col-3">
										<div class="row">
											<span class="scan-time current-label">16 <?php _e( 'Factors checked in', 'cgss' ); ?> <span id="ScanTime"></span> <?php _e( 'seconds', 'cgss' ); ?></span>
											<h1 class="theme-name">
												<span id="ShowTitle"></span><span class="theme-version title-tag"> - <?php _e( 'Title Tag of', 'cgss' ); ?> <span id="ComTitle"></span> <?php _e( 'Characters', 'cgss' ); ?></span>
											</h1>
											<h4 class="theme-author">
												<span id="UrlOk"> <?php echo $ok . __( 'Url is clean and tidy', 'cgss' ); ?></span><span id="UrlUnderscore"> <?php echo $no . __( 'Url has underscore', 'cgss' ); ?></span><span id="UrlDynamic"> <?php echo $no . __( 'Url is dynamic', 'cgss' ); ?></span><span id="UrlError"> <?php echo $no . __( 'Url is dynamic and has underscore', 'cgss' ); ?></span>
											</h4>
											<p class="theme-description"><span id="ShowDesc"></span><span class="theme-version meta-desc"> - <?php _e( 'Meta Description of', 'cgss' ); ?> <span id="ComDesc"></span> <?php _e( 'Characters', 'cgss' ); ?></span></p>
										</div>
										<p id="TextInfo"><?php _e( 'Total', 'cgss' ); ?> <strong><span id="WordCount"></span></strong> <?php _e( 'Words', 'cgss' ); ?>, <?php _e( 'with text to HTML ratio of', 'cgss' ); ?> <strong><span id="TextRatio"></span>%</strong>. <span id="TextComOk"><?php _e( 'And that\'s fine.', 'cgss' ); ?></span> <span id="TextComNo"><?php _e( 'That\'s too much of text, please write less.', 'cgss' ); ?></span> <span id="HtmlComNo"><?php _e( 'That\'s too little text, please write more.', 'cgss' ); ?></span></p>
										<div class="clear"></div>
										<p><span id="CanoYes"> <?php echo $ok . __( 'Canonical Link is', 'cgss' ); ?> <?php _e( 'present.', 'cgss' ); ?></span><span id="CanoNo"> <?php echo $no . __( 'Canonical Link is', 'cgss' ); ?> <?php _e( 'absent.', 'cgss' ); ?></span></p><br />
										<p><span id="RoboNone"><?php echo $ok . '<code>meta</code>' . __( 'Robots absent. No problem.', 'cgss' ); ?></span><span id="RoboYes"><?php echo $ok . '<code>meta</code>' . __( 'Robots are seo friendly.', 'cgss' ); ?></span><span id="RoboNo"><?php echo $no . '<code>meta</code>' . __( 'Robots is stoping search engine crawler. Please don\'t use', 'cgss' ); ?> <em class="danger-icon">noindex</em>, <em class="danger-icon">nofollow</em>.</span></p><br />
										<p><span id="StagsYes"><?php echo $ok; ?></span><span id="StagsNo"><?php echo $no; ?></span> <?php _e( 'Social media tags', 'cgss' ); ?> <span id="StagsVal"></span></p><br />
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<?php echo $title->content(); ?><br />
										<p><span id="HrchyYes"> <?php echo $ok . __( 'Hierarchical text with Heading tags', 'cgss' ); ?></span><span id="HrchyPoor"> <?php echo $flag . __( 'Poor text hierarchy with single heading tag', 'cgss' ); ?></span>: <span id="HrchyTags"></span><span id="HrchyNo"> <?php echo $no . __( 'Text hierarchy is absent. No Heading tags.', 'cgss' ); ?></span></p><br />
										<p><span id="LinkYes"><?php echo $ok; ?></span><span id="LinkPoor"><?php echo $flag; ?></span><span id="LinkNo"><?php echo $no; ?></span><span id="LinkNum"></span> <?php _e( 'Links total.', 'cgss' ); ?> <span id="LinkYesMsg"><?php _e( 'That\'s fine.', 'cgss' ); ?></span><span id="LinkPoorMsg"><?php _e( 'Please keep it little low.', 'cgss' ); ?></span><span id="LinkNoMsg"><?php _e( 'Too many, may be a link firm.', 'cgss' ); ?></span> ( <span id="NofLink"></span> <?php _e( 'Nofollow', 'cgss' ); ?>, <span id="ExtLink"></span> <?php _e( 'External', 'cgss' ); ?>, <span id="ImgLink"></span> <?php _e( 'Image links', 'cgss' ); ?> )</p><br />
										<p><span id="NoAltImage"><?php echo $ok; ?> <code>alt</code> <?php _e( 'tag problem not there, as there are no images', 'cgss' ); ?>.</span><span id="AltYes"><?php echo $ok; ?> <code>alt</code> <?php _e( 'tag is present in all images', 'cgss' ); ?>.</span><span id="AltNo"><?php echo $no; ?> <code>alt</code> <?php _e( 'tag is absent in following images', 'cgss' ); ?>:<br /><br /><span id="ImagesList"></span></span></p><br />
										<p><span id="KeysYes"><?php echo $ok; ?> <?php echo __( 'Top', 'cgss' ) . ' <span id="KeysCount"></span> ' . __( 'Most Used Words Found', 'cgss' ); ?></span><span id="KeysNo"><?php echo $no; ?> <?php _e( 'No significant most used words are there', 'cgss' ); ?></span>
										<br /><span id="KeysVal"></span></p><br />
									</div>
									<div class="col-3">
										<?php echo $title->usability(); ?><br />
										<p><?php echo '<span id="SpeedYes">' . $ok . '</span><span id="SpeedNo">' . $no . '</span>' . __( 'Loading Speed', 'cgss' ); ?> <span id="SpeedTime"></span>
										</p><br />
										<p><?php echo '<span id="ResponsiveYes">' . $ok . '</span><span id="ResponsiveNo">' . $no . '</span>' . __( 'Responsive design, mobile ready.', 'cgss' ); ?>
										</p><br />
										<p><?php echo '<span id="AttsYes">' . $ok . '</span><span id="AttsNo">' . $no . '</span><code>style</code>' . __( 'attributes in', 'cgss' ) . '<span id="AttsVal"></span> ' . __( 'elements.', 'cgss' ); ?>
										</p><br />
										<p><?php echo '<span id="TblYes">' . $ok . __( 'Nested tables absent.', 'cgss' ) . '</span><span id="TblNo">' . $no . __( 'Nested table(s) present.', 'cgss' ) . '</span>'; ?>
										</p><br />
										<p><?php echo '<span id="HttpYes">' . $ok . '<span id="HttpCount"></span> ' . __( 'Http requests.', 'cgss' ) . '</span><span id="HttpPoor">' . $flag . '<span id="HttpCount"></span> ' . __( 'Http requests.', 'cgss' ) . ' ' .__( 'Almost ok.', 'cgss' ) . '</span><span id="HttpNo">' . $no . '<span id="HttpCount"></span> ' . __( 'Http requests.', 'cgss' ) . ' ' . __( 'Too many.', 'cgss' ) . '</span><span id="HttpCount"></span> '; ?> ( <span id="HttpCss"></span> .css <?php _e( 'files', 'cgss' ); ?>, <span id="HttpJs"></span> .js <?php _e( 'files', 'cgss' ); ?>, <span id="HttpImg"></span> <?php _e( 'images', 'cgss' ); ?> )</p><br />
										<p><?php echo '<span id="ValidYes">' . $ok . '</span><span id="ValidNo">' . $no . '</span>' . '<span id="ValidVal"></span>' . __( 'Markup errors.', 'cgss' ); ?> <span id="ShowErrors"><?php _e( 'Show', 'cgss' ) ?></span><div class="well-points"><span id="PrintErrors"></span></div></p>
									</div>
								</div>
							</div>
						</div>
						<!--<div class="theme-actions">
							<div class="active-theme">
								<?php //echo $btns->report(); ?>
							</div>
						</div>-->
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

//Scan process ajax callback
function cgss_core_callback() {

	//require front end library to execute this function
	require_once( 'core/ajax-handle.php' );

	//add current time for better user access, using wordpress time function. Following variable
	//will return time in GMT, because if server location is changed, user won't be affected.
	$time = current_time( 'timestamp' );
	$time_arr = array(
		'time_now' => $time,
	);
	$complete_result = array_replace( $output_result, $time_arr );

	//Save reult to database
	require_once( 'db/do-db.php' );
	$data = new CGSS_DO_DB( $output_result['id'], $complete_result );
	$data_save = $data->save();

	wp_die();
} ?>
