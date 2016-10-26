<?php
/**
 * @/core/lib/do-db.php
 * on: 23.06.2015
 * An object for fetching and cleaning up database stored meta array and to convert it to an usable
 * array along with a custom function on time consumed.
 *
 * 2 properties.
 * $name for name of the post meta 
 * $timestamp for a stamp of time
 */
class CGSS_GET_DB {

	//define properties
	private $name;
	private $timestamp;

	//construct the object
	public function __construct( $name, $timestamp ) {
		$this->name = $name;
		$this->timestamp = $timestamp;
	}

	//generate individual words from text content
	public function fetch() {
		$output = false;
		$ids = $this->post_ids();
		if ( $ids and $this->name ) {
			$output = array();
			foreach ( $ids as $id ) {
				$result = get_post_meta( $id, $this->name, true );

				$time = false;
				$time_elapsed = false;
				$score = false;
				$marks = false;
				$links_num = false;
				$social_count = false;
				$keyword = false;
				$images = false;
				if ( $result ) {
					if ( array_key_exists( 'time', $result ) ) {
						$time = $result['time'];
					}
					if ( array_key_exists( 'time_now', $result ) ) {
						$time_scan = $result['time_now'];
					}
					if ( $time_scan ) {

						//a wordpress function to calculate the time difference
						$time_elapsed = human_time_diff( $time_scan, $this->timestamp );

						$time_elapsed_sec = $this->timestamp - $time_scan;
					}
					if ( $result and array_key_exists( 'score', $result ) ) {
						$score = $result['score'];
					}
					if ( $result and array_key_exists( 'marks', $result ) ) {
						$marks = $result['marks'];
					}
					if ( $result and array_key_exists( 'text', $result ) ) {
						$text = $result['text'];
					}
					if ( $text and array_key_exists( 'links', $text ) ) {
						$links = $text['links'];
					}
					if ( $links and array_key_exists( 'num', $links ) ) {
						$links_num = $links['num'];
					}
					if ( array_key_exists( 'over', $result ) ) {
						$over = $result['over'];
					}
					if ( $over and array_key_exists( 'social', $over ) ) {
						$social = $over['social'];
					}
					if ( $social and array_key_exists( 'num', $social ) ) {
						$social_count = $social['num'];
					}
					if ( $text and array_key_exists( 'keys', $text ) ) {
						$key_arr = $text['keys'];
						$one_keys = $key_arr[1];
					}
					if ( $one_keys and is_array( $one_keys ) ) {
						$main_key = key( $one_keys[0] );
						$keyword = esc_html( $main_key );
					}
					if ( $result and array_key_exists( 'usb', $result ) ) {
						$usb = $result['usb'];
						if ( $usb and array_key_exists( 'http_req', $usb ) ) {
							$http = $usb['http_req'];
							if ( $http and array_key_exists( 'img', $http ) ) {
								$images = $http['img'];
							}
						}
					}
					$output[$id] = array(
										'time' => $time,
										'since' => $time_elapsed,
										'since_sec' => $time_elapsed_sec,
										'score' => $score,
										'marks' => $marks,
										'links' => $links_num,
										'shares' => $social_count,
										'key' => $keyword,
										'img' => $images,
									);
				}
			}
		}
		return $output;
	}

	//Calculating of avarage scan time
	public function avg_scan_time() {
		$output = $this->fetch();
		if ( $output ) {
			$time_arr = array();
			foreach( $output as $val ) {
				$time_arr[] = $val['time'];
			}
			$time_now = array_filter( $time_arr );
			$time = array_sum ( $time_now );
			$time_per_post = $time / count( $output );
			$avg = ( $time_per_post * 1000 ) / 6;
			$rounded = round( $avg, 0 );
			return $rounded;
		} else {
			return false;
		}
	}

	//List of post ids
	public function post_ids() {
		$post_types = get_post_types( '', 'names' );
		$ids = array();
		foreach( $post_types as $types ) {
			$query = get_posts( array( 'posts_per_page' => -1, 'post_type' => $types ) );
			if( $query ) {
				foreach( $query as $val ) {
					$ids[] = $val->ID;
				}
			}
		}
		return $ids;
	}
}
?>
