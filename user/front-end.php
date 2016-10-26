<?php
/**
 * @/user/front-end.php
 * on: 02.07.2015
 * @since 2.0
 *
 * A compilation of classes and derived display classes for scan form and report display.
 *
 * It has 3 parts:
 * 1. BASE
 * 2. INCLUDE & BUILD
 * 3. INITIATE SEASON
 */





/**
 *
 * BASE
 */

//see if xtended plugin is installed or not
function cgss_plugin_xtend() {
	$xtend = false;
	$all_plugins = get_option('active_plugins');
	if ( $all_plugins ) {
		foreach ( $all_plugins as $key => $plug ) {
			if ( $plug == 'xtend-complete-google-seo-scan/xtend-complete-google-seo-scan.php' ) {
				$xtend = true;
			}
		}
	}
	return $xtend;
}

//Get screen options value
function cgss_nav_init() {
	$user = get_current_user_id();
	$screen = get_current_screen();
	$screen_option = $screen->get_option( 'per_page', 'option' );
	$per_page = get_user_meta($user, $screen_option, true);
	if ( empty ( $per_page) || $per_page < 1 ) {
		$per_page = $screen->get_option( 'per_page', 'default' );
	}
	return $per_page;
}

//Get public post types list in an array format
function post_types() {
	$post_types = get_post_types( array( 'public' => true, ), 'names' );
	unset( $post_types['attachment'] );

	$remove = get_option('cgss_screen_option_post_types');
	if ( ! $remove ) {
		$remove = $post_types;
	}
	$post_type_list = array();
	if ( $post_types and is_array( $post_types ) and count( $post_types ) > 0 ) {
		foreach( $post_types as $val ) {
			$count_posts = wp_count_posts( $val );
			if ( $count_posts->publish > 0 ) {

				//remove post types by screen options
				if ( in_array( $val, $remove ) ) {
					$post_type_list[] = array( $val, ucwords( get_post_type_object( $val )->labels->singular_name ), $count_posts->publish );
				}
			}
		}
	}

	return $post_type_list;
}





/**
 *
 * INCLUDE & BUILD
 */

//Required class files, essential for report display. You may use them in any sequence as they are
//independent of each other. But must call them before running this file.
require_once( 'lib/btn-object.php' );
require_once( 'lib/dropdown-object.php' );
require_once( 'lib/table-object.php' );
require_once( 'lib/accordion-object.php' );

//create more customize button group function
class cgss_group_btn {

	//button group for post types pages heading
	public function head() {
		$this->hbtn_one = $this->share_it();
		$this->hbtn_two = $this->overview();
		return $this->hbtn_two->display() . $this->hbtn_one->display() . $this->share();
	}

	//button group for post types pages heading
	public function overview_head() {
		$this->hbtn_one = $this->share_it();
		return $this->hbtn_one->display() . $this->share();
	}

	//button group for post types pages heading
	public function overview() {
		$btn = new CGSS_BTN( false, 'chart-pie', __( 'OVERVIEW', 'cgss' ), admin_url() . 'admin.php?page=seo-scan', ' push-icon-top' );
		return $btn;
	}

	//main share page link
	public function share_it() {
		$btn = new CGSS_BTN( 'SharePlug', 'smiley', ' ' . __( 'SHARE PLUGIN', 'cgss' ), false, ' push-icon-top' );
		return $btn;
	}

	//button group for report
	public function report() {
		$this->threebtn = new CGSS_BTN( 'ViewPage', 'external', false, '', false );
		$this->twobtn = new CGSS_BTN( 'DetailReport', 'analytics', ' ' . __( 'REPORT', 'cgss' ), false, false );
		$this->onebtn = new CGSS_BTN( 'BriefReport', 'editor-ul', ' <span id="ActionBtnNum"></span>' . __( 'ACTIONS', 'cgss' ), false, false );
		$this->btns = $this->onebtn->display() . $this->twobtn->display() . $this->threebtn->display();
		return '<div class="result-btns"><br />' . $this->btns .	'</div>';
	}

	//button group for sharing: google+, facebook, twitter
	public function share() {
		$this->gbtn = new CGSS_BTN( 'ShareGp', 'googleplus', false, 'https://plus.google.com/share?url=http%3A%2F%2Fgogretel.com%2F" target="_blank', ' push-icon-top' );
		$this->fbbtn = new CGSS_BTN( 'ShareFb', 'facebook', false, 'https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fgogretel.com%2F&t=Complete-Google-Seo-Scan-Plugin-for-WordPress" target="_blank', ' push-icon-top' );
		$this->twbtn = new CGSS_BTN( 'ShareTw', 'twitter', false, 'https://twitter.com/share?url=http%3A%2F%2Fgogretel.com%2F&via=gogretel&text=Complete-Google-Seo-Scan-Plugin-for-WordPress" target="_blank', ' push-icon-top' );
		return $this->gbtn->display() . $this->fbbtn->display() . $this->twbtn->display();
	}

