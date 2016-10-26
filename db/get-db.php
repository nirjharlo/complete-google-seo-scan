<?php
/**
 * @/core/lib/do-db.php
 * on: 23.06.2015
 * @since 2.0
 *
 * An object for fetching and cleaning up database stored meta array and to convert it to an usable
 * array along with a custom function on time consumed. It is also used for intel data delivary.
 *
 * There are 3 more objects for get compete data, design seo and server seo.
 *
 * 3 properties.
 * @prop string $name name of the post meta
 * @prop int $post_id an id for post
 * @prop string $timestamp a stamp of time
 */

class CGSS_GET_DB {

	//define properties
	private $name;
	private $post_id;
	private $timestamp;

	//construct the object
	public function __construct( $name, $post_id, $timestamp ) {
		$this->name = $name;
		$this->post_id = $post_id;
		$this->timestamp = $timestamp;
	}

	//get links count
	public function link() {
		$links_num = '--';
		$result = $this->fetch();
		if ( $result and array_key_exists( 'text', $result ) ) {
			$text = $result['text'];
			if ( $text and array_key_exists( 'links', $text ) ) {
				$links = $text['links'];
				if ( $links and array_key_exists( 'num', $links ) ) {
					$links_num = $links['num'];
				}
			}
		}
		return $links_num;
	}

	//get images count
	public function image() {
		$count = '--';
		$result = $this->fetch();
		if ( $result and array_key_exists( 'design', $result ) ) {
			$design = $result['design'];
			if ( $design and array_key_exists( 'image', $design ) ) {
				$image = $design['image'];
				if ( $image and array_key_exists( 'count', $image ) ) {
					$count = $image['count'];
				}
			}
		}
		return $count;
	}

