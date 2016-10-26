/**
 * @/assets/cgss-overview-script.js
 * on: 16.07.2015
 * @since 2.1
 *
 * It has 4 part:
 * 1. Include custom function.
 * 2. Ajax for scan archive pages.
 * 3. Ajax for server scan.
 * 4. Ajax for design scan of resource files.
 */
jQuery(document).ready(function($) {

	//Starting screen display, hide following selectors
	jQuery(".archive-panel,.archive-msg,.server-msg,.design-msg,.loading-archive,.loading-server,.loading-design,.loading-intel,.server-seo-result,.design-seo-result,.scan-failed,.show-msg,#ShareGp,#ShareFb,#ShareTw,#loadingProgressG,#ScanOver,.scan-report,#DetailReport,.scan-process,#Pros1,#Pros2,#Pros3,#Pros4,#Pros5,#Pros6,.view-again,#ShowTblNoticeFilter,#UrlOk,#UrlUnderscore,#UrlDynamic,#UrlError,#Fbmsg, #GplusMsg, #TwMsg,#TextComOk,#TextComNo,#LinkNo,#LinkYes,#LinkPoor,#LinkNoMsg,#LinkYesMsg,#LinkPoorMsg,#AltYes,#AltNo,#NoAltImage, #HtmlComNo,#HrchyYes,#HrchyPoor,#HrchyNo,#CanoYes,#CanoNo,#RoboYes,#RoboNone,#RoboNo,#StagsYes,#StagsNo,#KeysYes,#KeysNo,#KeysVal,#SpeedYes,#SpeedNo,#ResponsiveYes,#ResponsiveNo,#AttsYes,#AttsNo,#ValidYes,#ValidNo,.well-points,#TblYes,#TblNo,#NoMatched,#AllMatched").hide();

	//get notices from php file to display in front end through this script
	var note = overview_ajax_object.ajax_msg;
	var note_html = overview_ajax_object.ajax_html;
	var note_intel = overview_ajax_object.ajax_intel;

	//Sharing button actions
	jQuery("#SharePlug").click(function() {
		jQuery("#SharePlug").hide();
		jQuery("#ShareGp,#ShareFb,#ShareTw").show(250).removeAttr("style");
	});

	//disable archive scan button
	jQuery(".archive-scan").attr("disabled", "disabled");

	//Toggle archive dropdowns
	jQuery("#cgss-categories").change(function() {
		var cat = jQuery("#cgss-categories").val();
		if ( cat != "select" ) {
			jQuery("#cgss-tags").val("select");
			jQuery("#cgss-tags").attr("disabled", "disabled");
			jQuery(".archive-scan").removeAttr("disabled");
		} else {
			jQuery(".archive-scan").attr("disabled", "disabled");
			jQuery("#cgss-tags").removeAttr("disabled");
		}
	});
	jQuery("#cgss-tags").change(function() {
		var tag = jQuery("#cgss-tags").val();
		if ( tag != "select" ) {
			jQuery("#cgss-categories").val("select");
			jQuery("#cgss-categories").attr("disabled", "disabled");
			jQuery(".archive-scan").removeAttr("disabled");
		} else {
			jQuery(".archive-scan").attr("disabled", "disabled");
			jQuery("#cgss-categories").removeAttr("disabled");
		}
	});


	//Make archive scan ajax call
	jQuery(".archive-scan").click(function() {

		//Change display priority
		jQuery(".loading-archive").show();
		jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").attr("disabled", "disabled");
		jQuery(".archive-msg,.server-msg,.design-msg").hide();

		var cat_url = jQuery("#cgss-categories").val();
		var tag_url = jQuery("#cgss-tags").val();

		//very important bug fix
		if ( ! cat_url ) {
			cat_url = 'select';
		}
		if ( ! tag_url ) {
			tag_url = 'select';
		}

		//build a small functio to get id

		if ( cat_url == 'select' ) {
			var url = tag_url;
			var item = jQuery("#cgss-tags option:selected").attr("name");
		} else if ( tag_url == 'select' ) {
			var url = cat_url;
			var item = jQuery("#cgss-categories option:selected").attr("name");
		} else {
			var url = false;
		}

		if ( url != false ) {
			//Send Ajax request
			jQuery.ajax({
				type: "POST",
				url: overview_ajax_object.ajax_url,
				data: {'action': 'cgss_overview', 'type': 'scan', 'id': item, 'url': url},
				dataType: "json",
				success: function(data) {

					jQuery(".loading-archive").hide();
					jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").removeAttr("disabled");

					//If scan fails
					if ( data.ping == 'false' ) {
						jQuery(".archive-msg").text(note_html.no + " " + data.val);
						return false;
					}

					//After getting clearence, show the report
					jQuery(".scan-report,#ScanOver").show();

					//hide all actions
					var act = 1;
					jQuery("#TitleAct, #DescAct, #KeyAct, #CountWordAct, #RatioAct, #HrchyAct, #LinksAct, #NofLnAct, #ExtLnAct, #LnNumAct, #AltAct, #NtblAct, #StlAtrAct, #CssMediaAct, #VportAct, #SslAct, #UrlAct, #RoboAct, #WwwAct, #CanoAct, #IpAct, #SpeedAct, #GzipAct, #CacheAct, #FNumAct, #CompAct, #StagOgpAct").hide();

					var score = data.score;
					var new_score = show_score( score, note_html.full_star, note_html.half_star, note_html.blank_star );
					jQuery("#ScoreStars,#ScoreStarsAlt").html(new_score);

					act = scan_success( data, act, note_html.ok, note_html.no, note_html.spam, note_html.enabled, note_html.disabled, note_html.noindex, note_html.title, note_html.absent, note_html.mdesc, note_html.links_no, note_html.links_ok, note_html.img_none, note_html.image, note_html.img_no, note_html.img_ok, note_html.q_mark, note_html.under_mark, note_html.http, note_html.https, note_html.ok_compression, note_html.no_compression, note_html.absent, note_html.mdesc, note.none );

					act = stag_show( data, note_html.stag_title_absent, note_html.stag_desc_absent, note_html.stag_domain_absent, note_html.stag_image_absent, act );

					//Actions area heading toggle
					if ( act > 1 ) {
						jQuery("#ActionsList").show();
						jQuery("#NoActions").hide();
						jQuery("#ActNum").html(act);
						jQuery("#ActionBtnNum").html(act + " ");
					} else {
						jQuery("#NoActions").show();
						jQuery("#ActionsList").hide();
						jQuery("#ActionBtnNum").html("");
					}

				},
				error: function() {
					jQuery(".loading-archive").hide();
					jQuery(".archive-msg").html(note_html.no + " " + note.network);
					jQuery(".archive-msg").show();
					jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").removeAttr("disabled");
				}
			});
		}
	});


	//Make server seo scan ajax call
	jQuery(".server-scan").click(function() {

		//Change display priority
		jQuery(".loading-server").show();
		jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").attr("disabled", "disabled");
		jQuery(".archive-msg,.server-msg,.design-msg,.server-seo-result").hide();

		//Send Ajax request
		jQuery.ajax({
			type: "POST",
			url: overview_ajax_object.ajax_url,
			data: {'action': 'cgss_overview', 'type': 'server'},
			dataType: "json",
			success: function(data) {

				jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").removeAttr("disabled");
				jQuery(".loading-server").hide();

				//If scan fails
				if ( data.ping == 'false' ) {
					jQuery(".server-msg").text(note_html.no + " " + data.val);
					return false;
				}

				server_success( data, note_html.ok, note_html.no );

				jQuery(".server-seo-result").show();
			},
			error: function() {
				jQuery(".server-seo-result,.loading-server").hide();
				jQuery(".server-msg").html(note_html.no + " " + note.network);
				jQuery(".server-msg").show();
				jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").removeAttr("disabled");
			}
		});
	});


	//Make server seo scan ajax call
	jQuery(".design-scan").click(function() {

		//Change display priority
		jQuery(".loading-design").show();
		jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").attr("disabled", "disabled");
		jQuery(".archive-msg,.server-msg,.design-msg,.design-seo-result").hide();

		//get list of css and js
		var list_js = [];
		jQuery('.queued-script li').each( function() { list_js.push(jQuery(this).text()) });
		var list_css = [];
		jQuery('.queued-style li').each( function() { list_css.push(jQuery(this).text()) });

		//if there are no css and no js files
		if ( list_js.length > 0 || list_css.length > 0 ) {
			//Send Ajax request
			jQuery.ajax({
				type: "POST",
				url: overview_ajax_object.ajax_url,
				data: {'action': 'cgss_overview', 'type': 'design', 'css': list_css, 'js': list_js},
				dataType: "json",
				success: function(data) {

					jQuery(".loading-design").hide();
					jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").removeAttr("disabled");

					//If scan fails
					if ( data.ping == 'false' ) {
						jQuery(".design-msg").text(note_html.no + " " + data.val);
						return false;
					}

					design_success( data, note_html.ok, note_html.no );

					jQuery(".design-seo-result").show();

				},
				error: function() {
					jQuery(".design-seo-result,.loading-design").hide();
					jQuery(".design-msg").html(note_html.no + " " + note.network);
					jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").removeAttr("disabled");
				}
			});
		} else {
			jQuery(".design-msg").html(note_html.no + " " + note.no_script);
			jQuery(".design-msg").show();
			jQuery(".design-seo-result,.loading-design").hide();
		}
	});


	//Make intel scan ajax call
	jQuery(".cgss-intel").click( function() {

		jQuery(this).hide();
		jQuery(".loading-intel").show();
		jQuery(".intel-msg").hide();
		jQuery("#NumReports").html(0);
		jQuery(".intel-content,.intel-extra,.facebook-intel,.googleplus-intel,.twitter-intel").hide();
		jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").attr("disabled", "disabled");

		var ids = jQuery(".cgss-intel").attr("id");
		if ( ids != undefined ) {

			var intel_score = [];
			var intel_fb = [];
			var intel_gplus = [];
			var intel_twitter = [];
			var intel_stags = [];
			var intel_time = [];
			var intel_dynamic = [];
			var intel_underscore = [];
			var intel_mobile = [];
			var intel_image = [];
			var intel_words = [];
			var intel_ratio = [];
			var intel_links = [];
			var intel_links_ext = [];
			var intel_links_img = [];
			var intel_keyword = [];
			var ids_list = ids.split(",");
			var num_intel = 0;
			var total_num_intel = ids_list.length;
			jQuery("#NumReportsFull").html(total_num_intel);
			jQuery.each( ids_list, function( index, value ) {

				//Send Ajax request
				jQuery.ajax({
					type: "POST",
					url: overview_ajax_object.ajax_url,
					data: {'action': 'cgss_overview', 'type': 'intel', 'fetch': value},
					dataType: "json",

					//must be set to false so as it works other code elements
					async: false,
					success: function(data) {

						//If scan fails
						if ( data.ping == 'false' ) {
							jQuery(".intel-msg").text(note_html.no + " " + data.val);
							return false;
						}

						filter_intel_data( intel_score, data.score );
						filter_intel_data( intel_fb, data.share.fb );
						filter_intel_data( intel_gplus, data.share.gplus );
						filter_intel_data( intel_twitter, data.share.twitter );
						filter_intel_data( intel_stags, data.stags.num );
						filter_intel_data( intel_time, data.time );
						filter_intel_data( intel_dynamic, data.url.dynamic );
						filter_intel_data( intel_underscore, data.url.underscore );
						filter_intel_data( intel_mobile, data.mobile );
						filter_intel_data( intel_image, data.image );
						filter_intel_data( intel_words, data.words );
						filter_intel_data( intel_links, data.links.num );
						filter_intel_data( intel_links_ext, data.links.ext );
						filter_intel_data( intel_links_img, data.links.img );
						filter_intel_data( intel_keyword, data.keyword );
						filter_intel_data( intel_ratio, data.ratio );

						num_intel += 1;

						jQuery(".intel-msg").hide();
					},
					error: function() {
						total_num_intel -= 1;
						jQuery("#NumReportsFull").html(total_num_intel);
						jQuery(this).show();
						jQuery(".intel-content,.intel-extra,.loading-intel").hide();
						jQuery(".intel-msg").html(note_html.no + " " + note.network);
						jQuery(".intel-msg").show();
					}
				});

				jQuery("#NumReports").html(num_intel);
			});

			//display avg score
			var intel_marks = ( sum_arr( intel_score ) / total_num_intel ).toFixed(1);
			var intel_score = show_score( intel_marks, note_html.full_star, note_html.half_star, note_html.blank_star );
			jQuery(".cgss-intel-score").html(intel_score);

			//display total shares
			show_total_shares( intel_fb, "#FbShareIntel" );
			show_total_shares( intel_gplus, "#GplusCountIntel" );
			show_total_shares( intel_twitter, "#TweeetCountIntel" );

			//words intel
			words_intel( intel_words, intel_ratio, "#AmountIntel", "#WordsIntel", "#WordsPerPageIntel", "#WordsRatioIntel", note_intel.words );

			//links intel
			links_intel( intel_links, intel_links_ext, intel_links_img, "#LinksIntel", "#LinksNumIntel", "#ExtLinksIntel", "#ImgLinksIntel", note_intel.links );

			//keyword intel
			keyword_intel( intel_keyword, "#KeysIntel", "#KeyWordsSizeIntel", "#KeyWordsIntel", note_intel.keyword );

			//image design check
			image_intel( intel_image, "#ImagesIntel", "#ImagesIntelPercent", note_intel.image.ok, note_intel.image.no, note_intel.image.mid );

			//mobile design check
			mobile_intel( intel_mobile, "#DesignIntel", "#DesignIntelPercent", note_intel.mobile.ok, note_intel.mobile.no, note_intel.mobile.mid );

			//Url properties
			url_intel( intel_dynamic, intel_underscore, "#UrlIntel", "#UrlIntelPercent", "#UrlIntelPercentTwo", note_intel.url.ok, note_intel.url.no, note_intel.url.mid, note_intel.url.dynamic, note_intel.url.underscore );

			//Time needed for download
			time_intel( intel_time, "#ClockIntel", note_intel.time.fast, note_intel.time.slow, note_intel.time.mid, note_intel.time.very_slow );

			//display social tags
			stag_intel( intel_stags, "#ShareIntel", "#StagIntelNoPercent", note_intel.stag.ok, note_intel.stag.no, note_intel.stag.mid );

			jQuery(".intel-content,.intel-extra,.facebook-intel,.googleplus-intel,.twitter-intel").show();
			jQuery(".cgss-intel,.archive-scan,.server-scan,.design-scan").removeAttr("disabled");
			jQuery(this).show();
			jQuery(".loading-intel").hide();
		}
	});

	tog_accord( 'content' );
	tog_accord( 'design' );
	tog_accord( 'crawl' );
	tog_accord( 'time' );
	tog_accord( 'social-tags' );

	tog_help( 'words' );
	tog_help( 'thratio' );
	tog_help( 'thrcy' );
	tog_help( 'imglink' );
	tog_help( 'noflink' );
	tog_help( 'exlink' );
	tog_help( 'nestb' );
	tog_help( 'style' );
	tog_help( 'media' );
	tog_help( 'vport' );
	tog_help( 'www' );
	tog_help( 'if-mod' );
	tog_help( 'cano' );
	tog_help( 'robo' );
	tog_help( 'ip' );
	tog_help( 'gzip' );
	tog_help( 'cache' );

	//Toggle between detail and brief report
	jQuery("#BriefReport").click(function(){ jQuery(this).hide(); jQuery(".report-details").hide(); jQuery("#DetailReport,.report-brief").show(); });
	jQuery("#DetailReport").click(function(){ jQuery(this).hide(); jQuery(".report-brief").hide(); jQuery("#BriefReport,.report-details").show(); });

	//If user wants to go back to scan form from report page
	jQuery(".archive-show-btn").click(function() {
		jQuery(".archive-panel").toggle(150);
		jQuery(this).toggleClass("button-primary");
	});

	//If user wants to go back to scan form from report page
	jQuery("#ReportClose").click(function() {
		jQuery("#loadingProgressG, .scan-process, .scan-report").hide();
	});
});
