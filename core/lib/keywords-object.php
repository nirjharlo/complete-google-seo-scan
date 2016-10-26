<?php
/**
 * @/core/lib/keywords-object.php
 * on: 13.06.2015
 * An object to extract most used pharases in an array of phrases, containing one or more words.
 * But, for now we are using one word elements only.
 *
 * 1 properties.
 * $words for input words array
 */
class CGSS_KEYWORDS {

	//define properties
	private $words;

	//construct the object
	public function __construct( $words ) {
		$this->words = $words;
	}

	//generate individual words from text content
	public function output() {

		//
		$key_count = array();
		foreach( $this->prepare() as $string ) {
			$key_num = $string[0];
			$key_count[$key_num] = array();
			$key_count_ind = $key_count[$key_num];
			foreach( $string[1] as $key ) {
				$key_count_ind[$key] = 0;
				foreach( $string[1] as $val ) {
					if ( $key == $val ) {
						$key_count_ind[$key] = ( $key_count_ind[$key] + 1 );
					}
				}
			}
			if ( $key_num == '1' ) {
				foreach( $this->stop_word() as $stop ) {
					if ( array_key_exists( $stop, $key_count_ind ) ) {
						unset( $key_count_ind[$stop] );
					}
				}
			}
			$key_count[$key_num] = $key_count_ind;
			arsort( $key_count[$key_num] );
			$new_array = array();
			$count = 0;
			foreach( $key_count[$key_num] as $key => $val ) {
				$new_array[] = array( $key => $val );
				if ( $count == 5 ) {
					break;
				}
				$count = ( $count + 1 );
			}
			$keys[$key_num] = $new_array;

			foreach ( $keys[$key_num] as $key => $val ) {
				if ( array_pop( $val ) < 3 ) {
					unset( $keys[$key_num][$key] );
				}
			}
		}
		return $keys;
	}

	//generate individual words from text content
	public function prepare() {
		$array = array( 1 => '' );
		$array_one = $array[1];
		$words = $this->words;
		if ( $words ) {
			foreach( $words as $key ) {
				if ( strlen( $key ) > 3 ) {
					$array_one[] = trim( $key );
				}
			}
		}
		return array(
					array( '1', $array_one ),
				);
	}

	//array of stop words
	public function stop_word() {
		return array( 'i', 'a', 'about', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'com', 'de', 'en', 'for', 'from', 'how', 'in', 'is', 'it', 'la', 'of', 'on', 'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'where', 'who', 'will', 'with', 'und', 'the', 'www', 'have', 'they', 'just', 'your', 'most', 'their', 'some', 'there', 'them', 'make', 'ever', 'never', 'enough', 'should', 'would', 'could', 'also', 'such' );
	}
}
?>
