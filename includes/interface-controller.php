<?php

if ( ! interface_exists( 'TcController' ) ) {

	interface TcController {
		function register_handlers();
		function register_menu();
	}

}
