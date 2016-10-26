/**
 * @/assets/compete.js
 * on: 03.10.2015
 * @since 2.3
 *
 * Competative demo function
 * Note the use of .on() event, for binding dynamically created elements
 */

jQuery(document).ready(function($) {

	//turn off main compete button
	jQuery(".submit-compete").attr("disabled", "disabled");

	var comp_url = [];

	var ssl = [];
	var mobile = [];
	var words = [];
	var links = [];
	var links_ext = [];
	var thr = [];
	var images = [];
	var key_count = [];
	var key_per = [];
	var gplus = [];
	var fb = [];
	var tw = [];

	var h1 = [];
	var h2 = [];
	var h3 = [];
	var h4 = [];
	var h5 = [];
	var h6 = [];

	var domain = [];
	var title = [];
	var url = [];
	var desc = [];
	var alt = [];
	var anch = [];
	var htag = [];
	var plain = [];

	var speed = [];

	var hding = [];

	var comp_key = '';
	var client_free = '';
	var client_url = '';
	var brk_client_url = [];
	var brk_client_url_two = [];
	var client_domain = '';

	//initiate compete modal
	jQuery(".compete-now").click(function() {

		if ( jQuery(this).attr("disabled") == 'disabled' ) {
			return false;
		}

		//get client url
		client_url = jQuery(this).attr("id");
		client_id = jQuery(this).attr("name");
		brk_client_url = client_url.split("://");
		brk_client_url_two = brk_client_url[1].split("/");
		client_domain = brk_client_url_two[0];

		//show links
		jQuery("#EditPage").attr("href", cnote.admin + 'post.php?post=' + client_id + '&action=edit');
		jQuery("#EditPage").attr("target", "_blank");
		jQuery("#ViewPageCompete").attr("href", client_url);
		jQuery("#ViewPageCompete").attr("target", "_blank");

		counter = 1;
		jQuery(".scan-compete").show();
		jQuery(".compete-focus-keyword, .compete-url").val("").removeAttr( "style" );
		jQuery(".compete-form").children(".compete-url-input-cover").slice(1).remove();
		jQuery(".compete-form").children(".compete-url-input-cover-prev").remove();
		jQuery("#CompeteForm").addClass("disabled").attr("disabled", "disabled");
		jQuery("#CompeteResult").addClass("disabled").attr("disabled", "disabled");
		jQuery(".compete-form-container, .scan-to-compete").show();
		jQuery(".compete-result, .hide-scan-compete-ok").hide();
		jQuery(".cgss-notice, .cgss-comp-saved").remove();
		jQuery(".compete-url-input-cover").children().eq(0).html(counter);

		//start ajax for each url
		comp_url = [];

		ssl = [];
		mobile = [];
		words = [];
		links = [];
		links_ext = [];
		thr = [];
		images = [];
		key_count = [];
		key_per = [];
		gplus = [];
		fb = [];
		tw = [];

		h1 = [];
		h2 = [];
		h3 = [];
		h4 = [];
		h5 = [];
		h6 = [];

		domain = [];
		title = [];
		url = [];
		desc = [];
		alt = [];
		anch = [];
		htag = [];
		plain = [];

		speed = [];

		hding = [];
	});

	//start ajax for each url

	jQuery(".compete-form").show();
	jQuery(".compete-result").hide();
	jQuery("#CompeteResult").attr("disabled", "disabled");

	//get notices from php file to display in front end through this script
	var cnote = compete_ajax_object.ajax_compete;

	//Create new input fields on click
	var counter = 1;
	jQuery(document).on('click', '.url-more', function() {
		if ( counter == 99 ) {
			jQuery(this).hide();
		}
		var new_counter = counter + 1;
		jQuery(this).prev().after( '<div class="compete-url-input-cover">' + jQuery(this).prev().html().replace('<span class="view-url">' + counter + '</span>', '<span class="view-url">' + new_counter + '</span>') + '</div>' );
		jQuery(this).prev().children().eq(-1).hide();
		jQuery(this).prev().children().eq(1).show();
		counter += 1;
	});

	//reset button
	jQuery(".reset-compete").click(function() {
		counter = 1;
		jQuery(".compete-focus-keyword, .compete-url").val("").removeAttr( "style" );
		jQuery(".compete-form").children(".compete-url-input-cover").slice(1).remove();
		jQuery(".compete-form").children(".compete-url-input-cover-prev").remove();
		jQuery(".hide-scan-compete-ok").hide();
		jQuery(".scan-to-compete").show();
		jQuery(".cgss-notice").remove();
		jQuery(".compete-url-input-cover").children().eq(0).html(counter);

		//start ajax for each url
		comp_url = [];

		ssl = [];
		mobile = [];
		words = [];
		links = [];
		links_ext = [];
		thr = [];
		images = [];
		key_count = [];
		key_per = [];
		gplus = [];
		fb = [];
		tw = [];

		h1 = [];
		h2 = [];
		h3 = [];
		h4 = [];
		h5 = [];
		h6 = [];

		domain = [];
		title = [];
		url = [];
		desc = [];
		alt = [];
		anch = [];
		htag = [];
		plain = [];

		speed = [];

		hding = [];
	});

	//scan competitor url
	jQuery(document).on('click', '.scan-to-compete', function() {

		jQuery(".cgss-notice").remove();
		jQuery(".compete-focus-keyword, .compete-url").removeAttr( "style" );

		//5 validations
		var block_scan = 0;

		//keyword check
		var comp_key = jQuery.trim(jQuery(".compete-focus-keyword").val());
		if ( comp_key == undefined || comp_key == '' ) {
			jQuery(".compete-focus-keyword").css( "border", "1px solid #ff644d" );
			jQuery(".compete-focus-keyword").before( cnote.key_block.empty );
			block_scan += 1;
		} else {
			if ( comp_key.split(" ").length >= 31 ) {
				jQuery(".compete-focus-keyword").css( "border", "1px solid #ff644d" );
				jQuery(".compete-focus-keyword").before( cnote.key_block.long );
				block_scan += 1;
			}
			jQuery.each(comp_key.split(" "), function( index, value ) {
				if ( value.length >= 130 ) {
					jQuery(".compete-focus-keyword").css( "border", "1px solid #ff644d" );
					jQuery(".compete-focus-keyword").before( cnote.key_block.long_char );
					block_scan += 1;
				}
			});
		}

		//url array
		var new_comp_url = jQuery.trim(jQuery(this).next().val());
		if ( new_comp_url == undefined || new_comp_url == '' ) {
			jQuery(this).next().css( "border", "1px solid #ff644d" );
			block_scan += 1;
		} else if ( ! test_url(new_comp_url) ) {
			jQuery(this).next().css( "border", "1px solid #ff644d" );
			block_scan += 1;
		} else if ( new_comp_url.indexOf(client_domain) > -1 ) {
			jQuery(this).next().css( "border", "1px solid #ff644d" );
			jQuery(this).parent().before( cnote.same_domain );
			block_scan += 1;
		}

		//block for duplicate url
		if ( new_comp_url != '' && comp_url.indexOf(new_comp_url) != -1 ) {
			jQuery(this).next().css( "border", "1px solid #ff644d" );
			var pos_url = comp_url.indexOf(new_comp_url);
			jQuery(".compete-form").children(".compete-url-input-cover").eq(pos_url).children().eq(2).css( "border", "1px solid #ff644d" );
			jQuery(".compete-form").children(".compete-url-input-cover-prev").each( function() { if ( jQuery(this).children(".compete-url").val() == new_comp_url ) { jQuery(this).children(".compete-url").css( "border", "1px solid #ff644d" ); } });
			block_scan += 1;
		}

		if ( block_scan > 0 ) {
			return false;
		}

		var comp_data = [];
		jQuery.each(comp_url, function( value ) {
			comp_data.push(value);
		});

		//push client url data into array
		comp_data.push(url);

		//screen setup
		jQuery(".compete-scan-msg").hide();
		jQuery(".loading-compete-scan").show();
		jQuery(this).attr("disabled", "disabled");
		jQuery("#CompeteClose, #CompeteForm, #CompeteResult").attr("disabled", "disabled");
		jQuery(".compete-focus-keyword, .compete-url").removeAttr( "style" );
		jQuery(".compete-form").children(".compete-url-input-cover-prev").each( function() { jQuery(this).children(".compete-url").removeAttr("style"); });

		//competitor url scan
		var comp_pre = 0;
		var comp_scan_false = 0;
		var var_show_tbl = '';
		var var_key_none = 0;
		jQuery.ajax({
			type: "POST",
			url: compete_ajax_object.ajax_url,
			data: {'action': 'cgss_compete', 'type': 'compete', 'id': ' ', 'key': comp_key, 'url': new_comp_url},
			dataType: "json",
			async: false,
			success: function(data) {

				//If scan fails
				if ( data == undefined ) {
					alert("Some problem occured");
					return false;
				} else if ( data.ping == 'false' ) {
					comp_scan_false = data.val;
					return false;
				} else if ( data.ping == 'free' ) {
					comp_pre = 1;
					client_free = 'free';
					return false;
				} else if ( data.ping == 'valid' ) {
					comp_pre = 1;

					filter_data( ssl, data.crawl.ssl );
					filter_data( mobile, data.design.vport );
					filter_data( words, data.text.count );
					filter_data( links, data.text.links.num );
					filter_data( links_ext, data.text.links.external );
					filter_data( thr, data.text.ratio );
					filter_data( images, data.design.image.count );

					filter_data( gplus, data.social.gplus );
					filter_data( fb, data.social.fb_share );
					filter_data( tw, data.social.twitter );

					hding = jQuery.map(data.text.htags.names, function(el) { return el; });
					hd_entry( 'h1', h1, hding );
					hd_entry( 'h2', h2, hding );
					hd_entry( 'h3', h3, hding );
					hd_entry( 'h4', h4, hding );
					hd_entry( 'h5', h5, hding );
					hd_entry( 'h6', h6, hding );

					filter_data( key_count, data.compete.num );
					filter_data( key_per, data.compete.per );

					filter_data( domain, data.compete.domain );
					filter_data( url, data.compete.url );
					filter_data( title, data.compete.title );
					filter_data( desc, data.compete.desc );
					filter_data( alt, data.compete.alt );
					filter_data( anch, data.compete.anch );
					filter_data( htag, data.compete.htag );
					filter_data( plain, data.compete.plain );

					filter_data( speed, data.speed.down_time / 1000 );

					//if given keyword is not found, notify user
					var key_count_sum = key_count.reduce(function(a, b) { return a + b; });
					if ( data.compete.num != undefined && data.compete.num == 0 && key_count_sum == 0 ) {
						var var_show = '';
						var var_keys = jQuery.map(data.alt_key.keys, function(value, index) { return value; });
						var var_counts = jQuery.map(data.alt_key.counts, function(value, index) { return value; });
						var var_pers = jQuery.map(data.alt_key.pers, function(value, index) { return value; });
						jQuery.each(var_keys, function ( index, value ) { if ( var_counts[index] > 0 ) { var_show += '<tbody><tr>' + '<td>' + value + '</td><td>' + var_counts[index] + '</td><td>' + var_pers[index] + '</td></tr></tbody>'; } });
						if ( var_show.length > 0 ) {
							var_show_tbl = '<table class="wp-list-table widefat fixed striped pages cgss-no-key-found-table"><thead><tr>' + cnote.no_key_tbl + '</tr></thead>' + var_show + '</table><br />';
						}
						var_key_none = 1;
					}

					//then push it into url array, that it's scanned
					comp_url.push(new_comp_url);
				}
			},
			error: function() {
				comp_pre = -1;
			}
		});
		jQuery(".loading-compete-scan").hide();
		jQuery(this).removeAttr("disabled");
		jQuery("#CompeteClose, #CompeteForm, #CompeteResult").removeAttr("disabled");
		if ( var_key_none == 1 ) {
			if ( var_show_tbl != '' ) {
				jQuery(this).parent().before('<div class="cgss-notice">' + cnote.var_key + var_show_tbl + '</div>');
			} else {
				jQuery(this).parent().before('<div class="cgss-notice">' + cnote.var_key_none + '</div>');
			}
		}
		if ( comp_pre == 1 ) {

			//turn on main compete button
			jQuery(".submit-compete").removeAttr("disabled");

			jQuery(this).next().next().show();
			jQuery(this).hide();
		} else if ( comp_pre == -1 ) {
			jQuery(this).parent().before('<div class="cgss-notice">' + cnote.fail + '</div>');
		}

		if ( comp_scan_false != '' ) {
			jQuery(this).parent().before('<div class="error notice is-dismissible cgss-comp-scan-error"><p>' + comp_scan_false + '</p><button type="button" class="notice-dismiss cgss-comp-scan-error-btn"><span class="screen-reader-text">Dismis</span></button></div>');
		}

		//if you add a new compititor, don't go back to old comparison report
		jQuery("#CompeteForm,#CompeteResult").addClass("disabled").attr("disabled", "disabled");
	});

	//compare and show results
	//also FETCH DATA TO SHOW RESULTS
	jQuery(".submit-compete, .fetch-compete, .show-demo-result").click(function() {

		var which_data = '';
		if ( jQuery(this).attr("class").indexOf("submit-compete") != -1 ) {
			which_data = 'submit';
		}
		if ( jQuery(this).attr("class").indexOf("fetch-compete") != -1 ) {
			which_data = 'fetch';
		}
		if ( jQuery(this).attr("class").indexOf("show-demo-result") != -1 ) {
			which_data = 'demo';
		}

		//block for no keyword
		comp_key = jQuery.trim(jQuery(".compete-focus-keyword").val());

		if ( which_data == 'demo' ) {

			//show demo result
			jQuery(".compete-result").show();
			jQuery(".compete-form-container").hide();
			jQuery("#CompeteClose, #CompeteForm, #CompeteResult").removeAttr("disabled");

		} else if ( which_data == 'submit' ) {

			//block for no keyword
			if ( comp_key == undefined || comp_key == '' ) {
				jQuery(".compete-focus-keyword").css( "border", "1px solid #ff644d" );
				return false;
			}

			//block for no url
			if ( client_free != 'free' && comp_url.length <= 0 ) {
				jQuery(".compete-url").css( "border", "1px solid #ff644d" );
				return false;
			}

			//scan client useful
			jQuery(".loading-compete").show();
			jQuery(".compete-msg").hide();
			jQuery(this).attr("disabled", "disabled");
			jQuery("#CompeteClose, #CompeteForm, #CompeteResult").attr("disabled", "disabled");
			var comp_pre_go = 0;

			//ajax for display result
			jQuery.ajax({
				type: "POST",
				url: compete_ajax_object.ajax_url,
				data: {'action': 'cgss_compete', 'type': 'compete', 'id': ' ', 'key': comp_key, 'url': client_url},
				dataType: "json",
				async: false,
				success: function(data) {

					//If scan fails
					if ( data == undefined ) {
						alert("Some problem occured");
						return false;
					} else if ( data.ping == 'false' ) {
						jQuery(".compete-msg").show().html(data.val);
						return false;
					} else if ( data.ping == 'free' ) {
						return false;
					} else if ( data.ping == 'valid' ) {
						comp_pre_go = 1;

						filter_data( ssl, data.crawl.ssl );
						filter_data( mobile, data.design.vport );
						filter_data( words, data.text.count );
						filter_data( links, data.text.links.num );
						filter_data( links_ext, data.text.links.external );
						filter_data( thr, data.text.ratio );
						filter_data( images, data.design.image.count );

						filter_data( gplus, data.social.gplus );
						filter_data( fb, data.social.fb_share );
						filter_data( tw, data.social.twitter );

						hding = jQuery.map(data.text.htags.names, function(el) { return el; });
						hd_entry( 'h1', h1, hding );
						hd_entry( 'h2', h2, hding );
						hd_entry( 'h3', h3, hding );
						hd_entry( 'h4', h4, hding );
						hd_entry( 'h5', h5, hding );
						hd_entry( 'h6', h6, hding );

						filter_data( key_count, data.compete.num );
						filter_data( key_per, data.compete.per );
	
						filter_data( domain, data.compete.domain );
						filter_data( url, data.compete.url );
						filter_data( title, data.compete.title );
						filter_data( desc, data.compete.desc );
						filter_data( alt, data.compete.alt );
						filter_data( anch, data.compete.anch );
						filter_data( htag, data.compete.htag );
						filter_data( plain, data.compete.plain );

						filter_data( speed, data.speed.down_time / 1000 );
					}
				},
				error: function() {

					comp_pre_go = -1;
				}
			});

			jQuery(".loading-compete").hide();
			jQuery(this).removeAttr("disabled");
			jQuery("#CompeteClose, #CompeteForm, #CompeteResult").removeAttr("disabled");
			if ( comp_pre_go == -1 ) {
				jQuery(this).next().after('<div class="cgss-notice">' + cnote.fail + '</div>');
				return false;
			}

		} else if ( which_data == 'fetch' ) {

			var no_fetch = 0;
			var fetch_comp_key = '';

			jQuery(".loading-fetch-result").show();
			jQuery(".compete-fetch-msg").hide();
			jQuery(this).attr("disabled", "disabled");
			jQuery("#CompeteClose, #CompeteResult").attr("disabled", "disabled");

			//ajax to fetch scan data
			jQuery.ajax({
				type: "POST",
				url: compete_ajax_object.ajax_url,
				data: {'action': 'cgss_compete', 'type': 'fetch', 'find_post_id': client_id},
				dataType: "json",
				async: false,
				success: function(data) {

					//If scan fails
					if ( data == undefined ) {
						alert("Some problem occured");
						return false;
					} else if ( data.ping == 'false' ) {
						no_fetch = -1;
						jQuery(".compete-fetch-msg").show().html(cnote.fetch.no);
						return false;
					} else if ( data.ping == 'invalid' ) {
						no_fetch = -1;
						jQuery(".compete-fetch-msg").show().html(cnote.fetch.no_data);
						return false;
					} else if ( data.ping == 'valid' ) {
						no_fetch = 1;

						comp_url = [];

						ssl = [];
						mobile = [];
						words = [];
						links = [];
						links_ext = [];
						thr = [];
						images = [];
						key_count = [];
						key_per = [];
						gplus = [];
						fb = [];
						tw = [];

						h1 = [];
						h2 = [];
						h3 = [];
						h4 = [];
						h5 = [];
						h6 = [];

						domain = [];
						title = [];
						url = [];
						desc = [];
						alt = [];
						anch = [];
						htag = [];
						plain = [];

						speed = [];

						hding = [];

						//reset compete form data
						jQuery(".compete-focus-keyword").removeAttr("style");
						jQuery(".compete-focus-keyword").val(data.comp_key);
						jQuery(".compete-form").children(".compete-url-input-cover").slice(1).remove();
						jQuery(".compete-form").children(".compete-url-input-cover-prev").remove();
						var fetch_int = 1;
						jQuery.map(data.comp_url, function( val, i ) {
						jQuery(".compete-url-input-cover").before( '<div class="compete-url-input-cover-prev"><span class="view-url">' + fetch_int + '</span><span></span><input class="regular-text compete-url" name="compete-url" type="text" value="' + val + '" /><span class="success-icon">' + cnote.ok + '</span></div>' );
							comp_url.push(val);
							fetch_int += 1;
						}),
						counter = comp_url.length + 1;
						var form_trans = jQuery(".compete-url-input-cover").last().children();
						form_trans.eq(0).html(counter);
						form_trans.eq(1).show();
						form_trans.eq(2).val("");
						form_trans.eq(-1).hide();

						filter_data_eq( ssl, data.ssl );
						filter_data_eq( mobile, data.mobile );
						filter_data_eq( words, data.words );
						filter_data_eq( links, data.links );
						filter_data_eq( links_ext, data.links_ext );
						filter_data_eq( thr, data.thr );
						filter_data_eq( images, data.images );

						filter_data_eq( gplus, data.gplus );
						filter_data_eq( fb, data.fb );
						filter_data_eq( tw, data.tw );

						filter_data_eq( key_count, data.key_count );
						filter_data_eq( key_per, data.key_per );

						filter_data_eq( h1, data.hone );
						filter_data_eq( h2, data.htwo );
						filter_data_eq( h3, data.hthree );
						filter_data_eq( h4, data.hfour );
						filter_data_eq( h5, data.hfive );
						filter_data_eq( h6, data.hsix );

						filter_data_eq( domain, data.domain );
						filter_data_eq( url, data.url );
						filter_data_eq( title, data.title );
						filter_data_eq( desc, data.desc );
						filter_data_eq( alt, data.alt );
						filter_data_eq( anch, data.anch );
						filter_data_eq( htag, data.htag );
						filter_data_eq( plain, data.plain );

						filter_data_eq( speed, data.speed );

						fetch_comp_key = data.comp_key;

						jQuery(".compete-fetch-msg").show().html(cnote.fetch.ok);
					}
				},
				error: function() {
					no_fetch = -1;
					jQuery(".compete-fetch-msg").show().html(cnote.fetch.no);
				}
			});

			//ajax for display result
			var comp_pre_fetch_go = 0;

			jQuery.ajax({
				type: "POST",
				url: compete_ajax_object.ajax_url,
				data: {'action': 'cgss_compete', 'type': 'compete', 'id': ' ', 'key': fetch_comp_key, 'url': client_url},
				dataType: "json",
				async: false,
				success: function(data) {

					//If scan fails
					if ( data == undefined ) {
						alert("Some problem occured");
						return false;
					} else if ( data.ping == 'false' ) {
						comp_pre_fetch_go = -1;
						jQuery(".compete-fetch-msg").show().html(data.val);
						return false;
					} else if ( data.ping == 'valid' ) {
						comp_pre_fetch_go = 1;

						filter_data( ssl, data.crawl.ssl );
						filter_data( mobile, data.design.vport );
						filter_data( words, data.text.count );
						filter_data( links, data.text.links.num );
						filter_data( links_ext, data.text.links.external );
						filter_data( thr, data.text.ratio );
						filter_data( images, data.design.image.count );

						filter_data( gplus, data.social.gplus );
						filter_data( fb, data.social.fb_share );
						filter_data( tw, data.social.twitter );

						hding = jQuery.map(data.text.htags.names, function(el) { return el; });
						hd_entry( 'h1', h1, hding );
						hd_entry( 'h2', h2, hding );
						hd_entry( 'h3', h3, hding );
						hd_entry( 'h4', h4, hding );
						hd_entry( 'h5', h5, hding );
						hd_entry( 'h6', h6, hding );

						filter_data( key_count, data.compete.num );
						filter_data( key_per, data.compete.per );
	
						filter_data( domain, data.compete.domain );
						filter_data( url, data.compete.url );
						filter_data( title, data.compete.title );
						filter_data( desc, data.compete.desc );
						filter_data( alt, data.compete.alt );
						filter_data( anch, data.compete.anch );
						filter_data( htag, data.compete.htag );
						filter_data( plain, data.compete.plain );

						filter_data( speed, data.speed.down_time / 1000 );
					}
				},
				error: function() {
					comp_pre_fetch_go = -1;
					jQuery(".compete-fetch-msg").show().html(cnote.fetch.no);
				}
			});

			jQuery(".loading-fetch-result").hide();
			jQuery(this).removeAttr("disabled");
			jQuery("#CompeteClose, #CompeteResult").removeAttr("disabled");
		}

		if ( no_fetch != -1 && comp_pre_fetch_go != -1 ) {
			jQuery(".cgss-notice").remove();
			jQuery(".compete-form-container, .loading-compete").hide();
			jQuery(".compete-result").show();
			jQuery(".url-more .reset-compete").removeAttr("disabled");
			jQuery("#CompeteForm").removeClass("disabled").removeAttr("disabled");
			jQuery("#CompeteResult").addClass("disabled").attr("disabled", "disabled");
		}


		if ( client_free != 'free' && which_data != 'demo' ) {

			comp_out( words, "Word", cnote.ok, cnote.up, cnote.down, 0 );
			comp_out( thr, "Thr", cnote.ok, cnote.up, cnote.down, 1 );
			comp_out( links, "Links", cnote.ok, cnote.up, cnote.down, 0 );
			comp_out( links_ext, "ExtLinks", cnote.ok, cnote.up, cnote.down, 0 );
			comp_out( images, "ImagesCount", cnote.ok, cnote.up, cnote.down, 0 );
			comp_out( key_count, "KeysCount", cnote.ok, cnote.up, cnote.down, 0 );
			comp_out( key_per, "Keysperc", cnote.ok, cnote.up, cnote.down, 0 );

			comp_out_social( gplus, fb, tw, "Gp", "Fb", "Tw", cnote.not_found );

			comp_key_out( domain, "Domain", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( url, "Url", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( title, "Title", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( desc, "Desc", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( alt, "Alt", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( anch, "Anch", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( htag, "Htag", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( plain, "Txt", cnote.ok, cnote.no, cnote.not_found );

			comp_key_out( h1, "h1", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( h2, "h2", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( h3, "h3", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( h4, "h4", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( h5, "h5", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( h6, "h6", cnote.ok, cnote.no, cnote.not_found );

			comp_key_out( mobile, "Responsive", cnote.ok, cnote.no, cnote.not_found );
			comp_key_out( ssl, "SslSec", cnote.ok, cnote.no, cnote.not_found );

			comp_out_speed( speed, "Speed", cnote.not_found );

			jQuery("#CompNum, #CompNumResult").html(comp_url.length);

		} else {
			jQuery("#CompNum, #CompNumResult").html("");
		}

		//remove last data element after comparing or fetching
		ssl.splice(0, 1);
		mobile.splice(0, 1);
		words.splice(0, 1);
		links.splice(0, 1);
		links_ext.splice(0, 1);
		thr.splice(0, 1);
		images.splice(0, 1);
		key_count.splice(0, 1);
		key_per.splice(0, 1);
		gplus.splice(0, 1);
		fb.splice(0, 1);
		tw.splice(0, 1);
		domain.splice(0, 1);
		url.splice(0, 1);
		title.splice(0, 1);
		desc.splice(0, 1);
		alt.splice(0, 1);
		anch.splice(0, 1);
		htag.splice(0, 1);
		plain.splice(0, 1);
		speed.splice(0, 1);
		h1.splice(0, 1);
		h2.splice(0, 1);
		h3.splice(0, 1);
		h4.splice(0, 1);
		h5.splice(0, 1);
		h6.splice(0, 1);
	});

	//save required data
	jQuery(".save-compete").click(function() {

		//object converter for ajax
		function toObject(arr) {
			var rv = {};
			for (var i = 0; i < arr.length; ++i)
				rv[i] = arr[i];
			return rv;
		}

		//find keyword
		comp_key = jQuery.trim(jQuery(".compete-focus-keyword").val());

		jQuery(".loading-save-result").show();
		jQuery(".compete-save-msg").hide();
		jQuery(this).attr("disabled", "disabled");
		jQuery("#CompeteClose, #CompeteForm").attr("disabled", "disabled");

		jQuery.ajax({
			type: 'POST',
			url: compete_ajax_object.ajax_url,
			data: {'action': 'cgss_compete', 'type': 'save', 'id': client_id, 'comp_key': comp_key, 'comp_url': toObject(comp_url), 'ssl': toObject(ssl), 'mobile': toObject(mobile), 'words': toObject(words), 'links': toObject(links), 'links_ext': toObject(links_ext), 'thr': toObject(thr), 'images': toObject(images), 'key_count': toObject(key_count), 'key_per': toObject(key_per), 'gplus': toObject(gplus), 'fb': toObject(fb), 'tw': toObject(tw), 'domain': toObject(domain), 'title': toObject(title), 'url': toObject(url), 'desc': toObject(desc), 'alt': toObject(alt), 'anch': toObject(anch), 'htag': toObject(htag), 'plain': toObject(plain), 'speed': toObject(speed), 'h1': toObject(h1), 'h2': toObject(h2), 'h3': toObject(h3), 'h4': toObject(h4), 'h5': toObject(h5), 'h6': toObject(h6)},
			dataType: 'json',
			success: function(data) {
				//If scan fails
				if ( data == undefined ) {
					alert("Some problem occured");
					return false;
				} else if ( data.ping == 'false' ) {
					jQuery(".compete-save-msg").show().html(data.val);
					return false;
				} else if ( data.ping == 'valid' ) {
					jQuery(".compete-save-msg").show().html(cnote.saved);
				} else if ( data.ping == 'invalid' ) {
					jQuery(".compete-save-msg").show().html(cnote.no_saved);
				}
			},
			error: function() {
				jQuery(".compete-save-msg").show().html(cnote.no_saved);
			}
		});
		jQuery(".loading-save-result").hide();
		jQuery(this).removeAttr("disabled");
		jQuery("#CompeteClose, #CompeteForm").removeAttr("disabled");
	});

	//Close and toggle form and result competative report
	jQuery("#CompeteClose").click(function(){ jQuery(".scan-compete").hide(); });
	jQuery("#CompeteForm").click(function(){
		if ( jQuery(this).attr("class").indexOf("disabled") == -1 ) {
			jQuery(".compete-form-container").show();
			jQuery(".compete-result").hide();
			jQuery(this).addClass("disabled").attr("disabled", "disabled");
			jQuery("#CompeteResult").removeClass("disabled").removeAttr("disabled");
		}
	});
	jQuery("#CompeteResult").click(function(){
		if ( jQuery(this).attr("class").indexOf("disabled") == -1 ) {
			jQuery(".compete-form-container").hide();
			jQuery(".compete-result").show();
			jQuery(this).addClass("disabled").attr("disabled", "disabled");
			jQuery("#CompeteForm").removeClass("disabled").removeAttr("disabled");
		}
	});

	//toggle accordion
	tog_accord( 'comp-word' );
	tog_accord( 'comp-images' );
	tog_accord( 'comp-links' );
	tog_accord( 'comp-heads' );
	tog_accord( 'comp-shares' );
	tog_accord( 'comp-keys' );
	tog_accord( 'comp-variants' );
	tog_accord( 'comp-snippet' );

	//hide accordion inside
	jQuery("#inside-comp-word").hide();
	jQuery("#inside-comp-images").hide();
	jQuery("#inside-comp-links").hide();
	jQuery("#inside-comp-heads").hide();
	jQuery("#inside-comp-shares").hide();
	jQuery("#inside-comp-keys").hide();
	jQuery("#inside-comp-variants").hide();
	jQuery("#inside-comp-snippet").hide();

	//toggle input lebel help
	tog_help( 'focus-key' );
	tog_help( 'compete-url' );

	//toggle compete box help
	tog_compete_help( 'Words' );
	tog_compete_help( 'Links' );
	tog_compete_help( 'Heads' );
	tog_compete_help( 'Keys' );
	tog_compete_help( 'Snip' );
	tog_compete_help( 'Share' );

	//notice hide
	jQuery(document).on('click', '.cgss-scan-failed-btn', function() {
		jQuery(".cgss-scan-failed").remove();
	});
	jQuery(document).on('click', '.cgss-no-key-found-btn', function() {
		jQuery(".cgss-no-key-found, .cgss-no-key-found-table").remove();
	});
	jQuery(document).on('click', '.cgss-no-key-no-var-found-btn', function() {
		jQuery(".cgss-no-key-no-var-found, .cgss-no-key-no-var-found-table").remove();
	});
	jQuery(document).on('click', '.cgss-comp-saved-btn', function() {
		jQuery(".cgss-no-key-no-var-found, .cgss-comp-saved").remove();
	});
	jQuery(document).on('click', '.cgss-comp-scan-error-btn', function() {
		jQuery(".cgss-comp-scan-error, .cgss-comp-saved").remove();
	});

});
