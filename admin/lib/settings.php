<?php
/**
 *
 * @package: onpage-seo-checker/admin/
 * on: 16.07.2016
 * @since 2.5
 * @called_in: ONPAGE_SEO_CHECKER
 *
 * Add settings objects.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Call necessary database objects:
 *
 * 4 Properties:
 * $capability		(string)	user capability
 * $screen			(string)	Current screen
 * $help			(string)	Help object
 * $tabs			(string)	Tabs display
 *
 */
if ( ! class_exists( 'ONSEOCHECK_SETTINGS' ) ) {

	final class ONSEOCHECK_SETTINGS {



		private $capability;
		private $screen;
		private $help;
		private $tabs;



		/**
		 *
		 * Add basic actions for menu and settings
		 *
		 */
		public function __construct() {

			$this->capability = get_option( '_onseocheck_capability' );
			$this->tabs = new ONSEOCHECK_TABS();

			add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );

			add_action( 'admin_menu', array( $this, 'onseocheck_menu_page' ) );
			add_action( 'admin_menu', array( $this, 'onseocheck_submenu_page_add' ) );
			add_action( 'admin_menu', array( $this, 'onseocheck_submenu_page_analysis' ) );
			add_action( 'admin_menu', array( $this, 'onseocheck_submenu_page_action' ) );
			add_action( 'admin_menu', array( $this, 'onseocheck_submenu_page_monitor' ) );
			add_action( 'admin_menu', array( $this, 'onseocheck_submenu_page_settings' ) );
		}



		/**
		 *
		 * Menu callback
		 *
		 */
		public function onseocheck_menu_page() {

			$hook = add_menu_page(
						__( 'Seo Scan', 'onseocheck' ),
						__( 'Seo Scan', 'onseocheck' ),
						$this->capability,
						'onseocheck',
						array( $this, 'onseocheck_check_cb' ) );
			add_action( 'load-' . $hook, array( $this, 'onseocheck_help_tabs' ) );
		}



		/**
		 *
		 * Submenu callback
		 *
		 */
		public function onseocheck_submenu_page_add() {

			$hook = add_submenu_page(
						'onseocheck',
						__( 'Checks', 'onseocheck' ),
						__( 'Checks', 'onseocheck' ),
						$this->capability,
						'onseocheck',
						array( $this, 'onseocheck_check_cb' ) );
			add_action( "load-$hook", array( $this, 'onseocheck_add_screen_option' ) );
			add_action( 'load-' . $hook, array( $this, 'onseocheck_help_tabs' ) );
		}



		/**
		 *
		 * Submenu callback
		 *
		 */
		public function onseocheck_submenu_page_analysis() {

			$hook = add_submenu_page(
						'onseocheck',
						__( 'Analysis', 'onseocheck' ),
						__( 'Analysis', 'onseocheck' ),
						$this->capability,
						'onseocheck-analysis',
						array( $this, 'onseocheck_analysis_cb' ) );
			add_action( 'load-' . $hook, array( $this, 'onseocheck_help_tabs' ) );
		}



		/**
		 *
		 * Submenu callback
		 *
		 */
		public function onseocheck_submenu_page_action() {

			$hook = add_submenu_page(
						'onseocheck',
						__( 'Action', 'onseocheck' ),
						__( 'Action', 'onseocheck' ),
						$this->capability,
						'onseocheck-action',
						array( $this, 'onseocheck_action_cb' ) );
			add_action( 'load-' . $hook, array( $this, 'onseocheck_help_tabs' ) );
		}



		/**
		 *
		 * Submenu callback
		 *
		 */
		public function onseocheck_submenu_page_monitor() {

			$hook = add_submenu_page(
						'onseocheck',
						__( 'Monitor', 'onseocheck' ),
						__( 'Monitor', 'onseocheck' ),
						$this->capability,
						'onseocheck-monitor',
						array( $this, 'onseocheck_monitor_cb' ) );
			add_action( 'load-' . $hook, array( $this, 'onseocheck_help_tabs' ) );
		}



		/**
		 *
		 * Submenu callback
		 *
		 */
		public function onseocheck_submenu_page_settings() {

			$hook = add_submenu_page(
						'onseocheck',
						__( 'Settings', 'onseocheck' ),
						__( 'Settings', 'onseocheck' ),
						$this->capability,
						'onseocheck-settings',
						array( $this, 'onseocheck_settings_cb' ) );
			add_action( 'load-' . $hook, array( $this, 'onseocheck_help_tabs' ) );
		}



		/**
		 *
		 * Todo screen options
		 *
		 */
		public function onseocheck_add_screen_option() {

			$option = 'per_page';
			$args   = array(
						'label'   => __( 'Show Post items per page', 'onseocheck' ),
						'default' => 10,
						'option'  => 'todo_logs_per_page'
					);
			add_screen_option( $option, $args );
			$this->check_obj = new ONSEOCHECK_CHECK();
		}



		/**
		 *
		 * Add help tabs
		 *
		 * NOTE: Instantiate the class here because it requires a property, which needs
		 * to be called inside a callback function
		 *
		 */
		public function onseocheck_help_tabs() {

			$page = ( isset( $_GET['page'] ) ) ? str_replace( '-', '_', esc_attr( $_GET['page'] ) ) : false;

			$this->screen = get_current_screen();
			$this->help = new ONSEOCHECK_HELP( $page );

			foreach ( $this->help->info as $key ) {
				$this->screen->add_help_tab( $key );
			}
			$this->screen->set_help_sidebar( $this->help->link );
		}



		/**
		 *
		 * Check page Callback display. Calling in tabs and display table
		 *
		 */
		public function onseocheck_check_cb() { ?>

			<div class="wrap">
				<h2><?php _e( 'Scan Pages', 'onseocheck' ); ?>
				 <a href="<?php echo '?page=onseocheck-analysis'; ?>" class="page-title-action"><div class="dashicons dashicons-chart-pie" style="padding-top: 7px;"></div> <?php _e( 'Analysis', 'onseocheck' ); ?></a>
				</h2>
				<br class="clear">
				<?php settings_errors();
				$page = ( isset( $_GET['page'] ) ? $_GET['page'] : 'onseocheck' );
				$tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : '0' );
				$this->tabs->check_tabs( $page, $tab ); ?>
				<form action="" method="post">
					<?php
					$this->check_obj->prepare_items();
					$this->check_obj->display(); ?>
				</form>
				<br class="clear">
			</div>
		<?php
		}
	}
} ?>
