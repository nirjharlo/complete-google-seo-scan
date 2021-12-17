<?php
namespace NirjharLo\Cgss\Lib\Analysis\Lib;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * Object to get tag values and tag attributes with different methods. Return values are in array()
 *
 * 4 property:
 * @prop string $dom Document object model as obtained from acctual url
 * @prop string $tag The tag to be analyzed
 * @prop array $specify An array of 3 elements, for selecting attribute and it's value and
 * decide attribute to fetch. If unspecified use value "null". An example usage:
 *		$specify = array(
 * 			string 'att' => string,
 * 			string 'val' => string,
 * 			string 'get_att' => string,
 * 		);
 * @prop array $atts Specification of attribute values of tags. For example usage:
 * 		$specify = array( 'att' => string, 'val' => string );
 *
 * NOTE for fetch_tag() method: If $specify is there then I take individual object to get each
 * attribute. But in case of $specify is not there, we take whole object and not each of them to
 * take item and nodevalue. Because the proper function to extract nodevalue from individual object
 * is unknown.
 */
class Tags {


	public $dom;
	public $tag;
	public $specify;
	public $atts;

	//Fetch and scan tag values and return details as an array.
	public function tag() {

		$tag_obj = $this->dom->getElementsByTagName( $this->tag );
		$num = 0;
		$val = array();
		$specify = $this->specify;
		foreach ( $tag_obj as $obj ) {
			if ( $specify ) {
				if ( $specify["att"] && $specify["val"] ) {

					//individual_object->function_for_attribute
					$get_att = $this->get_att( $obj, $specify["att"] );
					$get_val = $this->get_att( $obj, $specify["get_att"] );
					if ( strtolower( $get_att ) == $specify["val"] and $get_val ) {
						$val[] = $get_val;
					}
				} else {
					$get_val = $this->get_att( $obj, $specify["get_att"] );
					if ( $get_val ) {
						$val[] = $get_val;
					}
				}
			} else {

				//whole_object->individual_object
				$tag_val = $tag_obj->item( $num );
				$val[] = $tag_val->nodeValue;
			}
			$num = $num + 1;
		}

		//Format output result for easy usage
		if ( count( $val ) != 0 ) {
			return $val;
		} else {
			return false;
		}
	}



	// Fetch multiple attributes.
	public function atts() {
		if ( $this->atts ) {
			$tag_obj = $this->dom->getElementsByTagName( $this->tag );
			$val = array();
			foreach( $this->atts as $key ) {
				$fetch = array();
				foreach( $tag_obj as $obj ) {
					$fetch[] = $this->get_att( $obj, $key );
				}
				$val[$key] = $fetch;
			}
			return $val;
		} else {
			return false;
		}
	}



	// Fetch single attributes.
	public function get_att( $obj, $val ) {

		$data = false;
		$data = $obj->getAttribute( strtolower( $val ) );
		if ( ! $data ) {
			$data = $obj->getAttribute( ucfirst( $val ) );
		}
		if ( ! $data ) {
			$data = $obj->getAttribute( strtoupper( $val ) );
		}
		return $data;
	}
}
?>
