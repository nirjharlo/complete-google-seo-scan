<?php
/**
 * Download and analyze .css or .js files, based on an url.
 *
 * 2 properties:
 * @property array $css_url Input CSS urls
 * @property array $js_url Input JS urls
 */

class CGSS_DESIGN {


	public $css_url;
	public $js_url;


	//analyze those urls and their content
	public function analyze_js() {

		$num = 0;
		$size = 0;
		$compress_num = 0;
		$compress_size = 0;

		if ( $this->js_url ) {

			$num = count($this->js_url);

			//get files and check their bodies
			foreach ( $this->js_url as $val ) {
				$body = @file_get_contents( $val, FILE_USE_INCLUDE_PATH );
				if ($body) {
					$pre_size = mb_strlen( $body, '8bit' );
					$size += round( ( $pre_size / 1024 ), 0 );
					$pure_body = filter_var( $body, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW );
					$pure_size = mb_strlen( $pure_body, '8bit' );
					$percent_comp = 1 - ( $pure_size / $pre_size );
					if ( $percent_comp > 0.05 ) {
						$compress_num += 1;
						$compress_size += round( ( ( $pre_size - $pure_size ) / 1024 ), 0 );
					}
				}
			}
		}
		return array(
					'count' => $num,
					'size' => $size,
					'compress_num' => $compress_num,
					'compress_size' => $compress_size,
				);
	}


	//analyze those urls and their content
	public function analyze_css() {

		$num = 0;
		$size = 0;
		$import = 0;
		$media = 0;
		$compress_num = 0;
		$compress_size = 0;

		if ( $this->css_url ) {

			$num = count($this->css_url);

			//get files and check their bodies
			foreach ( $this->css_url as $val ) {
				$body = @file_get_contents( $val, FILE_USE_INCLUDE_PATH );
				if ($body) {
					$pre_size = mb_strlen( $body, '8bit' );
					$size += round( ( $pre_size / 1024 ), 0 );
					if ( strpos( $val, '.css' ) !== false ) {
						$import += substr_count( $body, '@import' );
						$media += substr_count( $body, '@media' );
					}
					$pure_body = filter_var( $body, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW );
					$pure_size = mb_strlen( $pure_body, '8bit' );
					$percent_comp = 1 - ( $pure_size / $pre_size );
					if ( $percent_comp > 0.05 ) {
						$compress_num += 1;
						$compress_size += round( ( ( $pre_size - $pure_size ) / 1024 ), 0 );
					}
				}
			}
		}
		return array(
					'count' => $num,
					'size' => $size,
					'import' => $import,
					'media' => $media,
					'compress_num' => $compress_num,
					'compress_size' => $compress_size,
				);
	}
} ?>