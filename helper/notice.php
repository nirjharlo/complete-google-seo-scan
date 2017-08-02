<?php
/**
 *
 * @package: onpage-seo-checker/user/
 * on: 15.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Add database manipulation objects.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 1 Property:
 * $db_fail		(string)	notice for db faliure
 * 
 *
 */
if ( ! class_exists( 'ONSEOCHECK_NOTICE' ) ) {

	class ONSEOCHECK_NOTICE {



		public $db_fail;



		public function __construct() {

			$this->db_fail = $this->updated_note( __( 'Database is not installed properly, Complete Google Seo Scan Plugin won\'t work.', 'onseocheck' ) );
		}



		/**
		 *
		 * HTML through error notice is displayed
		 *
		 */
		public function error_note( $msg ) {

			return '<div class="notice error my-acf-notice is-dismissible"><p>' . $msg . '</p></div>';
		}



		/**
		 *
		 * HTML through updated notice is displayed
		 *
		 */
		public function updated_note( $msg ) {

			return '<div class="notice updated my-acf-notice is-dismissible"><p>' . $msg . '</p></div>';
		}
	}
} ?>
