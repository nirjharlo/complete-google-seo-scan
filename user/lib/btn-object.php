<?php
/**
 * @/user/lib/btn-class.php
 * on: 08.06.2015
 * Custom button method. css classes & icons taken from wordpress admin style.
 *
 * 5 properties:
 * 1. $btn_id for button id attribute.
 * 2. $btn_icon_class for button icon class attribute.
 * 3. $btn_text for button content. Required.
 * 4. $btn_href for button link href.
 * 5. $btn_class for button main class [not for icon].
 *
 * Using custom css class: hide-btn-text, this button hides text content and shows  for small screen.
 */
class CGSS_BTN {

	//declare properties
	private $btn_id;
	private $btn_icon_class;
	private $btn_text;
	private $btn_href;
	private $btn_class;

	//construct properties
	public function __construct( $btn_id, $btn_icon_class, $btn_text, $btn_href, $btn_class ) {
		$this->btn_id = $btn_id;
		$this->btn_icon_class = $btn_icon_class;
		$this->btn_text = $btn_text;
		$this->btn_href = $btn_href;
		$this->btn_class = $btn_class;
	}

	//define method: display() to output raw html. Use $btn_text property as required.
	public function display() {
		if ( ! $this->btn_text and ! $this->btn_icon_class and ! $this->btn_text ) :
			return __( 'Button Error', 'cgss' );
		else:
			return '<a' . ( $this->btn_id ? ' id="' . $this->btn_id . '"' : '' ) . ( $this->btn_href ? ' href="' . $this->btn_href . '"' : '' ) . ' class="hide-if-no-js add-new-h2' . ( $this->btn_class ? ' ' . $this->btn_class : '' ) . '"><span' . ( $this->btn_icon_class ? ' class="dashicons dashicons-' . $this->btn_icon_class . '"' : '' ) . '></span><span class="hide-btn-text">' . $this->btn_text . '</span></a>';
		endif;
	}
}
?>
