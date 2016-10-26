<?php
/**
 * @/core/lib/tag-object.php
 * on: 13.06.2015
 * Object to get tag values and tag attributes with different methods. Return values are in array()
 * with numeric indexes.
 *
 * 4 property:
 * $dom for  document object model as obtained from acctual url 
 * $tag for the tag to be analyzed
 * $specify for an array of 3 elements, for selecting attribute and it's value and decide attribute
 * to fetch. If unspecified use value "null". An example usage:
 *		$specify = array(
 * 			'att' => 'rel',
 * 			'val' => 'shortcut icon',
 * 			'get_att' => 'href',
 * 		);
 * $atts for specification of attribute values of tags. For example usage:
 * 		$specify = array(
 * 					'rel',
 * 					'href',
 * 		);
 *
 *
 * NOTE for fetch_tag() method: If $specify is there then I take individual object to get each
 * attribute. But in case of $specify is not there, we take whole object and not each of them to
 * take item and nodevalue. Because the proper function to extract nodevalue from individual object
 * is unknown. I have notified it in the method.
 */
class CGSS_FETCH {

	//declare properties
	private $dom;
	private $tag;
	private $specify;
	private $atts;

	//construct the object
	public function __construct( $dom, $tag, $specify, $atts ) {
		$this->dom = $dom;
		$this->tag = $tag;
		$this->specify = $specify;
		$this->atts = $atts;
	}

	//Fetch and scan tag values and return details as an array.
	public function tag() {
		$tag_obj = $this->dom->getElementsByTagName( $this->tag );
		$num = 0;
		$val = array();
		foreach ( $tag_obj as $obj ) {
			if( $this->specify ) {
				if ( $this->specify["att"] and $this->specify["val"] ) {

					//individual_object->function_for_attribute
					if ( $obj->getAttribute( $this->specify["att"] ) == $this->specify["val"] and $obj->getAttribute( $this->specify["get_att"] ) ) {
						$val[] = $obj->getAttribute( $this->specify["get_att"] );
					}
				} else {
					if ( $obj->getAttribute( $this->specify["get_att"] ) ) {
						$val[] = $obj->getAttribute( $this->specify["get_att"] );
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
					$fetch[] = $obj->getAttribute( $key );
				}
				$val[$key] = $fetch;
			}
			return $val;
		} else {
			return false;
		}
	}
}
?>
