<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/interface-plugin.php';
require_once BC_PLUGIN_DIR . 'includes/interface-admin-plugin.php';
require_once BC_PLUGIN_DIR . 'includes/interface-view.php';
require_once BC_PLUGIN_DIR . 'includes/class-callback-wrapper.php';

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
if ( ! class_exists( __NAMESPACE__ . '\TcPluginHelper' ) ) {
	class TcPluginHelper {

		const NOTICE_TYPE_ERROR = 'error';
		const NOTICE_TYPE_UPDATED = 'updated';
		const NOTICE_TYPE_NAG = 'update-nag';

		private $wph;
		private $strings;
		private $sanitizers;

		function __construct( $wph, $strings ) {
			$this->wph = $wph;
			$this->strings = $strings;
			$this->sanitizers = [
				'text' => array( $wph, 'sanitize_text_field' ),
				'url' => array( $wph, 'esc_url_raw' ),
			];
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

		function admin_notices( $notice, $type, $dismissable ) {
			echo '<div class="' . $this->wph->esc_attr( $type ) . ' notice' . ( $dismissable ? ' is-dismissable' : '' ) . '"><p>';
			echo $this->wph->esc_html( $notice );
			echo '</p></div>';
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		function add_admin_notice( $notice, $type = self::NOTICE_TYPE_ERROR, $dismissable = true ) {
			$delegate = new TcCallbackWrapper( array( $this, 'admin_notices' ), $notice, $type, $dismissable );
			$this->wph->add_action( 'admin_notices', array( $delegate, 'call' ) );
		}

		function get_wp_helper() {
			return $this->wph;
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
		 */
		function render( TcView $view, $path, $capability = null, $objid = null ) {
			$render = ( null !== $capability ? $this->wph->current_user_can( $capability, $objid ) : true );
			if ( $render ) {
				$tc_view = $view;
				$plugin_helper = $this;
				$wph = $this->wph;
				include( BC_PLUGIN_DIR . $path . '.php' );
			}
		}

		function param( $name, $type = 'text' ) {
			$val = null;
			if ( isset( $_POST[ $name ] ) ) {
				$val = $_POST[ $name ];
			} elseif ( isset( $_GET[ $name ] ) ) {
				$val = $_GET[ $name ];
			}

			if ( null === $val ) {
				return $val;
			}

			if ( is_array( $val ) ) {
				foreach ( $val as $key => $value ) {
					$val[ $key ] = call_user_func(
						$this->sanitizers[ $type ],
						$value
					);
				}
				return $val;
			}

			return call_user_func(
				$this->sanitizers[ $type ],
				$val
			);
		}

		function string( $code, $args = array() ) {
			return $this->strings->get_string( $code, $args );
		}
	}
}


