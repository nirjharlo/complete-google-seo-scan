<?php
/**
 * @/user/lib/dropdown-class.php
 * on: 08.06.2015
 * Custom dropdown select input method for front end, using wordpress admin style.
 *
 * It has 5 properties:
 * 1. $dp_id for select id attribute.
 * 2. $dp_name for select name attribute.
 * 3. $dp_options for creating options inside select input. Required.
 *    It is an array with individual elements of format array( 'value', 'display text' );
 * 4. $dp_submit_name for submit type input name attribute.
 * 5. $dp_submit_text for submit type input value attribute.
 */
class CGSS_DROPDOWN {

	//declare properties
	private $dp_id;
	private $dp_name;
	private $dp_options;
	private $dp_submit_name;
	private $dp_submit_text;

	//construct this properties
	public function __construct( $dp_id, $dp_name, $dp_options, $dp_submit_name, $dp_submit_text ) {
		$this->dp_id = $dp_id;
		$this->dp_name = $dp_name;
		$this->dp_options = $dp_options;
		$this->dp_submit_name = $dp_submit_name;
		$this->dp_submit_text = $dp_submit_text;
	}

	//define method: display() to output raw html. If $dp_options is not provided show error.
	public function display() {
	if ( $this->dp_options ) :
		$opts = '';
		foreach ( $this->dp_options as $type ) :
			$opts .= '<option value="' . $type[0] . '">' . $type[1] . '</option>';
		endforeach;
		return '<select' . ( $this->dp_id ? ' id="' . $this->dp_id . '"' : '' ) . 'type="dropdown"' . ( $this->dp_name ? ' name="' . $this->dp_name . '"' : '' ) . '>' . $opts . '</select>' . '<input type="submit"' . ( $this->dp_submit_name ? ' name="' . $this->dp_submit_name . '"' : '' ) . 'class="button action"' . ( $this->dp_submit_text ? ' value="' .  $this->dp_submit_text . '"' : '' ) . '/>';
	else :
		_e( 'DROPDOWN ERROR', 'cgss' );
	endif;
	}

	//define method: display() to output raw html. If $dp_options is not provided show error.
	public function double_display() {
	if ( is_array( $this->dp_id ) and is_array( $this->dp_name ) and is_array( $this->dp_options ) ) :
		$filds = '';
		for ( $i = 0; $i < 2; $i++ ) :
			$opts[$i] = '';
			foreach ( $this->dp_options[$i] as $type ) :
				$opts[$i] .= '<option value="' . $type[0] . '">' . $type[1] . '</option>';
			endforeach;
			$filds .= '<select' . ( $this->dp_id[$i] ? ' id="' . $this->dp_id[$i] . '"' : '' ) . 'type="dropdown"' . ( $this->dp_name[$i] ? ' name="' . $this->dp_name[$i] . '"' : '' ) . '>' . $opts[$i] . '</select>';
		endfor;
		return $filds . '<input type="submit"' . ( $this->dp_submit_name ? ' name="' . $this->dp_submit_name . '"' : '' ) . 'class="button action"' . ( $this->dp_submit_text ? ' value="' . $this->dp_submit_text . '"' : '' ) . '/>';
	else :
		_e( 'DROPDOWN ERROR', 'cgss' );
	endif;
	}
}
?>
