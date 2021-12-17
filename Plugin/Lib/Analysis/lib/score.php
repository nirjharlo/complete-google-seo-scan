<?php

namespace NirjharLo\Cgss\Lib\Analysis\Lib;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * From result data found in scan, analyze a score of on-page optimization. Using approximate values
 * from qualitative ideas of seo. Sometimes statistical approximation has been taken.
 *
 * 5 properties:
 * @property array $snippet
 * @property array $text
 * @property array $design
 * @property array $crawl
 * @property array $speed
 */
class Score {


	public $snippet;
	public $text;
	public $design;
	public $crawl;
	public $speed;


	public function exact() {
		$score = $this->snip() + $this->text() + $this->design() + $this->crawl() + $this->speed() + 245;
		return $score;
	}

	//out of [-245, +400] we score the page. First, add the grace 150 to total number obtained.
	// Now devide it in a series of 8 segment, remember Gap, betwwen the fingers. Put approx values.
	public function calculate() {
		$score = $this->exact();

		$star = 0;
		switch ( true ) {
			case ( $score >= 560 ):
				$star = 10;
				break;
			case ( $score >= 490 ):
				$star = 9;
				break;
			case ( $score >= 420 ):
				$star = 8;
				break;
			case ( $score >= 350 ):
				$star = 7;
				break;
			case ( $score >= 280 ):
				$star = 6;
				break;
			case ( $score >= 210 ):
				$star = 5;
				break;
			case ( $score >= 140 ):
				$star = 4;
				break;
			case ( $score >= 70 ):
				$star = 3;
				break;
			case ( $score >= 0 ):
				$star = 2;
				break;
			case ( $score <= 0 ):
				$star = 1;
				break;
		}

		return $star;
	}

	//Check snippet lengths and score it. Find most used word in them and score: 50, 50
	public function snip() {
		$data = $this->snippet;
		$score = 0;

		$arr = array( $data['title'], $data['desc'] );
		foreach ( $arr as $key ) {
			switch ( true ) {
				case ( $key && $key != '' ):
					$score += 25;
					break;
				case ( ! $key || $key == '' ):
					$score -= 25;
					break;
			}
		}
		return $score;
	}

