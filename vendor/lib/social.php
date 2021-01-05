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

	//Facebook data for likes and shares
	public function fb() {

		$share = false;
		$fb_data = @file_get_contents( 'http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls=' . $this->url );
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
		if (!$share) {
			$share = 0;
		}
		return $share;
	}
}
?>
