<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Perform fetch insight action
 */
if ( ! class_exists( 'CGSS_INSIGHT' ) ) {

	final class CGSS_INSIGHT {


		public function __construct() {
				
				$this->data = $this->fetch();
				$this->compile();
				$this->save();
		}


		//DB insert function
		public function save() {

			global $wpdb;

			$result = array(
				$this->score,
				$this->snippet,
				$this->text,
				$this->links,
				$this->keywords,
				$this->images,
				$this->responsive,
				$this->speed,
				$this->social,
				);
			foreach ($result as $key => $value) {
				$sql = $wpdb->prepare("UPDATE {$wpdb->prefix}cgss_insight SET remark = %s WHERE ID = %d", $value, ($key+1));
				$update = $wpdb->query($sql);
			}
		}


		//Compile the result
		public function compile() {

			$score = $this->data['score'];
			$this->count = count($score);
			$avarage_score = round(array_sum($score)/count($score), 0);

			$this->score = sprintf(__('Avarage SEO score is %d', 'cgss'),$avarage_score);
			$this->snippet = $this->snippet_analyze();
			$this->text = $this->text_analyze();
			$this->links = $this->link_analyze();
			$this->keywords = $this->keyword_analyze();
			$this->images = $this->image_analyze();
			$this->responsive = $this->resposivity();
			$this->speed = $this->speed_analyze();
			$this->social = $this->social_analyze();

		}


		// Analysis of links
		public function link_analyze() {

			$text = $this->data['text'];
			$links = array_column($text, 'links');
			$count = round(array_sum(array_column($links, 'count')) / $this->count, 0);
			$external = round(array_sum(array_column($links, 'external')) / $this->count, 0);
			$nofollow = round(array_sum(array_column($links, 'external')) / $this->count, 0);
			$external_percentage = round(($external/$count)*100, 0);
			$nofollow_percentage = round(($nofollow/$count)*100, 0);

			$output = sprintf(__('Avarage', 'cgss') . ' '.  _n( '%d link','%d links', $count, 'cgss' ) . ' ' . __('are found per page and %d percent are external and %d percent are nofollow among them.', 'cgss'),$count,$external_percentage,$nofollow_percentage);
			return $output;
		}


		// Analyze keywords
		public function keyword_analyze() {

			$text = $this->data['text'];
			$keywords = array_column($text, 'keys');

			$key_collect = array();
			$percent_collect = array();
			foreach ($keywords as $keyword) {
				$keys = array_keys($keyword);
				$top_key = $keys[0];
				$key_collect[] = count(explode(' ', $top_key));
				$percent_collect[] = $keyword[$top_key];
			}

			$key_count = round(array_sum($key_collect) / $this->count, 1);
			$percent = round(array_sum($percent_collect) / $this->count, 1);

			$output = sprintf(__('Avarage foucs keyword is', 'cgss') . ' ' . _n( '%d word','%d words',$key_count, 'cgss' ) . ' ' . __('long with keywords frequency of %d percent','cgss'),$key_count,$percent);

			return $output;
		}


		// Analyze images
		public function image_analyze() {

			$design = $this->data['design'];
			$images = array_column($design, 'image');

			$image_count = array_sum(array_column($images, 'count'));
			$no_alt_image = array_sum(array_column($images, 'no_alt_count'));

			$avg_image = round(($image_count/$this->count), 0);

			$output = sprintf(__('Avarage', 'cgss') . ' ' . _n( '%d image', '%d images', $avg_image, 'cgss' ) . ' ' . __( 'are found per page and', 'cgss'),$avg_image) . ' ';
			if ($no_alt_image == 0) {
				$output .= __('all of them are optimized', 'cgss');
			} else {
				$no_alt_percent = round(($no_alt_image/$image_count)*100, 0);
				$output .= sprintf(__('%d percent among them doesn\'t have alt tag', 'cgss'),$no_alt_percent);
			}
			return $output;

		}


		// Check mobile optimized
		public function resposivity() {

			$design = $this->data['design'];
			$vport = array_sum(array_column($design, 'vport'));

			if ($vport == $this->count) {
				$output = __('All pages are mobile optimized', 'cgss');
			} else {
				$no_mobile_percent = round(($vport/$this->count)*100, 0);
				$output = sprintf(__('%d percent pages aren\'t mobile optimized', 'cgss'),$no_mobile_percent);
			}

			return $output;
		}


		// Speed analyze
		public function speed_analyze() {

			$speed = $this->data['speed'];
			$res_time = array_sum(array_column($speed, 'res_time'));
			$down_time = array_sum(array_column($speed, 'down_time'));

			$avg_res_time = round(($res_time/$this->count), 2);
			$avg_down_time = round(($down_time/$this->count), 2);

			$output = sprintf( __('Average response time is %d s and average download time is %d s', 'cgss'), $avg_res_time, $avg_down_time);

			return $output;
		}


		// Count social shares
		public function social_analyze() {

			$social = $this->data['social'];

			$gplus = array_sum(array_column($social, 'gplus'));
			$fb_share = array_sum(array_column($social, 'fb_share'));
			$fb_like = array_sum(array_column($social, 'fb_like'));

			$output = sprintf( __('Total', 'cgss' ). ' ' . _n( '%d share', '%d shares', $gplus, 'cgss' ) . ' ' . __( 'in g+ and', 'cgss' ). ' ' . _n( '%d share', '%d shares', $fb_share, 'cgss' ) . ' ' . __( 'in FB and', 'cgss' ). ' ' . _n( '%d FB like', '%d FB likes', $fb_like, 'cgss' ) . ' ' . __( 'are present', 'cgss'), $gplus, $fb_share, $fb_like);

			return $output;
		}


		//Analyze text
		public function text_analyze() {

			$text = $this->data['text'];
			$count = round(array_sum(array_column($text, 'count')) / $this->count, 0);
			$ratio = round(array_sum(array_column($text, 'ratio')) / $this->count, 2);

			$output = sprintf(__('Avarage', 'cgss') . ' ' . _n('%d word','%d words',$count,'cgss') . ' ' . __( 'are found per page and avarage text to HTML ratio is %d percent', 'cgss'),$count,$ratio);
			return $output;
		}


		//Analyze snippets
		public function snippet_analyze() {

			$snippets = $this->data['snip'];
			$snip_count = 0;
			foreach ($snippets as $snippet) {
				$title = $snippet['title'];
				$desc = $snippet['desc'];
				if (!empty($title) && !empty($desc)) {
					$snip_count++;
				}
			}
			$snip_fraction = $this->count - $snip_count;

			$output = ($snip_fraction > 0) ? __( 'All snippets are ok', 'cgss' ) : sprintf(_n('%d page', '%d pages', $snip_fraction, 'cgss'), $snip_fraction) . ' ' . __( 'have incomplete snippets', 'cgss' );
			return $output;
		}


		//Fetch the scan results
		public function fetch() {

			global $wpdb;
			$sql = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type!='attachment'";
			$ids = $wpdb->get_results( $sql, 'ARRAY_A' );;

			$data_piece = array();
			foreach ($ids as $id) {

				$meta = get_post_meta( $id['ID'], 'cgss_scan_result', true );
				if ($meta) {
					$data_piece[] = $meta;
				}
			}

			$data = array();
			$data['score'] = array_column($data_piece, 'score');
			$data['snip'] = array_column($data_piece, 'snip');
			$data['social'] = array_column($data_piece, 'social');
			$data['text'] = array_column($data_piece, 'text');
			$data['design'] = array_column($data_piece, 'design');
			$data['crawl'] = array_column($data_piece, 'crawl');
			$data['speed'] = array_column($data_piece, 'speed');
			$data['social_tags'] = array_column($data_piece, 'social_tags');

			return $data;
		}
	}
} ?>