	//button group for report
	public function compete_on() {
		$this->twobtn = new CGSS_BTN( 'EditPage', 'edit', false, '', false );
		$this->onebtn = new CGSS_BTN( 'ViewPageCompete', 'external', false, '', false );
		$this->btns = $this->onebtn->display() . $this->twobtn->display();
		return '<div class="result-btns"><br />' . $this->btns .	'</div>';
	}

	//button group for report
	public function compete_off() {
		$this->onebtn = new CGSS_BTN( 'BuyNowBtn', 'awards', ' ' . __( 'BUY NOW | $64', 'cgss' ), 'http://gogretel.com/checkout-2?edd_action=straight_to_gateway&download_id=570" target="_blank', false );
		$this->twobtn = new CGSS_BTN( 'ViewNowBtn', 'tablet', ' ' . __( 'VIEW ON SITE', 'cgss' ), 'http://gogretel.com/extension" target="_blank', false );
		$this->btns = $this->onebtn->display() . $this->twobtn->display();
		return '<div class="result-btns"><br />' . $this->btns . ' <a href="http://gogretel.com/checkout-2?edd_action=straight_to_gateway&download_id=570" class="hide-if-no-js add-new-h2 buy-now-btn-mobile">' . __( 'BUY FOR', 'cgss' ) . ' $64</a><a class="learn-more-btn-mobile" href="http://gogretel.com/extension">' . __( 'LEARN MORE', 'cgss' ) . '</a></div>';
	}
}

//Create 2 tables needed for overview
class cgss_do_table {

	//for server features in overview table
	public function show_keys() {

			$input_hd = array( __( 'Heading', 'cgss' ), __( 'Alt', 'cgss' ), __( 'Anchor', 'cgss' ), __( 'Title', 'cgss' ), __( 'Description', 'cgss' ), __( 'Url', 'cgss' ) );
			$this->tbl_hd_data = array();
			foreach( $input_hd as $key ) {
				$this->tbl_hd_data[] = array(
					'id' => false,
					'class' => 'show-key aligncenter',
					'icon_class' => false,
					'val' => $key,
				);
			}

			//create input array for table content
			$this->tbl_data = array();
			$input_data = array( 'HeadingChk', 'AltChk', 'AnchorChk', 'TitleChk', 'MetaDescChk', 'UrlChk' );
			foreach ( $input_data as $val ) {
				$this->tbl_data[] = array(
										'id' => false,
										'class' => 'aligncenter',
										'val' => '<span id="' . $val . '"></span>',
									);
			}

			//instantiate the table
			$this->tbl = new  CGSS_TABLE( 'PostTypeData', 'wp-list-table fixed pages', 'iedit author-self type-post status-publish format-standard has-post-thumbnail hentry', false, $this->tbl_hd_data, array( $this->tbl_data ) );
			return $this->tbl->display( false );
	}

	//for server features in overview table
	public function server_seo() {

			$this->tbl_hd_data = array(
				array(
					'id' => false,
					'class' => 'manage-column column-title',
					'icon_class' => false,
					'val' => __( 'Name', 'cgss' ),
				),
				array(
					'id' => false,
					'class' => 'manage-column column-comments num sortable desc',
					'icon_class' => false,
					'val' => false,
				),
			);

			//create input array for table content
			$this->tbl_data_type = array();
			$input_data = array(
								array( __( 'SSL security', 'cgss' ), 'SSLChk', '<strong>https://</strong> prefix is present or not in url', 'http://searchengineland.com/google-starts-giving-ranking-boost-secure-httpsssl-sites-199446' ),
								array( __( 'WWW Resolve', 'cgss' ), 'WWWChk', 'Redirects to same url with and without <strong>www</strong> prefix', 'https://moz.com/learn/seo/duplicate-content' ),
								array( __( 'IP forwarding', 'cgss' ) . '<span id="IpVal"></span>', 'IPChk', 'Internet Protocol address, asigned to this domain', 'https://support.google.com/webmasters/answer/139066?hl=en' ),
								array( __( 'Gzip Compression', 'cgss' ), 'GzipChk', '<strong>HTTP_ACCEPT_ENCODING</strong> has gzip compression value', 'https://developers.google.com/speed/docs/insights/EnableCompression' ),
								array( __( 'Cache', 'cgss' ), 'CacheChk', 'Presense of <strong>Cache-Control</strong> key in header', 'https://developers.google.com/speed/docs/insights/LeverageBrowserCaching' ),
								array( __( 'If modified since', 'cgss' ), 'IfModChk', 'Presense of <strong>Last-Modified</strong> key in header', 'https://varvy.com/ifmodified.html' ),
								array( __( 'Response Time', 'cgss' ) . '<span id="ResVal"></span> ' . __( 'miliseconds', 'cgss' ), 'ResTChk', 'Time to get response to header request. Good if kept under 500 ms', 'https://moz.com/blog/how-website-speed-actually-impacts-search-ranking' ),
							);
			foreach ( $input_data as $val ) {
				$this->tbl_data_type[] = array(
											array(
												'id' => false,
												'class' => false,
												'val' => $val[0] . '<div class="row-actions little-dark-text">' . $val[2] . '. <a href="' . $val[3] . '">' . __( 'More', 'cgss' ) . '</a></div>',
											),
											array(
												'id' => false,
												'class' => 'comments column-comments aligncenter',
												'val' => '<span id="' . $val[1] . '"></span>',
											),
										);
			}

			//instantiate the table
			$this->tbl = new  CGSS_TABLE( 'PostTypeData', 'wp-list-table widefat fixed striped pages', 'iedit author-self type-post status-publish format-standard has-post-thumbnail hentry', false, $this->tbl_hd_data, $this->tbl_data_type );
			return $this->tbl->display( true );
	}

