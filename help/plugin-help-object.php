<?php
/**
 * @/help/plugin-help-object.php
 * @on 12.07.2015
 * @since 2.0
 *
 * An object with compilation of text and link, ready to be used in help section.
 *
 * It has 2 methods
 * @method Tab docs data
 * @method Sidebar links.
 */
class CGSS_HELP {

	public function data() {
		return array(
			array(
				'id' => 'cgss_help_start',
				'title' => __( 'Introduction', 'cgss' ),
				'content' => '<p>' . __( 'We have built this plugin for any WordPress user, who wants to check seo readiness of his or her webpages. Basic Google webmaster guidelines and PageSpeed rules are constructive principles of this seo scan.', 'cgss' ) . '</p><p>' . __( 'We have not included backlinks analysis, traffic metrics, knowledge graph and brand signals, because the intention is primarily on-page search optimization only.', 'cgss' ) . '</p><p>' . __( 'If you catch a bug or want to give any feedback or feature suggestion you are welcome to email the plugin author', 'cgss' ) . ', Nirjhar: <b>info@gogretel.com</b>' . '</p><p><strong>' . __( 'DISCLAIMER', 'cgss' ) . ':</strong> ' . __( 'This plugin or the plugin author is in no way affiliated to Google Inc. The word "Google" in name of this plugin implies, this plugin scans a webpage for some speculated ranking parameters of search engines like Google.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_over',
				'title' => __( 'Overview', 'cgss' ),
				'content' => '<p>' . __( 'Starting page of this plugin is named "Seo Overview". There you can find 3 types of features to scan 3 different aspects of your website.', 'cgss' ) . '</p><ul><li><strong>' . __( 'Server seo scan', 'cgss' ) . '</strong> ' . __( 'will make a header request and find out 10 important points about the server and it\'s configuration. Practically, You can change them by using different plugins available.', 'cgss' ) . '</li><li><strong>' . __( 'Design seo scan', 'cgss' ) . '</strong> ' . __( 'gives an idea about numbers of HTTP requests made by your webpages and resource size it takes to load a webpage. They are takken from <code>_enqueued</code> styles and scripts. All hard coded links are ignored, because it is assumed that you are using WordPress compatable themes.', 'cgss' ) . '</li><li><strong>' . __( 'Content Overview', 'cgss' ) . '</strong> ' . __( 'After you are done with scans for your webpages, you will be able to complie those individual content reports to a single report and gather actionable intelligence.', 'cgss' ) . '</li></ul><p><strong>' . __( 'Set Screen Options.', 'cgss' ) . '</strong> ' . __( 'for showing various post types in WordPress admin menus. You can select them using checkboxes. This option is available only in "Scan Overview" page.', 'cgss' ) . '</p><p>' . __( 'Secondly, you may use numbers of pages to show in any post type "Scan Status" plugins page. This is available in all other "Scan Status" pages created by this plugin.', 'cgss' ) . '</p><p>' . __( 'Close this "Help" tab and open "Screen Options" tab to explore more.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_use',
				'title' => __( 'How to use?', 'cgss' ),
				'content' => '<p>' . __( 'This plugin\'s look and feel is built using WordPress Admin UI and is mobile ready. So, for regular WordPress users it will be very intuitive. The usage process has 3 steps.', 'cgss' ) . '</p><ul><li><strong>' . __( 'Before you start', 'cgss' ) . '</strong> ' . __( 'make sure you have taken Design seo and Server seo scans available at Overview page.', 'cgss' ) . '</li><li><strong>' . __( 'Select a Page', 'cgss' ) . '</strong> ' . __( 'from the individual list of webpage blocks, by clicking on corresponding "SCAN" button. Top navigation is to select posts from multiple post types and you may filter them according to time of last scan. Remember, only "published" posts are shown.', 'cgss' ) . '</li><li><strong>' . __( 'Wait for scan', 'cgss' ) . '</strong> ' . __( 'to be completed. It will take a few seconds, approximately 2 to 30 seconds. Time varies mainly because of downloading webpage content.', 'cgss' ) . '</li><li><strong>' . __( 'See results', 'cgss' ) . '</strong> ' . __( 'in a pop up on the same page as soon as the scan is complete. The result will have 7 segments.', 'cgss' ) . '<ul><li>' . __( 'Overall score with social media counts.', 'cgss' ) . '</li><li>' . __( 'Search engine snippet and highlighted keyword.', 'cgss' ) . '</li><li>' . __( 'Most used words Analysis and information from text', 'cgss' ) . '</li><li>' . __( 'Use of Image and page design seo', 'cgss' ) . '</li><li>' . __( 'Required information on server and url.', 'cgss' ) . '</li><li>' . __( 'Page specific speed and usability analysis.', 'cgss' ) . '</li><li>' . __( 'A sample Facebook snippet from social media tags.', 'cgss' ) . '</li></ul><li><strong>' . __( 'See required actions', 'cgss' ) . '</strong> ' . __( 'listed in the same pop-up. Just click the button with the word "ACTION" at bottom. You will find step by step guidence.', 'cgss' ) . '</li></li></ul><p>' . __( 'Scan result is displayed in an organized fashion with <strong>Hints</strong> and are easy to understand for someone with basic seo knowledge. For a primitive guideline of seo aspects see links at right sidebar of this area.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_perform',
				'title' => __( 'Performance', 'cgss' ),
				'content' => '<p>' . __( 'This plugin uses resources from your web server for processing scan requests. So, you will get higher bandwidth and database usage. In standard WordPress installs, this increment is insignificant. You won\'t probably notice it.', 'cgss' ) . '</p><p><strong>' . 'Scan process time' . '</strong> ' . __( 'depends on your server and website quality. Actual process takes less than 5 seconds to complete. But it takes longer to check page header and download webpage resources.', 'cgss' ) . '</p><p><strong>' . 'Impact on Performance' . '</strong> ' . __( 'is very minimal, as this plugin is admin oriented. All Javascripts (.js files) and Stylesheets (.css files) are loaded only in admin side.', 'cgss' ) . ' <i>' . __( 'That means, it won\'t slow down your website.', 'cgss' ) . '</i></p>',
			),
			array(
				'id' => 'cgss_help_privacy',
				'title' => __( 'Data & Privacy', 'cgss' ),
				'content' => '<p>' . __( 'We value security of your website. All files and resulting data is stored in your web server only.', 'cgss' ) . '</p><p><strong>' . __( 'privacy Policy.', 'cgss' ) . '</strong> ' . __( 'If you install and update this plugin from wordpress.org plugin repository and use it as is, then the plugin author doesn\'t collect any data whatsoever. Altough other softwares installed in your server may use files of this plugin or collect data generated from usage of this plugin.', 'cgss' ) . '</p><p><strong>' . __( 'Data storage.', 'cgss' ) . '</strong> ' . __( 'This plugin currently stores data as an multidimensional array for each webpage you scan. Many things you see in this page are taken from that stored data. Other than those, there are 4 custom data arrays stored in "_options" database. When the plugin is uninstalled all of those data will be removed from your server.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_limit',
				'title' => __( 'Do\'s and Don\'ts', 'cgss' ),
				'content' => '<p>' . __( 'This plugin is built to help you understand search engine optimization status of your website only. It doesn\'t allow you to scan any webpage from any domain, other than where it is installed. We request you not to tweek the code in any way such that this plugin becomes able to scan pages from various other domains.', 'cgss' ) . '</p><p>' . __( 'We can\'t force you, so if you do such a thing, the plugin author will not be responsible for any legal and security consequences that may come. Otherwise, the code is under GPL License, you can do anything you like.', 'cgss' ) . '</p>',
			),
			array(
				'id' => 'cgss_help_update',
				'title' => __( 'Updates', 'cgss' ),
				'content' => '<p>' . __( 'Future updates of this plugin is expected to contain more features and a new interface. Some of the features you may expect are Video Seo, Spam detection, Rich snippet calculation, Twitter v card, above the fold optimization, sitemap.xml check and robots.txt check. Internationalization and automatic scan are also desired property to be included.', 'cgss' ) . '</p><p>' . __( 'Tough the plugin author will take final decision about updates, you are free to inform about bugs and feature requests in support forum.', 'cgss' ) . '</p>',
			),
		);
	}

	//Sidebar links in help section
	public function links() {
		return '<p><a href="https://wordpress.org/support/plugin/complete-google-seo-scan/" target="_blank">' . __( 'Support Forum', 'cgss' ) . '</a></p><p><a href="http://gogretel.com/resource/" rel="nofollow" target="_blank">' . __( 'Free Seo Resource', 'cgss' ) . '</a></p>';
	}
}?>
