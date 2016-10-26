<?php
/**
 * @/core/lib/url-object.php
 * on: 19.06.2015
 * @since 2.0
 *
 * Get domain name from the url. For example from url http://www.gogretel.com/media-markup/
 * get www.gogretel.com only.
 *
 * @method 1 break and remove http:// or https://. Then, remove trailing part to get only domain
 * @method 2 puts an https:// infront of url, conditionally
 *
 * 2 properties:
 * @prop string $url Input url, whose social value is to be counted
 * @prop string $ssl Condition of ssl security or https
 */
class CGSS_URL {

	//Input property
	private $url;
	private $ssl;

	//construct the object
	public function __construct( $url, $ssl ) {
		$this->url = $url;
		$this->ssl = $ssl;
	}

	//extract the domain from an url with or without long tails, ssl. Output only  domain name
	// without www. Includes subdomains (if any).
	public function domain() {
		$found = false;
		if ( strpos( $this->url, '://' ) !== false ) {
			$this->part_url = explode( '://', $this->url );
			$this->nohttp_url = $this->part_url[1];
		} else {
			$this->nohttp_url = $this->url;
		}
		if ( strpos( $this->nohttp_url, '/' ) !== false ) {
			$this->get_domain = explode( '/', $this->nohttp_url );
			$this->domain = $this->get_domain[0];
		} else {
			$this->domain = $this->nohttp_url;
		}
		if ( strpos( $this->domain, 'www.' ) !== false ) {
			$this->no_www_domain = explode( 'www.', $this->domain );
			$found = $this->no_www_domain[1];
		} else {
			$found = $this->domain;
		}
		return $found;
	}

	//Create a url with http:// or https:// depending on another parameter (basically ssl)
	public function put_ssl() {
		if ( $this->ssl == 1 ) {
			return "https://" . $this->url;
		} else {
			return "http://" . $this->url;
		}
	}

	//Create a url with http:// or https:// depending on another parameter (basically ssl)
	public function get_ssl() {
		if ( substr_count( $this->url, 'https://' ) > 0 ) {
			return 1;
		} else {
			return 0;
		}
	}

	//see if the url is dynamic or not
	public function dynamic() {
		if ( substr_count( $this->url, '?' ) > 0 ) {
			return 1;
		} else {
			return 0;
		}
	}

	//see if the url is dynamic or not
	public function underscore() {
		if ( substr_count( $this->url, '_' ) > 0 ) {
			return 1;
		} else {
			return 0;
		}
	}
}
?>
