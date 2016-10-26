/**
 * @/assets/scan.js
 * on: 08.08.2015
 * @since 2.1
 *
 * overall seo scan success function
 */

function show_score( score, full, half, blank ) {

	//change screen display for the particular row.
	if ( score > 4.5 ) {
		var new_score = Array(6).join(full);
	} else if ( score > 4 ) {
		var new_score = Array(5).join(full) + half;
	} else if ( score > 3.5 ) {
		var new_score = Array(5).join(full) + blank;
	} else if ( score > 3 ) {
		var new_score = Array(4).join(full) + half + blank;
	} else if ( score > 2.5 ) {
		var new_score =  Array(4).join(full) + Array(3).join(blank);
	} else if ( score > 2 ) {
		var new_score = Array(3).join(full) + half + Array(3).join(blank);
	} else if ( score > 1.5 ) {
		var new_score = Array(3).join(full) + Array(4).join(blank);
	} else if ( score > 1 ) {
		var new_score = full + half + Array(4).join(blank);
	} else if ( score == 1 ) {
		var new_score = full + Array(5).join(blank);
	}
	return new_score;
}

function scan_success( data, act, ok, no, spam, enabled, disabled, noindex, note_title, note_absent, note_desc, links_no, links_ok, img_none, image, img_no, img_ok, note_q_mark, note_under_mark, note_http, note_https, ok_compression, no_compression, absent, mdesc, note_none ) {

	//Show scan time
	var time_now = data.time;
	jQuery("#ScanTime").html(time_now.toFixed(0));

	//show opening url
	var url_show = data.crawl.val;
	jQuery("#ViewPage").attr( "href", url_show ).attr( "target", "_blank" );

	//Display social media counts
	var social_num = data.social.num;
	var gplus = data.social.gplus;
	var twitter = data.social.twitter;
	var fb_share = data.social.fb_share;
	jQuery("#FbShare").html(shorten_num( fb_share ));
	jQuery("#GplusCount").html(shorten_num( gplus ));
	jQuery("#TweeetCount").html(shorten_num( twitter ));

	//Title tag info
	var title = data.snip.title;
	if ( title.length > 65 ) {
		title = title.substr(0, 61) + " ...";
	}
	html_show( title, "SnipTitle", title, note_title );
	act = simple_act( title, "SnipTitle", act );

	if ( url_show.length > 85 ) {
		url_show = url_show.substr(0, 81) + " ...";
	}
	html_show( url_show, "SnipUrl", url_show, note_absent );

	//Meta description tag
	var desc = data.snip.desc;
	if ( desc.length > 166 ) {
		desc = desc.substr(0, 162) + " ...";
	}
	html_show( desc, "SnipContent", desc, note_desc );
	act = simple_act( desc, "DescAct", act );

	//Count words
	var words = data.text.count;
	if ( words == 0 ) {
		words = '0';
	}
	html_show( words, "WordsNum", "<span class='highlight'>&nbsp;" + words + "&nbsp;</span>", " " );
	if ( words < 200 ) {
		jQuery("#WordsSwitch").html(no);
		act += 1;
		jQuery("#CountWordAct").show();
	} else {
		jQuery("#WordsSwitch").html(ok);
	}

	//text to html ratio
	var ratio = data.text.ratio;
	if ( ratio < 15 ) {
		jQuery("#TratioSwitch").html(no);
		act += 1;
		jQuery("#RatioAct").show();
	} else if ( ratio < 70 ) {
		jQuery("#TratioSwitch").html(ok);
	} else {
		jQuery("#TratioSwitch").html(no);
		act += 1;
		jQuery("#RatioAct").show();
	}
	if ( ratio == 0 ) {
		ratio = '0';
	}
	html_show( ratio, "Tratio", "<span class='highlight'>&nbsp;" + ratio + "%&nbsp;</span>", " " );

	//text to html ratio
	var head_tags_arr = data.text.htags.names;
	if ( head_tags_arr != undefined ) {
		head_tags_arr = jQuery.map(head_tags_arr, function(val) { return val; })
	} else {
		head_tags_arr = [];
	}
	var head_tags_con = data.text.htags.content;
	if ( head_tags_arr.length > 0 ) {
		var htags_val = head_tags_arr.join(', ');
		jQuery("#ThrchySwitch").html(ok);
	} else {
		jQuery("#ThrchySwitch").html(no);
		act += 1;
		jQuery("#HrchyAct").show();
	}
	if ( head_tags_con == undefined ) {
		var head_tags_con = '';
	}
	head_tags_con_size = head_tags_con.split(" ").length;
	html_show( htags_val, "Thrchy", "<span class='highlight'>&nbsp;" + htags_val + "&nbsp;</span>", " " );

	//Total links
	var links = data.text.links.num;
	jQuery("#LinksNumMsg").show();
	if ( words > 0 && (( links / words ) * 100).toFixed(0) > 50 ) {
		jQuery("#LinksNumMsg").html(links_no);
		act += 1;
		jQuery("#LnNumAct").show();
	} else {
		jQuery("#LinksNumMsg").html(links_ok);
	}
	if ( links == 0 ) {
		links = '0';
	}
	html_show( links, "LinksNum", links, "0" );

	//Image Link
	var img_link = data.text.links.no_text;
	jQuery("#ImgLinkTick").html("");
	if ( img_link > 1 ) {
		jQuery("#ImgLinkTick").html(no);
		act += 1;
		jQuery("#ImgLnAct").show();
	} else {
		jQuery("#ImgLinkTick").html(ok);
	}
	if ( img_link == 0 ) {
		img_link = '0';
	}
	html_show( img_link, "ImgLink", "<span class='highlight'>&nbsp;" + img_link + "&nbsp;</span>", " " );

	//Nofollow Link
	var nof_link = data.text.links.nofollow;
	if ( data.text.links.num != 0 ) {
		if ( ( ( nof_link / data.text.links.num ) * 100 ).toFixed(0) > 50 ) {
			jQuery("#NofLinkTick").html(no);
			act += 1;
			jQuery("#NofLnAct").show();
		} else {
			jQuery("#NofLinkTick").html(ok);
		}
	}
	if ( nof_link == 0 ) {
		nof_link = '0';
	}
	html_show( nof_link, "NofLink", "<span class='highlight'>&nbsp;" + nof_link + "&nbsp;</span>", " " );

	//External Link
	var ex_link = data.text.links.external;
	jQuery("#ExtLinkTick").html("");
	if ( data.text.links.num != 0 ) {
		if ( ( ( nof_link / data.text.links.num ) * 100 ).toFixed(0) > 50 ) {
			jQuery("#ExtLinkTick").html(no);
			act += 1;
			jQuery("#ExtLnAct").show();
		} else {
			jQuery("#ExtLinkTick").html(ok);
		}
	}
	if ( ex_link == 0 ) {
		ex_link = '0';
	}
	html_show( ex_link, "ExLink", "<span class='highlight'>&nbsp;" + ex_link + "&nbsp;</span>", " " );

	//Link Anchor text
	var links_anch = data.text.links.anchors;
	if ( links_anch == undefined ) {
		var links_anch = '';
	}
	var links_anch_size = links_anch.split(" ").length;

	//List Images without alt tags
	var img_num = data.design.image.count;
	jQuery(".img-list").hide();
	jQuery("#ImgSwitch, #ImgAltDis").html("");
	if ( img_num == 0 ) {
		jQuery("#ImgSwitch").html(img_none);
	} else {
		var no_alt_src = data.design.image.no_alt_src;
		if ( no_alt_src != '' ) {
			var no_alt_src_arr = no_alt_src.split(',');
			var dis_no_alt = [];
			jQuery.map( no_alt_src_arr, function( value ) { dis_no_alt.push('<a class="social-top" href="' + value + '" target="_blank">' + image + '</a>'); });
			jQuery(".img-list").show();
			jQuery("#ImgAltDis").html(dis_no_alt.join(""));
			jQuery("#ImgSwitch").html(img_no);
			act += 1;
			jQuery("#AltAct").show();
		} else {
			jQuery("#ImgSwitch").html(img_ok);
		}
	}
	var img_alt = data.design.image.alt;
	if ( img_alt == undefined ) {
		var img_alt = '';
	}
	var img_alt_size = img_alt.split(" ").length;

	//Nested table
	var nested_table = data.design.nested_table;
	if ( nested_table ) {
		html_show( nested_table, "NtblVal", "<span class='highlight'>&nbsp;" + nested_table + "&nbsp;</span>", " " );
	}
	if ( nested_table > 0 ) {
		nested_table = 1;
		act += 1;
		jQuery("#NtblAct").show();
	}
	on_off_alt( 1 - nested_table, "Ntbl", ok, no );

	//Style Attributes
	var tg_stl = data.design.tag_style.num;
	var tg_stl_val = data.design.tag_style.list;
	if ( tg_stl > 0 ) {
		tg_stl = 1;
	} else {
		act += 1;
		jQuery("#StlAtrAct").show();
	}
	if ( tg_stl_val ) {
		html_show( tg_stl, "TgStlVal", "<span class='highlight'>&nbsp;" + tg_stl_val + "&nbsp;</span>", " " );
	}
	on_off_alt( 1 - tg_stl, "TgStl", ok, no );

	//Viewport tag
	var vport = data.design.vport;
	on_off( vport, "Vport", enabled, disabled );
	act = simple_act( vport, "VportAct", act );

	//Media Queries
	var media_qr = data.design.media.ok;
	var media_qr_num = data.design.media.num;
	on_off_alt( media_qr, "MdQr", ok, no );
	if ( media_qr_num > 0 ) {
		html_show( media_qr_num, "MdQrVal", "<span class='highlight'>&nbsp;" + media_qr_num + "&nbsp;</span>", '' );
	} else {
		act += 1;
		jQuery("#CssMediaAct").show();
	}

	//Url analysis
	jQuery(".show-url-text").html( data.crawl.val.replace( "?", note_q_mark ).replace( "_", note_under_mark ).replace( "http://", note_http ).replace( "https://", note_https ) );

	//ssl url
	var ssl = data.crawl.ssl;
	on_off_alt( ssl, "ssl", ok, no );
	act = on_off_act( ssl, "SslAct", act );

	//dynamic url
	var dynamic = data.crawl.dynamic;
	on_off_alt( 1 - dynamic, "dynamic", ok, no );
	act = on_off_act( 1 - dynamic, "UrlAct", act );

	//underscore url
	var underscore = data.crawl.underscore;
	on_off_alt( 1 - underscore, "underscore", ok, no );
	act = on_off_act( 1 - underscore, "UrlAct", act );

	//www resolve
	var www = data.crawl.www;
	on_off( www, "Www", enabled, disabled );
	act = on_off_act( www, "WwwAct", act );

	//Canonical
	var cano = data.crawl.cano;
	on_off( cano, "Cano", enabled, disabled );
	act = on_off_act( cano, "CanoAct", act );

	//If modified since
	var if_mod = data.crawl.if_mod;
	on_off( if_mod, "IfMod", enabled, disabled );
	act = on_off_act( if_mod, "IfModAct", act );

	//Meta robot
	var robo = data.crawl.meta_robot.ok;
	var robo_val = data.crawl.meta_robot.val;
	if ( ! robo_val || robo_val.indexOf('noindex') != -1 ) {
		html_show( robo, "RoboChk", no, no );
	} else {
		html_show( robo, "RoboChk", ok, ok );
	}
	if ( robo_val ) {
		html_show( robo_val, "RoboVal", robo_val.replace( "noindex", noindex ), '' );
	}
	act = on_off_act( robo, "RoboAct", act );

	//ip address
	var ip = data.crawl.ip.ok;
	var ip_addr = data.crawl.ip.val;
	on_off_alt( ip, "Ip", ok, no );
	if ( ip_addr ) {
		html_show( ip_addr, "IpAddr", "<span class='highlight'>&nbsp;" + ip_addr + "&nbsp;</span>", '' );
	}
	act = on_off_act( ip, "IpAct", act );

	//Loading time
	var down_time = data.speed.down_time;
	html_show( down_time, "DownTime", ( down_time / 1000 ), note_none );

	//Response time
	var res_time = data.speed.res_time;
	html_show( res_time, "ResTime", res_time, note_none );
	if ( res_time > 1000 ) {
		act += 1;
		jQuery("#SpeedAct").show();
	}

	//Gzip
	var gzip = data.speed.gzip;
	on_off( gzip, "Gzip", enabled, disabled );
	act = on_off_act( gzip, "GzipAct", act );

	//Cache
	var cache = data.speed.cache;
	on_off( cache, "Cache", enabled, disabled );
	act = on_off_act( cache, "CacheAct", act );

	//.css details
	var css_num = data.speed.css.num;
	var css_size = data.speed.css.size;
	css_num = color_it( css_num, 10 );
	css_size = color_it( css_size, 100 );
	html_show( css_num, "cssNumSize", css_num + " (" + css_size + " kb)", '' );

	//.js details
	var js_num = data.speed.js.num;
	var js_size = data.speed.js.size;
	js_num = color_it( js_num, 10 );
	js_size = color_it( js_size, 100 );
	html_show( js_num, "jsNumSize", js_num + " (" + js_size + " kb)", '' );

	//number of resource action
	var resource_num = css_num + js_num;
	if ( resource_num > 20 ) {
		act += 1;
		jQuery("#FNumAct").show();
	}

	//resource compression
	var css_comp_num = data.speed.comp.css.num;
	var css_comp_size = data.speed.comp.css.size;
	var js_comp_num = data.speed.comp.js.num;
	var js_comp_size = data.speed.comp.js.size;
	var comp_num = css_comp_num + js_comp_num;
	var comp_percent = ( ( ( css_comp_size + js_comp_size ) / ( css_size + js_size ) ) * 100 );
	jQuery("#ResComp, #CompFiles, #CompSize").html("");
	if ( comp_percent && comp_percent.toFixed(0) > 5 ) {
		jQuery("#ResComp").html( ok_compression );
		jQuery("#CompFiles").html( comp_num );
		jQuery("#CompSize").html( comp_percent.toFixed(0) );

		//Show compression action
		act += 1;
		jQuery("#CompAct").show();
	} else {
		jQuery("#ResComp").html( no_compression );
	}

	//Keyword listing
	var keys_arr = data.text.keys;
	var keys_list = Object.keys(keys_arr);
	if ( keys_list && keys_list.length > 0 ) {
		jQuery("#keyDis").html('');

		//show keywords
		jQuery("#KeywordList").html("");
		jQuery.each( keys_list, function(val, text) { jQuery("#KeywordList").append( jQuery('<option></option>').val(text).html(text + " (" + keys_arr[text] + "%)") ); });

		//find out most popular keyword
		var key_list_found = jQuery.grep( keys_list, function( val ) { return ( title.indexOf(val) != -1 || desc.indexOf(val) != -1 || url_show.indexOf(val) != -1 || url_show.indexOf(val.split(" ").join("-")) != -1 || url_show.indexOf(val.split(" ").join("")) != -1 ); });
		if ( key_list_found && key_list_found.length > 0 ) {
			var top_key = key_list_found[0];
			html_show( title, "SnipTitle", title.toLowerCase().replace( top_key, '<strong>' + top_key + '</strong>' ), title );
			html_show( url_show, "SnipUrl", url_show.replace( top_key, '<strong>' + top_key + '</strong>' ).replace( top_key.split(" ").join("-"), '<strong>' + top_key.split(" ").join("-") + '</strong>' ).replace( top_key.split(" ").join(""), '<strong>' + top_key.split(" ").join("") + '</strong>' ), absent );
			html_show( desc, "SnipContent", desc.toLowerCase().replace( top_key, '<strong>' + top_key + '</strong>' ), mdesc );
		}
	} else {
		jQuery("#keyDis").html(keys);
	}

	//keywords action
	if ( keys_list.length < 10 ) {
		act += 1;
		jQuery("#KeyAct").show();
	}

	//Keywords dropdown toggle
	function key_toggle() {
		var key = jQuery("#KeywordList").val();
		if_key_found( head_tags_con, head_tags_con_size, key, "#HeadingChk", ok, no, spam );
		if_key_found( img_alt, img_alt_size, key, "#AltChk", ok, no, spam );
		if_key_found( links_anch, links_anch_size, key, "#AnchorChk", ok, no, spam );
		if_key_found( title, title.length, key, "#TitleChk", ok, no, spam );
		if_key_found( desc, desc.length, key, "#MetaDescChk", ok, no, spam );
		if_key_found( url_show, url_show.length, key, "#UrlChk", ok, no, spam );
	}
	key_toggle();
	jQuery("#KeywordList").change(function() {
		key_toggle();
	});

	return act;
}

