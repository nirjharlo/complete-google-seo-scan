<?php
/**
 * @/user/lib/table-object.php
 * @on 10.07.2015
 * @since 2.0
 *
 * Custom table object with thead-tfoot and tbody.
 *
 * 6 properties:
 * @prop string $tbl_id Table id.
 * @prop string $tbl_class Table class.
 * @prop string $tbl_row_class Class of each table row.
 * @boolean string $tbl_init 1st element of thead-tfoot and tbody. If set true, will return
 * 			predefined value in 1st td of table body and blank in 1st td of table header-footer.
 * @prop array $tbl_hd_data for table header data. It is an array with following pattarn:
 * 		array(
 *			array(
 *				boolean 'id' => true/false,
 *				boolean 'class' => true/false,
 *				boolean 'icon_class' => true/false,
 *				string 'val' => TEXT or HTML,
 *			), ...
 *		)
 * @prop array $tbl_data for tbody display. Elements are like:
 * 		array(
 *			 array(
 *				array(
 *					boolean 'id' => true/false,
 *					string 'class' => TEXT,
 *					string 'val' => TEXT or HTML,
 *				), ... repeat for columns
 *			), ... repeat for rows
 *		),
 */
class CGSS_TABLE {

	//declare properties
	private $tbl_id;
	private $tbl_class;
	private $tbl_row_class;
	private $tbl_init;
	private $tbl_hd_data;
	private $tbl_data;

	//construct properties
	public function __construct( $tbl_id, $tbl_class, $tbl_row_class, $tbl_init, $tbl_hd_data, $tbl_data ) {
		$this->tbl_id = $tbl_id;
		$this->tbl_class = $tbl_class;
		$this->tbl_row_class = $tbl_row_class;
		$this->tbl_init = $tbl_init;
		$this->tbl_hd_data = $tbl_hd_data;
		$this->tbl_data = $tbl_data;
	}

	//create table head or foot content
	public function head_foot() {

		//initiate header info
		$this->hcon = '';

		if ( $this->tbl_hd_data ) {

			//create table column heading contents
			foreach( $this->tbl_hd_data as $key ) :
				$this->hcon .= '<th' . ( $key['id'] ? ' id="' . $key['id'] . '"' : '' ) . ( $key['class'] ? ' class="' . $key['class'] . '"' : '' ) . '>' . ( $key['icon_class'] ? '<span class="dashicons dashicons-' . $key['icon_class'] . '"></span>' : '' ) . ( $key['val'] ? '<strong>' . $key['val'] . '</strong>' : '' ) . '</th>';
			endforeach;

			//output table header or footer data
			return '<tr>' .
				( $this->tbl_init ? '<th class="manage-column column-cb check-column"></th>' : '' ) . $this->hcon . '</tr>';
		}
	}

	//create table body
	public function body() {

		//initiate header info
		$this->bcon = '';

		if ( $this->tbl_data ) {

			//create table body columns
			foreach ( $this->tbl_data as $val ) :
				$this->bcon .= '<tr' . ( $this->tbl_row_class ? ' class="' . $this->tbl_row_class . '"' : '' ) . '>';
				foreach ( $val as $key ) :
					$this->bcon .= '<td' . ( $key['id'] ? ' id="' . $key['id'] . '"' : '' ) . ( $key['class'] ? ' class="' . $key['class'] . '"' : '' ) . '>' . ( $key['val'] ? $key['val'] : '' ) . '</td>';
				endforeach;
				$this->bcon .= '</tr>';
			endforeach;

			//output table body data
			return $this->bcon;
		}
	}

	//define method: display() to output raw html
	//$tfoot is true/false
	public function display( $tfoot ) {
		return '<table' . ( $this->tbl_id ? ' id="' . $this->tbl_id . '"' : '' ) . ( $this->tbl_class ? ' class="' . $this->tbl_class . '"' : '' ) . '><thead>' . $this->head_foot() . '</thead><tbody>' . $this->body() . '</tbody>' . ( $tfoot ? '<tfoot>' . $this->head_foot() . '</tfoot>' : '' ) . '</table>';
	}
} ?>
