<?php
/**
 * Get server features from header, taken from the url.
 *
 * 1 property:
 * @prop array $header Input header, whose social value is to be counted
 */
class CGSS_SERVER {


	public $header;


	//check cache
	public function cache() {

		$cache = 0;
		if ( is_array( $this->header ) ) {
			if ( array_key_exists( "Cache-Control", $this->header ) ) {
				$cache_val = $this->header["Cache-Control"];
				if ( $cache_val ) {
					$cache = 1;
				}
			}
		}
		return $cache;
	}


	//check if modified since
	public function if_mod() {

		$if_mod = 0;
		if ( is_array( $this->header ) ) {
			if ( array_key_exists( "Last-Modified", $this->header ) ) {
				$if_mod = 1;
			}
		}
		return $if_mod;
	}


	//check keep alive
	public function alive() {

		$alive = 0;
		if ( is_array( $this->header ) ) {
			if ( array_key_exists( "Connection", $this->header ) ) {
				$connection = $this->header["Connection"];
				if ( $connection == "Keep-Alive" ) {
					$alive = 1;
				}
			}
		}
		return $alive;
	}


	//check gZip compression
	public function gzip() {

		$gzip = 0;
		$encode = $_SERVER['HTTP_ACCEPT_ENCODING'];
		if ( substr( $encode, 0, 4 ) == 'gzip' ) {
			$gzip = 1;
		}
		return $gzip;
	}
}
?>
