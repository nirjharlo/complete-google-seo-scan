<?php
/**
 * @/core/lib/do-db.php
 * on: 17.06.2015
 * An object for treating text by using methods of xpath and generating array of words along with
 * text to html ration interms of bits size.
 *
 * 1 properties.
 * $dom for input document object model.
 */
class CGSS_DO_DB {

	//define properties
	private $post_id;
	private $data;

	//construct the object
	public function __construct( $post_id, $data ) {
		$this->post_id = $post_id;
		$this->data = $data;
	}

	//generate individual words from text content
	public function save() {
		$save = false;
		$store = $this->data;
		if ( $this->post_id and $store ) {
			$pure_store = sanitize_meta ( 'cgss_scan_result', $store, 'post' );
			if ( ! update_post_meta ( $this->post_id, 'cgss_scan_result', $pure_store ) ) {
				$save = add_post_meta( $this->post_id, 'cgss_scan_result', $pure_store, true );
			} else {
				$save = update_post_meta ( $this->post_id, 'cgss_scan_result', $pure_store );
			}
		}
		if ( $save ) {
			return 'done';
		} else {
			return false;
		}
	}
}
?>
