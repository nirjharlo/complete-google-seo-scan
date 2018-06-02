<?php
/**
 * Object to fetch social meia information from various networks. Twitter, Google Plus and Facebook
 * from an input url.
 *
 * 1 property:
 * @prop string $url Input url, whose social value is to be counted
 */
class CGSS_SOCIAL {


	public $url;
	

	//Google Plus data for g+ counts
	public function gplus() {
		$share = false;
		$url_encode = urlencode( $this->url );
		$plus_data =  file_get_contents( "https://plusone.google.com/_/+1/fastbutton?url=" . $url_encode );
		if ( ! empty( $plus_data ) ) {
			$plus_doc = new DOMDocument();
			$plus_doc->loadHTML($plus_data);
			$plus_counter = $plus_doc->getElementById('aggregateCount');
			if (is_object($plus_counter)) {
				if ( $plus_counter->nodeValue ) {
					$share = $plus_counter->nodeValue;
				}
			}
		}
		return $share;
	}

	//Facebook data for likes and shares
	public function fb() {
		$share = false;
		$fb_data = file_get_contents( 'http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls=' . $this->url );
		if ( ! empty( $fb_data ) ) {
			$fb_shares = json_decode( $fb_data, true );
			if ( array_key_exists ( 0, $fb_shares ) ) {
				$fb_shares_act = $fb_shares[0];
				if ( is_array( $fb_shares_act ) and array_key_exists ( 'share_count', $fb_shares_act ) and array_key_exists ( 'like_count', $fb_shares_act ) ) {
					$share = array(
						'share' => $fb_shares_act['share_count'],
						'like' => $fb_shares_act['like_count'],
					);
				}
			}
		}
		return $share;
	}
}
?>
