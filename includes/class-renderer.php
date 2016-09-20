<?php

if ( ! class_exists( 'BcRenderer' ) ) {

	class BcRenderer {
		function render( $controller, $view ) {
			$bc_controller = $controller;
			include( BC_PLUGIN_DIR . $view );
		}
	}

}
