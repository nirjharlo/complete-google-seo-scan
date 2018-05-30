<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Backend settings page class, can have settings fields or data table
 */
if ( ! class_exists( 'CGSS_SETTINGS' ) ) {

	final class CGSS_SETTINGS {

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
									'name' => __( 'Overview', 'cgss' ),
									'heading' => __( 'Overview', 'cgss' ),
									'slug' => 'seo-scan',
									'parent_slug' => 'seo-scan',
									'help' => true,
									'screen' => false
								);
			$this->subMenuPageCpt = $this->get_post_type_menus();

			$this->helpData = array(
								array(
								'slug' => '',
								'help' => array(
											'info' => array(
														array(
															'id' => 'helpId',
															'title' => __( 'Title', 'textdomain' ),
															'content' => __( 'Description', 'textdomain' ),
														),
													),
											'link' => '<p><a href="#">' . __( 'helpLink', 'textdomain' ) . '</a></p>',
											)
								)
							);
			$this->screen = ''; // true/false

			add_action( 'admin_menu', array( $this, 'add_settings' ) );
			add_action( 'admin_menu', array( $this, 'menu_page' ) );
			add_action( 'admin_menu', array( $this, 'sub_menu_page' ) );
			add_action( 'admin_menu', array( $this, 'cpt_sub_menu_page' ) );
			add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );
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
									'help' => true,
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
					if ($this->subMenuPage['help']) {
						add_action( 'load-' . $hook, array( $this, 'help_tabs' ) );
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
					if ($value['help']) {
						add_action( 'load-' . $hook, array( $this, 'help_tabs' ) );
					}
					if ($value['screen']) {
						add_action( 'load-' . $hook, array( $this, 'screen_option' ) );
					}
				}
			}
		}



		//Set screen option
		public function set_screen($status, $option, $value) {
 
    		if ( 'option_name_per_page' == $option ) return $value; // Related to PLUGIN_TABLE()
    			//return $status; 
		}



		//Set screen option for Items table
		public function screen_option() {

			$option = 'per_page';
			$args   = array(
						'label'   => __( 'Show per page', '' ),
						'default' => 10,
						'option'  => 'option_name_per_page' // Related to PLUGIN_TABLE()
						);
			add_screen_option( $option, $args );
			$this->Table = new PLUGIN_TABLE();
		}



		// Menu page callback
		public function overview_content_cb() { ?>

			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<br class="clear">
				<?php settings_errors(); ?>

				<br class="clear">
			</div>
		<?php
		}



		// Add submenu page callback
		public function cpt_content_cb() { ?>

			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<br class="clear">
				<?php settings_errors(); ?>
					<form method="post" action="">
					<?php
							// Source: /lib/table.php
							$table = new CGSS_TABLE();
							$table->prepare_items();
							$table->display();
						?>
					</form>
				<br class="clear">
			</div>
		<?php
		}



		// Add help tabs using help data
		public function help_tabs() {

			foreach ($this->helpData as $value) {
				if ($_GET['page'] == $value['slug']) {
					$this->screen = get_current_screen();
					foreach( $value['info'] as $key ) {
						$this->screen->add_help_tab( $key );
					}
					$this->screen->set_help_sidebar( $value['link'] );
				}
			}
		}



		//Add different types of settings and corrosponding sections
		public function add_settings() {

			add_settings_section( 'SettingsId', __( 'Section Name', 'textdomain' ), array( $this,'SectionCb' ), 'SettingsName' );
			register_setting( 'SettingsId', 'SettingsField' );
			add_settings_field( 'SettingsFieldName', __( 'Field Name', 'textdomain' ), array( $this, 'SettingsFieldCb' ), 'SettingsName', 'SettingsId' );
		}



		//Section description
		public function SectionCb() {

			echo '<p class="description">' . __( 'Set up settings', 'textdomain' ) . '</p>';
		}



		//Field explanation
		public function SettingsFieldCb() {

			echo '<input type="text" class="medium-text" name="SettingsFieldName" id="SettingsFieldName" value="' . get_option('SettingsFieldName') . '" placeholder="' . __( 'Enter Value', 'textdomain' ) . '" required />';
		}
	}
} ?>