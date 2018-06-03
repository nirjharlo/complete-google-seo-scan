<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Perform fetch insight action
 */
if ( ! class_exists( 'CGSS_INSIGHT' ) ) {

	final class CGSS_INSIGHT {


		public function __construct() {
				
				$this->data = $this->fetch();
				$result = $this->compile();
				$this->save($result);
		}


		//DB insert function
		public function save() {

			global $wpdb;

			$sql = $wpdb->prepare("UPDATE {$wpdb->prefix}cgss_insight SET remark = %d WHERE ID = %d", $remark, $ID);
			$update = $wpdb->query($sql);
		}


		//Compile the result
		public function compile() {

			$score = $this->data['score'];
			$this->count = count($score);
			$this->score = round(array_sum($score)/count($score), 0);
			$this->snippet = $this->snippet_analyze();
			$this->text = $this->text_analyze();

		}


		//Analyze text
		public function text_analyze() {

			$text = $this->data['text'];
			$count = array_sum(array_column($text, 'count')) / $this->count;
			$ratio = array_sum(array_column($text, 'ratio')) / $this->count;

			$output = sprintf(__('Avarage %d words are found and avarage text to HTML ratio is %d', 'cgss'),$count,$ratio);
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

			$output = ($snip_fraction > 0) ? __( 'All snippets are ok', 'cgss' ) : $snip_fraction . ' ' . __( 'pages have incomplete snippets', 'cgss' );
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