/**
 * @/user/assets/cgss-script.js
 * on: 02.07.2015
 * A compilation of classes and derived display classes for scan form and report display.
 *
 * It has 2 part:
 * 1. Include custom objects by required() function.
 * 2. Create derived objects and functions. MUST Maintain sequence.
 */
jQuery(document).ready(function($) {


	//Starting screen display
	jQuery("#message,#ShareGp,#ShareFb,#ShareTw,#loadingProgressG,#ScanOver,.scan-report,.scan-process,#Pros1,#Pros2,#Pros3,#Pros4,#Pros5,#Pros6,.view-again,#ShowTblNoticeFilter,#UnFilterAlert,#FilterAlert,#UrlOk,#UrlUnderscore,#UrlDynamic,#UrlError,#Fbmsg, #GplusMsg, #TwMsg,#TextComOk,#TextComNo,#LinkNo,#LinkYes,#LinkPoor,#LinkNoMsg,#LinkYesMsg,#LinkPoorMsg,#AltYes,#AltNo,#NoAltImage, #HtmlComNo,#HrchyYes,#HrchyPoor,#HrchyNo,#CanoYes,#CanoNo,#RoboYes,#RoboNone,#RoboNo,#StagsYes,#StagsNo,#KeysYes,#KeysNo,#KeysVal,#SpeedYes,#SpeedNo,#ResponsiveYes,#ResponsiveNo,#AttsYes,#AttsNo,#ValidYes,#ValidNo,.well-points,#TblYes,#TblNo").hide();
	jQuery(".scan-form").show();
	jQuery(".message-dismiss").click(function() {jQuery("#message").hide();});


	//Sharing button actions
	jQuery("#SharePlug").click(function() {
		jQuery("#SharePlug").hide();
		jQuery("#ShareGp,#ShareFb,#ShareTw").show(250).removeAttr("style");
	});


	/**
	 * Toggle form tables. This will do:
	 *
	 * 1. Hide all tables.
	 * 2. Display post tables
	 * 3. If selected an option:
	 * 		3.1. Hide all tables
	 * 		3.2. Show particular table
	 */
	jQuery(".cgss-table").hide();
	jQuery("#PTIDpost").show(500);

	jQuery("input[name='submit-cgss-post-type']").click(function() {
		jQuery(".cgss-table").hide(100);
		var ptid = "#PTID" + jQuery.trim(jQuery("select[name='cgss-post-type']").val());
		jQuery(ptid).show(250);
	});


	/**
	 * Post Table Navigation
	 * Create a Filter for tables using time and score, because those are too long. 2 code blocks:
	 *
	 * 1. Initiate by disabling filter
	 * 2. Normalize filtering effect on selection of any dropdown
	 * 3. Display filtering effect on button click
	 *
	 * NOTE: for last 2 blocks we get selected dropdown values differently
	 */
	//STEP: 1
	jQuery("input[name='submit-cgss-filter']").attr("disabled", "disabled");

	//STEP: 2
	jQuery("#cgss_time_filter,#cgss_score_filter").change(function() {

		//Normalize buttons, drop the special class, hide filter message (if any)
		jQuery("input[name='submit-cgss-filter']").removeClass("button-primary");
		jQuery("#FilterAlert,#UnFilterAlert").hide(250);

		//Get selected values
		var score = jQuery("#cgss_score_filter").val();
		var time = jQuery("#cgss_time_filter").val();

		//Change dropdowns and buttons for selected values
		if ( time != "selected" ) {
			jQuery("#cgss_score_filter").val("selected").attr("disabled", "disabled");
		} else {
			jQuery("#cgss_score_filter").removeAttr("disabled");
		}
		if ( score != 'selected' ) {
			jQuery("#cgss_time_filter").val("selected").attr("disabled", "disabled");
		} else {
			jQuery("#cgss_time_filter").removeAttr("disabled");
		}
		if ( score != 'selected' || time != 'selected' ) {
			jQuery("input[name='submit-cgss-filter']").removeAttr("disabled");
		} else {
			jQuery("input[name='submit-cgss-filter']").attr("disabled", "disabled");
		}

		//Drop any changes on tables, if dropdown options are changed.
		jQuery("#scanlink").children("table").children("tbody").children("tr").each( function() { jQuery(this).show(); });
		jQuery(".score-in-form,.time-in-form").show();
	});


	//STEP: 3
	jQuery("input[name='submit-cgss-filter']").click(function() {

		//COunt nimber of filtered rows
		var filtered = 0;
		var un_filtered = 0;

		//Change button class on click
		jQuery(this).toggleClass("button-primary");

		//Get selected values
		var score = jQuery("#cgss_score_filter").val();
		var time = jQuery("#cgss_time_filter").val();

		//Depending on selected values change table row bottom display
		if ( score != 'selected' ) {
			jQuery(".score-in-form").toggle();
		}
		if ( time != 'selected' ) {
			jQuery(".time-in-form").toggle();
		}

		//Make changes on tables, based on dropdown options values.
		jQuery("#scanlink").children("table").children("tbody").children("tr").each( function() {
			var td_id = jQuery(this).find("td:first").attr("id");
			var td_id_arr = td_id.split("-");
			var td_score = td_id_arr[0];
			var td_time = td_id_arr[1];
			if ( time != 'nil' ) {
				if ( td_time == 'nil' ) {
					filtered = filtered + 1;
					jQuery(this).toggle();
				}
				var time_gap = time - td_time;
				if ( score != td_score && time == 'selected' ) {
					filtered = filtered + 1;
					jQuery(this).toggle();
					if ( td_time == 'nil' ) {
						jQuery(this).toggle();
					}
				} else if ( score == 'selected' && time_gap < 0 ) {
					filtered = filtered + 1;
					jQuery(this).toggle();
				} else {
					un_filtered = un_filtered + 1;
				}
			} else {
				if ( td_time != 'nil' ) {
					filtered = filtered + 1;
					jQuery(this).toggle();

				} else {
					un_filtered = un_filtered + 1;
				}
			}
		});

		//If none is filtered show all and append a notice
		if ( un_filtered == 0 ) {
			jQuery("#scanlink table tr,.score-in-form,.time-in-form").show();
			jQuery("#UnFilterAlert").toggle(250);
		} else {
			jQuery("#UnFilterAlert").hide(250);
		}

		//If all are filtered show all and append a notice
		if ( filtered == 0 ) {
			jQuery(".score-in-form,.time-in-form").show();
			jQuery("#FilterAlert").toggle(250);
		} else {
			jQuery("#FilterAlert").hide(250);
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
	jQuery("input[name='scan-url']").change(function() {

		//Change display priority
		jQuery("#loadingProgressG,.scan-process").show();
		jQuery(".scan-form,#Pros1,#Pros2,#Pros3,#Pros4,#Pros5").hide();

		//Show processing tick marks
		var ticks_obj = { 1: "#Pros1", 2: "#Pros2", 3: "#Pros3", 4: "#Pros4", 5: "#Pros5" };
		var time_interval = jQuery.trim(jQuery(".scan-process").attr("id"));
		if ( ! time_interval ) {
			time_interval = 1000;
		}
		jQuery.each(ticks_obj, function(index, val){ setTimeout(function(){ jQuery(val).show(500); }, index * time_interval); });

		//Fetch data for url from radio input and post id from id attribute of that input
		var item = jQuery.trim(jQuery("input[name='scan-url']:checked").attr("id"));
		var url = jQuery.trim(jQuery("input[name='scan-url']:checked").val());

		//Send Ajax request
		jQuery.ajax({
			type: "POST",
			url: ajax_object.ajax_url,
			data: {'action': 'cgss_core', 'id': item, 'url': url},
			dataType: "json",
			success: function(data) {

				//Initiate reporting screen
				jQuery("#loadingProgressG,.scan-process").hide();
				jQuery(".scan-report,#ScanOver").show();

				//Get ping and respond accordingly.
				var ping = data.ping;
				if ( ping == 'false' || ping == 'error' || ping == 'nobody' ) {
					jQuery("#message").show(100);
					jQuery("#ShowMessage").text(data.val);
					return false;
				}

				//Define data variables from received json object
				var id = data.id;
				var score = data.score;
				var marks = data.marks;
				var time = data.time;
				var time_now = data.time_now;
				var ssl = data.over.url_prop.ssl;
				var dynamic = data.over.url_prop.dynamic;
				var underscore = data.over.url_prop.underscore;
				var social_num = data.over.social.num;
				var gplus = data.over.social.gplus;
				var twitter = data.over.social.twitter;
				var fb_share = data.over.social.fb_share;
				var fb_like = data.over.social.fb_like;
				var cano = data.over.cano;
				var robot = data.over.meta_robot;
				var title = data.snip.title;
				var url = data.snip.url;
				var desc = data.snip.desc;
				var words = data.text.count;
				var size = data.text.size;
				var ratio = data.text.ratio;
				var links_num = data.text.links.num;
				var links_nof = data.text.links.nofollow;
				var links_ext = data.text.links.external;
				var links_no_txt = data.text.links.no_text;
				var links_anch = data.text.links.anchors;
				var htags = data.text.htags;
				var keys = data.text.keys;
				var iframe = data.media.iframe;
				var img = data.media.image;
				var down_time = data.usb.down_time;
				var nested_table = data.usb.nested_table;
				var tag_style = data.usb.tag_style;
				var err_num = data.usb.code_errors.num;
				var err_val = data.usb.code_errors.val;
				var http_num = data.usb.http_req.num;
				var http_css = data.usb.http_req.css;
				var http_js = data.usb.http_req.js;
				var http_img = data.usb.http_req.img;
				var vport = data.usb.vport;
				var st_title = data.usb.social_tags.title;
				var st_desc = data.usb.social_tags.desc;
				var st_url = data.usb.social_tags.url;
				var st_img = data.usb.social_tags.img;

				//change screen display for the particular row.
				jQuery(".view-again").hide();
				jQuery("#ViewAgain-" + id).show();
				if ( score == 5 ) {
					var new_score = Array(6).join('<span class="dashicons dashicons-star-filled warning-icon"></span>');
				} else if ( score == 4.5 ) {
					var new_score = Array(5).join('<span class="dashicons dashicons-star-filled warning-icon"></span>') + '<span class="dashicons dashicons-star-half warning-icon"></span>';
				} else if ( score == 4 ) {
					var new_score = Array(5).join('<span class="dashicons dashicons-star-filled warning-icon"></span>') + '<span class="dashicons dashicons-star-empty warning-icon"></span>';
				} else if ( score == 3.5 ) {
					var new_score = Array(4).join('<span class="dashicons dashicons-star-filled warning-icon"></span>') + '<span class="dashicons dashicons-star-half warning-icon"></span>' + '<span class="dashicons dashicons-star-empty warning-icon"></span>';
				} else if ( score == 3 ) {
					var new_score =  Array(4).join('<span class="dashicons dashicons-star-filled warning-icon"></span>') + Array(3).join('<span class="dashicons dashicons-star-empty warning-icon"></span>');
				} else if ( score == 2.5 ) {
					var new_score = Array(3).join('<span class="dashicons dashicons-star-filled warning-icon"></span>') + '<span class="dashicons dashicons-star-half warning-icon"></span>' + Array(3).join('<span class="dashicons dashicons-star-empty warning-icon"></span>');
				} else if ( score == 2 ) {
					var new_score = Array(3).join('<span class="dashicons dashicons-star-filled warning-icon"></span>') + Array(4).join('<span class="dashicons dashicons-star-empty warning-icon"></span>');
				} else if ( score == 1.5 ) {
					var new_score = '<span class="dashicons dashicons-star-filled warning-icon"></span><span class="dashicons dashicons-star-half warning-icon"></span>' + Array(4).join('<span class="dashicons dashicons-star-empty warning-icon"></span>');
				} else if ( score == 1 ) {
					var new_score = '<span class="dashicons dashicons-star-filled warning-icon"></span>' + Array(5).join('<span class="dashicons dashicons-star-empty warning-icon"></span>');
				}
				var pre_marks = jQuery.trim(jQuery(".exact-no-got-" + id).attr("id"));
				if ( ! pre_marks ) {
					jQuery("#score-" + id).html(new_score);
				} else {
					var up_down = ( marks - pre_marks ).toFixed();
					var up_down_percent = ( ( up_down / pre_marks ) * 100 ).toFixed();
					if ( up_down == 0 ) {
						jQuery("#score-" + id).html('<span class="dashicons dashicons-sort warning-icon"></span> <span class="dark">0%</span>');
					} else if ( up_down > 0 ) {
						jQuery("#score-" + id).html('<span class="dashicons dashicons-arrow-up success-icon"></span> <span class="dark">' + up_down_percent + '%</span>');
					} else if ( up_down < 0 ) {
						jQuery("#score-" + id).html('<span class="dashicons dashicons-arrow-down danger-icon"></span> <span class="dark">' + ( up_down_percent * -1 ) + '%</span>');
					} else {
						jQuery("#score-" + id).html(new_score);
					}
				}
				jQuery("#time-" + id).html('<span class="dashicons dashicons-backup"></span>');
				jQuery(".button-page-scan-" + id).attr( "id", score + "-" + 0 );
				if ( ! http_img ) {
					jQuery(".images-no-got-" + id).html('--');
				} else {
					jQuery(".images-no-got-" + id).html(http_img);
				}
				if ( ! links_num ) {
					jQuery(".links-no-got-" + id).html('--');
				} else {
					jQuery(".links-no-got-" + id).html(links_num);
				}
				if ( ! social_num ) {
					jQuery(".shares-no-got-" + id).html('--');
				} else {
					jQuery(".shares-no-got-" + id).html(social_num);
				}

				//find out most popular keyword
				top_key = Object.keys(keys[1][0]);
				if ( top_key ) {
					jQuery(".keys-no-got-" + id).html(top_key);
				} else {
					jQuery(".keys-no-got-" + id).html('--');
				}

				/**
				 * Display result report according to variables
				 */

				//Show scan time
				jQuery("#ScanTime").html(time - down_time);

				//Show stars and marks
				if ( score < 2 ) {
					jQuery(".score-pie").css( "border-color", "#ff644d" );
					jQuery(".score-pie h1").css( "color", "#ff644d" );
				} else if ( score < 3 ) {
					jQuery(".score-pie").css( "border-color", "#ffcc33" );
					jQuery(".score-pie h1").css( "color", "#ffcc33" );
				} else {
					jQuery(".score-pie").css( "border-color", "#8bba30" );
					jQuery(".score-pie h1").css( "color", "#8bba30" );
				}
				jQuery("#ScoreStarts").html(new_score);
				jQuery("#ShowMarks").html(( marks / 3.75 ).toFixed());

				//Display social media counts
				jQuery("#FbShare").html(fb_share);
				jQuery("#GplusCount").html(gplus);
				jQuery("#TweeetCount").html(twitter);

				//Show title tag
				jQuery("#ComTitle").html(title.length);
				if ( title.length > 65 ) {
					show_title = title.substr(0, 61) + " ...";
					title_color = "#ff644d";
				} else if ( title.length < 15 ) {
					if ( title.length == 0 ) {
						show_title = Array(11).join('-');
						title_color = "#ff644d";
					} else {
						show_title = title;
						title_color = "#ffcc33";
					}
				} else {
					show_title = title;
					title_color = "#8bba30";
				}
				jQuery("#ShowTitle").html(show_title.replace( new RegExp(top_key, "ig"), '<strong>' + top_key + '</strong>' ));
				jQuery(".title-tag").css( "color", title_color );

				//Show url and it's features
				jQuery("#UrlOk,#UrlUnderscore,#UrlDynamic,#UrlError").hide();
				if ( dynamic != 0 && underscore == 0 ) {
					jQuery("#UrlDynamic").show();
				} else if ( dynamic == 0 && underscore != 0 ) {
					jQuery("#UrlUnderscore").show();
				} else if ( dynamic != 0 && underscore != 0 ) {
					jQuery("#UrlError").show();
				} else {
					jQuery("#UrlOk").show();
				}

				//Show meta description
				jQuery("#ComDesc").html(desc.length);
				if ( desc.length > 160 ) {
					show_desc = desc.substr(0, 156) + " ...";
					desc_color = "#ff644d";
				} else if ( desc.length < 50 ) {
					if ( desc.length == 0 ) {
						show_desc = Array(51).join('-');
						desc_color = "#ff644d";
					} else {
						show_desc = desc;
						desc_color = "#ffcc33";
					}
				} else {
					show_desc = desc;
					desc_color = "#8bba30";
				}
				jQuery("#ShowDesc").html(show_desc.replace( new RegExp(top_key, "ig"), '<strong>' + top_key + '</strong>' ));
				jQuery(".meta-desc").css( "color", desc_color );

				//Basic Information
				jQuery("#WordCount").html(words);
				jQuery("#TextRatio").html(ratio);
				jQuery("#TextComOk").show();
				jQuery("#TextInfo").removeClass();
				jQuery("#HtmlComNo,#TextComNo,#TextComOk").hide();
				if ( ratio < 15 ) {
					text_class = "theme-update-message";
					jQuery("#HtmlComNo").show();
				} else if ( ratio > 70 ) {
					text_class = "theme-update-message";
					jQuery("#TextComNo").show();
				} else {
					text_class = "parent-theme";
					jQuery("#TextComOk").show();
				}
				jQuery("#TextInfo").addClass(text_class);

				//canonical link
				if ( cano && cano.length > 0 ) {
					jQuery("#CanoYes").show();
					jQuery("#CanoNo").hide();
				} else {
					jQuery("#CanoNo").show();
					jQuery("#CanoYes").hide();
				}

				//meta robot property
				if ( robot && robot.length > 0 ) {
					if ( robot.toLowerCase().indexOf('noindex') != -1 || robot.toLowerCase().indexOf('nofollow') != -1 ) {
						jQuery("#RoboNo").show();
						jQuery("#RoboYes,#RoboNone").hide();
					} else {
						jQuery("#RoboYes").show();
						jQuery("#RoboNo,#RoboNone").hide();
					}
				} else {
					jQuery("#RoboNone").show();
					jQuery("#RoboYes,#RoboNo").hide();
				}

				//Social media ogp
				var stag_print = [];
				if ( st_title && st_title.length > 0 ) {
					stag_print.push('title');
				}
				if ( st_desc && st_desc.length > 0 ) {
					stag_print.push('description');
				}
				if ( st_url && st_url.length > 0 ) {
					stag_print.push('url');
				}
				if ( st_img && st_img.length > 0 ) {
					stag_print.push('image');
				}
				if ( stag_print.length > 0 ) {
					jQuery("#StagsYes").show();
					jQuery("#StagsNo").hide();
					jQuery("#StagsVal").html(": " + stag_print.join(", ") + ".");
				} else {
					jQuery("#StagsYes").hide();
					jQuery("#StagsNo").show();
					jQuery("#StagsVal").html(": 0");
				}

				//Heading tags analysis
				var head_tags_arr = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];
				head_tags_arr = jQuery.grep(head_tags_arr, function( val ) { return ( Object.keys(htags[val]).length > 0 ); });
				if ( head_tags_arr.length > 0 ) {
					if ( head_tags_arr.length != 1 ) {
						jQuery("#HrchyYes").show();
						jQuery("#HrchyPoor,#HrchyNo").hide();
						jQuery("#HrchyTags").html(head_tags_arr.join(', '));
					} else {
						jQuery("#HrchyPoor").show();
						jQuery("#HrchyYes,#HrchyNo").hide();
						jQuery("#HrchyTags").html(head_tags_arr.join(''));
					}
				} else {
					jQuery("#HrchyPoor,#HrchyYes").hide();
					jQuery("#HrchyNo").show();
				}				

				//Link details
				jQuery("#LinkNum").html(links_num);
				link_percent = ( links_num / words ) * 100;
				if ( link_percent > 75 ) {
					jQuery("#LinkNo,#LinkNoMsg").show();
					jQuery("#LinkYes,#LinkPoor,#LinkYesMsg,#LinkPoorMsg").hide();
				} else if ( link_percent > 50 ) {
					jQuery("#LinkPoor,#LinkPoorMsg").show();
					jQuery("#LinkNo,#LinkYes,#LinkNoMsg,#LinkYesMsg").hide();
				} else {
					jQuery("#LinkYes,#LinkYesMsg").show();
					jQuery("#LinkNo,#LinkPoor,#LinkNoMsg,#LinkPoorMsg").hide();
				}
				jQuery("#ImgLink").html(links_no_txt);
				jQuery("#NofLink").html(links_nof);
				jQuery("#ExtLink").html(links_ext);

				//Image alt analysis
				if ( http_img == 0 ) {
					jQuery("#NoAltImage").show();
					jQuery("#AltYes,#AltNo").hide();
				} else {
					no_alt_src_arr = [];
					jQuery.map( img, function( value, index ) { if ( value.alt.length == 0 ) { no_alt_src_arr.push('<a href="' + value.src + '" target="_blank"><span class="dashicons dashicons-images-alt2"></span></a>'); } });
					if ( no_alt_src_arr.length > 0 ) {
						jQuery("#AltNo").show();
						jQuery("#AltYes,#NoAltImage").hide();
						jQuery("#ImagesList").html(Array(8).join('&nbsp;') + no_alt_src_arr.join("&nbsp;&nbsp;&nbsp;"));
					} else {
						jQuery("#AltYes").show();
						jQuery("#AltNo,#NoAltImage").hide();
					}
				}

				//Keywords from text content and their beheaviour
				Key_num = [ 0, 1, 2, 3, 4, 5 ];
				Keys_option = [];
				keys_count_sep = [];
				jQuery.map( Key_num, function( value ) {
					var key_in = Object.keys(keys[1][value]);
					var key_in_count = ( ( keys[1][value][key_in] / words ) * 100 ).toFixed(1);
					if ( key_in_count > 0.5 ) {
						Keys_option.push(Array(8).join('&nbsp;') + '<span>' + key_in + '<sup>' + key_in_count + '%</sup></span>');
					}
				});
				if ( Keys_option.length > 0 ) {
					jQuery("#KeysVal").html(Keys_option.join('<br />'));
					jQuery("#KeysCount").html(Keys_option.length);
					jQuery("#KeysYes,#KeysVal").show();
					jQuery("#KeysNo").hide();
				} else {
					jQuery("#KeysVal").html('');
					jQuery("#KeysNo").show();
					jQuery("#KeysYes,#KeysVal").hide();
				}
				

				//Loading time
				if ( down_time < 4 ) {
					jQuery("#SpeedYes").show();
					jQuery("#SpeedNo").hide();
				} else {
					jQuery("#SpeedNo").show();
					jQuery("#SpeedYes").hide();
				}
				if ( down_time != 0 ) {
					show_time = "" + down_time;
				} else {
					show_time = "< 1";
				}
				jQuery("#SpeedTime").html(" " + show_time + " sec.");

				//Viewport tag for responsive design
				if ( vport.length > 0 ) {
					jQuery("#ResponsiveYes").show();
					jQuery("#ResponsiveNo").hide();
				} else {
					jQuery("#ResponsiveNo").show();
					jQuery("#ResponsiveYes").hide();
				}

				//Style attribute tag
				if ( tag_style != 0 ) {
					jQuery("#AttsVal").html(": " + Object.keys(tag_style).join(', '));
					jQuery("#AttsNo").show();
					jQuery("#AttsYes").hide();
				} else {
					jQuery("#AttsVal").html(" 0");
					jQuery("#AttsYes").show();
					jQuery("#AttsNo").hide();
				}

				//Nested tables analysis
				if ( nested_table != 0 ) {
					jQuery("#TblNo").show();
					jQuery("#TblYes").hide();
				} else {
					jQuery("#TblYes").show();
					jQuery("#TblNo").hide();
				}

				//Count Http requests
				if ( http_num > 100 ) {
					jQuery("#HttpNo").show();
					jQuery("#HttpYes,#HttpPoor").hide();
				} else if ( http_num > 50 ) {
					jQuery("#HttpPoor").show();
					jQuery("#HttpYes,#HttpNo").hide();
				} else {
					jQuery("#HttpYes").show();
					jQuery("#HttpPoor,#HttpNo").hide();
				}
				jQuery("#HttpCount").html(http_num); 
				jQuery("#HttpCss").html(http_css);
				jQuery("#HttpJs").html(http_js);
				jQuery("#HttpImg").html(http_img);

				//W3 error tag
				jQuery("#ValidVal").html(err_num + " ");
				if ( err_num > 0 ) {
					jQuery("#ValidNo,#ShowErrors,.well-points").show();
					err_print = jQuery.map( err_val, function( value, index ) { return err_val[index]; });
					jQuery("#PrintErrors").html('<span>' + err_print.join('</span><br /><span>') + '</span>');
					jQuery("#ValidYes").hide();
				} else {
					jQuery("#ValidYes").show();
					jQuery("#PrintErrors").html('');
					jQuery("#ValidNo,#ShowErrors,.well-points").hide();
				}

			},
			error: function() {
				alert("SCAN FAILED");
				jQuery("#loadingProgressG,.scan-process,.scan-report,#ScanOver").hide();
				jQuery(".scan-form").show();
			}
		});
	});


	//Toggle elments on events, kept outside scan function intentionally.
	//Otherwise they won't function properly
	jQuery(".facebook").hover(function() { jQuery("#Fbmsg").toggle(); });
	jQuery(".gplus").hover(function() { jQuery("#GplusMsg").toggle(); });
	jQuery(".twitter").hover(function() { jQuery("#TwMsg").toggle(); });
	jQuery("#ShowErrors").click(function () { jQuery(".well-points").toggle(); });

	//If user wants to go back to scan form from report page
	jQuery("#ReportClose").click(function() {
		jQuery("#loadingProgressG, .scan-process, .scan-report").hide();
		jQuery(".scan-form").show();
	});

	//When clicked on this link, show the report again.
	jQuery(".view-again").click(function() { jQuery(".scan-report").show(); });
});
