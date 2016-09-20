<?php

require_once 'includes/class-controller.php';

if ( ! class_exists( BcControllerStub ) ) {

	class BcRendererStub extends BcRenderer {
		/**
		 * Render flag
		 *
		 * @var bool
		 * @access public
		 */
		public $render_called = false;

		function render( $controller, $view ) {
			$this->render_called = true;
		}

		function reset() {
			$this->render_called = false;
		}
	}

}
