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
