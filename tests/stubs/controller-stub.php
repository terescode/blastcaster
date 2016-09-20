<?php

require_once 'includes/class-controller.php';

if ( ! class_exists( BcControllerStub ) ) {

	class BcControllerStub extends BcController {
		function __construct( $plugin, $renderer = null ) {
			parent::__construct( $plugin, $renderer );
		}

		function do_action() {
			$this->render( 'tests/view.php' );
		}

		function print_message( $string ) {
			echo $string;
		}
	}

}
