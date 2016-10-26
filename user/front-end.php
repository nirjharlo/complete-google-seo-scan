<?php
/**
 * @/user/front-end.php
 * on: 02.07.2015
 * A compilation of classes and derived display classes for scan form and report display.
 *
 * It has 2 part:
 * 1. Include custom objects by required() function.
 * 2. Create derived objects and functions. MUST Maintain sequence.
 */

//Required class files, essential for report display. You may use them in any sequence as they are
//independent of each other. But must call them before running this file.
require_once( 'lib/btn-object.php' );
require_once( 'lib/dropdown-object.php' );
require_once( 'lib/table-object.php' );
require_once( 'lib/heading-object.php' );
require_once( 'lib/textarea-object.php' );
require_once( 'lib/snippet-object.php' );


//create more customize button group function
class cgss_group_btn {

	//button group for heading
	public function head() {
		$this->hbtn_one = new CGSS_BTN( 'SharePlug', 'smiley push-icon-top', ' ' . __( 'SHARE PLUGIN', 'cgss' ), null, null );
		return $this->hbtn_one->display() . $this->share();
	}

	//button group for report
	public function report() {
		$this->twobtn = new CGSS_BTN( 'DoEmail', 'email-alt', ' ' . __( 'EMAIL', 'cgss' ), null, null );
		$this->btns = $this->onebtn->display() . $this->twobtn->display();
		return '<div class="result-btns"><br />' . $this->btns .	'</div>';
	}

	//button group for sharing: google+, facebook, twitter
	public function share() {
		$this->gbtn = new CGSS_BTN( 'ShareGp', 'googleplus push-icon-top', null, 'https://plus.google.com/share?url=http%3A%2F%2Fgogretel.com%2Fcomplete-google-seo-scan-plugin%2F" target="_blank', null );
		$this->fbbtn = new CGSS_BTN( 'ShareFb', 'facebook push-icon-top', null, 'https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fgogretel.com%2Fcomplete-google-seo-scan-plugin%2F&t=Complete-Google-Seo-Scan-Plugin-for-WordPress" target="_blank', null );
		$this->twbtn = new CGSS_BTN( 'ShareTw', 'twitter push-icon-top', null, 'https://twitter.com/share?url=http%3A%2F%2Fgogretel.com%2Fcomplete-google-seo-scan-plugin%2F&via=gogretel&text=Complete-Google-Seo-Scan-Plugin-for-WordPress" target="_blank', null );
		return $this->gbtn->display() . $this->fbbtn->display() . $this->twbtn->display();
	}
}


//Custom heading class with inbuilt text variables.
class cgss_do_title {

	//for status page heading
	public function top() {
		$this->btn_top = new cgss_group_btn();
		$this->hd = new CGSS_HEADING( 1, __( 'Seo Status', 'cgss' ), ' ' . $this->btn_top->head(), __( 'See overall and specific seo points of pages in', 'cgss' ) . ' ' . get_bloginfo('name') . '. ' . __( 'Check for documentation in Help tab above.', 'cgss' ), 'about-text' );
		return '<div class="point-releases status-header">' . $this->hd->display() . '</div>';
	}

	//for keyword section
	public function content() {
		$this->hd = new CGSS_HEADING( 2, __( 'Content', 'cgss' ), '', '', null );
		return $this->hd->display();
	}

	//for speed section
	public function usability() {
		$this->hd = new CGSS_HEADING( 2, __( 'Usability', 'cgss' ), '', '', null );
		return $this->hd->display();
	}
}


//Custom table class with inbuilt data as array.
class cgss_form_table {

	//
	private $form_post_type;

	//
	public function __construct( $form_post_type ) {
		$this->form_post_type = $form_post_type;
	}