	//get shares count
	public function share() {
		$num = '--';
		$fb = 0;
		$gplus = 0;
		$twitter = 0;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'social', $result ) ) {
			$social = $result['social'];
			if ( $social and array_key_exists( 'num', $social ) ) {
				$num = $social['num'];
			}
			if ( $social and array_key_exists( 'gplus', $social ) ) {
				$gplus = $social['gplus'];
			}
			if ( $social and array_key_exists( 'twitter', $social ) ) {
				$twitter = $social['twitter'];
			}
			if ( $social and array_key_exists( 'fb_share', $social ) ) {
				$fb = $social['fb_share'];
			}
		}
		return array(
						'num' => $num,
						'fb' => $fb,
						'gplus' => $gplus,
						'twitter' => $twitter,
					);
	}

	//get time difference
	public function time() {
		$time = '--';
		$result = $this->fetch();
		if ( $result and array_key_exists( 'speed', $result ) ) {
			$speed = $result['speed'];
			if ( $speed and array_key_exists( 'down_time', $speed ) ) {
				if ( $speed['down_time'] < 1000 ) {
					$time = round( $speed['down_time'], 0 ) . ' ' . __( 'ms', 'cgss' );
				} else {
					$time = round( ( $speed['down_time'] / 1000 ), 0 ) . ' ' . __( 's', 'cgss' );
				}
			}
		}
		return $time;
	}

	//get time difference
	public function words() {
		$words = '--';
		$result = $this->fetch();
		if ( $result and array_key_exists( 'text', $result ) ) {
			$text = $result['text'];
			if ( $text and array_key_exists( 'count', $text ) ) {
				$words = $text['count'];
			}
		}
		return $words;
	}

	//get keyword
	public function keyword() {
		$keyword = '--';
		$result = $this->fetch();
		if ( $result and array_key_exists( 'text', $result ) ) {
			$text = $result['text'];
			if ( $text and array_key_exists( 'top_key', $text ) ) {
				$keyword = $text['top_key'];
			}
		}
		return $keyword;
	}

	//get time difference
	public function time_diff() {
		$time = array( 'since' => false, 'since_sec' => 'nil' );
		$result = $this->fetch();
		if ( $result and array_key_exists( 'time_now', $result ) ) {
			$time_got = $result['time_now'];
			if ( $time_got ) {

				//a wordpress function to calculate the time difference
				$time['since'] = human_time_diff( $time_got, $this->timestamp );
				$time['since_sec'] = $this->timestamp - $time_got;
			}
		}
		return $time;
	}

	//get score
	public function score() {
		$score = array( 'score' => 0, 'marks' => false, 'stars' => false );
		$result = $this->fetch();
		if ( $result and array_key_exists( 'score', $result ) ) {
			$score['score'] = $result['score'];
		}
		if ( $result and array_key_exists( 'marks', $result ) ) {
			$score['marks'] = $result['marks'];
		}
		if ( $score['score'] ) {
			$star = $this->stars();
			switch ( $score['score'] ) {
				case 1:
					$score['stars'] = $star['full'] . str_repeat( $star['blank'], 4 );
					break;
				case 1.5:
					$score['stars'] = $star['full'] . $star['half'] . str_repeat( $star['blank'], 3 );
					break;
				case 2:
					$score['stars'] = str_repeat( $star['full'], 2 ) . str_repeat( $star['blank'], 3 );
					break;
				case 2.5:
					$score['stars'] = str_repeat( $star['full'], 2 ) . $star['half'] . str_repeat( $star['blank'], 2 );
					break;
				case 3:
					$score['stars'] = str_repeat( $star['full'], 3 ) . str_repeat( $star['blank'], 2 );
					break;
				case 3.5:
					$score['stars'] = str_repeat( $star['full'], 3 ) . $star['half'] . $star['blank'];
					break;
				case 4:
					$score['stars'] = str_repeat( $star['full'], 4 ) . $star['blank'];
					break;
				case 4.5:
					$score['stars'] = str_repeat( $star['full'], 4 ) . $star['half'];
					break;
				case 5:
					$score['stars'] = str_repeat( $star['full'], 5 );
					break;
				default:
					$score['stars'] = '<span class="dashicons dashicons-heart danger-icon"></span>';
					break;
			}
		}
		return $score;
	}

	public function stars() {
		$full = '<span class="dashicons dashicons-star-filled warning-icon"></span>';
		$half = '<span class="dashicons dashicons-star-half warning-icon"></span>';
		$blank = '<span class="dashicons dashicons-star-empty warning-icon"></span>';
		return array(
					'full' => $full,
					'half' => $half,
					'blank' => $blank,
		);
	}

	//get time text to html ratio
	public function ratio_intel() {
		$ratio = false;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'text', $result ) ) {
			$text = $result['text'];
			if ( $text and array_key_exists( 'ratio', $text ) ) {
				$ratio = $text['ratio'];
			}
		}
		return $ratio;
	}

	//get links count
	public function links_intel() {
		$num = false;
		$external = false;
		$no_text = false;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'text', $result ) ) {
			$text = $result['text'];
			if ( $text and array_key_exists( 'links', $text ) ) {
				$links = $text['links'];
				if ( $links and array_key_exists( 'num', $links ) ) {
					$num = $links['num'];
				}
				if ( $links and array_key_exists( 'external', $links ) ) {
					$external = $links['external'];
				}
				if ( $links and array_key_exists( 'no_text', $links ) ) {
					$no_text = $links['no_text'];
				}
			}
		}
		return array(
					'num' => $num,
					'ext' => $external,
					'img' => $no_text,
				);
	}

	//get images count
	public function image_intel() {
		$count = false;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'design', $result ) ) {
			$design = $result['design'];
			if ( $design and array_key_exists( 'image', $design ) ) {
				$image = $design['image'];
				if ( $image and array_key_exists( 'no_alt_src', $image ) ) {
					if ( strlen( $image['no_alt_src'] ) > 0 ) {
						$count = count( explode( ", ", $image['no_alt_src'] ) );
					} else {
						$count = 0;
					}
				}
			}
		}
		return $count;
	}

	//get design images
	public function mobile_intel() {
		$vport = 0;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'design', $result ) ) {
			$design = $result['design'];
			if ( $design and array_key_exists( 'vport', $design ) ) {
				if ( $design['vport'] ) {
					$vport = 1;
				}
			}
		}
		return $vport;
	}

	//get time of downloading
	public function time_intel() {
		$time = false;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'speed', $result ) ) {
			$speed = $result['speed'];
			if ( $speed and array_key_exists( 'down_time', $speed ) ) {
				$time = $speed['down_time'];
			}
		}
		return $time;
	}

	//get url for intel
	public function url_intel() {
		$dynamic = false;
		$underscore = false;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'crawl', $result ) ) {
			$crawl = $result['crawl'];
			if ( $crawl and array_key_exists( 'dynamic', $crawl ) ) {
				$dynamic = $crawl['dynamic'];
			}
			if ( $crawl and array_key_exists( 'underscore', $crawl ) ) {
				$underscore = $crawl['underscore'];
			}
		}
		return array(
					'dynamic' => $dynamic,
					'underscore' => $underscore,
				);
	}

	//get intel data delivary
	public function social_tags_count() {
		$title = false;
		$desc = false;
		$url = false;
		$img = false;
		$result = $this->fetch();
		if ( $result and array_key_exists( 'social_tags', $result ) ) {
			$social_tags = $result['social_tags'];
			if ( $social_tags and array_key_exists( 'ogp', $social_tags ) ) {
				$ogp = $social_tags['ogp'];
				if ( $ogp and array_key_exists( 'title', $ogp ) ) {
					if ( $ogp['title'] ) {
						$title = 1;
					}
				}
				if ( $ogp and array_key_exists( 'desc', $ogp ) ) {
					if ( $ogp['desc'] ) {
						$desc = 1;
					}
				}
				if ( $ogp and array_key_exists( 'url', $ogp ) ) {
					if ( $ogp['url'] ) {
						$url = 1;
					}
				}
				if ( $ogp and array_key_exists( 'img', $ogp ) ) {
					if ( $ogp['img'] ) {
						$img = 1;
					}
				}
			}
		}
		return array(
					'num' => $title + $desc + $url + $img,
					'title' => $title,
					'desc' => $desc,
					'url' => $url,
					'img' => $img,
				);
	}

	//get intel data delivary
	public function intel() {
		$score = $this->score();
		$marks = $score['score'];
		$words = $this->words();
		$ratio = $this->ratio_intel();
		$links = $this->links_intel();
		$keyword = $this->keyword();
		$image = $this->image_intel();
		$share = $this->share();
		$mobile = $this->mobile_intel();
		$url = $this->url_intel();
		$time = $this->time_intel();
		$social_tags_count = $this->social_tags_count();
		return array(
					'marks' => $marks,
					'share' => $share,
					'words' => $words,
					'ratio' => $ratio,
					'links' => $links,
					'keyword' => $keyword,
					'image' => $image,
					'mobile' => $mobile,
					'url' => $url,
					'time' => $time,
					'stags' => $social_tags_count,
				);
	}

	//generate individual words from text content
	public function fetch() {
		$output = false;
		if ( $this->post_id and $this->name ) {
			$output = get_post_meta( $this->post_id, $this->name, true );
		}
		return $output;
	}
}

