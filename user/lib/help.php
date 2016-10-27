<?php
/**
 *
 * @package: onpage-seo-checker/user/lib/
 * on: 12.07.2016
 * @since 2.5
 * @called_in: ONSEOCHECK_USER
 *
 * Add help objects.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 1 Property:
 * $		(string)	Definition of path
 *
 */
if ( ! class_exists( 'ONSEOCHECK_HELP' ) ) {

	class ONSEOCHECK_HELP {



		public $info;
		public $link;
		private $type;



		public function __construct( $type ) {

			$this->type = $type;
			$output = call_user_func( array( $this, $this->type ) );
			$this->info = $output['info'];
			$this->link = $output['link'];
		}



		/**
		 *
		 * Output the data for main page
		 *
		 */
		public function onseocheck() {

			$data = array(
						'info' => array(
									array(
										'id' => 'cgss_help_start',
										'title' => __( 'Introduction', 'cgss' ),
										'content' => '<p>' . __( 'We have built this plugin for any WordPress user, who wants to check seo readiness of his or her webpages. Basic Google webmaster guidelines and PageSpeed rules are constructive principles of this seo scan.', 'cgss' ) . '</p><p>' . __( 'We have not included backlinks analysis, traffic metrics, knowledge graph and brand signals, because the intention is primarily on-page search optimization only.', 'cgss' ) . '</p><p>' . __( 'If you catch a bug or want to give any feedback or feature suggestion you are welcome to email the plugin author', 'cgss' ) . ', Nirjhar: <b>info@gogretel.com</b>' . '</p><p><strong>' . __( 'DISCLAIMER', 'cgss' ) . ':</strong> ' . __( 'This plugin or the plugin author is in no way affiliated to Google Inc. The word "Google" in name of this plugin implies, this plugin scans a webpage for some speculated ranking parameters of search engines like Google.', 'cgss' ) . '</p>'
										),
									),
						'link' => '<a href="#">Link</a>',
					);

			return $data;
		}



		/**
		 *
		 * Output the data for analysis page
		 *
		 */
		public function onseocheck_analysis() {

			$data = array(
						'info' => array(
									array(
										'id' => 'cgss_help_start',
										'title' => __( 'Introduction', 'cgss' ),
										'content' => '<p>' . __( 'We have built this plugin for any WordPress user, who wants to check seo readiness of his or her webpages. Basic Google webmaster guidelines and PageSpeed rules are constructive principles of this seo scan.', 'cgss' ) . '</p><p>' . __( 'We have not included backlinks analysis, traffic metrics, knowledge graph and brand signals, because the intention is primarily on-page search optimization only.', 'cgss' ) . '</p><p>' . __( 'If you catch a bug or want to give any feedback or feature suggestion you are welcome to email the plugin author', 'cgss' ) . ', Nirjhar: <b>info@gogretel.com</b>' . '</p><p><strong>' . __( 'DISCLAIMER', 'cgss' ) . ':</strong> ' . __( 'This plugin or the plugin author is in no way affiliated to Google Inc. The word "Google" in name of this plugin implies, this plugin scans a webpage for some speculated ranking parameters of search engines like Google.', 'cgss' ) . '</p>'
										),
									),
						'link' => '<a href="#">Link</a>',
					);

			return $data;
		}



		/**
		 *
		 * Output the data for action page
		 *
		 */
		public function onseocheck_action() {

			$data = array(
						'info' => array(
									array(
										'id' => 'cgss_help_start',
										'title' => __( 'Introduction', 'cgss' ),
										'content' => '<p>' . __( 'We have built this plugin for any WordPress user, who wants to check seo readiness of his or her webpages. Basic Google webmaster guidelines and PageSpeed rules are constructive principles of this seo scan.', 'cgss' ) . '</p><p>' . __( 'We have not included backlinks analysis, traffic metrics, knowledge graph and brand signals, because the intention is primarily on-page search optimization only.', 'cgss' ) . '</p><p>' . __( 'If you catch a bug or want to give any feedback or feature suggestion you are welcome to email the plugin author', 'cgss' ) . ', Nirjhar: <b>info@gogretel.com</b>' . '</p><p><strong>' . __( 'DISCLAIMER', 'cgss' ) . ':</strong> ' . __( 'This plugin or the plugin author is in no way affiliated to Google Inc. The word "Google" in name of this plugin implies, this plugin scans a webpage for some speculated ranking parameters of search engines like Google.', 'cgss' ) . '</p>'
										),
									),
						'link' => '<a href="#">Link</a>',
					);

			return $data;
		}



		/**
		 *
		 * Output the data for monitor page
		 *
		 */
		public function onseocheck_monitor() {

			$data = array(
						'info' => array(
									array(
										'id' => 'cgss_help_start',
										'title' => __( 'Introduction', 'cgss' ),
										'content' => '<p>' . __( 'We have built this plugin for any WordPress user, who wants to check seo readiness of his or her webpages. Basic Google webmaster guidelines and PageSpeed rules are constructive principles of this seo scan.', 'cgss' ) . '</p><p>' . __( 'We have not included backlinks analysis, traffic metrics, knowledge graph and brand signals, because the intention is primarily on-page search optimization only.', 'cgss' ) . '</p><p>' . __( 'If you catch a bug or want to give any feedback or feature suggestion you are welcome to email the plugin author', 'cgss' ) . ', Nirjhar: <b>info@gogretel.com</b>' . '</p><p><strong>' . __( 'DISCLAIMER', 'cgss' ) . ':</strong> ' . __( 'This plugin or the plugin author is in no way affiliated to Google Inc. The word "Google" in name of this plugin implies, this plugin scans a webpage for some speculated ranking parameters of search engines like Google.', 'cgss' ) . '</p>'
										),
									),
						'link' => '<a href="#">Link</a>',
					);

			return $data;
		}



		/**
		 *
		 * Output the data for settings page
		 *
		 */
		public function onseocheck_settings() {

			$data = array(
						'info' => array(
									array(
										'id' => 'cgss_help_start',
										'title' => __( 'Introduction', 'cgss' ),
										'content' => '<p>' . __( 'We have built this plugin for any WordPress user, who wants to check seo readiness of his or her webpages. Basic Google webmaster guidelines and PageSpeed rules are constructive principles of this seo scan.', 'cgss' ) . '</p><p>' . __( 'We have not included backlinks analysis, traffic metrics, knowledge graph and brand signals, because the intention is primarily on-page search optimization only.', 'cgss' ) . '</p><p>' . __( 'If you catch a bug or want to give any feedback or feature suggestion you are welcome to email the plugin author', 'cgss' ) . ', Nirjhar: <b>info@gogretel.com</b>' . '</p><p><strong>' . __( 'DISCLAIMER', 'cgss' ) . ':</strong> ' . __( 'This plugin or the plugin author is in no way affiliated to Google Inc. The word "Google" in name of this plugin implies, this plugin scans a webpage for some speculated ranking parameters of search engines like Google.', 'cgss' ) . '</p>'
										),
									),
						'link' => '<a href="#">Link</a>',
					);

			return $data;
		}
	}
} ?>
