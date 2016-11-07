<?php

require_once BC_PLUGIN_DIR . 'includes/interface-plugin.php';
require_once BC_PLUGIN_DIR . 'includes/interface-admin-plugin.php';
require_once BC_PLUGIN_DIR . 'includes/interface-controller.php';
require_once BC_PLUGIN_DIR . 'includes/class-callback-wrapper.php';

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
if ( ! class_exists( 'TcPluginHelper' ) ) {
	class TcPluginHelper {

		const NOTICE_TYPE_ERROR = 'error';
		const NOTICE_TYPE_UPDATED = 'updated';
		const NOTICE_TYPE_NAG = 'update-nag';

		private $wph;

		function __construct( $wph ) {
			$this->wph = $wph;
		}

		function init_plugin( TcPlugin $plugin ) {
			$plugin_file = $this->plugin_file_name( $plugin );
			// Register activation
			$this->wph->register_activation_hook( $plugin_file, array( $plugin, 'activate' ) );
			// Register deactivation
			$this->wph->register_deactivation_hook( $plugin_file , array( $plugin, 'deactivate' ) );
			// do_action( 'plugins_loaded' )
			$this->wph->add_action( 'plugins_loaded', array( $plugin, 'load' ) );
		}

		function init_admin_plugin( TcAdminPlugin $plugin ) {
			$this->init_plugin( $plugin );
			// do_action( 'admin_menu', string $context )
			$this->wph->add_action( 'admin_menu', array( $plugin, 'install_admin_menus' ) );
		}

		function load_textdomain( TcPlugin $plugin ) {
			$this->wph->load_plugin_textdomain(
				$plugin->get_plugin_id(),
				false,
				BC_PLUGIN_DIR . '/languages/'
			);
		}

		function plugin_file_name( TcPlugin $plugin ) {
			return BC_PLUGIN_DIR . '/' . $plugin->get_plugin_id() . '.php';
		}

		function load_pagenow( $hookname ) {
			/* Fire metabox hooks */
			$this->wph->do_action( 'add_meta_boxes_' . $hookname, null );
			$this->wph->do_action( 'add_meta_boxes', $hookname, null );

			/* Ensure postbox is loaded */
			$this->wph->wp_enqueue_script( 'postbox' );
		}

		function admin_notices( $notice, $type, $dismissable ) {
			echo '<div class="' . $this->wph->esc_attr( $type ) . ' notice' . ( $dismissable ? ' is-dismissable' : '' ) . '"><p>';
			echo $this->wph->esc_html( $notice );
			echo '</p></div>';
		}


		function add_postbox_script_in_footer() {
			echo '<script type="text/javascript">jQuery(function () { postboxes.add_postbox_toggles(pagenow); });</script>';
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		function add_admin_notice( $notice, $type = self::NOTICE_TYPE_ERROR, $dismissable = true ) {
			$delegate = new TcCallbackWrapper( array( $this, 'admin_notices' ), $notice, $type, $dismissable );
			$this->wph->add_action( 'admin_notices', array( $delegate, 'call' ) );
		}

		function install_admin_menus( $controllers ) {
			$hooknames = array();

			foreach ( $controllers as $controller ) {
				$hook = $controller->register_menu();
				if ( $hook ) {
					$hooknames[] = $hook;
				}
			}

			foreach ( $hooknames as $hookname ) {
				// do_action( 'load-{$pagenow}' )
				$delegate = new TcCallbackWrapper( array( $this, 'load_pagenow' ), $hookname );
				$this->wph->add_action( 'load-' . $hookname, array( $delegate, 'call' ) );
				// do_action( 'admin_footer-{$hookname}' )
				$this->wph->add_action(
					'admin_footer-' . $hookname,
					array( $this, 'add_postbox_script_in_footer' )
				);
			}

			return $hooknames;
		}

		function get_wp_helper() {
			return $this->wph;
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedLocalVariable) The controller is intentionally made
		 * available to the view as $tc_controller.
		 */
		function render( TcController $controller, $view, $capability = null, $objid = null ) {
			$render = ( null !== $capability ? $this->wph->current_user_can( $capability, $objid ) : true );
			if ( $render ) {
				$tc_controller = $controller;
				$wph = $this->wph;
				include( BC_PLUGIN_DIR . $view . '.php' );
			}
		}
	}
}


