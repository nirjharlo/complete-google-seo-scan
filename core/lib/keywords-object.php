<?php
/**
 * @/core/lib/keywords-object.php
 * on: 13.06.2015
 * @since 2.0
 *
 * An object to extract most used pharases in an array of phrases, containing one or more words.
 * But, for now we are using one word elements only.
 *
 * 2 properties.
 * @prop string $words Input words array
 * @prop string $text Input words array
 */
class CGSS_KEYWORDS {

	//define properties
	private $words;
	private $text;

	//construct the object
	public function __construct( $words, $text ) {
		$this->words = $words;
		$this->text = $text;
	}

	//generate individual words from text content
	public function output() {

		$one = $this->output_one();
		$two = $this->fetch(2);
		$three = $this->fetch(3);
		$four = $this->fetch(4);
		$five = $this->fetch(5);
		$six = $this->fetch(6);
		$keys = array_merge( $six, $five, $four, $three, $two, $one );
		return $keys;
	}

	//generate individual words from text content
	public function output_one() {

		$one_arr = $this->fetch(1);
		$stop = $this->stop();
		$output = array();
		foreach ( $one_arr as $val => $key ) {
			if ( strlen( $val ) > 3 and ! in_array( $val, $stop ) ) {
				$output[$val] = $key;
			}
		}
		return array_slice( $output, 0, 10 );
	}

	//generate individual words from text content
	public function fetch( $n ) {

		//compare text and words to find keyword
		$keys = array();
		$phrs = $this->prepare( $n );
		$phrs_count = count($phrs);
		foreach( $phrs as $val ) {
			$key_num = substr_count( $this->text, $val );
			$key_per = round( ( ( $key_num / $phrs_count ) * 100 ), 3 );
			if ( $key_num > 1 and $key_per > 0.2 and ! isset( $keys[$val] ) ) {
				$keys[$val] = $key_per;
			}
		}

		//arrange them to select top final 3 keyword
		if ( is_array( $keys ) ) {
			arsort( $keys );
			if ( $n != 1 ) {

				//remove keys without much significance
				$del_keys = array();
				foreach ( $keys as $val => $key ) {
					$key_size = strlen( implode( '', explode( ' ', $val ) ) ) / 2;
					if ( $key_size <= $n ) {
						$del_keys[] = $val;
					}
				}
				$new_keys = array_diff( $keys, $del_keys );

				$key_top = array_slice( $new_keys, 0, 3 );
			} else {
				$key_top = $keys;
			}
		}
		return $key_top;
	}

	//generate individual words from text content
	public function prepare( $n ) {
		$i = 0;
		$part = array();
		$w_input = $this->words;
		for( $i = 0; $i < $n; $i++ ) {

			//create an array of words of pharses as sub-arrays
			$pieces = array_chunk( $w_input, $n );
			$last_one = array_pop( $pieces );
			if ( is_array( $last_one ) and count( $last_one ) != $i ) {
				unset($pieces[count($pieces) - 1]);
			}

			//create phrase out of those sub array
			foreach( $pieces as $val ) {
				$part[] = implode( ' ', $val );
			}

			//Offset input words first value to create more unique words
			$del_w_input = array_diff( $w_input, array( $w_input[0] ) );
			$w_input = array_values($del_w_input);
		}
		return $part;
	}

	//array of stop words
	public function stop() {
		return array( 'from', 'that', 'this', 'what', 'when', 'where', 'have', 'they', 'just', 'your', 'most', 'their', 'some', 'then', 'there', 'them', 'make', 'ever', 'never', 'enough', 'should', 'would', 'could', 'also', 'such', 'shall', 'will', 'with' );
	}
}
?>
