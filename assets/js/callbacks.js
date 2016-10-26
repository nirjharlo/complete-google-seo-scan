/**
 * @/assets/callbacks.js
 * on: 08.08.2015
 * @since 2.1
 *
 * Function library
 */

//Cut short any number above thousand, million
function shorten_num( num ) {
	if ( num >= 1000000 ) {
		return '1M+';
	} else if ( num >= 10000 ) {
		return ( num / 10000 ).toFixed(0) + "K";
	} else if ( num >= 1000 ) {
		return ( num / 1000 ).toFixed(1) + "K";
	} else {
		return num;
	}
}

//show action from boolean data
function simple_act( param, element, act ) {
	if ( param == undefined || param.length == 0 ) {
		act += 1;
		jQuery("#" + element).show();
	}
	return act;
}

//show action from boolean data
function on_off_act( param, element, act ) {
	if ( param != 1 ) {
		act += 1;
		jQuery("#" + element).show();
	}
	return act;
}

//show tick or cross from data
function on_off( param, element, enabled, disabled ) {
	jQuery("#" + element).html("");
	if ( param && param == 1 ) {
		jQuery("#" + element + "OnOff").html(enabled);
	} else {
		jQuery("#" + element + "OnOff").html(disabled);
	}
}

//show tick or cross from data
function on_off_alt( param, element, ok, no ) {
	jQuery("#" + element).html("");
	if ( param && param == 1 ) {
		jQuery("#" + element + "Switch").html(ok);
	} else {
		jQuery("#" + element + "Switch").html(no);
	}
}

//show tick or cross from data
function html_show( param, element, notice_ok, notice_no ) {
	jQuery("#" + element).html("");
	if ( param ) {
		jQuery("#" + element).html(notice_ok);
	} else {
		jQuery("#" + element).html(notice_no);
	}
}

//color elements based on number
function color_it( param, max_allow ) {
	if ( param > max_allow ) {
		return '<span class="danger-icon">' + param + '</span>';
	} else {
		return param;
	}
}

//show social tags elements in snippet
function social_tag_show( param, max_num, elem, none ) {
	jQuery("#" + elem).html("");
	if( param && param.length > 0 ) {
		if ( param.length > max_num ) {
			param = param.substr(0, max_num - 4) + " ...";
		}
		jQuery("#" + elem).html( param );
	} else {
		jQuery("#" + elem).html( none );
	}
}

//function to click to toggle accordion
function tog_accord( param ) {
	jQuery("#hndle-" + param).click(function(){ jQuery("#inside-" + param).toggle(150); });
	jQuery(".handlediv-" + param).click(function(){ jQuery("#inside-" + param).toggle(150); });
}

//function to toggle on report help docs
function tog_help( param ) {
	jQuery("." + param + "-help").click(function() {
		jQuery("." + param + "-help-msg").toggle();
	});
}

//count repeatation of string
function occurrences( haystack, needle ){
    var n = 0;
    var pos = 0;
    while( true ){
        pos = haystack.indexOf( needle, pos );
        if( pos != -1 ){ n++; pos += needle.length; }
        else{ break; }
    }
    return (n);
}

//toggle keywords value
function if_key_found( haystack, size, key, element, ok, no, spam ) {
	jQuery(element).html("");
	if ( haystack.toLowerCase().indexOf(key) != -1 ) {
		jQuery(element).html(ok);
	} else {
		jQuery(element).html(no);
	}
	if ( size != undefined && size > 0 ) {
		var count = occurrences( haystack, key );
		var percent = ( ( count / size ) * 100 ).toFixed(0);
		if ( percent > 10 ) {
			jQuery(element).html(spam);
		}
	}
}

//real time result toggle
function show_real_time( elem, param ) {
	if ( param != undefined ) {
		jQuery(elem).html(param);
	} else {
		jQuery(elem).html("");
	}
}

//check if url is ok or not
function test_url(url) {
	var out = false;
	var url_validate = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
	if ( url_validate.test(url) ) {
		out = true;
	}
	return out;
}

//filter input data and fit into array
function filter_data( push_in, param ) {
	if (param != undefined) {
		push_in.push( parseFloat(param) );
	}
}

//filter input data and fit into array
function filter_data_eq( push_in, param ) {
	if (param != undefined) {
		jQuery.map(param, function( i, v ) { push_in.push( parseFloat(i) ); });
	}
}