	//for table form tables
	public function form() {

			$type = $this->form_post_type;
			$this->tbl_hd_data = array(
				array(
					'id' => false,
					'class' => 'manage-column column-title',
					'icon_class' => false,
					'val' => $type[1] . ' ' . __( 'Title', 'cgss' ),
				),
				array(
					'id' => false,
					'class' => 'manage-column column-categories',
					'icon_class' => 'images-alt2',
					'val' => false,
				),
				array(
					'id' => false,
					'class' => 'manage-column column-categories',
					'icon_class' => 'admin-links',
					'val' => false,
				),
				array(
					'id' => false,
					'class' => 'manage-column column-categories',
					'icon_class' => 'share',
					'val' => false,
				),
				array(
					'id' => false,
					'class' => 'manage-column column-categories',
					'icon_class' => 'editor-paste-word',
					'val' => false,
				),
			);

			//create input array for table content
			$form_data_args = array( 'posts_per_page' => -1, 'post_type' => $type[0] );
			$form_data = get_posts($form_data_args);

			//Get $scan variable for results of previous scans
			$pre_scan_data = scan_data();
			$scan_data = $pre_scan_data[0];

			$this->tbl_data = array();
			foreach ( $form_data as $val ) {
				$post_id = $val->ID;

				//Utilize result from previous scans
				$scan = false;
				$tsince_ind = false;
				$gscore = 0;
				if ( array_key_exists( $post_id, $scan_data ) ) {
					$scan = $scan_data[$post_id];
				}
				if ( $scan and array_key_exists( 'since', $scan ) ) {
					$tsince = $scan['since'];
				} else {
					$tsince = __( 'Never', 'cgss' );
				}
				if ( $scan and array_key_exists( 'since_sec', $scan ) ) {
						$tsince_ind = $scan['since_sec'];
				}
				if ( ! $tsince_ind ) {
					$tsince_ind = 'nil';
				}
				if ( $scan and array_key_exists( 'score', $scan ) ) {
					$gscore = $scan['score'];
					switch ( $gscore ) {
						case 1:
							$tscore = '<span class="dashicons dashicons-star-filled warning-icon"></span>' . str_repeat( '<span class="dashicons dashicons-star-empty warning-icon"></span>', 4 );
							break;
						case 1.5:
							$tscore = '<span class="dashicons dashicons-star-filled warning-icon"></span>' . '<span class="dashicons dashicons-star-half warning-icon"></span>' . str_repeat( '<span class="dashicons dashicons-star-empty warning-icon"></span>', 3 );
							break;
						case 2:
							$tscore = str_repeat( '<span class="dashicons dashicons-star-filled warning-icon"></span>', 2 ) . str_repeat( '<span class="dashicons dashicons-star-empty warning-icon"></span>', 3 );
							break;
						case 2.5:
							$tscore = str_repeat( '<span class="dashicons dashicons-star-filled warning-icon"></span>', 2 ) . '<span class="dashicons dashicons-star-half warning-icon"></span>' . str_repeat( '<span class="dashicons dashicons-star-empty warning-icon"></span>', 2 );
							break;
						case 3:
							$tscore = str_repeat( '<span class="dashicons dashicons-star-filled warning-icon"></span>', 3 ) . str_repeat( '<span class="dashicons dashicons-star-empty warning-icon"></span>', 2 );
							break;
						case 3.5:
							$tscore = str_repeat( '<span class="dashicons dashicons-star-filled warning-icon"></span>', 3 ) . '<span class="dashicons dashicons-star-half warning-icon"></span>' . '<span class="dashicons dashicons-star-empty warning-icon"></span>';
							break;
						case 4:
							$tscore = str_repeat( '<span class="dashicons dashicons-star-filled warning-icon"></span>', 4 ) . '<span class="dashicons dashicons-star-empty warning-icon"></span>';
							break;
						case 4.5:
							$tscore = str_repeat( '<span class="dashicons dashicons-star-filled warning-icon"></span>', 4 ) . '<span class="dashicons dashicons-star-half warning-icon"></span>';
							break;
						case 5:
							$tscore = str_repeat( '<span class="dashicons dashicons-star-filled warning-icon"></span>', 5 );
							break;
						default:
							$tscore = '<span class="dashicons dashicons-heart danger-icon"></span>';
							break;
					}
				} else {
					$tscore = '<span class="dashicons dashicons-heart danger-icon"></span>';
				}
				if ( $scan and array_key_exists( 'links', $scan ) ) {
					if ( $scan['links'] != 0 ) {
						$tlinks = $scan['links'];
					} else {
						$tlinks = '--';
					}
				} else {
					$tlinks = '--';
				}
				if ( $scan and array_key_exists( 'share', $scan ) ) {
					if ( $scan['share'] != 0 ) {
						$tshare = $scan['share'];
					} else {
						$tshare = '--';
					}
				} else {
					$tshare = '--';
				}
				if ( $scan and array_key_exists( 'key', $scan ) ) {
					if ( $scan['key'] ) {
						$tkey = $scan['key'];
					} else {
						$tkey = '--';
					}
				} else {
					$tkey = '--';
				}
				if ( $scan and array_key_exists( 'marks', $scan ) ) {
					if ( $scan['marks'] != 0 ) {
						$tmarks = $scan['marks'];
					} else {
						$tmarks = '--';
					}
				} else {
					$tmarks = '--';
				}
				if ( $scan and array_key_exists( 'img', $scan ) ) {
					if ( $scan['img'] != 0 ) {
						$timages = $scan['img'];
					} else {
						$timages = '--';
					}
				} else {
					$timages = '--';
				}

				//prepare table data
				$row_identity = '<span id="' . $tmarks . '" class="exact-no-got-' . $post_id . ' hide"></span>';
				$row_btm_view = '<div class="row-actions"><span class="view"><a target="_blank" href="' . get_permalink( $post_id ) . '"><span class="dashicons dashicons-external"></span></a></span> | <span class="view"><a target="_blank" href="' . get_bloginfo( 'url' ) . '/wp-admin/post.php?post=' . $post_id . '&action=edit"><span class="dashicons dashicons-edit"></span></a></span><span class="score-in-form">' . str_repeat( '&nbsp;', 3 ) . '<span id="score-' . $post_id . '">' . $tscore . '</span></span><span class="time-in-form">' . str_repeat( '&nbsp;', 3 ) . '<span class="view"><span id="time-' . $post_id . '"><span class="dashicons dashicons-clock"></span> ' . $tsince . '</span></span></span>' . $row_identity . '</div>';
				$this->tbl_data[] = array(
					array(
						'id' => $gscore . '-' . $tsince_ind,
						'class' => 'check-column button-page-scan-' . $post_id . '" scope="row',
						'val' => '<input class="scan-url-input" id="' . $post_id . '" type="radio" name="scan-url" value="' . get_permalink( $post_id ) . '" />',
					),
					array(
						'id' => false,
						'class' => 'post-title page-title column-title',
						'val' => get_the_title( $post_id ) . ' <a class="view-again" id="ViewAgain-' . $post_id . '"><span class="dashicons dashicons-editor-expand"></span></a>' . $row_btm_view,
					),
					array(
						'id' => false,
						'class' => 'comments column-author',
						'val' => '<span class="images-no-got-' . $post_id . '">' . $timages . '</span>',
					),
					array(
						'id' => false,
						'class' => 'comments column-author',
						'val' => '<span class="links-no-got-' . $post_id . '">' . $tlinks . '</span>',
					),
					array(
						'id' => false,
						'class' => 'comments column-author',
						'val' => '<span class="shares-no-got-' . $post_id . '">' . $tshare . '</span>',
					),
					array(
						'id' => false,
						'class' => 'categories column-categories',
						'val' => '<span class="keys-no-got-' . $post_id . '">' . $tkey . '</span>',
					),
				);
			}

			//instantiate the table
			$this->tbl = new  CGSS_TABLE( 'PTID' . $type[0], 'wp-list-table widefat fixed striped pages cgss-table', 'iedit author-self type-post status-publish format-standard has-post-thumbnail hentry', true, $this->tbl_hd_data, $this->tbl_data );
			return $this->tbl->display();
	}
}


