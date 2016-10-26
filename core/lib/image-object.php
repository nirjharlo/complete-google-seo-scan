<?php
/**
 * @/core/lib/image-object.php
 * on: 11.06.2015
 * A dependent object, which uses CGSS_URL class, to format the image array delivered from
 * CGSS_FETCH. It will deliver an image array with elements having src, width, height and alt values.
 *
 * 2 properties.
 * $img_details for input image array
 * $ssl for determining http or https
 */
class CGSS_FORMAT_IMAGES {

	//define properties
	private $img_details;
	private $ssl;

	//construct the object
	public function __construct( $img_details, $ssl ) {
		$this->img_details = $img_details;
		$this->ssl = $ssl;
	}

	//output the manufatured images array
	public function output() {
		$width = $this->img_details['width'];
		$height = $this->img_details['height'];
		$alt = $this->img_details['alt'];
		$images_out = array();
		$key = 0;
		foreach( $this->alter() as $val ) {
			$images_out[$key] = array(
									'src' => $val,
									'width' => $width[$key],
									'height' => $height[$key],
									'alt' => $alt[$key],
								);
			$key = ( $key + 1 );
		}
		return $images_out;
	}

	//Alter url values in images src array, if domain name is not found in them.
	public function alter() {
		$key = 0;
		$image_new_src = array();
		foreach( $this->img_details['src'] as $val ) {
			if ( $val ) {
				$img_url = new CGSS_URL( $val, false );
				if( ! $img_url->domain() ) {
					$new_val = new CGSS_URL( $img_url->domain() . $val, $this->ssl );
					$image_new_src[] = $new_val->put_ssl();
				} else {
					$image_new_src[] = $val;
				}
				$key = $key + 1;
			} else {
				$image_new_src[] = '';
			}
		}

		//use this function for replacing with new array
		return array_replace( $this->img_details['src'], $image_new_src );
	}
}
?>