//function to output competative keyword
function comp_out( raw_param, elem, up, down, roundit ) {

	var optimum = 0;

	if ( raw_param != undefined && raw_param != null && raw_param.length > 0 ) {
		var you_val = raw_param.slice(-1);
		jQuery("#You" + elem).html(you_val);
	} else {
		jQuery("#You" + elem).html("--");
	}

	//remove last element, which is client webpage
	param = raw_param;
	if ( raw_param != undefined && raw_param != null ) {
		if ( param.length > 1 ) {
			var pmax = Math.max.apply(Math, param);
			var pmin = Math.min.apply(Math, param);
			if ( param.length > 1 ) {
				var sum = param.reduce(function(a, b) { return a + b; });
			} else {
				var sum = param[0];
			}
			var avg = sum / param.length;
			var diffs = param.map(function(value){ var diff = value - avg; return diff * diff; });
			if ( diffs.length > 1 ) {
				var diffs_sum = diffs.reduce(function(a, b) { return a + b; });
			} else {
				var diffs_sum = diffs[0];
			}
			var diffs_avg = diffs_sum / ( diffs.length - 1 );
			var pstd = Math.sqrt(diffs_avg);
			var prange_min = (avg - pstd).toFixed(roundit);
			if ( prange_min <= 0 ) { prange_min = 0; }
			var prange_max = (pstd + avg).toFixed(roundit);
			if ( prange_max <= 0 ) { prange_max = 0; }
			var prange = "(" + prange_min + ", " + prange_max + ")";

			//show tick or cross based on your value
			if ( you_val < parseFloat(prange_min) ) {
				jQuery("#You" + elem).append(" " + down);
				optimum = -1;
			} else if ( you_val > parseFloat(prange_max) ) {
				jQuery("#You" + elem).append(" " + up);
				optimum = 1;
			}
		} else {
			var pmax = param[0];
			var pmin = param[0];
			var prange = param[0];
		}
	} else {
		var pmax = '--';
		var pmin = '--';
		var prange = "(--, --)";
	}
	if ( raw_param.length == 0 ) {
		var pmax = '--';
		var pmin = '--';
		var prange = "(--, --)";
	}

	jQuery("#Max" + elem).html(pmax);
	jQuery("#Min" + elem).html(pmin);
	jQuery("#Avg" + elem).html(prange);
	return optimum;
}

//function to show keyword by parts
function comp_key_out( raw_param, elem, ok, no ) {

	var optimum = 0;

	if ( raw_param != undefined && raw_param != null && raw_param.length > 0 ) {
		var key_you = raw_param.slice(-1);
		if ( key_you == 1 ) {
			jQuery("#You" + elem).html(ok);
		} else {
			jQuery("#You" + elem).html(no);
		}
	} else {
		jQuery("#You" + elem).html("--");
	}

	//remove last element, which is client webpage
	param = raw_param;
	if ( param != undefined && param != null && param.length > 0 ) {
		var comp_key = param.filter(function(value){ return value == 1; }).length;
		if ( comp_key > 0 ) {
			jQuery("#Comp" + elem).html(ok + " " + comp_key);
			optimum = comp_key;
		} else {
			jQuery("#Comp" + elem).html(no);
		}
	} else {
		jQuery("#Comp" + elem).html("--");
	}
	return optimum;
}

