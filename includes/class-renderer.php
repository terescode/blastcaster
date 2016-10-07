<?php

if ( ! class_exists( 'BcRenderer' ) ) {

	class BcRenderer {
		/**
		 * @SuppressWarnings(PHPMD.UnusedLocalVariable) The controller is intentionally made
		 * available to the view as $bc_controller.
		 */
		function render( $controller, $view ) {
			$bc_controller = $controller;
			include( BC_PLUGIN_DIR . $view );
		}
	}

}
