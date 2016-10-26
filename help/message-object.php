<?php
/**
 * @/user/lib/message-object.php
 * @on 19.07.2015
 * @since 2.1
 *
 * Custom object to organize reporting messages, delivered to javascripts.
 *
 */
class CGSS_MSG {

	//for scan reports.
	public function scan() {
		return array(
					'msg' => __( 'Message', 'cgss' ),
				);
	}

	//for scan overview page.
	public function overview() {
		return array(
					'network' => __( 'Network error', 'cgss' ),
					'no_script' => __( 'No .css or .js file', 'cgss' ),
					'none' => __( 'is undefined', 'cgss' ),
					'view' => __( 'View', 'cgss' ),
					'titlet' => __( 'TITLE TAG', 'cgss' ),
					'mdesc' => __( 'META DESCRIPTION TAG', 'cgss' ),
				);
	}

	//for html in any page.
	public function html() {
		return array(
					'ok' => $this->ok(),
					'flag' => $this->flag(),
					'no' => $this->no(),
					'full_star' => $this->full_star(),
					'half_star' => $this->half_star(),
					'blank_star' => $this->blank_star(),
					'love' => $this->love(),
					'none' => $this->none(),
					'enabled' => $this->enabled(),
					'disabled' => $this->disabled(),
					'image' => $this->image(),
					'absent' => '<span class="danger-icon">' . __( 'NOT FOUND', 'cgss' ) . '</span>',
					'stag_image_absent' => __( 'SOCIAL MEDIA IMAGE TAG NOT FOUND', 'cgss' ),
					'stag_title_absent' => '<span class="danger-icon">' . __( 'SOCIAL MEDIA TITLE TAG NOT FOUND', 'cgss' ) . '</span>',
					'stag_desc_absent' => '<span class="danger-icon">' . __( 'SOCIAL MEDIA DESCRIPTION TAG NOT FOUND', 'cgss' ) . '</span>',
					'stag_domain_absent' => '<span class="danger-icon">' . __( 'DOMAIN NAME NOT FOUND', 'cgss' ) . '</span>',
					'title' => '<span class="danger-icon">' . __( 'TITLE TAG NOT FOUND', 'cgss' ) . '</span>',
					'mdesc' => '<span class="danger-icon">' . __( 'META DESCRIPTION TAG NOT FOUND', 'cgss' ) . '</span>',
					'noindex' => '<span class="highlight">&nbsp;noindex&nbsp;</span>',
					'ok_compression' => $this->no() . __( 'Compression', 'cgss' ) . ': <span id="CompFiles"></span> ' . __( '.css and .js files can be more compressed to reduce', 'cgss' ) . ' <span id="CompSize"></span>% ' . __( 'in total size', 'cgss' ),
					'no_compression' => $this->ok() . __( 'Compression', 'cgss' ) . ': ' . __( 'All .css and .js files are compressed enough.', 'cgss' ),
					'img_ok' => $this->ok() . ' ' . __( 'All images are optimized.', 'cgss' ),
					'img_no' => $this->no() . ' ' . __( 'Above images does not have <code>alt</code> tags.', 'cgss' ),
					'img_none' => $this->ok() . ' ' . __( 'No images are present.', 'cgss' ),
					'links_ok' => $this->ok() . ' <span id="LinksNum"></span> ' . __( 'Links all total', 'cgss' ) . ', ' . __( 'numbers are ok.', 'cgss' ),
					'links_no' => $this->no() . ' <span id="LinksNum"></span> ' . __( 'Links all total', 'cgss' ) . ', ' . __( 'Too much in number.', 'cgss' ),
					'q_mark' => '<strong class="danger-icon">?</strong>',
					'under_mark' => '<strong class="danger-icon">_</strong>',
					'http' => '<span class="danger-border">http://</span>',
					'https' => '<span class="danger-border">https://</span>',
					'keys' => '<span class="danger-icon">' . __( 'NO KEYWORDS FOUND', 'CGSS' ) . '</span>',
					'spam' => '<span class="danger-icon">' . __( 'SPAM', 'CGSS' ) . '</span>',
					'after_scan' => __( 'words, focus is', 'cgss' ),
				);
	}

