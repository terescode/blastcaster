<?php
if ( ! class_exists( 'BcBasicPlugin' ) ) {

	abstract class BcBasicPlugin {
		/**
		 * Stores the plugin slug.
		 *
		 * @var string
		 * @access private
		 */
		protected $plugin_id;

		public function __construct( $plugin_id ) {
			$this->plugin_id = $plugin_id;
		}

		public function init() {
			$plugin_file = $this->plugin_file_name();
			// Register activation
			register_activation_hook( $plugin_file, array( $this, 'activate' ) );
			// Register deactivation
			register_deactivation_hook( $plugin_file , array( $this, 'deactivate' ) );
			// do_action( 'plugins_loaded' )
			add_action( 'plugins_loaded', array( $this, 'load' ) );
		}

		/**
		 * We can ignore this block since it is a no-op for sub-classes to override.
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

		public function load() {
			load_plugin_textdomain(
				$this->plugin_id,
				false,
				basename( dirname( __FILE__ ) ) . '/languages/'
			);
		}

		public function plugin_file_name() {
			return dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . $this->plugin_id . '.php';
		}

		public function get_plugin_id() {
			return $this->plugin_id;
		}
	}

}
