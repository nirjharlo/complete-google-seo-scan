<?php
/**
 * @/user/lib/dropdown-object.php
 * @on 10.07.2015
 * @since 2.0
 *
 * Custom dropdown select input method for front end, using wordpress admin style.
 *
 * It has 5 properties:
 * @prop string $dp_id Select id attribute.
 * @prop string $dp_class Select class attribute.
 * @prop string $dp_name Select name attribute.
 * @prop array $dp_options Creating options inside select input. Required.
 *	array(
 *		array(
 *			@string 'value',
 *			@string 'display text'
 *		), ...
 *	);
 * @prop string $dp_submit_name Submit type input name attribute.
 * @prop string $dp_submit_text Submit type input value attribute.
 * @prop boolean $dp_submit_switch Submit type input value attribute.
 */
class CGSS_DROPDOWN {

	//declare properties
	private $dp_id;
	private $dp_class;
	private $dp_name;
	private $dp_options;
	private $dp_submit_name;
	private $dp_submit_text;
	private $dp_submit_switch;

	//construct this properties
	public function __construct( $dp_id, $dp_class, $dp_name, $dp_options, $dp_submit_name, $dp_submit_text, $dp_submit_switch ) {
		$this->dp_id = $dp_id;
		$this->dp_class = $dp_class;
		$this->dp_name = $dp_name;
		$this->dp_options = $dp_options;
		$this->dp_submit_name = $dp_submit_name;
		$this->dp_submit_text = $dp_submit_text;
		$this->dp_submit_switch = $dp_submit_switch;
	}

	//define method: display() to output raw html. If $dp_options is not provided show error.
	public function display() {
	if ( $this->dp_options ) :
		$opts = '';
		foreach ( $this->dp_options as $type ) :
			$opts .= '<option value="' . $type[0] . '">' . $type[1] . '</option>';
		endforeach;
		return '<select' . ( $this->dp_id ? ' id="' . $this->dp_id . '"' : '' ) . ( $this->dp_class ? ' class="' . $this->dp_class . '"' : '' ) . 'type="dropdown"' . ( $this->dp_name ? ' name="' . $this->dp_name . '"' : '' ) . '>' . $opts . '</select>' . ( $this->dp_submit_name ? '<input type="submit"' . ( $this->dp_submit_name ? ' name="' . $this->dp_submit_name . '"' : '' ) . 'class="button action"' . ( $this->dp_submit_text ? ' value="' .  $this->dp_submit_text . '"' : '' ) . '/>' : '' );
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
} ?>