	//for intel in overview.
	public function intel() {
		return array(
					'words' => __( 'Total <span id="WordsIntel"></span> words used. <span id="WordsPerPageIntel"></span> per page. Avg text to html ratio is <span id="WordsRatioIntel"></span>', 'cgss' ),
					'links' => __( '<span id="LinksNumIntel"></span> links per page, nearly <span id="ExtLinksIntel"></span> external and <span id="ImgLinksIntel"></span> image links of them.', 'cgss' ),
					'keyword' => __( 'Long tail keywords (avg <span id="KeyWordsSizeIntel"></span> words long) were found in <span id="KeyWordsIntel"></span> webpages.', 'cgss' ),
					'image' => array(
									'ok' => __( 'Almost all images seems to be optimized.', 'cgss' ),
									'mid' => __( 'Nearly <span id="ImagesIntelPercent"></span> of pages have images without alt tags.', 'cgss' ),
									'no' => __( 'This website images are <span class="danger-icon">not optimized</span>.', 'cgss' ),
								),
					'mobile' => array(
									'ok' => __( 'Your website is mobile optimized.', 'cgss' ),
									'mid' => __( 'Some of your webpages<span id="DesignIntelPercent"></span> are not mobile optimized.', 'cgss' ),
									'no' => __( 'Your website is <span class="danger-icon">not mobile optimized</span>.', 'cgss' ),
								),
					'url' => array(
									'ok' => __( 'All urls are neat and clean.', 'cgss' ),
									'mid' => __( 'Some urls <span class="danger-icon">are dynamic</span><span id="UrlIntelPercent"></span> and <span class="danger-icon">have underscore</span><span id="UrlIntelPercentTwo"></span>.', 'cgss' ),
									'no' => __( 'Some urls <span id="UrlIntelPercent"></span> are <span class="danger-icon">dynamic and contains underscore</span>.', 'cgss' ),
									'dynamic' => __( 'Some urls <span id="UrlIntelPercent"></span> <span class="danger-icon">are dynamic</span>.', 'cgss' ),
									'underscore' => __( 'Some urls <span id="UrlIntelPercentTwo"></span> <span class="danger-icon">have underscores</span>.', 'cgss' ),
								),
					'time' => array(
									'fast' => __( 'Your website is fast, takes less than a second load.', 'cgss' ),
									'mid' => __( 'Your website loads fine, may try a little faster.', 'cgss' ),
									'slow' => __( 'Your website is slow, make it faster.', 'cgss' ),
									'very_slow' => __( 'Your website is <span class="danger-icon">too slow</span>, serious improvement needed.', 'cgss' ),
								),
					'stag' => array(
									'ok' => __( 'Social media tags are present. Optimized for social sharing.', 'cgss' ),
									'mid' => __( 'Social media tags are <span class="danger-icon">incomplete</span> in almost <span id="StagIntelNoPercent"></span>% pages.', 'cgss' ),
									'no' => __( 'Social media tags are nearly <span class="danger-icon">absent</span>. Use them for better sharing.', 'cgss' ),
								),
				);
	}

	//for compete in scan status.
	public function compete() {
		return array(
					'admin' => admin_url(),
					'ok' => $this->ok(),
					'flag' => $this->flag(),
					'no' => $this->no(),
					'up' => $this->up(),
					'down' => $this->down(),
					'avg' => __( 'Average', 'cgss' ),
					'all' => __( 'All', 'cgss' ),
					'none' => __( 'None', 'cgss' ),
					'err_note' => $this->err_notice( '' ),
					'same_domain' => $this->err_notice( __( 'Competitors should be from different domain.', 'cgss' ) ),
					'fetch' => array(
									'ok' => $this->suc_notice( __( 'Report fetched and shown.', 'cgss' ) ),
									'no' => $this->err_notice( __( 'Report fetch failed.', 'cgss' ) ),
									'no_data' => $this->err_notice( __( 'No saved report to fetch.', 'cgss' ) ),
								),
					'key_block' => array(
										'empty' => $this->err_notice( __( 'Please enter a focus keyword.', 'cgss' ) ),
										'long' => $this->err_notice( __( 'Focus keyword is too long. 32 words are allowed.', 'cgss' ) ),
										'long_char' => $this->err_notice( __( 'Word in focus keyword is too long. 129 characters per word are allowed.', 'cgss' ) ),
									),
					'no_key_tbl' => '<th><strong>' . __( 'Keyword', 'cgss' ) . '</strong></th><th><strong>' . __( 'Count', 'cgss' ) . '</strong></th><th><strong>' . __( 'Percent', 'cgss' ) . '</strong></th>',
					'saved' => $this->suc_notice( __( 'Report Saved.', 'cgss' ) ),
					'no_saved' => $this->err_notice( __( 'Report not Saved.', 'cgss' ) ),
					'var_key_none' => $this->war_notice( __( 'That keyword is not found in content. You may RESET and scan with other keywords.', 'cgss' ) ),
					'var_key' => $this->war_notice( __( 'That keyword is not found in content. You may RESET and scan with other variants.', 'cgss' ) ),
					'fail' => $this->err_notice( __( 'Scan Failed. Check your network, make sure that page exists and try again.', 'cgss' ) ),
					'word' => array(
									'ok' => __( 'Words and HTML seems adequate.', 'cgss' ),
									'num_ok' => __( 'Number of words are enough, but optimize HTML amount.', 'cgss' ),
									'ratio_ok' => __( 'Optimize number of words, while amount of HTML is ok.', 'cgss' ),
									'no' => __( 'Both number of words and amount of HTML need to be optimized.', 'cgss' ),
								),
					'links' => array(
									'ok' => __( 'Using proper amount of links(both internal and external).', 'cgss' ),
									'num_ok' => __( 'Amount of links are fine but optimize external and internal link ratio.', 'cgss' ),
									'ext_ok' => __( 'Enough external links, but optimize internal link numbers.', 'cgss' ),
									'no' => __( 'Number of links are not optimized.', 'cgss' ),
								),
					'images' => array(
									'ok' => __( 'Number of images are satisfactory.', 'cgss' ),
									'more' => __( 'More number of images will be good.', 'cgss' ),
									'less' => __( 'Reduce of number of images will be ideal.', 'cgss' ),
								),
					'speed' => array(
									'ok' => __( 'Loading time is balanced.', 'cgss' ),
									'no' => __( 'You need to improve webpage speed.', 'cgss' ),
								),
					'social' => array(
									'ok' => __( 'Focus social media network', 'cgss' ) . ': <span id="FamousSocial"></span>. ' . __( 'Social popularity is fine.', 'cgss' ),
									'no' => __( 'Focus social media network', 'cgss' ) . ': <span id="FamousSocial"></span>. ' . __( 'Improve social popularity.', 'cgss' ),
								),
					'keys' => array(
									'ok' => __( 'Repetation of keyword are ok. Important focus keyword usage area:', 'cgss' ) . ' <span id="FamousKeys"></span>.',
									'more' => __( 'Use focus keyword more. Important focus keyword usage area:', 'cgss' ) . ' <span id="FamousKeys"></span>.',
									'less' => __( 'Use focus keyword less. Important focus keyword usage area:', 'cgss' ) . ' <span id="FamousKeys"></span>.',
								),
					'bonus' => __( 'About <span id="NumOfSsl"></span> have SSL certificate and <span id="NumOfMobile"></span> have responsive design. These are 2 seo factors.', 'cgss' ),
					'fail_opt' => __( 'Could not optimize.', 'cgss' ),
				);
	}