//get compete data
class CGSS_GET_COMPETE_DB {

	//define properties
	private $name;
	private $post_id;

	//construct the object
	public function __construct( $name, $post_id ) {
		$this->name = $name;
		$this->post_id = $post_id;
	}

	//generate individual words from text content
	public function filter() {
		$result = $this->fetch();
		if ( $result ) {
			$output = array( 'ping' => 'valid' );

			$list_of_keys = array( 'id', 'comp_key', 'comp_url', 'ssl', 'mobile', 'words', 'links', 'links_ext', 'links_nof', 'thr', 'images', 'speed', 'key_count', 'key_per', 'gplus', 'fb', 'tw', 'domain', 'title', 'url', 'desc', 'alt', 'anch', 'plain', 'bold' );
			foreach ( $list_of_keys as $key ) {
				if ( array_key_exists( $key, $result ) ) {
					$output = array_merge( $output, array( $key => $result[$key] ) );
				}
			}
		} else {
			$output = array( 'ping' => 'invalid' );
		}
		return $output;
	}

	//generate individual words from text content
	public function fetch() {
		$output = false;
		if ( $this->post_id and $this->name ) {
			$output = get_post_meta( $this->post_id, $this->name, true );
		}
		return $output;
	}

}

//get server data
class CGSS_GET_SERVER {

	//generate individual words from text content
	public function fetch() {
		$output = false;
		$get_data = get_option( 'cgss_server_seo_data' );
		if ( $get_data ) {
			if( array_key_exists( 'ping', $get_data ) ) {
				$output_ping = $get_data['ping'];
				if ( $output_ping == 'ok' ) {
					$output = $get_data;
				}
			}
		}
		return $output;
	}
}

//get design data
class CGSS_GET_DESIGN {

	//generate individual words from text content
	public function fetch() {
		$output = false;
		$get_data = get_option( 'cgss_design_seo_data' );
		if ( $get_data ) {
			$output = $get_data;
		}
		return $output;
	}
}
?>