//optimum for compete data
function optimize( param_one, param_two, elem, msg_ok, msg_first_ok, msg_second_ok, msg_no, msg_fail ) {
	if ( param_one != undefined && param_two != undefined && param != null && param_two != null ) {
		if ( param_one == 0 && param_two == 0 ) {
			jQuery("#" + elem).html(msg_ok);
			jQuery("#" + elem).prev().attr("class", "success-icon");
		} else if ( param_one != 0 && param_two == 0 ) {
			jQuery("#" + elem).html(msg_second_ok);
			jQuery("#" + elem).prev().attr("class", "warning-icon");
		} else if ( param_one == 0 && param_two != 0 ) {
			jQuery("#" + elem).html(msg_first_ok);
			jQuery("#" + elem).prev().attr("class", "warning-icon");
		} else if ( param_one != 0 && param_two != 0 ) {
			jQuery("#" + elem).html(msg_no);
			jQuery("#" + elem).prev().attr("class", "danger-icon");
		}
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}

//optimum images
function optimize_images( param, elem, msg_ok, msg_more, msg_less, msg_fail ) {
	if ( param != undefined && param != null ) {
		if ( param == 1 ) {
			jQuery("#" + elem).html(msg_more);
			jQuery("#" + elem).prev().attr("class", "warning-icon");
		} else if ( param == -1 ) {
			jQuery("#" + elem).html(msg_less);
			jQuery("#" + elem).prev().attr("class", "warning-icon");
		} else {
			jQuery("#" + elem).html(msg_ok);
			jQuery("#" + elem).prev().attr("class", "success-icon");
		}
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}

//optimum for speed
function optimize_speed( param, elem, msg_ok, msg_no, msg_fail ) {
	if ( param != undefined && param != null ) {
		if ( param == 1 ) {
			jQuery("#" + elem).html(msg_no);
			jQuery("#" + elem).prev().attr("class", "danger-icon");
		} else {
			jQuery("#" + elem).html(msg_ok);
			jQuery("#" + elem).prev().attr("class", "success-icon");
		}
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}

//optimum social popularity
function optimize_social( param, elem, msg_ok, msg_no, msg_fail ) {
	if ( param != undefined && param != null ) {
		if ( param <= -2 ) {
			jQuery("#" + elem).html(msg_no);
			jQuery("#" + elem).prev().attr("class", "danger-icon");
		} else {
			jQuery("#" + elem).html(msg_ok);
			jQuery("#" + elem).prev().attr("class", "success-icon");
		}
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}

//optimum social network
function optimize_social_focus( gplus, fb, tw, elem ) {
	if ( gplus != undefined && gplus != null && fb != undefined && fb != null &&  tw != undefined && tw != null ) {
		var best_key_loc = [];
		gplus.slice(-1);
		fb.slice(-1);
		tw.slice(-1);
		if ( gplus.length > 1 ) {
			var gplus_count = gplus.reduce(function(a, b) { return a + b; });
		} else {
			var gplus_count = gplus[0];
		}
		if ( fb.length > 1 ) {
			var fb_count = fb.reduce(function(a, b) { return a + b; });
		} else {
			var fb_count = fb[0];
		}
		if ( tw.length > 1 ) {
			var tw_count = tw.reduce(function(a, b) { return a + b; });
		} else {
			var tw_count = tw[0];
		}
		var social_count = { 'google plus': gplus_count, 'facebook': fb_count, 'twitter': tw_count };
		var s_array = [gplus_count, fb_count, tw_count];
		var location_max = Math.max.apply(Math, s_array);
		jQuery.each(social_count, function( index, value ) { if ( value == location_max ) { best_key_loc.push(index); } });
		jQuery("#" + elem).append(best_key_loc.join(", "));
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}

//optimum keywords
function optimize_keys( param, elem, msg_ok, msg_more, msg_less, msg_fail ) {
	if ( param != undefined && param != null ) {
		if ( param == 1 ) {
			jQuery("#" + elem).html(msg_more);
			jQuery("#" + elem).prev().attr("class", "danger-icon");
		} else if ( param == -1 ) {
			jQuery("#" + elem).html(msg_less);
			jQuery("#" + elem).prev().attr("class", "danger-icon");
		} else {
			jQuery("#" + elem).html(msg_ok);
			jQuery("#" + elem).prev().attr("class", "success-icon");
		}
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}

//optimum keyword location
function optimize_key_location( domain, url, title, desc, alt, anch, htag, plain, elem ) {
	if ( domain != undefined && domain != null && url != undefined && url != null && title != undefined && title != null && desc != undefined && desc != null && alt != undefined && alt != null && anch != undefined && anch != null && htag != undefined && htag != null && plain != undefined && plain != null ) {
		var best_key_loc = [];
		var opt_location = [domain, url, title, desc, alt, anch, htag, plain];
		var opt_location_array = {'domain': domain, 'url': url, 'title tag': title, 'meta description': desc, 'alt tag': alt, 'anchor text': anch, 'heading tags': htag, 'plain text': plain};
		var opt_location_max = Math.max.apply(Math, opt_location);
		jQuery.each(opt_location_array, function( index, value ) { if ( value == opt_location_max ) { best_key_loc.push(index); } });
		jQuery("#" + elem).append(best_key_loc.join(", "));
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}

//optimize bonus points
function optimize_bonus( param, param_ex, elem, elem_ssl, elem_mobile, msg, msg_fail, all, none ) {
	if ( param != undefined && param != null && param_ex != undefined && param_ex != null ) {
		param.slice(-1);
		param_ex.slice(-1);
		if ( param.length > 1 ) {
			var param_count = ( ( param.reduce(function(a, b) { return a + b; }) * 100 ) / param.length ).toFixed(0);
		} else {
			var param_count = param[0];
		}
		if ( param_ex.length > 1 ) {
			var param_ex_count = ( ( param_ex.reduce(function(a, b) { return a + b; }) * 100 ) / param_ex.length ).toFixed(0);
		} else {
			var param_ex_count = param_ex[0];
		}

		jQuery("#" + elem).html(msg);

		if ( parseFloat(param_count) == 0 ) {
			jQuery("#" + elem_ssl).html(none);
		} else if ( parseFloat(param_count) == 100 ) {
			jQuery("#" + elem_ssl).html(all);
		} else {
			jQuery("#" + elem_ssl).html(param_count + "%");
		}

		if ( parseFloat(param_ex_count) == 0 ) {
			jQuery("#" + elem_mobile).html(none);
		} else if ( parseFloat(param_ex_count) == 100 ) {
			jQuery("#" + elem_mobile).html(all);
		} else {
			jQuery("#" + elem_mobile).html(param_ex_count + "%");
		}

		if ( param_count >= 50 && param_ex_count >= 50 ) {
			jQuery("#" + elem).prev().attr("class", "success-icon");
		} else if ( param_count < 50 && param_ex_count >= 50 ) {
			jQuery("#" + elem).prev().attr("class", "warning-icon");
		} else if ( param_count >= 50 && param_ex_count < 50 ) {
			jQuery("#" + elem).prev().attr("class", "warning-icon");
		} else if ( param_count < 50 && param_ex_count < 50 ) {
			jQuery("#" + elem).prev().attr("class", "danger-icon");
		}
	} else {
		jQuery("#" + elem).html(msg_fail);
	}
}
