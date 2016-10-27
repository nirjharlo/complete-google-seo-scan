<?php
/**
 Plugin Name: Complete Google Seo Scan
 Plugin URI: http://gogretel.com/
 Description: Find issues, check status and get fixes for Seo of individual pages and whole website.
 Version: 2.5
 Author: Nirjhar Lo
 Author URI: https://www.upwork.com/freelancers/~018a6e17ae7d831796
 Text Domain: cgss
 Domain Path: /assets/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( ! defined( 'ABSPATH' ) ) exit;



defined( 'ONSEOCHECK_DEBUG' ) or define( 'ONSEOCHECK_DEBUG', false );



defined( 'ONSEOCHECK_PATH' ) or define( 'ONSEOCHECK_PATH', plugin_dir_path( __FILE__ ) );
defined( 'ONSEOCHECK_FILE' ) or define( 'ONSEOCHECK_FILE', plugin_basename( __FILE__ ) );



defined( 'ONSEOCHECK_DB_DEBUG' ) or define( 'ONSEOCHECK_DB_PATH', plugin_dir_path( __FILE__ ) . 'db/' );
defined( 'ONSEOCHECK_CORE_DEBUG' ) or define( 'ONSEOCHECK_CORE_PATH', plugin_dir_path( __FILE__ ) . 'core/' );
defined( 'ONSEOCHECK_USER_DEBUG' ) or define( 'ONSEOCHECK_USER_PATH', plugin_dir_path( __FILE__ ) . 'user/' );
defined( 'ONSEOCHECK_ADMIN_DEBUG' ) or define( 'ONSEOCHECK_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin/' );



defined( 'ONSEOCHECK_LN_PATH' ) or define( 'ONSEOCHECK_LN_PATH', plugin_basename( plugin_dir_path( __FILE__ ) . 'asset/ln/' ) );
defined( 'ONSEOCHECK_JS_PATH' ) or define( 'ONSEOCHECK_JS_PATH', plugins_url() . '/onpage-seo-checker/asset/js/' );
defined( 'ONSEOCHECK_CSS_PATH' ) or define( 'ONSEOCHECK_CSS_PATH', plugins_url() . '/onpage-seo-checker/asset/css/' );



//The Plugin
require_once( 'main.php' );
if ( class_exists( 'ONPAGE_SEO_CHECKER' ) ) new ONPAGE_SEO_CHECKER(); ?>
