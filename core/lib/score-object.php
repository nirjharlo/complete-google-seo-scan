<?php
/**
 * @/core/lib/score-object.php
 * on: 22.06.2015
 * From result data found in scan, analyze a score of on-page optimization. Using approximate values
 * from qualitative ideas of seo. Sometimes statistical approximation has been taken.
 *
 * 1 property:
 * $result for the input resulting array from the scan.
 */
class CGSS_SCORE {

	//Input property
	private $result;

	//construct the object
	public function __construct( $result ) {
		$this->result = $result;
	}

	//
	public function exact() {
		$score = $this->over() + $this->snip() + $this->text() + $this->media() + $this->usb() + 150;
		return $score;
	}

	//out of [-150, +210] we score the page. First, add the grace 150 to total number obtained.
	// Now devide it in a series of 8 segment, remember Gap, betwwen the fingers. Put approx values.
	public function calculate() {
		$score = $this->exact();
		$star = 0;
		switch ( true ) {
			case ( $score >= 320 ):
				$star = '5';
				break;
			case ( $score >= 280 ):
				$star = '4.5';
				break;
			case ( $score >= 240 ):
				$star = '4';
				break;
			case ( $score >= 200 ):
				$star = '3.5';
				break;
			case ( $score >= 160 ):
				$star = '3';
				break;
			case ( $score >= 120 ):
				$star = '2.5';
				break;
			case ( $score >= 80 ):
				$star = '2';
				break;
			case ( $score >= 40 ):
				$star = '1.5';
				break;
			case ( $score >= 0 ):
				$star = '1';
				break;
		}
		return $star;
	}

	//Overview score for url and canonical tag. Excluding robots tag,to be taken care of by jquery.
	public function over() {
		$data = $this->result['over'];
		if ( $data ) {
			$url = $data['url_prop'];
			if ( ! $data['cano'] ) {
				$cano = 1;
			} else {
				$cano = 0;
			}
			$arr = array( $url['ssl'], $url['dynamic'], $url['underscore'], $cano );
			$score = 0;
			foreach ( $arr as $val ) {
				switch ( $val ) {
					case 0:
						$score += 10;
						break;
					default:
						$score -= 5;
						break;
				}
			}
			return $score;
		} else {
			return 0;
		}
	}

	//Check snippet lengths and score it. Find most used word in them and score
	public function snip() {
		$data = $this->result['snip'];
		if ( $data ) {
			$arr = array(
				array( strlen( $data['title'] ), 65 ),
				array( strlen( $data['desc'] ), 165 ),
			);
			$score = 0;
			foreach ( $arr as $key => $val ) {
				switch ( true ) {
					case ( $key > $val ):
						$score -= 5;
						break;
					case ( $key < $val ):
						$score += 10;
						break;
				}
			}
			$arr_two = array( $data['title'], $data['desc'], $data['url'] );
			foreach ( $arr_two as $key ) {
				$got_key = $this->key();
				if ( $key and $got_key ) {
					$find_key = substr_count( $key, $got_key );
					switch ( $find_key ) {
						case 0:
							$score -= 5;
							break;
						default:
							$score += 10;
							break;
					}
				}
			}
			return $score;
		} else {
			return 0;
		}
	}

	//Analyze text for calculating score by words count, html/text ratio, link to word ratio,
	//image link to all link ratio and usage of most used word in anchor texts and heading texts.
	public function text() {
		$data = $this->result['text'];
		if ( $data ) {
			$score = 0;
			$count = $data['count'];
			$ratio = $data['ratio'];
			switch ( true ) {
				case ( $ratio <= 5 ):
					$score -= 10;
					break;
				case ( $ratio <= 15 ):
					$score -= 5;
					break;
				case ( $ratio <= 70 ):
					$score += 10;
					break;
				case ( $ratio <= 90 ):
					$score -= 5;
					break;
				case ( $ratio > 90 ):
					$score -= 10;
					break;
			}
			if ( $data['links'] ) {
				$links = $data['links'];
				if ( $links['num'] and $count ) {
					$link_ratio = ( ( $links['num'] / $count ) * 100 );
					switch ( true ) {
						case ( $link_ratio <= 1 ):
							$score -= 5;
							break;
						case ( $link_ratio <= 10 ):
							$score += 10;
							break;
						case ( $link_ratio <= 50 ):
							$score += 5;
							break;
						case ( $link_ratio <= 75 ):
							$score -= 5;
							break;
						case ( $link_ratio > 75 ):
							$score -= 10;
							break;
					}
				}
				if ( $links['no_text'] and $links['num'] ) {
					$img_ratio = ( ( $links['no_text'] / $links['num'] ) * 100 );
					switch ( true ) {
						case ( $img_ratio <= 2 ):
							$score += 10;
							break;
						case ( $img_ratio <= 5 ):
							$score += 5;
							break;
						case ( $img_ratio <= 50 ):
							$score -= 5;
							break;
						case ( $img_ratio > 50 ):
							$score -= 10;
							break;
					}
				}
				if ( $links['anchors'] ) {
					$anch = implode( ' ', $links['anchors'] );
				}
				$hds = $this->headings();
				$arr = array(
							'anchor' => $anch,
							'heading' => $hds,
						);
				foreach ( $arr as $key => $val ) {
					$got_key = $this->key();
					if ( $val and $got_key ) {
						$find_key = substr_count( $val, $got_key );
						$key_percent = ( $find_key / count( explode( ' ', $val ) ) * 100 );
						switch ( true ) {
							case ( $key_percent = 0 ):
								$score -= 5;
								break;
							case ( $key_percent <= 5 ):
								$score += 10;
								break;
							case ( $key_percent <= 10 ):
								$score += 5;
								break;
							case ( $key_percent > 10 ):
								$score -= 10;
								break;
						}
					}
				}
			}
			return $score;
		} else {
			return 0;
		}
	}

