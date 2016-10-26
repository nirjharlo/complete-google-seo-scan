<?php
/**
 * @/core/lib/design-object.php
 * on: 10.08.2015
 * @since 2.1
 *
 * Donload and analyze content, based on an array of url. Used for .css or .js files. Depends
 * on lib/error-object.php
 *
 * 1 property:
 * @prop array $arr Input url
 */

class CGSS_DESIGN {

	//Input property
	public $arr;

	//construct the object
	public function __construct( $arr ) {
		$this->arr = $arr;
	}

	//analyze those urls and their content
	public function analyze() {

		$num = 0;
		$size = 0;
		$import = 0;
		$media = 0;
		$compress_num = 0;
		$compress_size = 0;

		if ( $this->arr ) {

			//check headers and create new array, if files exists
			foreach ( $this->arr as $val ) {

				if ( $val != null ) {
					$header = get_headers( $val, 1 );
					$hresponse = $header[0];

					//Check if headers are ok or not
					$err = new CGSS_HEADERS_ERROR( $hresponse );
					$head_check = $err->check();
					if ( $head_check ) {
						$del_arr = array_diff( $this->arr, array( $val ) );
						$this->arr = array_values( $del_arr );
					}
				}
				$num += 1;
			}

			//get files and check their bodies
			foreach ( $this->arr as $val ) {
				$body = file_get_contents( $val, FILE_USE_INCLUDE_PATH );
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
		return array(
					'num' => $num,
					'size' => $size,
					'import' => $import,
					'media' => $media,
					'compress_num' => $compress_num,
					'compress_size' => $compress_size,
				);
	}
}
?>
