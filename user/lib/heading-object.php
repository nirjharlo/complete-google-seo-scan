<?php
/**
 * @/user/lib/heading-class.php
 * on: 08.06.2015
 * Custom heading method with one htag and paragraph.
 *
 * 5 properties:
 * 1. $hd_num for heading tag markup. Chose among 1, 2, 3, 4, 5, 6. Required.
 * 2. $hd_txt for heading text. HTML tags are allowed. Required.
 * 3. $hd_no_txt for heading side HTML tags.
 * 4. $hd_sub for heading sub text. HTML tags are allowed.
 * 5. $hd_sub_class for sub-heading or paragraph class.
 */
class CGSS_HEADING {

	//declare properties
	private $hd_num;
	private $hd_txt;
	private $hd_no_txt;
	private $hd_sub;
	private $hd_sub_class;

	//construct properties
	public function __construct( $hd_num, $hd_txt, $hd_no_txt, $hd_sub, $hd_sub_class ) {
		$this->hd_num = $hd_num;
		$this->hd_txt = $hd_txt;
		$this->hd_no_txt = $hd_no_txt;
		$this->hd_sub = $hd_sub;
		$this->hd_sub_class = $hd_sub_class;
	}

	//define method: display() to output raw html
	public function display() {
		if ( $this->hd_num and $this->hd_txt ) :
			return '<h' . $this->hd_num . '>' .	$this->hd_txt . ( $this->hd_no_txt ? $this->hd_no_txt : '' ) . '</h' . $this->hd_num . '>' . ( $this->hd_sub ? '<p' . ( $this->hd_sub_class ? ' class="' . $this->hd_sub_class . '"' : '' ) . '>' . $this->hd_sub . '</p>' : '' );
		else :
			return __( 'HEADING ERROR', 'cgss' );
		endif;
	}
}
?>
