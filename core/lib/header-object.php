<?php
/**
 * @/core/lib/server-object.php
 * on: 10.08.2015
 * @since 2.1
 *
 * Get server details from header. It's a dependent object, depends on lib/url-object.php and
 * lib/server-object.php
 *
 * 3 properties:
 * @prop string $url Input url
 * @prop string $time_start START timing of header download
 * @prop string $time_end END timing of header download
 */

//Check headers
class CGSS_HEADER_CHK {

	//Input property
	private $url;
	private $header;
	private $time_start;
	private $time_end;

	//construct the object
	public function __construct( $url, $header, $time_start, $time_end ) {
		$this->url = $url;
		$this->header = $header;
		$this->time_start = $time_start;
		$this->time_end = $time_end;
	}

	//Call url object and see ssl
	public function analyze() {

		$cut_url = new CGSS_URL( $this->url, false );
		$ssl = $cut_url->get_ssl();
		$domain = $cut_url->domain();

		//Create domain to check
		if ( substr_count( $www_url, '://www.' ) > 0 ) {
			$chk_domain = $domain;
		} else {
			$chk_domain = "www." . $domain;
		}

		$www = 0;

		//Get header response with new check domain
		$make_url = new CGSS_URL( $chk_domain, $ssl );
		$make_domain = $make_url->put_ssl();
		$new_header = get_headers( $make_domain, 1 );
		$new_header_respond = $new_header[0];

		//Check the domain www resolved
		if ( strpos( $new_header_respond, "301 Moved Permanently" ) !== false ) {

			// if redirecting to new url, get the url
			if ( array_key_exists( "Location", $new_header ) ) {
				if ( is_array ( $new_header["Location"] ) ) {
					$loc = $new_header["Location"];
					$new_url = $loc[count($loc) - 1];
				} else {
					$new_url = $new_header["Location"];
				}
			}

			//match new and old domain
			$new_cut_url = new CGSS_URL( $new_url, false );
			$new_domain = $new_cut_url->domain();
			if ( $new_domain == $domain ) {
				$www = 1;
			}
		}

		//get info from url
		$ip = gethostbyname( $domain );
		if ( ! $ip ) {
			$ip = 0;
		}
		return array(
					'ssl' => $ssl,
					'www' => $www,
					'ip' => $ip,
				);
	}

	//output result
	public function result() {

		//Call server object and find features
		$cut_header = new CGSS_SERVER( $this->header );
		$gzip = $cut_header->gzip();
		$cache = $cut_header->cache();
		$if_mod = $cut_header->if_mod();
		$alive = $cut_header->alive();
		$time = 0;
		$response = round( ( $this->time_end - $this->time_start ), 3 ) * 1000;
		if ( $response < 1000 ) {
			$time = 1;
		}
		$analyze = $this->analyze();
		return array(
					'ping' => 'ok',
					'ssl' => $analyze['ssl'],
					'www' => $analyze['www'],
					'ip' => $analyze['ip'],
					'gzip' => $gzip,
					'cache' => $cache,
					'if_mod' => $if_mod,
					'alive' => $alive,
					'time' => $time,
					'time_val' => $response,
				);
	}
}
?>
