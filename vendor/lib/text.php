<?php
/**
 * An object for treating text by using methods of xpath and generating array of words along with
 * text to html ratio in kb.
 *
 * 2 properties.
 * @property obj $dom document object model
 * @property string $body_size Size of complete HTML
 */
class CGSS_TEXT_TREATMENT {


	public $dom;
	public $body_size;


	// Execute the xPath first, to prevent multiple execution
	public function execute() {

		$this->xpath = $this->xpath();
		$this->text = $this->text();
	}


	//generate individual words from text content
	public function words() {

		//get word counts from text string. Here I use 2 loops to check for voids and characters.
		$text = str_replace( array( '.', ',', ':', '\'', '"', ')', '(', ']', '[', '}', '{', ';', '+', '-', '_', '*', '&', '^', '%', '$', '#', '@', '!', '~', '?', '>', '<', '/', '\\', '|' ), ' ' , $this->text );
		$pure_text = filter_var( $text, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW );

		//after formating text, explode the string into words and remove empty elements.
		$text_string = explode( ' ', $pure_text );
		$words = array();
		foreach ( $text_string as $key ) {
			if ( $key ) {
				$words[] = trim( $key );
			}
		}
		return $words;
	}

	//get text to html ratio
	public function ratio() {

		$xpath = $this->xpath;
		return $xpath['ratio'];
	}

	//get total text content from xpath
	public function text() {

		$xpath = $this->xpath;
		return preg_replace( '/[ \n]+/', ' ', preg_replace( '/[ \t]+/', ' ', preg_replace( '/\s*$^\s*/m', ' ', $xpath['content'] ) ) );
	}

	//get total text content from xpath
	public function size() {
		$xpath = $this->xpath;
		return $xpath['size'];
	}

	//Create xpath object from document object model
	public function xpath() {

		//generate whole html
		$xpath = new DomXPath( $this->dom );

		//Get html size
		$html_size = $this->body_size;

		//make it ready to get body xpath for text.
		foreach ( $xpath->query( '//script' ) as $key ) {
			$key->parentNode->removeChild( $key );
		}
		foreach ( $xpath->query( '//style' ) as $key ) {
			$key->parentNode->removeChild( $key );
		}
		$all_text = $xpath->query( '//body[text()]' );

		//generate whole text
		$all_text_target = $all_text->item(0);
		if ( $all_text_target ) {
			$only_text = strtolower( trim( $all_text_target->nodeValue ) );
		} else {
			$only_text = '';
		}

		//get text size
		$text_size = mb_strlen( $only_text, '8bit' );

		//get html to text ratio
		$ht_ratio = round( ( $text_size / $html_size ) * 100, 1 );

		return array(
					'content' => $only_text,
					'ratio' => $ht_ratio,
					'size' => round( ( $text_size / 1024 ), 1 ),
		);
	}
}
?>
