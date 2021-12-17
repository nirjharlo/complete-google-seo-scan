<?php

namespace NirjharLo\Cgss\Src;

if ( ! defined( 'ABSPATH' ) ) exit;

use \NirjharLo\Cgss\Lib\Table;
use \NirjharLo\Cgss\Lib\OverviewTable;


/**
 * Backend settings page class, can have settings fields or data table
 */

	final class Settings {

		public $capability;
		public $menuPage;
		public $subMenuPage;
		public $subMenuPageCpt;
		public $help;
		public $screen;

		// Add basic actions for menu and settings
		public function __construct() {

			$this->capability = 'manage_options';
			$this->menuPage = array(
								'name' => __( 'SEO Scan', 'cgss' ),
								'heading' => __( 'SEO Scan', 'cgss' ),
								'slug' => 'seo-scan'
							);
			$this->subMenuPage = array(
									'name' => __( 'Seo Scan Overview', 'cgss' ),
									'heading' => __( 'Overview', 'cgss' ),
									'slug' => 'seo-scan',
									'parent_slug' => 'seo-scan',
									'help' => false,
									'screen' => true
								);
			$this->subMenuPageCpt = $this->get_post_type_menus();

			$this->screen = ''; // true/false

			add_action( 'admin_menu', array( $this, 'menu_page' ) );
			add_action( 'admin_menu', array( $this, 'sub_menu_page' ) );
			add_action( 'admin_menu', array( $this, 'cpt_sub_menu_page' ) );
			add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );
			add_filter('screen_options_show_screen', array( $this, 'remove_screen_options'));
		}


		public function remove_screen_options() {

			if (isset($_GET['page']) && $_GET['page'] == 'seo-scan') {
				return false;
			} else {
				return true;
			}
		}

		// Get post type data to form menu variables
		public function get_post_type_menus() {

			$menu_vars = array();

			$post_types = get_post_types( array( 'public' => true, ), 'names' );
			unset( $post_types['attachment'] );

			foreach ( $post_types as $type ) {

				$count_posts = wp_count_posts( $type );
				if ( $count_posts->publish > 0 ) {

					$type_name = ucwords( get_post_type_object( $type )->labels->singular_name );
					$menu_vars[] = array(
									'name' => $type_name . ' ' . __( 'Seo Scan', 'cgss' ),
									'heading' => $type_name,
									'slug' => 'seo-scan-' . $type,
									'parent_slug' => 'seo-scan',
									'help' => false,
									'screen' => true
							);
				}
			}

			return $menu_vars;
		}


		// Add main menu page callback
		public function menu_page() {

			if ($this->menuPage) {
				add_menu_page(
					$this->menuPage['name'],
					$this->menuPage['heading'],
					$this->capability,
					$this->menuPage['slug'],
					array( $this, 'overview_content_cb' ),
					'dashicons-search'
				);
			}
		}



		//Add a Submenu page callback
		public function sub_menu_page() {

			if ($this->subMenuPage) {
				$hook = add_submenu_page(
							$this->subMenuPage['parent_slug'],
							$this->subMenuPage['name'],
							$this->subMenuPage['heading'],
							$this->capability,
							$this->subMenuPage['slug'],
							array( $this, 'overview_content_cb' )
				);
				if ($this->subMenuPage['screen']) {
					add_action( 'load-' . $hook, array( $this, 'overview_screen_option' ) );
				}
			}
		}



		// Add Submenu page callback for cpts
		public function cpt_sub_menu_page() {

			if ($this->subMenuPageCpt) {
				foreach ($this->subMenuPageCpt as $value) {
					$hook = add_submenu_page(
							$value['parent_slug'],
							$value['name'],
							$value['heading'],
							$this->capability,
							$value['slug'],
							array( $this, 'cpt_content_cb' )
						);
					if ($value['screen']) {
						add_action( 'load-' . $hook, array( $this, 'screen_option' ) );
					}
				}
			}
		}



		//Set screen option
		public function set_screen($status, $option, $value) {

    		if ( 'post_per_page' == $option ) return $value; // Related to PLUGIN_TABLE()
    			//return $status;
		}



		//Set screen option for Items table
		public function overview_screen_option() {

			$this->overview = new OverviewTable();
		}



		//Set screen option for Items table
		public function screen_option() {

			$option = 'per_page';
			$args   = array(
						'label'   => __( 'Show per page', 'cgss' ),
						'default' => 10,
						'option'  => 'post_per_page' // Related to TABLE()
						);
			add_screen_option( $option, $args );
			$this->table = new Table();
		}



		// Menu page callback
		public function overview_content_cb() { ?>

			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?>
					&nbsp;
					<a href="?page=seo-scan&fetch=true" class="button button-secondary">
						<?php _e( 'Fetch Insight', 'cgss' ); ?>
					</a>
				</h1>
				<br class="clear">
				<?php if (isset($_GET['fetch'])) : ?>
					<?php do_action( 'cgss_fetch_insight' ); ?>
				<?php endif; ?>
				<?php
					// Source: /lib/overview-table.php
					$this->overview = new OverviewTable();
					$this->overview->prepare_items();
					$this->overview->display();
				?>
				<br class="clear">
			</div>
		<?php
		}



		// Add submenu page callback
		public function cpt_content_cb() { ?>

			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<br class="clear">
					<?php if (isset($_GET['scan'])) : ?>
						<?php do_action( 'cgss_scan' ); ?>
					<?php elseif (isset($_GET['compete'])) : ?>
						<?php do_action( 'cgss_compete' ); ?>
					<?php else : ?>
						<form method="post" action="">
						<?php
							// Source: /lib/table.php
							$this->table = new Table();
							$this->table->prepare_items();
							$this->table->display();
						?>
						</form>
					<?php endif; ?>
				<br class="clear">
			</div>
		<?php
		}
	} ?>
