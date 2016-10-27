<?php
/**
 *
 * @package: onpage-seo-checker/user/lib/display/
 * on: 15.08.2016
 * @since 2.5
 * @called_in: ONPAGE_SETTINGS
 *
 * Add tabs in plugin pages.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 1 Property:
 * $user_path		(string)	Definition of path
 *
 */
if ( ! class_exists( 'ONSEOCHECK_TABS' ) ) {

	class ONSEOCHECK_TABS {



		/**
		 *
		 * The filter before outputing the display
		 *
		 */
		public function filter( $tabs, $page, $current ) { ?>

			<h2 class="nav-tab-wrapper">
				<?php foreach( $tabs as $tab => $name ) :
					$class = ( $tab == $current ) ? ' nav-tab-active' : '';
					echo '<a class="nav-tab' . $class . '" href="?page=' . $page . '&tab=' . $tab . '">' . $name . '</a>';
				endforeach; ?>
			</h2>
			<?php
		}



		/**
		 *
		 * The all console data page
		 *
		 */
		public function check_tabs( $page, $current ) {

			$post_types = get_post_types( array( 'public' => true, ), 'names' );
			unset( $post_types['attachment'] );

			$remove = get_option('onseocheck_show_post_types');
			if ( ! $remove ) {
				$remove = $post_types;
			}

			$tabs = array();
			if ( $post_types and is_array( $post_types ) and count( $post_types ) > 0 ) {
				foreach ( $post_types as $type ) {
					$count_posts = wp_count_posts( $type );
					if ( $count_posts->publish > 0 ) {
						if ( in_array( $type, $remove ) ) {
							$tabs[] = ucwords( get_post_type_object( $type )->labels->singular_name ) . '<sup>' . $count_posts->publish . '</sup>';
						}
					}
				}
			}
			$this->filter( $tabs, $page, $current );
		}
	}
} ?>
