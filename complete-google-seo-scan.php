<?php
/**
 Plugin Name: Complete Google SEO Scan
 Plugin URI: http://nirjharlo.com/
 Description: Find issues, check status and get fixes for Seo of individual pages and whole website.
 Version: 3.0
 Author: Nirjhar Lo
 Author URI: http://nirjharlo.com/
 Text Domain: cgss
 Domain Path: /assets/ln
 License: GPLv3
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('ABSPATH')) exit;


//Define basic names
//Edit the "CGSS" in following namespaces for compatibility with your desired name.
defined('CGSS_DEBUG') or define('CGSS_DEBUG', false);

defined('CGSS_PATH') or define('CGSS_PATH', plugin_dir_path(__FILE__));
defined('CGSS_FILE') or define('CGSS_FILE', plugin_basename(__FILE__));

defined('CGSS_EXECUTE') or define('CGSS_EXECUTE', plugin_dir_path(__FILE__).'src/');
defined('CGSS_HELPER') or define('CGSS_HELPER', plugin_dir_path(__FILE__).'helper/');
defined('CGSS_TRANSLATE') or define('CGSS_TRANSLATE', plugin_basename( plugin_dir_path(__FILE__).'asset/ln/'));

//change /wp-plugin-framework/ with your /plugin-name/
defined('CGSS_JS') or define('CGSS_JS', plugins_url().'/complete-google-seo-scan/asset/js/');
defined('CGSS_CSS') or define('CGSS_CSS', plugins_url().'/complete-google-seo-scan/asset/css/');
defined('CGSS_IMAGE') or define('CGSS_IMAGE', plugins_url().'/complete-google-seo-scan/asset/img/');


//The Plugin
require_once('autoload.php');
if ( class_exists( 'CGSS_BUILD' ) ) new CGSS_BUILD(); ?>