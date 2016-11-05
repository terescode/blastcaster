<?php
require_once BC_PLUGIN_DIR . 'includes/class-wp-helper.php';
require_once BC_PLUGIN_DIR . 'includes/class-plugin-helper.php';
require_once BC_PLUGIN_DIR . 'includes/interface-admin-plugin.php';
require_once BC_PLUGIN_DIR . 'admin/controllers/class-add-blast-controller.php';
require_once BC_PLUGIN_DIR . 'admin/class-add-blast-form-helper.php';

if ( ! class_exists( 'BlastCasterPlugin' ) ) {

	class BlastCasterPlugin implements TcAdminPlugin {
		const BC_PLUGIN_ID = BC_PLUGIN_ID;

		/**
		 * Helper object
		 *
		 * @var object
		 * @access private
		 */
		private $plugin_helper;

		/**
		 * Add Blast Controller object
		 *
		 * @var array
		 * @access private
		 */
		private $add_blast_controller;

		public function __construct( $plugin_helper ) {
			$this->plugin_helper = $plugin_helper;
			$this->add_blast_controller = new BcAddBlastController( $this, $plugin_helper, new BcAddBlastFormHelper() );
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
			return BC_PLUGIN_ID;
		}

		public function install_admin_menus() {
			$this->plugin_helper->install_admin_menus( array(
				$this->add_blast_controller
			));
		}
	}

	if ( ! function_exists( 'com_terescode_create_blastcaster' ) ) {
		function com_terescode_create_blastcaster() {
			return new BlastCasterPlugin(
				new TcPluginHelper( new TcWpHelper() )
			);
		}
	}
}
