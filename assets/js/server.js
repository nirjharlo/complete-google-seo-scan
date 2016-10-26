/**
 * @/assets/server.js
 * on: 08.08.2015
 * @since 2.1
 *
 * server seo scan success function
 */

function server_success( data, ok, no ) {

	//display data
	if ( data.ping == 'ok' ) {

		if ( data.ssl && data.ssl == 1 ) {
			jQuery("#SSLChk").html(ok);
		} else {
			jQuery("#SSLChk").html(no);
		}

		if ( data.www && data.www == 1 ) {
			jQuery("#WWWChk").html(ok);
		} else {
			jQuery("#WWWChk").html(no);
		}

		if ( data.ip && data.ip != 0 ) {
			jQuery("#IPChk").html(ok);
		} else {
			jQuery("#IPChk").html(no);
		}

		if ( data.gzip && data.gzip == 1 ) {
			jQuery("#GzipChk").html(ok);
		} else {
			jQuery("#GzipChk").html(no);
		}

		if ( data.cache && data.cache == 1 ) {
			jQuery("#CacheChk").html(ok);
		} else {
			jQuery("#CacheChk").html(no);
		}

		if ( data.if_mod && data.if_mod == 1 ) {
			jQuery("#IfModChk").html(ok);
		} else {
			jQuery("#IfModChk").html(no);
		}

		if ( data.time && data.time == 1 ) {
			jQuery("#ResTChk").html(ok);
		} else {
			jQuery("#ResTChk").html(no);
		}

		if ( data.ip && data.ip != 0 ) {
			jQuery("#IpVal").html(": " + data.ip);
		}

		if ( data.time_val ) {
			jQuery("#ResVal").html(": " + data.time_val);
		}
	}
}
