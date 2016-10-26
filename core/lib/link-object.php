<?php
/**
 * @/core/lib/image-object.php
 * on: 19.06.2015
 * @since 2.0
 *
 * An object for calculating link numbers with specific values. First, format links for hrefs. Then
 * pass them through foreach loops for and prepare the output.
 *
 * 3 properties.
 * @prop string $domain domain name
 * @prop array $links link attributes (hrefs and rels)
 * @prop array $anchors array of link anchor texts
 */
class CGSS_FORMAT_LINKS {

	//define properties
	private $domain;
	private $links;
	private $anchors;

	//construct the object
	public function __construct( $domain, $links, $anchors ) {
		$this->domain = $domain;
		$this->links = $links;
		$this->anchors = $anchors;
	}

	//Extract links with internal domain name
	public function count() {
		$new_links = $this->format();
		return $new_links['num'];
	}

	//Extract links with internal domain name
	public function internal() {
		$new_links = $this->format();
		$hrefs = $new_links['href'];
		$internal_links = 0;
		foreach( $hrefs as $key ) {
			$link_url = new CGSS_URL( $key, false );
			$link_domain = $link_url->domain();
			$input_domain = $this->domain;
			if ( $link_domain != true or strpos( $link_domain, $input_domain ) !== false or strpos( $input_domain, $link_domain ) !== false ) {
				$internal_links = $internal_links + 1;
			}
		}
		return $internal_links;
	}

	//Extract links with nofollow attributes
	public function nofollow() {
		$new_links = $this->format();
		$count_rel = array_count_values ( $new_links['rel'] );
		$nofollow_links = 0;
		foreach ( $count_rel as $key => $val ) {
			if ( strpos( $key, 'nofollow' ) !== false ) {
				$nofollow_links = $nofollow_links + $val;
			}
		}
		return $nofollow_links;
	}

	//Extract links with no text
	public function no_text() {
		$no_txt_links = 0;
		$anchors = $this->anchors();
		foreach ( $anchors as $val ) {
			if ( ! $val ) {
				$no_txt_links = $no_txt_links + 1;
			}
		}
		return $no_txt_links;
	}

	//output formatted anchor texts
	public function anchors() {
		$new_links = $this->format();
		$anch = array();

		if ( $new_links['anchor'] ) {
			foreach ( $new_links['anchor'] as $val ) {
				$anch[] = filter_var( $val, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW );
			}
		}
		return $anch;
	}

	//Create array of link rels, hrefs and anchors with original links in href
	public function format() {
		$hrefs = $this->links['href'];
		$rels = $this->links['rel'];
		$anchors = $this->anchors;

		//remove # hrefs, used in javascript
		foreach( $hrefs as $key ) {
			if ( substr( $key, 0, 1 ) == '#' ) {
				$pos = array_search( $key, $hrefs );
				$del_hrefs = array_diff( $hrefs, array( $key ) );
				$hrefs = array_values( $del_hrefs );
				if ( $pos ) {
					if ( is_array( $rels ) and array_key_exists( $pos, $rels ) ) {
						$del_rels = array_diff( $rels, array( $rels[$pos] ) );
						$rels = array_values( $del_rels );
					}
					if ( is_array( $anchors ) and array_key_exists( $pos, $anchors ) ) {
						$del_anch = array_diff( $anchors, array( $anchors[$pos] ) );
						$anchors = array_values( $del_anch );
					}
				}
			}
		}
		return array(
					'href' => $hrefs,
					'rel' => $rels,
					'anchor' => $anchors,
					'num' => count( $hrefs ),
				);
	}
}
?>
