<?php
/**
 * @/user/lib/help-object.php
 * on: 08.06.2015
 * An object with compilation of text and link, ready to be used in help section.
 *
 * It has 2 methods for Main docs, Associated links.
*/
class CGSS_HELP {

	//Main help section text in a tabbed manner.
	public function data() {
		return array(
			array(
				'id' => 'cgss_help_start',
				'title' => __( 'Overview', 'cgss' ),
				'content' => '<p>' . __( 'We have built this plugin for any WordPress user, who wants to check seo readiness of his or her webpages. Basic Google webmaster guidelines and PageSpeed rules are constructive principles of this seo scan. We have not included backlinks analysis, traffic metrics, details on social media, knowledge graph and brand signals, because the intention is primarily on-page search optimization only. If you catch a bug or want to give any feedback or feature suggestion you are welcome to email the plugin author', 'cgss' ) . ', Nirjhar: <b>info@gogretel.com</b>' . '</p><p><strong>' . __( 'DISCLAIMER', 'cgss' ) . ':</strong> ' . __( 'This plugin or the plugin author is in no way affiliated to Google Inc. The word "Google" in name of this plugin implies, this plugin scans a webpage for some speculated ranking parameters of search engines like Google.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_use',
				'title' => __( 'How to use?', 'cgss' ),
				'content' => '<p>' . __( 'This plugin\'s look and feel is built using WordPress Admin UI and is almost mobile ready. So, for regular WordPress users it will be very intuitive. The usage process has 3 steps.', 'cgss' ) . '</p><ul><li><strong>' . __( 'Select a Page', 'cgss' ) . '</strong> ' . __( 'from the individual tables of webpages, by clicking on corresponding radio button. There are 2 dropdown menus to select posts from multiple post types and filter them according to time and score of last scan. Remember, only "published" posts are shown.', 'cgss' ) . '</li><li><strong>' . __( 'Wait for scan', 'cgss' ) . '</strong> ' . __( 'to be completed. It will take a few seconds, approximately 15 to 30 seconds. Time varies mainly because of downloading webpage content.', 'cgss' ) . '</li><li><strong>' . __( 'See results', 'cgss' ) . '</strong> ' . __( 'in a pop up in the same page as soon as the scan is complete. The result will have 5 segments.', 'cgss' ) . '<ol><li>' . __( 'Overview and basic information as well as social media counts.', 'cgss' ) . '</li><li>' . __( 'Search engine snippet and related information.', 'cgss' ) . '</li><li>' . __( 'Most used words Analysis and information from text', 'cgss' ) . '</li><li>' . __( 'Use of Media or Image and Video seo', 'cgss' ) . '</li><li>' . __( 'Page specific speed and usability analysis.', 'cgss' ) . '</li></ol></li></ul><p>' . __( 'Navigate within segments using top arrow buttons. Scan result is displayed in an organized fashion and are is easy to understand for someone with basic seo knowledge. For a primitive guideline of seo aspects see links at right sidebar of this area.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_perform',
				'title' => __( 'Performance', 'cgss' ),
				'content' => '<p>' . __( 'This plugin uses resources from your web server for processing scan requests. So, you will get higher bandwidth and database usage. In standard WordPress installs, this increment is insignificant. You won\'t probably notice it.', 'cgss' ) . '</p><p><strong>' . 'Scan process time' . '</strong> ' . __( 'depends on your server and website quality. Actual process takes around 10 seconds to complete. But it takes longer to check page header and download webpage resources.', 'cgss' ) . '</p><p><strong>' . 'Impact on Performance' . '</strong> ' . __( 'is very minimal, as this plugin is admin oriented. All Javascripts (.js files) and Stylesheets (.css files) are loaded only in admin side.', 'cgss' ) . ' <i>' . __( 'That means, it won\'t slow down your website.', 'cgss' ) . '</i></p>',
			),
			array(
				'id' => 'cgss_help_privacy',
				'title' => __( 'Data & Privacy', 'cgss' ),
				'content' => '<p>' . __( 'We value security of your website. All files and resulting data is stored in your web server only.', 'cgss' ) . '</p><p><strong>' . __( 'privacy Policy.', 'cgss' ) . '</strong> ' . __( 'If you install and update this plugin from wordpress.org plugin repository and use it as is, then the plugin author doesn\'t collect any data whatsoever. Altough other softwares installed in your server may use files of this plugin or collect data generated from usage of this plugin.', 'cgss' ) . '</p><p><strong>' . __( 'Data storage.', 'cgss' ) . '</strong> ' . __( 'This plugin currently stores data as an multidimensional array for each webpage you scan. Many things you see in this page are taken from that stored data. When the plugin is uninstalled these data will be removed from your server.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_limit',
				'title' => __( 'Do\'s and Don\'ts', 'cgss' ),
				'content' => '<p>' . __( 'This plugin is built to help you understand search engine optimization status of your website only. It doesn\'t allow you to scan any webpage from any domain, other than where it is installed. We request you not to tweek the code in any way such that this plugin becomes able to scan pages from various other domains.', 'cgss' ) . '</p><p>' . __( 'We can\'t force you, so if you do such a thing, the plugin author will not be responsible for any legal and security consequences that may come. Otherwise, the code is under GPL License, you can do anything you like.', 'cgss' ) . '</p>',
			),
		);
	}

	//Sidebar links in help section
	public function links() {
		return '<p><a href="https://wordpress.org/support/plugin/complete-google-seo-scan/" target="_blank">' . __( 'Support Forum', 'cgss' ) . '</a></p><p><a href="http://gogretel.com/resource/" rel="nofollow" target="_blank">' . __( 'Free Seo Resource', 'cgss' ) . '</a></p>';
	}
}?>
