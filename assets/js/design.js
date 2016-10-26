/**
 * @/assets/design.js
 * on: 08.08.2015
 * @since 2.1
 *
 * design seo scan success function
 */

function design_success( data, ok, no ) {

	var css_import = data.css_import;
	var req_num = data.css_num + data.js_num + data.css_import;
	var size = data.css_size + data.js_size;
	var compress_size = data.css_compress_size + data.js_compress_size;
	var compression = compress_size / size;

	jQuery("#JsNum").text(data.css_num);
	jQuery("#CssNum").text(data.js_num);
	jQuery("#CssImportNum").text(css_import);
	jQuery("#CssSize").text(data.css_size);
	jQuery("#JsSize").text(data.js_size);
	jQuery("#CompressJsNum").text(data.js_compress_num);
	jQuery("#CompressCssNum").text(data.css_compress_num);
	jQuery("#CompressSize").text(compress_size);

	jQuery("#TotalDesignSeoNum").text(req_num);
	jQuery("#TotalDesignSeosize").text(data.css_size + data.js_size);

	if ( css_import > 0 || req_num > 20 ) {
		jQuery("#DesReqNumIcon").html(no);
	} else {
		jQuery("#DesReqNumIcon").html(ok);
	}

	if ( compression > 0.1 ) {
		jQuery("#DesResSizeIcon").html(no);
	} else {
		jQuery("#DesResSizeIcon").html(ok);
	}
}