	//for multiple competative table
	//@array $value, $max, $avg, $min, $you
	public function comp_multi( $heads, $max, $min, $avg, $you, $id ) {

			$this->tbl_hd_data = array();
			foreach ( $heads as $val ) {
				$this->tbl_hd_data[] = array(
											'id' => false,
											'class' => false,
											'icon_class' => false,
											'val' => $val,
										);
			}

			//create input array for table content
			$this->tbl_data = array();
			$input_data = array(
								array( __( 'Maximum', 'cgss' ), $max ),
								array( __( 'Minimum', 'cgss' ), $min ),
								array( __( 'Optimum Range', 'cgss' ), $avg ),
								array( __( 'Yours', 'cgss' ), $you ),
							);
			foreach ( $input_data as $val ) {
				$this->tbl_cols = array();
				$this->tbl_cols[] = array(
										'id' => false,
										'class' => false,
										'val' => $val[0],
									);
				foreach ( $val[1] as $key ) {
					$this->tbl_cols[] = array(
											'id' => false,
											'class' => false,
											'val' => '<span id="' . $key . '"></span>',
										);
				}
				$this->tbl_data[] = $this->tbl_cols;
			}

			//instantiate the table
			$this->tbl = new  CGSS_TABLE( $id, 'wp-list-table widefat fixed striped pages', 'iedit author-self type-post status-publish format-standard has-post-thumbnail hentry', false, $this->tbl_hd_data, $this->tbl_data );
			return $this->tbl->display( false );
	}

	//specially for keywords in multiple competative table
	//@array $value, $max, $avg, $min, $you
	public function comp_key_snip( $heads, $domain, $title, $url, $desc, $alt, $anch, $htag, $bold, $txt ) {

			$this->tbl_hd_data = array();
			foreach ( $heads as $val ) {
				$this->tbl_hd_data[] = array(
											'id' => false,
											'class' => false,
											'icon_class' => false,
											'val' => $val,
										);
			}

			//create input array for table content
			$this->tbl_data = array();
			$input_data = array(
								array( __( 'Domain', 'cgss' ), $domain ),
								array( __( 'Title Tag', 'cgss' ), $title ),
								array( __( 'Url', 'cgss' ), $url ),
								array( __( 'Meta Description', 'cgss' ), $desc ),
								array( __( 'Image Alt Tag', 'cgss' ), $alt ),
								array( __( 'Anchor text', 'cgss' ), $anch ),
								array( __( 'Heading tags', 'cgss' ), $htag ),
								array( __( 'Plain Text', 'cgss' ), $txt ),
								array( __( '<strong>Bold Keyword</strong>', 'cgss' ), $bold ),
							);
			foreach ( $input_data as $val ) {
				$this->tbl_cols = array();
				$this->tbl_cols[] = array(
										'id' => false,
										'class' => false,
										'val' => $val[0],
									);
				foreach ( $val[1] as $key ) {
					$this->tbl_cols[] = array(
											'id' => false,
											'class' => false,
											'val' => '<span id="' . $key . '"></span>',
										);
				}
				$this->tbl_data[] = $this->tbl_cols;
			}

			//instantiate the table
			$this->tbl = new  CGSS_TABLE( 'PostTypeData', 'wp-list-table widefat fixed striped pages', 'iedit author-self type-post status-publish format-standard has-post-thumbnail hentry', false, $this->tbl_hd_data, $this->tbl_data );
			return $this->tbl->display( false );
	}
}

//Get list of post types with nick-name and visible name
class cgss_do_dpd {

	public function filter() {
		$this->dpd = new CGSS_DROPDOWN( 'cgss_time_filter', 'attachment-filters', 'cgss-time-filter', $this->time(), null, null, false );
		return $this->dpd->display();
	}

