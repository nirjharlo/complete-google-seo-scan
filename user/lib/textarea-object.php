<?php
/**
 * @/user/lib/textarea-class.php
 * on: 21.05.2015
 * Custom textarea input method. css classes taken from wordpress admin style.
 *
 * 3 properties:
 * 1. $ta_id for textarea id attribute.
 * 2. $ta_icon_class for textarea icon class attribute.
 * 3. $ta_text for textarea content. Required.
 *
 * Using custom css class: hide-btn-text, this button hides text content and shows  for small screen.
 */
class CGSS_TXTAREA {

	//declare properties
	private $ta_id;
	private $ta_icon_class;
	private $ta_text;

	//construct properties
	public function __construct( $ta_id, $ta_class, $ta_text ) {
		$this->ta_id = $ta_id;
		$this->ta_class = $ta_class;
		$this->ta_text = $ta_text;
	}

	//define method: display() to output raw html
	public function display() {
		if ( $this->ta_id ) :
		return '<textarea' . ( $this->ta_id ? ' id="' . $this->ta_id . '"' : '' ) . ( $this->ta_class ? ' class="' . $this->ta_class . '"' : '' ) . ' rows="8" cols="40">' . ( $this->ta_text ? $this->ta_text : '' ) . '</textarea>';
		else:
			return __( 'Textarea Error', 'cgss' );
		endif;
	}
}
?>
