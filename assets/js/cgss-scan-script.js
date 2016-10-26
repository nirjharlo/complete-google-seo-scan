/**
 * @/assets/cgss-script.js
 * on: 02.07.2015
 * A compilation of classes and derived display classes for scan form and report display.
 *
 * It has 2 part:
 * 1. Include custom objects by required() function.
 * 2. Create derived objects and functions. MUST Maintain sequence.
 */
jQuery(document).ready(function($) {


	//Starting screen display
	jQuery(".scan-failed,.show-msg,#ShareGp,#ShareFb,#ShareTw,#loadingProgressG,#ScanOver,.scan-report,.scan-process,#Pros1,#Pros2,#Pros3,#Pros4,#Pros5,#Pros6,.view-again,#ShowTblNoticeFilter,#UrlOk,#UrlUnderscore,#UrlDynamic,#UrlError,#Fbmsg, #GplusMsg, #TwMsg,#TextComOk,#TextComNo,#LinkNo,#LinkYes,#LinkPoor,#LinkNoMsg,#LinkYesMsg,#LinkPoorMsg,#AltYes,#AltNo,#NoAltImage, #HtmlComNo,#HrchyYes,#HrchyPoor,#HrchyNo,#CanoYes,#CanoNo,#RoboYes,#RoboNone,#RoboNo,#StagsYes,#StagsNo,#KeysYes,#KeysNo,#KeysVal,#SpeedYes,#SpeedNo,#ResponsiveYes,#ResponsiveNo,#AttsYes,#AttsNo,#ValidYes,#ValidNo,.well-points,#TblYes,#TblNo,#NoMatched,#AllMatched,#DetailReport").hide();

	//get notices from php file to display in front end through this script
	var note = ajax_object.ajax_msg;
	var note_html = ajax_object.ajax_html;


	//Sharing button actions
	jQuery("#SharePlug").click(function() {
		jQuery("#SharePlug").hide();
		jQuery("#ShareGp,#ShareFb,#ShareTw").show(250).removeAttr("style");
	});



	/**
	 * Post Table Navigation
	 * Create a Filter for tables using time and score, because those are too long. 2 code blocks.
	 */
	jQuery("select[name='cgss-time-filter']").change(function() {

		//COunt nimber of filtered rows
		var filtered = 0;
		var card_count = 0;

		jQuery("#NoMatched,#AllMatched").hide();

		//Get selected values
		var time = jQuery("#cgss_time_filter").val();

		//Make changes on tables, based on dropdown options values.
		jQuery(".plugin-card").each( function() {

			//first, show all elements
			jQuery(this).show();

			//then, hide some elements
			var td_id = jQuery(this).attr("id");
			var td_id_arr = td_id.split("-");
			var td_time = td_id_arr[1];

			//set up filter functionality
			if ( time != 'selected' ) {
				if ( time != 'nil' ) {
					if ( td_time == 'nil' ) {
						filtered = filtered + 1;
						jQuery(this).toggle();
					}
					var time_gap = time - td_time;
					if ( time_gap < 0 ) {
						filtered = filtered + 1;
						jQuery(this).toggle();
					}
				} else {
					if ( td_time != 'nil' ) {
						filtered = filtered + 1;
						jQuery(this).toggle();
					}
				}
			}
			card_count = card_count + 1;
		});

		if ( time != 'selected' ) {

			//If none is filtered show all and append a notice
			if ( filtered == card_count ) {
				jQuery("#NoMatched").show();
				jQuery(".plugin-card").each( function() { jQuery(this).show(); });
			}

			//If all are filtered show all and append a notice
			if ( filtered == 0 ) {
				jQuery("#AllMatched").show();
			}
		}
	});


	/**
	 * When clicked on any radio button. Main process initiates. Functionality:
	 *
	 * 1. Change display priority
	 * 2. Show process animation
	 * 3. Deine Ajax params
	 * 4. Send the ajax request
	 * 		4.1. Define success function
	 * 		4.2. Define error function
	 */
	jQuery(".scan-now").click(function() {

		//Fetch data for url from radio input and post id from id attribute of that input
		var item = jQuery(this).attr("href").substring(1, this.length);
		var url = jQuery(this).attr("id");

		//Change display priority
		jQuery(".loading-" + item + ",.scan-process").show();
		jQuery(".scan-now").attr("disabled", "disabled");
		jQuery(".view-again,.scan-failed,.show-msg").hide();

		//Send Ajax request
		jQuery.ajax({
			type: "POST",
			url: ajax_object.ajax_url,
			data: {'action': 'cgss_core', 'id': item, 'url': url},
			dataType: "json",
			success: function(data) {

				//Initiate reporting screen
				jQuery("#loadingProgressG,.scan-process").hide();
				jQuery(".scan-now").removeAttr("disabled");

				//Get ping and respond accordingly.
				var ping = data.ping;
				if ( ping == 'false' ) {
					jQuery("#ShowMessage-" + item).show(100);
					jQuery("#ShowMessage-" + item).text(data.val);
					return false;
				}

				//After getting clearence, show the report
				jQuery(".scan-report,#ScanOver").show();

				//change screen display for the particular row.
				jQuery(".view-again").hide();
				jQuery("#ViewAgain-" + data.id).show();

				//hide all actions
				var act = 1;
				jQuery("#TitleAct, #DescAct, #KeyAct, #CountWordAct, #RatioAct, #HrchyAct, #LinksAct, #NofLnAct, #ExtLnAct, #LnNumAct, #ImgLnAct, #AltAct, #NtblAct, #StlAtrAct, #CssMediaAct, #VportAct, #SslAct, #UrlAct, #RoboAct, #WwwAct, #CanoAct, #IpAct, #SpeedAct, #GzipAct, #CacheAct, #FNumAct, #CompAct, #StagOgpAct").hide();

				var score = data.score;
				var new_score = show_score( score, note_html.full_star, note_html.half_star, note_html.blank_star );
				jQuery("#ScoreStars,#ScoreStarsAlt").html(new_score);

				act = scan_success( data, act, note_html.ok, note_html.no, note_html.spam, note_html.enabled, note_html.disabled, note_html.noindex, note_html.title, note_html.absent, note_html.mdesc, note_html.links_no, note_html.links_ok, note_html.img_none, note_html.image, note_html.img_no, note_html.img_ok, note_html.q_mark, note_html.under_mark, note_html.http, note_html.https, note_html.ok_compression, note_html.no_compression, note_html.absent, note_html.mdesc, note.none );

				act = stag_show( data, note_html.stag_title_absent, note_html.stag_desc_absent, note_html.stag_domain_absent, note_html.stag_image_absent, act );

				//Actions area heading toggle
				if ( act > 1 ) {
					jQuery("#ActionsList").show();
					jQuery("#NoActions").hide();
					jQuery("#ActNum").html(act - 1);
					jQuery("#ActionBtnNum").html(act - 1 + " ");
				} else {
					jQuery("#NoActions").show();
					jQuery("#ActionsList").hide();
					jQuery("#ActionBtnNum").html("");
				}

				//Real time data
				show_real_time( "#time-" + data.id, '<span class="dashicons dashicons-backup"></span>' );
				show_real_time( "#score-" + data.id, new_score );
				show_real_time( ".links-no-got-" + data.id, data.text.links.num );
				show_real_time( ".images-no-got-" + data.id, data.design.image.count );
				show_real_time( ".shares-no-got-" + data.id, data.social.num );
				if ( ( data.speed.down_time / 1000 ).toFixed(0) <= 0 ) {
					show_real_time( ".time-no-got-" + data.id, data.speed.down_time.toFixed(0) + " ms" );
				} else {
					show_real_time( ".time-no-got-" + data.id, ( data.speed.down_time / 1000 ).toFixed(0) + " s" );
				}
				jQuery( ".not-scaned-yet-" + data.id ).html( data.text.count + " " + note_html.after_scan + ': <strong>' + data.text.top_key + '</strong>' );

			},
			error: function() {
				jQuery("#ScanFailed-" + item).show();
				jQuery("#loadingProgressG,.scan-process,.scan-report,#ScanOver").hide();
				jQuery(".scan-now").removeAttr("disabled");
			}
		});
	});


	//Make server seo scan ajax call
	jQuery(".server-scan").click(function() {

		//Change display priority
		jQuery(".loading-scan-extra").show();
		jQuery(".server-scan,.design-scan").attr("disabled", "disabled");
		jQuery(".archive-msg,.server-msg,.design-msg,.server-seo-result").hide();

		//Send Ajax request
		jQuery.ajax({
			type: "POST",
			url: overview_ajax_object.ajax_url,
			data: {'action': 'cgss_overview', 'type': 'server'},
			dataType: "json",
			success: function(data) {

				jQuery(".server-scan,.design-scan").removeAttr("disabled");
				jQuery(".loading-scan-extra").hide();

				//If scan fails
				if ( data.ping == 'false' ) {
					jQuery(".server-msg").text(note_html.no + " " + data.val);
					return false;
				}

				server_success( data, note_html.ok, note_html.no );

				jQuery(".server-seo-result").show();
			},
			error: function() {
				jQuery(".server-seo-result,.loading-scan-extra").hide();
				jQuery(".server-msg").html(note_html.no + " " + note.network);
				jQuery(".server-msg").show();
				jQuery(".server-scan,.design-scan").removeAttr("disabled");
			}
		});
	});


	//Make server seo scan ajax call
	jQuery(".design-scan").click(function() {

		//Change display priority
		jQuery(".loading-scan-extra").show();
		jQuery(".server-scan,.design-scan").attr("disabled", "disabled");
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
					jQuery(".server-scan,.design-scan").removeAttr("disabled");

					//If scan fails
					if ( data.ping == 'false' ) {
						jQuery(".design-msg").text(note_html.no + " " + data.val);
						return false;
					}

					design_success( data, note_html.ok, note_html.no );

					jQuery(".design-seo-result").show();

				},
				error: function() {
					jQuery(".design-seo-result,.loading-scan-extra").hide();
					jQuery(".design-msg").html(note_html.no + " " + note.network);
					jQuery(".server-scan,.design-scan").removeAttr("disabled");
				}
			});
		} else {
			jQuery(".design-msg").html(note_html.no + " " + note.no_script);
			jQuery(".design-msg").show();
			jQuery(".design-seo-result,.loading-scan-extra").hide();
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

	//When clicked on this link, show the report again.
	jQuery(".view-again").click(function() { jQuery(".scan-report").show(); });
});