	public function categories() {
		$cats = get_categories();
		$options = '';
		if ( $cats ) {
			foreach( $cats as $cat ) {
				$options .= '<option name="cgss_category_seo_' . strtolower( $cat->name ) . '" value="' . get_category_link($cat->term_id) . '">' . $cat->name . '</option>'; 
			}
		}
		if ( $options ) {
			return '<select id="cgss-categories" class="attachment-filters"><option value="select">' . __( 'Categories', 'cgss' ) . '</option>' . $options . '</select>';
		} else {
			return false;
		}
	}

	public function tags() {
		$posttags = get_tags();
		$options = '';
		if ( $posttags ) {
			foreach( $posttags as $tag ) {
				$options .= '<option name="cgss_tag_seo_' . strtolower( $tag->name ) . '" value="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</option>'; 
			}
		}
		if ( $options ) {
			return '<select id="cgss-tags" class="attachment-filters"><option value="select">' . __( 'Tags', 'cgss' ) . '</option>' . $options . '</select>';
		} else {
			return false;
		}
	}

	//get time filter options
	public function time() {
		return array(
					array( 'selected', __( 'All Time', 'cgss' ) ),
					array( '86400', __( 'Today', 'cgss' ) ),
					array( '604800', __( '7 days', 'cgss' ) ),
					array( '2592000', __( '30 days', 'cgss' ) ),
					array( '7776000', __( '90 days', 'cgss' ) ),
					array( 'nil', __( 'Never', 'cgss' ) ),
				);
	}
}

//Create required elements for display
class cgss_elements {

	//Help data for report display
	public function report_show() {
		$content = $this->content();
		$design = $this->design();
		$crawl = $this->crawl();
		$time = $this->report_time();
		$stag = $this->social_tags();
		return array(
					 'content' => $content,
					 'design' => $design,
					 'crawl' => $crawl,
					 'time' => $time,
					 'social-tags' => $stag,
				);
	}

	//Display report
	public function action() {
		$arr = array(
					array( 'TitleAct', __( '<code>< title</code> tag is not found. Put a proper tag.', 'cgss' ), 'https://moz.com/learn/seo/title-tag', 'marker' ),
					array( 'DescAct', __( '<code>< meta name="description"</code> tag is not found. Put a proper tag.', 'cgss' ), 'https://moz.com/learn/seo/meta-description', 'marker' ),
					array( 'KeyAct', __( 'Long tail keywords are helpful for seo. Use them with percistance but don\'t spam.', 'cgss' ), '', 'text' ),
					array( 'CountWordAct', __( 'Write a minimum of 200 words atleast.', 'cgss' ), '', 'text' ),
					array( 'RatioAct', __( 'Use enough text in comparison of HTML. But also don\'t use too much text.', 'cgss' ), '', 'text' ),
					array( 'HrchyAct', __( 'Text should have hierarchy with heading tags like <code>h1</code>, <code>h2</code>, <code>h3</code>.', 'cgss' ), '', 'text' ),
					array( 'ImgLnAct', __( 'Crawler robots can not read image links. Please use some text inside links also.', 'cgss' ), '', 'text' ),
					array( 'NofLnAct', __( 'Use nofollow links and dofollow links in a reasonable propotion.', 'cgss' ), '', 'text' ),
					array( 'ExtLnAct', __( 'Use external links and internal links in a reasonable propotion.', 'cgss' ), '', 'text' ),
					array( 'LnNumAct', __( 'The webpage looks like a link farm. Reduce number of links.', 'cgss' ), '', 'text' ),
					array( 'AltAct', __( 'Put proper <code>alt</code> tags in images.', 'cgss' ), '', 'smartphone' ),
					array( 'NtblAct', __( 'Don\'t use nested tables, i.e. tables within table columns.', 'cgss' ), '', 'smartphone' ),
					array( 'StlAtrAct', __( 'Remove style attributes from all HTML tags and use .css file instead.', 'cgss' ), '', 'smartphone' ),
					array( 'VportAct', __( '<code>< meta name="viewport"</code> tag should be present for scaling webpage with device size.', 'cgss' ), 'https://developers.google.com/speed/docs/insights/ConfigureViewport', 'smartphone' ),
					array( 'CssMediaAct', __( '<code>@media</code> query is not found. The webpage should be mobile ready.', 'cgss' ), 'https://developers.google.com/web/fundamentals/layouts/rwd-fundamentals/use-media-queries?hl=en', 'smartphone' ),
					array( 'SslAct', __( 'Get an SSL certificate and use url with <code>https://</code>.', 'cgss' ), '', 'randomize' ),
					array( 'UrlAct', __( 'Url is not clean and tidy. Don\'t use underscores and dynamic url.', 'cgss' ), '', 'randomize' ),
					array( 'WwwAct', __( 'Url with and without WWW should redirect to same url. Resolve it.', 'cgss' ), '', 'randomize' ),
					array( 'CanoAct', __( 'Put a canonical link.', 'cgss' ), 'https://support.google.com/webmasters/answer/139066?hl=en', 'randomize' ),
					array( 'IfModAct', __( 'Enable If modified since header.', 'cgss' ), '', 'randomize' ),
					array( 'RoboAct', __( '<code>< meta name="robots"</code> should not have <code>noindex</code> as value.', 'cgss' ), 'https://support.google.com/webmasters/answer/93710?hl=en', 'randomize' ),
					array( 'IpAct', __( 'Put an IP address and forward to page url', 'cgss' ), 'https://support.google.com/webmasters/answer/139066?hl=en', 'randomize' ),
					array( 'SpeedAct', __( 'Header response time is too long. Improve website server.', 'cgss' ), '', 'clock' ),
					array( 'GzipAct', __( 'Enable gZip compression.', 'cgss' ), 'https://developers.google.com/speed/docs/insights/EnableCompression', 'clock' ),
					array( 'CacheAct', __( 'Enable Browser Caching.', 'cgss' ), 'https://developers.google.com/speed/docs/insights/LeverageBrowserCaching', 'clock' ),
					array( 'FNumAct', __( 'Reduce number of HTTP requests made by .css and .js files.', 'cgss' ), '', 'clock' ),
					array( 'CompAct', __( 'Compress resource files and increase loading speed.', 'cgss' ), '', 'clock' ),
					array( 'StagOgpAct', __( 'Complete all Open Graph Protocol <code>meta</code> tags, needed for social sharing.', 'cgss' ), '', 'marker' ),
		);
		$action = '';
		foreach ( $arr as $key ) {
			$action .= '<div id="' . $key[0] . '" class="cgss-actions-comment">' .
						$this->dashicon( $key[3] . ' danger-icon' ) . ' <span>' . $key[1] . '</span>' .
						( $key[2] ? ' <a href="' . $key[2] . '" target="_blank">' . __( 'Help', 'cgss' ) . '</a>' : '' ) .
					 '</div>';
		}
		return '<div class="row">' .
					$action .
				'</div>';
	}