	//Analyze text for calculating score by words count, html/text ratio, link to word ratio,
	//image link to all link ratio and usage of most used word in anchor texts and heading texts: 200, 75
	public function text() {
		$data = $this->text;
		if ( $data ) {
			$score = 0;
			$count = $data['count'];
			switch ( true ) {
				case ( $count < 200 ):
					$score -= 10;
					break;
				case ( $count > 200 ):
					$score += 25;
					break;
			}
			$ratio = $data['ratio'];
			switch ( true ) {
				case ( $ratio <= 15 ):
					$score -= 10;
					break;
				case ( $ratio <= 70 ):
					$score += 25;
					break;
				case ( $ratio > 70 ):
					$score -= 10;
					break;
			}
			if ( $data['links'] ) {
				$links = $data['links'];
				if ( $count > 0 ) {
					$link_ratio = ( ( $links['count'] / $count ) * 100 );
					switch ( true ) {
						case ( $link_ratio <= 50 ):
							$score += 10;
							break;
						case ( $link_ratio > 50 ):
							$score -= 5;
							break;
					}
				}
				switch ( true ) {
					case ( $links['no_text'] < 2 ):
						$score += 10;
						break;
					case ( $links['no_text'] > 2 ):
						$score -= 5;
						break;
				}
			}

			//Check for keywords in various area: 60, 0
			$anch = $links['anchors'];
			$htags = $data['htags']['content'];
			$hds = '';
			foreach ($htags as $value) {
				$hds .= ' ' . implode(' ', $value);
			}
			$snip_data = $this->snippet;
			$design_data = $this->design;
			$img_data = $design_data['image'];
			$alts = implode(' ', $img_data['alt_value']);
			$url = implode( " ", explode( "-", $snip_data['url'] ) );
			$arr = array( $snip_data['title'], $snip_data['desc'], $alts, $url, $anch, $hds );
			foreach ( $arr as $val ) {
				if ( $val && $val != '' ) {
					$find_key = $this->key_chk( $val );
					switch ( true ) {
						case ( ! $find_key ):
							$score += 0;
							break;
						case ( $find_key > 0 ):
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

	//Score based on alt tags presence in images and top word found in those alt tags: 70, 45
	public function design() {
		$data = $this->design;
		if ( $data ) {
			$score = 0;
			$img_data = $data['image'];
			$no_alt_data = $img_data['no_alt_count'];
			switch ( $no_alt_data ) {
				case 0:
					$score += 25;
					break;
				default:
					$score -= 25;
					break;
			}

			$nested_table = $data['nested_table'];
			switch ( $nested_table ) {
				case 0:
					$score += 10;
					break;
				default:
					$score -= 5;
					break;
			}

			$tag_style = $data['tag_style'];
			switch ( $tag_style['count'] ) {
				case 0:
					$score += 10;
					break;
				default:
					$score -= 5;
					break;
			}

			$vport = $data['vport'];
			$media = $data['media'];
			$media_ok = $media['ok'];
			if ( $vport and $media_ok and strlen( $vport ) > 0 and $media_ok > 0 ) {
				$score += 25;
			} else {
				$score -= 10;
			}
			return $score;
		} else {
			return 0;
		}
	}

	//determine score number of http requests and presence of code errors, viewport and social tags: 80, 40
	public function crawl() {
		$data = $this->crawl;
		if ( $data ) {
			$ip = $data['ip'];
			$meta_robot = $data['meta_robot'];

			if ( ! $data['cano'] or strlen( $data['cano'] ) != 0 ) {
				$cano = 1;
			} else {
				$cano = 0;
			}
			$arr = array( $data['ssl'], $data['dynamic'], $data['underscore'], $cano, $ip, $data['if_mod'], $meta_robot['ok'] );
			$score = 0;
			foreach ( $arr as $val ) {
				switch ( $val ) {
					case 1:
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

	//Overview score for url and canonical tag. Excluding robots tag,to be taken care of by jquery: 70, 35
	public function speed() {
		$data = $this->speed;
		if ( $data ) {
			$score = 0;
			$down_time = $data['down_time'];
			switch ( true ) {
				case ( $down_time > 10 ):
					$score -= 5;
					break;
				case ( $down_time < 3 ):
					$score += 10;
					break;
				case ( $down_time <= 5 ):
					$score += 5;
					break;
			}
			$arr = array( $data['gzip'], $data['cache'] );
			foreach ( $arr as $val ) {
				switch ( $val ) {
					case 1:
						$score += 10;
						break;
					default:
						$score -= 5;
						break;
				}
			}
			$css = $data['css'];
			$js = $data['js'];
			$files_arr = array( $css['count'], $js['count'] );
			foreach ( $files_arr as $val ) {
				switch ( true ) {
					case ( $val <= 10 ):
						$score += 10;
						break;
					case ( $val > 10 ):
						$score -= 5;
						break;
				}
			}
			$files_size_arr = array( $css['size'], $js['size'] );
			foreach ( $files_size_arr as $val ) {
				switch ( true ) {
					case ( $val <= 100 ):
						$score += 10;
						break;
					case ( $val > 100 ):
						$score -= 5;
						break;
				}
			}
			return $score;
		} else {
			return 0;
		}
	}

	//Get the top word found
	public function key_chk( $txt ) {
		$show = 0;
		$text = $this->text;
		$keys = $text['keys'];
		foreach ( $keys as $key => $val ) {

			$num = substr_count( $key, $txt );
			if ( $num > 0 ) {
				$show = 1;
			}
		}
		if ( $show == 0 ) {
			return false;
		} else {
			return $show;
		}
	}
}
?>