	//Score based on alt tags presence in images and top word found in those alt tags
	public function media() {
		$data = $this->result['media'];
		if ( $data ) {
			$score = 0;
			if ( $data['image'] ) {
				$img = $data['image'];
				$count_img = count( $img );

				//if there are no images, then this calculation is useless.
				if ( $count_img > 0 ) {
					$alts = '';
					$no_alt = 0;
					foreach ( $img as $val ) {
						if ( empty( $val['alt'] ) ) {
							$no_alt += 1;
						} else {
							$alts .= ' ' . $val['alt'];
						}
					}
					$no_alt_per = ( $no_alt / $count_img ) * 100;
					switch ( true ) {
						case ( $no_alt_per = 100 ):
							$score -= 10;
							break;
						case ( $no_alt_per > 50 ):
							$score -= 5;
							break;
						case ( $no_alt_per > 10 ):
							$score += 5;
							break;
						case ( $no_alt_per <= 10 ):
							$score += 10;
							break;
					}

					$main_key = $this->key();
					if ( $alts and $main_key ) {
						$find_key = substr_count( $alts, $main_key );
						$alt_words = explode( ' ', $alts );
						$alt_words_count = count( $alt_words );
						$key_per = ( $find_key / $alt_words_count ) * 100;
						switch ( true ) {
							case ( $key_per = 0 ):
								$score -= 5;
								break;
							case ( $key_per <= 5 ):
								$score += 10;
								break;
							case ( $key_per <= 10 ):
								$score += 5;
								break;
							case ( $key_per > 10 ):
								$score -= 10;
								break;
						}
					}
				}
			}
			return $score;
		} else {
			return 0;
		}
	}

	//determine score number of http requests and presence of code errors, viewport and social tags
	public function usb() {
		$data = $this->result['usb'];
		if ( $data ) {
			$score = 0;
			if ( $data['down_time'] ) {
				$time = $data['down_time'];
				switch ( true ) {
						case ( $time > 10 ):
							$score -= 5;
							break;
						case ( $time < 3 ):
							$score += 10;
							break;
						case ( $time <= 10 ):
							$score += 5;
							break;
				}
			}
			$http = $data['http_req'];
			$rq_num = $http['num'];
			switch ( true ) {
					case ( $rq_num > 100 ):
						$score -= 10;
						break;
					case ( $rq_num > 50 ):
						$score -= 5;
						break;
					case ( $rq_num <= 50 ):
						$score += 10;
						break;
			}
			$err = $data['code_errors'];
			$arr = array( $data['nested_table'], $data['tag_style'], $err['num'] );
			foreach( $arr as $val ) {
				switch ( $val ) {
					case 0:
						$score += 5;
						break;
					default:
						$score -= 5;
						break;
				}
			}
			if ( $data['vport'] ) {
				$score += 10;
			} else {
				$score -= 5;
			}
			$stag = $data['social_tags'];
			if ( $stag['title'] and $stag['img'] ) {
				$score += 5;
			}
			return $score;
		} else {
			return 0;
		}
	}

	//Get the top word found
	public function key() {
		$found_top_key = false;
		$result = $this->result;
		$data = $result['text'];
		$keys = $data['keys'];
		$one_word = $keys[1];
		$found_top_key = (string) key( $one_word[0] );
		if ( $found_top_key ) {
			return $found_top_key;
		} else {
			return false;
		}
	}

	//Implode all heading tags into one string
	public function headings() {
		$txt = $this->result['text'];
		$htag = $txt['htags'];
		$arr = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		$headings = '';
		foreach ( $arr as $val ) {
			if ( $val ) {
				$headings .= implode( ' ', $htag[$val] );
			}
		}
		return $headings;
	}
}
?>