	//Display report
	public function report( $accord ) {
		return '<div class="row">
					<div class="col-1 hide-mobile"></div>
					<div class="col-4">
						<div class="point-releases main-tags">' .
							$accord .
						'</div>
					</div>
				</div>';
	}

	// for title description
	public function content() {
		$tables = new cgss_do_table();
		$key_check = $tables->show_keys();
		return '<div id="keyDis"></div>' .
				__( 'Select popular words', 'cgss' ) . ': <select id="KeywordList"></select><br /><br />' .
				$key_check . '<br />' .
				'<div class="row">
					<div class="col-3">' .
						$this->slide( 'words', '<span id="WordsSwitch"></span> ' . __( 'Number of words', 'cgss' ), 'WordsNum', __( 'Minimum 200 words in a webpage is required. Write enough words to describe the page well.', 'cgss' ), false ) .
						$this->slide( 'thratio', '<span id="TratioSwitch"></span> ' . __( 'Text to html ratio', 'cgss' ), 'Tratio', __( 'There must be enough text in webpage as compared with total size. About 15% to 70%', 'cgss' ), false ) .
						$this->slide( 'thrcy', '<span id="ThrchySwitch"></span> ' . __( 'Text hierarchy', 'cgss' ), 'Thrchy', __( 'A bulk of text is not useful, rather text content with proper heading tags are needed.', 'cgss' ), false ) .
					'</div>
					<div class="col-3">' .
						$this->slide( 'imglink', '<span id="ImgLinkTick"></span> ' . __( 'Image Links', 'cgss' ), 'ImgLink', __( 'Links that conatin only image but no text is considered poorly optimized.', 'cgss' ), false ) .
						$this->slide( 'noflink', '<span id="NofLinkTick"></span> ' . __( 'Nofollow Links', 'cgss' ), 'NofLink', __( 'Use <code>rel="nofollow"</code> links as many as you want, but don\'t block all links from crawling.', 'cgss' ), false ) .
						$this->slide( 'exlink', '<span id="ExtLinkTick"></span> ' . __( 'External Links', 'cgss' ), 'ExLink', __( 'Links that points to other domain must be present, in a proportional number.', 'cgss' ), false ) .
					'</div>
				</div>
				<div class="cgss-comment">
					<p><span id="LinksNumMsg"></span></p>
				</div>';
	}

	// for title description
	public function design() {
		return '<div class="img-list border-top">
					<span id="ImgAltDis"></span>
				</div>
				<div class="cgss-comment">
					<span id="ImgSwitch"></span>
				</div>' .
				'<div class="row">
					<div class="col-3">' .
						$this->slide( 'nestb', '<span id="NtblSwitch"></span> ' . __( 'Nested Tables', 'cgss' ), 'NtblVal', __( 'In markup of webpage, if table element is put inside a column of another table, it makes the page slow.', 'cgss' ), false ) .
						$this->slide( 'style', '<span id="TgStlSwitch"></span> ' . __( 'Style Attributes', 'cgss' ), 'TgStlVal', '<code>style</code> ' . __( 'attribute present in markup tags is not a good practice and increases render time.', 'cgss' ), false ) .
					'</div>
					<div class="col-3">' .
						$this->slide( 'media', '<span id="MdQrSwitch"></span> ' . __( 'Media Queries', 'cgss' ), 'MdQrVal', __( 'For responsive web design, you need to have <code>@media</code> elements inside .css files.', 'cgss' ), false ) .
						$this->slide( 'vport', __( 'Viewport Tag', 'cgss' ), 'VportOnOff', __( 'On <code>head</code> area of webpage, there is a tag which helps in scaling the dimensions according to display size.', 'cgss' ), false ) .
					'</div>
				</div>';
	}

