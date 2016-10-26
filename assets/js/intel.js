/**
 * @/assets/intel.js
 * on: 14.08.2015
 * @since 2.1
 *
 * Intel seo scan analysis function. To be used intel functions are described by their names.
 */

function filter_intel_data( push_in, param ) {
	if (param != undefined) {
		push_in.push(param);
	}
}

function show_total_shares( arr, element ) {
	var count = 0;
	jQuery.each( arr, function() { count += +this; });
	jQuery(element).html(shorten_num( count ));
}

function sum_arr( arr ) {
	var sum = 0;
	jQuery.each( arr, function() { sum += +this; });
	return sum;
}

function words_intel( arr, arr_ratio, element, element_num, element_per, element_ratio, msg ) {
	jQuery(element).html(msg);
	jQuery(element_num).html(shorten_num( sum_arr( arr ) ));
	var chk = ( sum_arr( arr ) / arr.length ).toFixed(0);
	jQuery(element_per).html(chk);
	if ( chk <= 200 ) {
		jQuery(element_per).html(chk.replace(chk, '<span class="danger-icon">' + chk + '<span>'));
	}
	var chk_ratio = ( ( sum_arr( arr_ratio ) / arr_ratio.length ) ).toFixed(0);
	if ( chk_ratio <= 15 ) {
		jQuery(element_ratio).html(chk_ratio.replace(chk_ratio, '<span class="danger-icon">' + chk_ratio + '%<span>'));
	} else if ( chk_ratio >= 70 ) {
		jQuery(element_ratio).html(chk_ratio.replace(chk_ratio, '<span class="danger-icon">' + chk_ratio + '%<span>'));
	} else {
		jQuery(element_ratio).html(chk_ratio + '%');
	}
}

function links_intel( arr, arr_ext, arr_img, element, element_num, element_ext, element_img, msg ) {
	var chk = ( sum_arr( arr ) / arr.length ).toFixed(0);
	var chk_ext = ( ( sum_arr( arr_ext ) / sum_arr( arr ) ) * 10 ).toFixed(0);
	var chk_img = ( ( sum_arr( arr_img ) / sum_arr( arr ) ) * 10 ).toFixed(0);
		jQuery(element).html(msg);
	if ( chk != undefined ) {
		jQuery(element_num).html(chk);
		if ( chk > 100 ) {
			jQuery(element_num).html(chk.replace(chk, '<span class="danger-icon">' + chk + '<span>'));
		}
	}
	if ( chk_ext != undefined ) {
		if ( chk_ext == 0 ) {
			jQuery(element_ext).html(0);
		} else {
			jQuery(element_ext).html(chk_ext * 10 + "%");
		}
	}
	if ( chk_img != undefined ) {
		if ( chk_img == 0 ) {
			jQuery(element_img).html(0);
		} else {
			jQuery(element_img).html('<span class="danger-icon">' + chk_img * 10 + '%<span>');
		}
	}
}

function keyword_intel( arr, element, element_size, element_per, msg ) {
	var chk = ( ( ( jQuery.grep( arr, function( val ) { return val != false && val != undefined && val.indexOf(" ") != -1 }).length ) / arr.length ) * 10).toFixed(0);
	var chk_size = [];
	jQuery.each( arr, function (index, val) {
		if ( val != false && val != undefined && val.indexOf(" ") != -1 ) {
			key_parts = val.indexOf(" ");
			if ( key_parts != undefined ) {
				chk_size.push(key_parts);
			}
		}
	});
	jQuery(element).html(msg);
	if ( chk != undefined ) {
		jQuery(element_size).html((sum_arr( chk_size ) / chk_size.length).toFixed(0));
		jQuery(element_per).html(chk * 10 + "%");
		
	}
}

function image_intel( arr, element, element_show, ok, no, mid ) {
	var chk = ( ( ( jQuery.grep( arr, function( val ) { return val != 0 }).length ) / arr.length ) * 10).toFixed(0);
	if ( chk != undefined ) {
		if ( chk < 0.5 ) {
			jQuery(element).html(no);
		} else if ( chk == 10 ) {
			jQuery(element).html(no);
		} else {
			jQuery(element).html(mid);
			jQuery(element_show).html(" " + chk * 10 + "%");
		}
	}
}

function mobile_intel( arr, element, element_show, ok, no, mid ) {
	var chk = ( ( ( jQuery.grep( arr, function( val ) { return val == 0 }).length ) / arr.length ) * 10).toFixed(0);
	if ( chk != undefined ) {
		if ( chk == 0 || chk < 0.5 ) {
			jQuery(element).html(ok);
		} else if ( chk == 10 ) {
			jQuery(element).html(no);
		} else {
			jQuery(element).html(mid);
			jQuery(element_show).html(" (" + chk * 10 + "%)");
		}
	}
}

function url_intel( arr, arr_two, element, element_show, element_show_two, ok, no, mid, dynamic, underscore ) {
	var chk = ( ( ( jQuery.grep( arr, function( val ) { return val == 1 }).length ) / arr.length ) * 10).toFixed(0);
	var chk_two = ( ( ( jQuery.grep( arr_two, function( val ) { return val == 1 }).length ) / arr_two.length ) * 10).toFixed(0);
	if ( chk != undefined ) {
		if ( chk == 10 && chk_two == 10 ) {
			jQuery(element).html(no);
		} else if ( chk == 0 && chk_two == 0 ) {
			jQuery(element).html(ok);
		} else if ( chk <= 10 && chk_two == 0 ) {
			jQuery(element).html(dynamic);
			jQuery(element_show).html(" (" + chk * 10 + "%) ");
		} else if ( chk == 0 && chk_two <= 10 ) {
			jQuery(element).html(underscore);
			jQuery(element_show_two).html(" (" + chk_two * 10 + "%) ");
		} else if ( chk > 0 && chk_two > 0 ) {
			jQuery(element).html(mid);
			jQuery(element_show).html(" (" + chk * 10 + "%) ");
			jQuery(element_show_two).html(" (" + chk_two * 10 + "%) ");
		}
	}
}

function time_intel( arr, element, ok, no, mid, too ) {
	var avg = ( sum_arr( arr ) / arr.length ).toFixed(0);
	if ( avg != undefined ) {
		if ( avg <= 1000 ) {
			jQuery(element).html(ok);
		} else if ( avg <= 3000 ) {
			jQuery(element).html(mid);
		} else if ( avg <= 5000 ) {
			jQuery(element).html(no);
		} else {
			jQuery(element).html(too);
		}
	}
}

function stag_intel( arr, element, element_show, ok, no, mid ) {
	var chk = ( ( ( jQuery.grep( arr, function( val ) { return val < 4 }).length ) / arr.length ) * 10).toFixed(0);
	if ( chk != undefined ) {
		if ( chk == 0 || chk < 0.5 ) {
			jQuery(element).html(ok);
		} else if ( chk > 9 ) {
			jQuery(element).html(no);
		} else {
			jQuery(element).html(mid);
			jQuery(element_show).html(chk * 10);
		}
	}
}
