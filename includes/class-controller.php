<?php
require_once( BC_PLUGIN_DIR . 'includes/class-renderer.php' );

if ( ! class_exists( 'BcController' ) ) {

	abstract class BcController {

		/**
		 * The plugin that created the controller.
		 *
		 * @var object
		 * @access protected
		 */
		protected $plugin;

		/**
		 * The renderer to use.
		 *
		 * @var object
		 * @access protected
		 */
		protected $renderer;

		function __construct( $plugin, $renderer = null ) {
			$this->plugin = $plugin;
			$this->renderer = ( null == $renderer ? new BcRenderer() : $renderer );
		}

		protected function render( $view ) {
			$this->renderer->render( $this, $view );
		}

		function get_plugin() {
			return $this->plugin;
		}
	}

}
