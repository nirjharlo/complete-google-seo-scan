<?php
/*
Plugin Name: Complete Google SEO Scan
Plugin URI: http://gogretel.com/
Description: Is your website maintains google guidelines and other SEO points? 82.35 % websites we checked missed some. See it for yourself. Go to Settings > Guides submenu page and take the quick scan for each page.<strong>It will find out SEO errors for you.</strong>
Version: 1.5
Author: Nirjhar Lo
Author URI: http://gogretel.com/about/
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
?>
<?php
defined( 'ABSPATH' ) or exit;
define( 'COMPLETE_GOOGLE_SEO_SCAN_VERSION', cgss_get_version() );
defined( 'COMPLETE_GOOGLE_SEO_SCAN_DEBUG' ) or define( 'COMPLETE_GOOGLE_SEO_SCAN_DEBUG', false );

//load plugin textdomain
add_action( 'cgss', 'cgss_textdomain_cb' );
function cgss_textdomain_cb() {
  load_plugin_textdomain( 'cgss', false, dirname( plugin_basename( __FILE__ ) ) . '/includes/ln/' ); 
}

//software version returning function
function cgss_get_version() {
  $version = "0.0.1";
  $plugin_file = file_get_contents( __FILE__ );
  preg_match('#^\s*Version\:\s*(.*)$#im', $plugin_file, $matches);
  if (!empty($matches[1])) {
    $version = $matches[1];
  }
  return $version;
}

//define php error
if (version_compare(phpversion(), '5.3', '<')) {
  add_action('admin_notices', 'cgss_php_too_low');
  function cgss_php_too_low() {
    echo '<div class="error"><p>' . __( 'Complete Google SEO Scan requires PHP 5.3+ and will not be activated, your current server configuration is running PHP version ', 'cgss' ) . phpversion() . __( 'Any PHP version less than 5.3.0 has reached "End of Life" from PHP.net and no longer receives bugfixes orsecurity updates. The official information on how to update and why at ', 'cgss' ) . '<a href="http://php.net/eol.php/" target="_blank">' . __( 'php.net/eol.php', 'cgss' ) . '</a></p></div>';
  }
  return;
}

//add settings-submenu page
add_action( 'admin_menu', 'cgss_admin_page' );
function cgss_admin_page() {
	$cgss_scan_admin = add_management_page( __( 'SEO Scan', 'gretel' ), __( 'SEO Scan', 'gretel' ), 'manage_options', 'cgss-scan-page', 'cgss_scan_page_content' );
	add_action( 'admin_print_scripts-' . $cgss_scan_admin, 'cgss_admin_base_script' );
}

//admin script callback function
function cgss_admin_base_script() {
	wp_enqueue_script( 'cgss-admin-base-style', plugins_url() . '/complete-google-seo-scan/includes/cgss-jsscript.js', array( 'jquery' ), '', TRUE );
}

//submenu page callback
function cgss_scan_page_content() {
	global $user_identity;
	?>
	  <style type="text/css">.inside-po { padding-top: 7px;padding-bottom: 8px;}#pagecontent,#advancedSETlink,#directlinkSELL{cursor:pointer;}small{line-height:80%;}.body-content{max-height:250px;overflow-y:scroll;}#advancedSETbody{display:none;}div.success-call{padding:0 .6em;border:2px solid #6495ed;}.center{text-align:center;}</style>
		<div class="wrap">
		 <div id="icon-tools" class="icon32"><br /></div><h2><?php _e( 'Complete Google SEO Scan', 'cgss' ); ?></h2>
		 <?php if ( isset( $_POST[ 'submit-cgss-url' ] ) ) : ?>
		  <?php
      //load time counter start
      $cgss_load_time = microtime();
      $cgss_load_time = explode( ' ', $cgss_load_time );
      $cgss_load_time = $cgss_load_time[1] + $cgss_load_time[0];
      $cgss_load_start = $cgss_load_time;

      $cgss_robot_command = $_POST[ 'cgss-robots-command'];
      $cgss_sitemap_command = $_POST[ 'cgss-sitemap-input'];
		  $cgss_raw_url = $_POST[ 'cgss-url' ];
		  $cgss_url_check = esc_url_raw( $cgss_raw_url );
		  if ( ! filter_var( $cgss_url_check, FILTER_VALIDATE_URL ) or ! strpos( $cgss_url_check, '.' ) ) {
		   echo '<div class="error"><p><strong>' . __( 'The url you have entered is invalid. Please enter a valid url of any webpage of your website', 'cgss' ) . ' ' . home_url() . '</strong></p></div>';
		   require_once( 'scan-form.php' );
		  } else {
		   if ( substr( $cgss_url_check, 0, strlen( home_url() ) ) != home_url() ) {
		    echo '<div class="error"><p>' . __( 'We can not allow you send our scaning robot (user agent) to websites of other people. Because,', 'cgss' ) . '</p><p>' . __( 'It may give extra load on their server or it may be illegal according to their usage policy or', 'cgss' ) . ' <strong>' . __( 'they might think you reached the door way to hack their site, which is wrong.', 'cgss' ) . '</strong></p><p>' . __( 'We request you to scan webpages from your website only. If you have other WordPress websites, install this plugin on those websites and check them too.', 'cgss' ) . '</p></div>';
		    require_once( 'scan-form.php' );
		   } else {
		    $cgss_headers_check = get_headers( $cgss_url_check, 1 );
		    if ( strpos( $cgss_headers_check[0], 'Moved Permanently' ) ) {
		     if ( $cgss_headers_check['Location'] != null ) {
		      if ( substr( $cgss_headers_check['Location'], 0, strlen( home_url() ) ) != home_url() ) {
		       echo '<div class="error"><p>' . __( 'This url re-directs to a url', 'cgss' ) . '&nbsp;<a href="' . $cgss_headers_check['Location'] . '" target="_blank">' . $cgss_headers_check['Location'] . '</a> ' . __( 'We can not allow you send our scaning robot (user agent) to websites of other people. Because,', 'cgss' ) . '</p><p>' . __( 'It may give extra load on their server or it may be illegal according to their usage policy or', 'cgss' ) . ' <strong>' . __( 'they might think you reached the door way to hack their site, which is wrong.', 'cgss' ) . '</strong></p><p>' . __( 'We request you to scan webpages from your website only. If you have other WordPress websites, install this plugin on those websites and check them too.', 'cgss' ) . '</p></div>';
		       require_once( 'scan-form.php' );
		      } else {
		       $cgss_headers = get_headers( $cgss_headers_check['Location'], 1 );
		       if ( strpos( $cgss_headers[0], 'Moved Permanently' ) ) {
		        echo '<div class="error"><p>' . __( 'This url have already re-directed to a new url', 'cgss' ) . '&nbsp;<a href="' . $cgss_headers_check['Location'] . '" target="_blank">' . $cgss_headers_check['Location'] . '</a> ' . __( 'From that it is now redirected to.', 'cgss' ) . '&nbsp;<a href="' . $cgss_headers['Location'] . '" target="_blank">' . $cgss_headers['Location'] . '</a>' . __( 'We do not allow that kind of redirects. Please try with a good url.', 'cgss' ) . '</p></div>';
		        require_once( 'scan-form.php' );
		       } else {
		        if ( strpos( $cgss_headers[0], '404 Not Found' ) ) {
		         echo '<div class="error"><p>' . __( 'This url have already re-directed to a new url', 'cgss' ) . '&nbsp;<a href="' . $cgss_headers_check['Location'] . '" target="_blank">' . $cgss_headers_check['Location'] . '</a> ' . '<strong>' . __( 'The new url returns error : 404 Not Found, That means there is no indexable webpage and search engines will not index it. Enter existing webpage url of your website.', 'cgss' ) . '</strong></p></div>';
		         require_once( 'scan-form.php' );
		        } else {
		         $cgss_url = $cgss_headers_check['Location'];
		         require_once( 'scan-result.php' );
		         require_once( 'scan-html.php' );
		        }
		       }
		      }
		     } else {
		      echo '<div class="error"><p>' . __( 'This url re-directs to a page with no location. This is confusing and we request you to re-enter a proper url.', 'cgss' ) . '</p></div>';
		      require_once( 'scan-form.php' );
		     }
		    } else {
		     if ( strpos( $cgss_headers_check[0], '404 Not Found' ) ) {
          echo '<div class="error"><p><strong>' . __( 'The url returns error : 404 Not Found, That means there is no indexable webpage and search engines will not index it. Enter existing webpage url of your website.', 'cgss' ) . '</strong></p></div>';
          require_once( 'scan-form.php' );
		     } else {
		      $cgss_url = $cgss_url_check;
		      $cgss_headers = get_headers( $cgss_url, 1 );
          require_once( 'scan-result.php' );
		      require_once( 'scan-html.php' );
         }
		    }
		   }
		  }
		  ?>
		 <?php else : ?>
		  <?php require_once( 'scan-form.php' ); ?>
		 <?php endif; ?>
		 <p><?php echo __( 'Brought to you by Gretel, Please give', 'cgss' ) . '&nbsp;' . '<a href="http://gogretel.com/feedback-page/" target="_blank">' . __( 'your feedback', 'cgss' ) . '</a>'; ?></p>
		</div>
	<?php
}

//add settings link to plugin page
add_filter( 'plugin_action_links', 'cgss_scan_page_link', 10, 2 );
function cgss_scan_page_link( $links, $file ) {
 static $this_plugin;
 if (!$this_plugin) {
  $this_plugin = plugin_basename(__FILE__);
 }
 if ( $file == $this_plugin ) {
  $settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/tools.php?page=cgss-scan-page">' . __( 'Scan Now', 'cgss' ) . '</a>';
  array_unshift( $links, $settings_link );
 }
 return $links;
}
?>
