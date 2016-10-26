<?php
/**
 * @/core/lib/image-object.php
 * on: 03.07.2015
 * @since 2.1
 *
 * A dependent object, to fetch and output social tags. OGP and Twitter V card data.
 *
 * 1 property.
 * @prop array $dom object
 */
class CGSS_SOCIAL_TAGS {

	//define properties
	private $dom;

	//construct the object
	public function __construct( $dom ) {
		$this->dom = $dom;
	}

	//output OGP data
	public function ogp() {
		$social_tags = $this->ogp_tags();
		$tags = $this->fetch( $social_tags );
		$social_tags = array(
							array( 'title', 'txt', 'og:title' ),
							array( 'desc', 'txt', 'og:description' ),
							array( 'url', 'url', 'og:url' ),
							array( 'img', 'url', 'og:image' ),
						);
		$ogp = array();
		foreach ( $social_tags as $each ) {
			$val = $each[0];
			if ( $each[1] == 'txt' ) {
				$ogp[$val] = $this->text( $tags, $each[2] );
			} elseif ( $each[1] == 'url' ) {
				$ogp[$val] = $this->url( $tags, $each[2] );
			}
		}
		return $ogp;
	}

	//output twitter card data
	public function tv() {
		$social_tags = $this->tv_tags();
		$tags = $this->fetch( $social_tags );
		$ogp = array();
		foreach ( $social_tags as $each ) {
			$val = $each['val'];
			$ogp[$val] = $this->text( $tags, $val );
		}
		return $ogp;
	}
	

	//output text list
	public function text( $input, $val ) {
		$input_val = $input[$val];
		if ( $input_val ) {
			$extract_val = $input_val[count($input_val) - 1];
			$output = sanitize_text_field( $extract_val );
		} else {
			$output = false;
		}
		return $output;
	}

	//output url list
	public function url( $input, $val ) {
		$input_val = $input[$val];
		if ( $input_val ) {
			$extract_val = $input_val[count($input_val) - 1];
			$output = esc_url_raw( $extract_val );
		} else {
			$output = false;
		}
		return $output;
	}

	//fetch tag
	public function fetch( $tags ) {
		$stag = array();
		foreach( $tags as $each ) {
			$get_stag = new CGSS_FETCH( $this->dom, 'meta', $each, null );
			$key = $each['val'];
			$stag[$key] = $get_stag->tag();
		}
		return $stag;
	}

	//OGP tags list
	public function ogp_tags() {
		return array(
			array(
				'att' => 'property',
				'val' => 'og:title',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:description',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:url',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:image',
				'get_att' => 'content',
			),
        );
	}

	//Twitter Vcard list
	public function tv_tags() {
		return array(
			array(
				'att' => 'property',
				'val' => 'og:title',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:description',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:url',
				'get_att' => 'content',
			),
            array(
				'att' => 'property',
				'val' => 'og:image',
				'get_att' => 'content',
			),
        );
	}
}
?>
