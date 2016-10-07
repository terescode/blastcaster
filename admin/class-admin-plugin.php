<?php
require_once( BC_PLUGIN_DIR . 'includes/class-basic-plugin.php' );

if ( ! class_exists( 'BcAdminPlugin' ) ) {

	abstract class BcAdminPlugin extends BcBasicPlugin {

		const NOTICE_TYPE_ERROR = 'error';
		const NOTICE_TYPE_UPDATED = 'updated';
		const NOTICE_TYPE_NAG = 'update-nag';

		public function __construct( $plugin_id ) {
			parent::__construct( $plugin_id );
		}

		public function init() {
			parent::init();
			// do_action( 'admin_menu', string $context )
			add_action( 'admin_menu', array( $this, 'install_admin_menus' ) );
		}

		protected function create_load_pagenow_hook( $hookname ) {
			return function () use ( $hookname ) {
				$this->add_screen_meta_boxes( $hookname );
			};
		}

		protected function create_admin_notices_hook( $notice, $type, $dismissable ) {
			return function () use ( $notice, $type, $dismissable ) {
				$this->render_admin_notice( $notice, $type, $dismissable );
			};
		}

		public function install_admin_menus() {
			$hooknames = $this->register_admin_menus();
			foreach ( $hooknames as $hookname ) {
				// do_action( 'load-{$pagenow}' )
				add_action( 'load-' . $hookname, $this->create_load_pagenow_hook( $hookname ) );
				// do_action( 'admin_footer-{$hookname}' )
				add_action( 'admin_footer-' . $hookname, array( $this, 'add_script_in_footer' ) );
			}
		}

		abstract public function register_admin_menus();

		public function add_screen_meta_boxes( $hookname ) {
			/* Fire metabox hooks */
			do_action( 'add_meta_boxes_' . $hookname, null );
			do_action( 'add_meta_boxes', $hookname, null );

			/* Ensure postbox is loaded */
			wp_enqueue_script( 'postbox' );
		}

		public function get_script_for_footer() {
			return '<script type="text/javascript">jQuery(function () {postboxes.add_postbox_toggles(pagenow);</script>';
		}

		public function add_script_in_footer() {
			echo $this->get_script_for_footer();
		}

		public function render_admin_notice( $notice, $type, $dismissable ) {
			echo '<div class="' . esc_attr( $type ) . ' notice' . ( $dismissable ? ' is-dismissable' : '' ) . '"><p>';
			echo esc_html( $notice );
			echo '</p></div>';
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		public function add_admin_notice( $notice, $type = self::NOTICE_TYPE_ERROR, $dismissable = true ) {
			add_action( 'admin_notices', $this->create_admin_notices_hook( $notice, $type, $dismissable ) );
		}

		public function get_plugin_id() {
			return $this->plugin_id;
		}
	}

}