	// for title description
	public function crawl() {
		return '<div class="show-url">
					<span class="show-url-text"></span>
				</div>
				<p>
					<span id="sslSwitch"></span> <span>' . __( 'SSL security', 'cgss' ) . '</span>&nbsp;&nbsp;&nbsp;
					<span id="dynamicSwitch"></span> <span>' . __( 'Static url ', 'cgss' ) . '</span>&nbsp;&nbsp;&nbsp;
					<span id="underscoreSwitch"></span> <span>' . __( 'Use of underscores in Url', 'cgss' ) . '</span>&nbsp;&nbsp;&nbsp;
				</p>
				<div class="row">
					<div class="col-3">' .
						$this->slide( 'www', __( 'www Resolve', 'cgss' ), 'WwwOnOff', __( 'Check if url is showing same webpage for url with and without "www." prefix', 'cgss' ), false ) .
						$this->slide( 'if-mod', __( 'If modified since', 'cgss' ), 'IfModOnOff', __( 'Let web server tell Google whether your content has changed since google bot last crawled your site.', 'cgss' ), false ) .
					'</div>
					<div class="col-3">' .
						$this->slide( 'cano', __( 'Canonical Url', 'cgss' ), 'CanoOnOff', __( 'Let Google know, same content is available through multiple URL structures or via syndication.', 'cgss' ), false ) .
						$this->slide( 'robo', '<span id="RoboChk"></span> ' . __( 'Meta Robot', 'cgss' ), 'RoboVal', __( 'Visibility in search result stops on use of "noindex" value in robots meta tag.', 'cgss' ), false ) .
					'</div>
				</div>' .
				$this->slide( 'ip', '<span id="IpSwitch"></span> ' . __( 'IP Forwarded', 'cgss' ), 'IpAddr', __( 'Check if server IP address (internet protocol) is forwarding to the same url or not.', 'cgss' ), false );
	}

	// for title description
	public function report_time() {
		return '<ul>
					<li style="list-style-ttype: none;">' . __( 'Header response time', 'cgss' ) . ': <strong><span id="ResTime"></span></strong> ' . __( 'miliseconds', 'cgss' ) . '</li>
					<li style="list-style-ttype: none;">' . __( 'Loading time', 'cgss' ) . ': <strong><span id="DownTime"></span></strong> ' . __( 'Seconds', 'cgss' ) . '</li>
				</ul>
				<div class="row">
					<div class="col-3">' .
						$this->slide( 'gzip', __( 'Gzip Compression', 'cgss' ), 'GzipOnOff', __( 'Deliver page content in a compressed .gzip file to reduce bandwidth and loading time.', 'cgss' ), false ) .
						$this->slide( 'cache', __( 'Browser Caching', 'cgss' ), 'CacheOnOff', __( 'Store page assets in browser of user for specific time, to load it faster next time.', 'cgss' ), false ) .
					'</div>
					<div class="col-3">
						<br /><p><span id="cssOnOff"></span> .css ' . __( 'requests', 'cgss' ) . ': <span id="cssNumSize"></span></p>
						<br /><p><span id="jsOnOff"></span> .js ' . __( 'requests', 'cgss' ) . ': <span id="jsNumSize"></span></p>
					</div>
				</div>
				<div class="cgss-comment">
					<span id="ResComp"></span>
				</div>';
	}

	// for title description
	public function social_tags() {
		return '<div class="fb-snippet">
					<a class="fb-snippet-url" id="StagUrl" target="_blank" href="">
						<div class="fb-snippet-img">
							<img id="StagImage" src="" alt="" width="470px" height="246px" />
						</div>
						<div class="fb-snippet-con">
							<div class="fb-snippet-title"><span id="StagTitle"></span></div>
							<div class="fb-snippet-desc"><span id="StagDesc"></span></div>
							<div class="fb-snippet-domain"><span id="StagDomain"></span></div>
						</div>
					</a>
				</div>
				<div class="row aligncenter">
					<br /><h4>' .__( 'Sample Facebook Snippet, by Open Graph Protocol Data', 'cgss' ) . '. <a target="_blank" href="http://ogp.me/">' . __( 'More', 'cgss' ) . '</a></h4>
				</div>';
	}

