<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/class-wp-helper.php';
require_once BC_PLUGIN_DIR . 'includes/class-plugin-helper.php';
require_once BC_PLUGIN_DIR . 'includes/interface-admin-plugin.php';

if ( ! class_exists( __NAMESPACE__ . '\TcGenericPlugin' ) ) {

	class TcGenericPlugin implements TcAdminPlugin {

		/**
		 * The plugin id.
		 *
		 * @var string
		 * @access private
		 */
		private $plugin_id;

		/**
		 * The wp helper to use.
		 *
		 * @var object
		 * @access private
		 */
		private $wph;

		/**
		 * Helper object
		 *
		 * @var object
		 * @access private
		 */
		private $plugin_helper;

		/**
		 * Array of admin menu controllers
		 *
		 * @var array
		 * @access private
		 */
		private $menu_controllers;

		public function __construct( $plugin_id, $plugin_helper, $menu_controllers = array() ) {
			$this->plugin_id = $plugin_id;
			$this->plugin_helper = $plugin_helper;
			$this->wph = $this->plugin_helper->get_wp_helper();
			$this->menu_controllers = $menu_controllers;
		}

		public function init() {
			$this->plugin_helper->init_admin_plugin( $this );
		}

		public function load() {
			$this->plugin_helper->load_textdomain( $this );
		}

		/**
		 * We can ignore this block since we currently don't use it
		 *
		 * @codeCoverageIgnore
		 */
		public function activate() {
			// no-op
		}

		/**
		 * We can ignore this block since it is a no-op for sub-classes to override.
		 *
		 * @codeCoverageIgnore
		 */
		public function deactivate() {
			// no-op
		}

		public function get_plugin_id() {
			return $this->plugin_id;
		}

		public function get_plugin_helper() {
			return $this->plugin_helper;
		}

		public function install_admin_menus() {
			$hooknames = array();

			foreach ( $this->menu_controllers as $controller ) {
				$hook_suffix = $controller->register_menu();
				if ( $hook_suffix ) {
					// do_action( 'load-{$pagenow}' )
					$this->wph->add_action(
						'load-' . $hook_suffix,
						array( $controller, 'load_pagenow' )
					);
					// do_action( 'admin_head-{$pagenow}' )
					$this->wph->add_action(
						'admin_head-' . $hook_suffix,
						array( $controller, 'admin_head' )
					);
					// do_action( 'admin_footer-{$hookname}' )
					$this->wph->add_action(
						'admin_footer-' . $hook_suffix,
						array( $controller, 'admin_footer' )
					);
					$hooknames[] = $hook_suffix;
				}
			}

			return $hooknames;
		}
	}
}