/**
*
* The object cgss_form_table() for Forms is different from Standard table object cgss_do_table()
* Because the first one has a constructing property, the second one doesn't have.
* cgss_form_table() is thus different and can't be used as following cgss_do_table()
*
*/

//Custom table class with inbuilt data as array.
class cgss_do_table {

	//for data processing table
	public function process() {

			$this->tbl_hd_data = false;

			//create input array for table content
			$this->tbl_data = array(
				array(
					array(
						'id' => false,
						'class' => 'check-column" scope="row',
						'val' => '<span id="Pros1" class="dashicons dashicons-yes success-icon"></span>',
					),
					array(
						'id' => false,
						'class' => false,
						'val' => '<br /><span class="process-push-left">' . __( 'Send Request', 'cgss' ) . '</span><br /><br />',
					),
				),
				array(
					array(
						'id' => false,
						'class' => 'check-column" scope="row',
						'val' => '<span id="Pros2" class="dashicons dashicons-yes success-icon"></span>',
					),
					array(
						'id' => false,
						'class' => false,
						'val' => '<br /><span class="process-push-left">' . __( 'Validate Request', 'cgss' ) . '</span><br /><br />',
					),
				),
				array(
					array(
						'id' => false,
						'class' => 'check-column" scope="row',
						'val' => '<span id="Pros3" class="dashicons dashicons-yes success-icon"></span>',
					),
					array(
						'id' => false,
						'class' => false,
						'val' => '<br /><span class="process-push-left">' . __( 'Download Content', 'cgss' ) . '</span><br /><br />',
					),
				),
				array(
					array(
						'id' => false,
						'class' => 'check-column" scope="row',
						'val' => '<span id="Pros4" class="dashicons dashicons-yes success-icon"></span>',
					),
					array(
						'id' => false,
						'class' => false,
						'val' => '<br /><span class="process-push-left">' . __( 'Analyze Data', 'cgss' ) . '</span><br /><br />',
					),
				),
				array(
					array(
						'id' => false,
						'class' => 'check-column" scope="row',
						'val' => '<span id="Pros5" class="dashicons dashicons-yes success-icon"></span>',
					),
					array(
						'id' => false,
						'class' => false,
						'val' => '<br /><span class="process-push-left">' . __( 'Save Information', 'cgss' ) . '</span><br /><br />',
					),
				),
				array(
					array(
						'id' => false,
						'class' => 'check-column" scope="row',
						'val' => '<span id="Pros6" class="dashicons dashicons-yes success-icon"></span>',
					),
					array(
						'id' => false,
						'class' => false,
						'val' => '<br /><span class="process-push-left">' . __( 'Fetch Results', 'cgss' ) . '</span><br /><br />',
					),
				),
			);

			//instantiate the table
			$this->tbl = new  CGSS_TABLE( 'ProcessingData', 'fixed', false, true, $this->tbl_hd_data, $this->tbl_data );
			return $this->tbl->display();
	}
}