	//
	public function slide( $tog_id, $name, $id, $help, $href ) {
		return '<br /><p><a href="#" class="' . $tog_id . '-help">' . $name . '</a> <span id="' . $id . '"></span></p>
				<div class="cgss-comment ' . $tog_id . '-help-msg">' . $help . ( $href ? ' <a target="_blank" href="' . $href . '">' . __( 'More', 'cgss' ) . '</a>' : '' ) . '</div>';
	}

	// for loading animation
	public function loading_alt( $id ) {
		return '<!--Scan Process Area-->
			<div id="loadingProgressG"' . ( $id ? ' class="loading-' . $id . '"' : false ) . '>
				<div id="loadingProgressG_1" class="loadingProgressG"></div>
			</div>';
	}

	// for loading animation
	public function loading( $id ) {
		return '<!--Scan Process Area-->
			<div id="circleG"' . ( $id ? ' class="loading-' . $id . '"' : false ) . '>
				<div id="circleG_1" class="circleG"></div>
				<div id="circleG_2" class="circleG"></div>
				<div id="circleG_3" class="circleG"></div>
			</div>';
	}

	//descriptions for numbers of http requests
	public function design_number() {
		return '<ul>
					<li class="page-count"><span id="JsNum"></span> by Javascripts (.js)</li>
					<li class="page-count"><span id="CssNum"></span> Stylesheets (.css)</li>
				</ul><br />
				<p><span id="CssImportNum"></span> <code>@import</code> present in .css files. If present, they are counted as extra requests.</p><br />';
	}

	//descriptions for size of 
	public function design_size() {
		return '<ul>
					<li class="page-count"><span id="JsSize"></span> kb by Javascripts (.js)</li>
					<li class="page-count"><span id="CssSize"></span> kb Stylesheets (.css)</li>
				</ul><br />
				<p><span id="CompressJsNum"></span> .js files and <span id="CompressCssNum"></span> .css files can be more compressed and you can reduce <span id="CompressSize"></span> kb.</p><br />';
	}

	//url display
	public function text_report_arr() {
		return array(
					array( "TextComOk", __( 'And that\'s fine.', 'cgss' ) ),
					array( "TextComNo", __( 'That\'s too much of text, please write less.', 'cgss' ) ),
					array( "HtmlComNo", __( 'That\'s too little text, please write more.', 'cgss' ) ),
				);
	}

	//url display
	public function url_report_arr() {
		return array(
					array( "UrlOk", $this->ok(), __( 'Url is clean and tidy', 'cgss' ) ),
					array( "UrlUnderscore", $this->no(), __( 'Url has underscore', 'cgss' ) ),
					array( "UrlDynamic", $this->no(), __( 'Url is dynamic', 'cgss' ) ),
					array( "UrlError", $this->no(), __( 'Url is dynamic and has underscore', 'cgss' ) ),
				);
	}

	//Output intel display content points
	public function intel_content() {
		$other_arr_intel = array(
								array( 'text', 'AmountIntel' ),
								array( 'admin-links', 'LinksIntel' ),
								array( 'editor-spellcheck', 'KeysIntel' ),
								array( 'format-image', 'ImagesIntel' ),
							);
		$output = '';
		foreach( $other_arr_intel as $val ) {
			$output .= '<li class="cgss-actions-comment"><span class="welcome-icon">' . $this->dashicon( $val[0] ) . $this->gaps( 2 ) . '<span id="' . $val[1] . '"></span></span></li>';
		}
		return '<ul>' . $output . '</ul>';
	}

	//Output intel display extra points
	public function intel_extra() {
		$other_arr_intel = array(
								array( 'smartphone', 'DesignIntel' ),
								array( 'randomize', 'UrlIntel' ),
								array( 'clock', 'ClockIntel' ),
								array( 'share', 'ShareIntel' ),
							);
		$output = '';
		foreach( $other_arr_intel as $val ) {
			$output .= '<li class="cgss-actions-comment"><span class="welcome-icon">' . $this->dashicon( $val[0] ) . $this->gaps( 2 ) . '<span id="' . $val[1] . '"></span></span></li>';
		}
		return '<ul>' . $output . '</ul>';
	}

	//Create success icon 
	public function ok() {
		return $this->dashicon( 'yes success-icon' );
	}

	//Create warning icon
	public function flag() {
		return $this->dashicon( 'flag warning-icon' );
	}

	//Create danger icon 
	public function no() {
		return $this->dashicon( 'no-alt danger-icon' );
	}

	//@param string $class_part Rest part of class to complete 
	public function dashicon( $class_part ) {
		return '<span class="dashicons dashicons-' . $class_part . '"></span>';
	}

	//Multiple space elements
	//@param int $n A number of times to repeat
	public function gaps( $n ) {
		return str_repeat( '&nbsp;', $n );
	}
}

//get all enqueeued scripts and styles
class cgss_enqueued {

