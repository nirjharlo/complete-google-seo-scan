<?php
/**
 * @/core/lib/error-object.php
 * on: 09.06.2015
 * Object to check headers response if it's showing temporary and permanent errors of server.
 *
 * 1 property:
 * $header_respond for first element of total header.
 */
class CGSS_HEADERS_ERROR {

	//declare input property
	private $header_respond;

	//construct the object
	public function __construct( $header_respond ) {
		$this->header_respond = $header_respond;
	}

	// Stop the script if it's a Network Error or a Server Haedware Error.
	public function check() {
		$err = false;
		foreach ( $this->response_list() as $val ) {
			if ( strpos( $this->header_respond, $val ) !== false ) {
				$err = 'ok';
				return $err;
			}
		}
		if ( ! $err ) {
			return false;
		}
	}

	//A method describing server response lists taken from
	// http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	public function response_list() {
		return array (
			'400 Bad Request',
			'401 Unauthorized',
			'402 Payment Required',
			'403 Forbidden',
			'404 Not Found',
			'405 Method Not Allowed',
			'406 Not Acceptable',
			'407 Proxy Authentication Required',
			'408 Request Timeout',
			'409 Conflict',
			'410 Gone',
			'411 Length Required',
			'412 Precondition Failed',
			'413 Request Entity Too Large',
			'414 Request-URI Too Long',
			'415 Unsupported Media Type',
			'416 Requested Range Not Satisfiable',
			'417 Expectation Failed',
			'418 I\'m a teapot',
			'419 Authentication Timeout',
			'420 Method Failure',
			'420 Enhance Your Calm',
			'422 Unprocessable Entity',
			'423 Locked',
			'424 Failed Dependency',
			'426 Upgrade Required',
			'428 Precondition Required',
			'429 Too Many Requests',
			'431 Request Header Fields Too Large',
			'440 Login Timeout',
			'444 No Response',
			'449 Retry With',
			'450 Blocked by Windows Parental Controls',
			'451 Unavailable For Legal Reasons',
			'451 Redirect',
			'494 Request Header Too Large',
			'495 Cert Error',
			'496 No Cert',
			'497 HTTP to HTTPS',
			'498 Token expired/invalid',
			'499 Client Closed Request',
			'499 Token required',
			'500 Internal Server Error',
			'501 Not Implemented',
			'502 Bad Gateway',
			'503 Service Unavailable',
			'504 Gateway Timeout',
			'505 HTTP Version Not Supported',
			'506 Variant Also Negotiates',
			'507 Insufficient Storage',
			'508 Loop Detected',
			'509 Bandwidth Limit Exceeded',
			'510 Not Extended',
			'511 Network Authentication Required',
			'598 Network read timeout error',
			'599 Network connect timeout error',
		);
	}
}
?>
