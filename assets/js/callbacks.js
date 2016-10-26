/**
 * @/assets/callbacks.js
 * on: 03.10.2015
 * @since 2.3
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

//function to click to toggle accordion
function tog_compete_help( param ) {
	jQuery("#" + param + "HelpTrigger").click(function(){
		jQuery("#" + param + "Help").toggle(150);
		jQuery(this).toggleClass("button-primary");
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

//heading entry
function hd_entry( elem, param, entry ) {
	if ( entry.indexOf(elem) != -1 ) {
		filter_data( param, 1 );
	} else {
		 filter_data( param, 0 );
	}
}

//filter input data and fit into array
function filter_data_eq( push_in, param ) {
	if (param != undefined) {
		jQuery.map(param, function( i, v ) { push_in.push( parseFloat(i) ); });
	}
}

//function to output competative keyword
function comp_out( raw_param, elem, ok, up, down, roundit ) {

	var optimum = 0;

	if ( raw_param != undefined && raw_param != null && raw_param.length > 0 ) {
		var you_val = raw_param.slice(-1);
		jQuery("#You" + elem).html(you_val);
	} else {
		jQuery("#You" + elem).html("--");
	}

	//remove last element, which is client webpage
	param = raw_param.reverse().slice(1);
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
			if ( prange_min <= pmin ) {
				prange_min = pmin;
			} else if ( prange_min >= pmax ) {
				prange_min = pmax;
			}

			var prange_max = (pstd + avg).toFixed(roundit);
			if ( prange_max <= pmin ) {
				prange_max = pmin;
			} else if ( prange_max >= pmax ) {
				prange_max = pmax;
			}

			var prange = shorten_num( prange_min ) + " - " + shorten_num( prange_max );

			//show tick or cross based on your value
			if ( you_val < parseFloat(prange_min) ) {
				jQuery("#You" + elem).append(" " + down);
				optimum = -1;
			} else if ( you_val > parseFloat(prange_max) ) {
				jQuery("#You" + elem).append(" " + up);
				optimum = 1;
			} else {
				jQuery("#You" + elem).append(" " + ok);
				optimum = 1;
			}
			var pavg = avg.toFixed(0);

			var prange_max_percentage = ( ( ( prange_max - pmin ) / ( pmax - pmin ) ) * 100 ).toFixed(0);
			jQuery("#StartOpt" + elem).attr("style", "width: " + prange_max_percentage + "%; background: #8bba30;");

			var prange_min_percentage = ( ( prange_min - pmin ) / ( pmax - pmin ) * 100 ).toFixed(0);
			jQuery("#EndOpt" + elem).attr("style", "width: " + prange_min_percentage + "%; background: #ffffff;");

		} else {
			var pmax = param[0];
			var pmin = param[0];
			var pavg = param[0];
			var prange = param[0];
		}
	} else {
		var pmax = '--';
		var pmin = '--';
		var pavg = '--';
		var prange = "-- - --";
	}

	jQuery("#Max" + elem).html(shorten_num( pmax ));
	jQuery("#Min" + elem).html(shorten_num( pmin ));
	jQuery("#Avg" + elem).html(shorten_num( pavg ));
	jQuery("#Opt" + elem).html(prange);
	return optimum;
}


//social comparison out
function comp_social_each(raw_param) {
	if ( raw_param != undefined && raw_param != null ) {
		param = raw_param.reverse().slice(1);
		if ( param.length > 1 ) {
			var sum = param.reduce(function(a, b) { return a + b; });
		} else {
			var sum = param[0];
		}
	}
	return sum;
}

function comp_out_social( gp, fb, tw, elem_Gp, elem_Fb, elem_Tw, have ) {

	var fail = 0;

	if ( gp != undefined && gp != null && gp.length > 1 && fb != undefined && fb != null && fb.length > 1 && tw != undefined && tw != null && tw.length > 1 ) {
		gp_sum = comp_social_each(gp);
		fb_sum = comp_social_each(fb);
		tw_sum = comp_social_each(tw);
		all_sum = gp_sum + fb_sum + tw_sum;
		gp_per = ( ( gp_sum / all_sum ) * 100 ).toFixed(0);
		fb_per = ( ( fb_sum / all_sum ) * 100 ).toFixed(0);
		tw_per = ( ( tw_sum / all_sum ) * 100 ).toFixed(0);
		jQuery("#Total" + elem_Gp).html(shorten_num( gp_sum ));
		jQuery("#" + elem_Gp + " .alignright").html(gp_per + "%");
		jQuery("#" + elem_Gp + " .part-colors-fill").attr("style", "width:" + gp_per + "%; background: #6495ed;");
		jQuery("#Total" + elem_Fb).html(shorten_num( fb_sum ));
		jQuery("#" + elem_Fb + " .alignright").html(fb_per + "%");
		jQuery("#" + elem_Fb + " .part-colors-fill").attr("style", "width:" + fb_per + "%; background: #6495ed;");
		jQuery("#Total" + elem_Tw).html(shorten_num( tw_sum ));
		jQuery("#" + elem_Tw + " .alignright").html(tw_per + "%");
		jQuery("#" + elem_Tw + " .part-colors-fill").attr("style", "width:" + tw_per + "%; background: #6495ed;");
	} else {
		fail = 1;
	}

	if ( fail == 1 ) {
		jQuery("#Comp" + elem + " .alignright").html(have);
		jQuery("#Comp" + elem + " .part-colors-fill").attr("style", "width: 100%; background: #ff644d;");
	}
}

//function to show keyword by parts
function comp_key_out( raw_param, elem, ok, no, have ) {

	var fail = 0;
	var location_max = '';

	if ( raw_param != undefined && raw_param != null && raw_param.length > 1 ) {

		var key_you = raw_param.slice(-1);
		if ( key_you >= 1 ) {
			jQuery("#You" + elem).html(ok);
		} else {
			jQuery("#You" + elem).html("");
		}

		var param = raw_param.reverse().slice(1);
		var param_sum = 0;

		jQuery.each(param, function( index, value ) { if ( value >= 1 ) { param_sum += 1; } });

		if ( param != undefined && param != null && param.length > 0 ) {
			location_max = ( ( param_sum / param.length ) * 100 ).toFixed(0);
			jQuery("#Comp" + elem + " .alignright").html(location_max + "%");
			jQuery("#Comp" + elem + " .part-colors-fill").attr("style", "width:" + location_max + "%; background: #6495ed;");
			if ( location_max == 0 ) {
				jQuery("#Comp" + elem + " .part-colors-fill").attr("style", "width: 3px; background: #6495ed;");
			}
		} else {
			fail = 1;
		}
	} else {
		fail = 1;
	}

	if ( fail == 1 ) {
		jQuery("#Comp" + elem + " .alignright").html(have);
		jQuery("#Comp" + elem + " .part-colors-fill").attr("style", "width: 100%; background: #ff644d;");
	}
}

//function to output competative keyword
function comp_out_speed( raw_param, elem, have ) {

	var fail = 0;

	if ( raw_param != undefined && raw_param != null && raw_param.length > 0 ) {

		var param = raw_param.reverse().slice(1);

		if ( param != undefined && param != null && param.length > 0 ) {

			var param_max = Math.max.apply(Math, param);

			if ( param.length > 1 ) {
				var sum = param.reduce(function(a, b) { return a + b; });
			} else {
				var sum = param[0];
			}
			var avg = sum / param.length;

			jQuery("#Avg" + elem).html(avg.toFixed(3));

			location_max = 100 - ( ( avg / param_max ) * 100 ).toFixed(0);

			jQuery("#Comp" + elem + " .alignright").html(location_max + "%");
			jQuery("#Comp" + elem + " .part-colors-fill").attr("style", "width:" + location_max + "%; background: #6495ed;");
			if ( location_max == 0 ) {
				jQuery("#Comp" + elem + " .part-colors-fill").attr("style", "width: 3px; background: #6495ed;");
			}
		} else {
			fail = 1;
		}
	} else {
		fail = 1;
	}

	if ( fail == 1 ) {
		jQuery("#Comp" + elem + " .alignright").html(have);
		jQuery("#Comp" + elem + " .part-colors-fill").attr("style", "width: 100%; background: #ff644d;");
	}
}