	//Unordered list of scripts
	public function script_ul( $id, $obj ) {
		$base = $obj->base_url;
		$queued_scripts = $obj->queue;
		$list_content = '';
		if ( count( $queued_scripts ) > 0 ) :
			$registered_scripts = $obj->registered;
			foreach ( $queued_scripts as $script ) :
				$script_obj = $registered_scripts[$script];
				$script_src = $script_obj->src;
				if ( $script_src ) :
					if ( substr( $script_src, 0, strlen($base) ) != $base ) {
						$new_script_src = $base . $script_src;
					} else {
						$new_script_src = $script_src;
					}
					if( filter_var( $new_script_src, FILTER_VALIDATE_URL ) !== false ) :
						if ( strpos( $new_script_src, '.css' ) !== false or strpos( $new_script_src, '.js' ) !== false ) :
							$list_content .= '<li>' . $new_script_src . '</li>';
						endif;
					endif;
				endif;
			endforeach;
		endif;
		if ( $list_content ) {
			return '<ul class="' . $id . ' hide" style="display: none;">' . $list_content . '</ul>';
		} else {
			return false;
		}
	}
}





/**
 *
 * INITIATE SEASON
 */

//Get the url trailing segment and cut it into pieces of parameters
function url_params() {

	$type_out = false;
	$paged_out = false;
	$path = $_SERVER['REQUEST_URI'];
	if ( strpos( $path, 'seo-scan-' ) !== false ) {
		$types = explode( 'seo-scan-', $path );
		if ( array_key_exists( 1, $types ) ) {
			if ( strpos( $path, '&paged=' ) !== false ) {
				$types_brk = explode( '&paged=', $types[1] );
				$type_out = $types_brk[0];
				$paged_out = $types_brk[1];
			} else {
				$type_out = $types[1];
			}
		}
	}
	return array(
				'type' => $type_out,
				'paged' => $paged_out,
	);
}

//Initiate basic session details
$params = url_params();

$xtend_install = cgss_plugin_xtend();
$post_types = post_types();
$time_now = current_time( 'timestamp' );
$btns = new cgss_group_btn();
$tables = new cgss_do_table();
$dpd = new cgss_do_dpd();
$elem = new cgss_elements();
$queued = new cgss_enqueued();
$accord = new CGSS_ACCORDION();



//create post navigation
function cgss_page_nav( $type ) {

	$per_page = cgss_nav_init();

	$post_types = wp_count_posts( $type );
	$post_types_num = $post_types->publish;
	if ( $post_types_num > $per_page ) :
		$count = ceil( $post_types_num / $per_page );

		$params = url_params();
		$paged = $params['paged'];
		if ( ! $paged ) {
			$paged = 1;
		}
		$pre = ( $paged - 1 );
		$post = ( $paged + 1 );
?>
	<div class="tablenav-pages">
		<span class="displaying-num"><?php echo $post_types_num . ' ' . __( 'items', 'cgss' ); ?></span>
		<span class="pagination-links">
			<a class="first-page<?php echo ( $paged == 1 ? ' disabled' : '' ); ?>" title="Go to the first page" href="<?php echo ( $paged != 1 ? admin_url() . 'admin.php?page=seo-scan-' . $type . '&paged=1' : '#' ); ?>">&laquo;</a>
			<a class="prev-page<?php echo ( $paged == 1 ? ' disabled' : '' ); ?>" title="Go to the previous page" href="<?php echo ( $paged != 1 ? admin_url() . 'admin.php?page=seo-scan-' . $type . '&paged=' . $pre : '#' ); ?>">&lsaquo;</a>
			<span class="paging-input">
				<?php echo $paged . ' ' . __( 'of', 'cgss' ) . ' ' . $count; ?>
			</span>
			<a class="next-page<?php echo ( $paged == $count ? ' disabled' : '' ); ?>" title="Go to the next page" href="<?php echo ( $paged != $count ? admin_url() . 'admin.php?page=seo-scan-' . $type . '&paged=' . $post : '#' ); ?>">&rsaquo;</a>
			<a class="last-page<?php echo ( $paged == $count ? ' disabled' : '' ); ?>" title="Go to the last page" href="<?php echo ( $paged != $count ? admin_url() . 'admin.php?page=seo-scan-' . $type . '&paged=' . $count : '#' ); ?>">&raquo;</a>
		</span>
	</div>
<?php endif;
}

//Show notices for filters
function cgss_filter_notice() {

	$elem = new cgss_elements(); ?>
	<strong>
		<span id="AllMatched" class="displaying-num">
			<?php echo $elem->dashicon( 'welcome-learn-more success-icon' ) . ' ' . __( 'All Items Matched', 'cgss' ); ?>
		</span>
		<span id="NoMatched" class="displaying-num">
			<?php echo $elem->dashicon( 'welcome-comments danger-icon' ) . ' ' . __( 'No Match Found', 'cgss' ); ?>
		</span>
	</strong>
<?php } ?>