	//success notice mark.
	public function suc_notice( $txt ) {
		return '<div class="updated notice is-dismissible cgss-comp-saved"><p>' . $txt . '</p><button type="button" class="notice-dismiss cgss-comp-saved-btn"><span class="screen-reader-text">Dismis</span></button></div>';
	}

	//warning notice mark.
	public function war_notice( $txt ) {
		return '<div class="notice notice-warning is-dismissible cgss-comp-saved"><p>' . $txt . '</p><button type="button" class="notice-dismiss cgss-comp-saved-btn"><span class="screen-reader-text">Dismis</span></button></div>';
	}

	//error notice mark.
	public function err_notice( $txt ) {
		return '<div class="error notice is-dismissible cgss-comp-saved"><p>' . $txt . '</p><button type="button" class="notice-dismiss cgss-comp-saved-btn"><span class="screen-reader-text">Dismis</span></button></div>';
	}

	//green tick mark.
	public function enabled() {
		return '<span class="success-back white">&nbsp;' . __( 'enabled', 'cgss' ) . '&nbsp;</span>';
	}

	//green tick mark.
	public function disabled() {
		return '<span class="danger-back white">&nbsp;' . __( 'disabled', 'cgss' ) . '&nbsp;</span>';
	}

	//green tick mark.
	public function ok() {
		return $this->dashicon( 'yes success-icon' );
	}

	//red danger mark.
	public function no() {
		return $this->dashicon( 'no-alt danger-icon' );
	}

	//golden flag mark.
	public function flag() {
		return $this->dashicon( 'flag warning-icon' );
	}

	//full star.
	public function full_star() {
		return $this->dashicon( 'star-filled warning-icon' );
	}

	//half star.
	public function half_star() {
		return $this->dashicon( 'star-half warning-icon' );
	}

	//blank star.
	public function blank_star() {
		return $this->dashicon( 'star-empty warning-icon' );
	}

	//red love star.
	public function none() {
		return $this->dashicon( 'marker purple-icon' );
	}

	//red love star.
	public function love() {
		return $this->dashicon( 'heart danger-icon' );
	}

	//red love star.
	public function image() {
		return $this->dashicon( 'images-alt2' );
	}

	//for html in any page.
	public function dashicon( $icon ) {
		return '<span class="dashicons dashicons-' . $icon . '"></span>';
	}

	//green tick mark.
	public function up() {
		return $this->dashicon( 'arrow-up warning-icon' );
	}

	//green tick mark.
	public function down() {
		return $this->dashicon( 'arrow-down warning-icon' );
	}

} ?>