//Get list of post types with nick-name and visible name
class cgss_do_dpd {

	//
	public function post_types() {
		$post_types = get_post_types( '', 'names' );
		foreach( array( 'attachment', 'revision', 'nav_menu_item' ) as $val ) {
			unset( $post_types[$val] );
		}
		$post_type_list = array();
		foreach( $post_types as $val ) {
			$post_type_list[] = array( $val, ucwords( get_post_type_object( $val )->labels->singular_name ) );
		}
		return $post_type_list;
	}

	public function types() {
		$this->dpd = new CGSS_DROPDOWN( 'cgss_post_type_select', 'cgss-post-type', $this->post_types(), 'submit-cgss-post-type', __( 'SHOW POST TYPES', 'cgss' ) );
		return $this->dpd->display();
	}

	//
	public function filter() {
		$this->dpd = new CGSS_DROPDOWN( array( 'cgss_score_filter', 'cgss_time_filter' ), array( 'cgss-score-filter', 'cgss-time-filter' ), array( $this->score(), $this->time() ), 'submit-cgss-filter', __( 'FILTER', 'cgss' ) );
		return $this->dpd->double_display();
	}

	//
	public function score() {
		return array(
					array( 'selected', __( 'Score', 'cgss' ) ),
					array( '1', '1 ' . __( 'Star', 'cgss' ) ),
					array( '1.5', '1.5 ' . __( 'Stars', 'cgss' ) ),
					array( '2', '2 ' . __( 'Stars', 'cgss' ) ),
					array( '2.5', '2.5 ' . __( 'Stars', 'cgss' ) ),
					array( '3', '3 ' . __( 'Stars', 'cgss' ) ),
					array( '3.5', '3.5 ' . __( 'Stars', 'cgss' ) ),
					array( '4', '4 ' . __( 'Stars', 'cgss' ) ),
					array( '4.5', '4.5 ' . __( 'Stars', 'cgss' ) ),
					array( '5', '5 ' . __( 'Stars', 'cgss' ) ),
				);
	}

	//
	public function time() {
		return array(
					array( 'selected', __( 'Time', 'cgss' ) ),
					array( '3600', __( 'This hour', 'cgss' ) ),
					array( '86400', __( 'Today', 'cgss' ) ),
					array( '604800', __( '7 days', 'cgss' ) ),
					array( '2592000', __( '30 days', 'cgss' ) ),
					array( '7776000', __( '90 days', 'cgss' ) ),
					array( 'nil', __( 'Never', 'cgss' ) ),
				);
	}
}
?>