//Custom keyword function
function stag_show( data, title_absent, desc_absent, domain_absent, image_absent, act ) {

	//Social tag: url
	var stag_url = data.social_tags.ogp.url;
	if( stag_url && stag_url.length > 0 ) {
		jQuery("#StagUrl").attr( "href", stag_url );
	} else {
		jQuery("#StagUrl").attr( "href", "" );
	}

	//Social tag: title
	var stag_title = data.social_tags.ogp.title;
	social_tag_show( stag_title, 55, "StagTitle", title_absent );

	//Social tag: description
	var stag_desc = data.social_tags.ogp.desc;
	social_tag_show( stag_desc, 150, "StagDesc", desc_absent );

	//Social tag: domain
	var domain = data.domain;
	social_tag_show( domain, 55, "StagDomain", domain_absent );

	//Social tag: image
	var stag_img = data.social_tags.ogp.img;
	if( stag_img && stag_img.length > 0 ) {
		jQuery("#StagImage").attr( "src", stag_img );
		jQuery("#StagImage").attr( "alt", "" );
	} else {
		jQuery("#StagImage").attr( "src", "" );
		jQuery("#StagImage").attr( "alt", image_absent );
	}

	//Social tags action
	if ( stag_url == undefined || stag_url.length == 0 || stag_title == undefined || stag_title.length == 0 || stag_desc == undefined || stag_desc.length == 0 || stag_img == undefined || stag_img.length == 0 ) {
		act += 1;
		jQuery("#StagOgpAct").show();
	}

	return act;
}
