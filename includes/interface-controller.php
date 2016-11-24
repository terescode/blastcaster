<?php

namespace Terescode\WordPress;

if ( ! interface_exists( __NAMESPACE__ . '\TcController' ) ) {

	interface TcController {
		function process_actions();
		function register_menu();
		function load_pagenow();
		function admin_head();
		function admin_footer();
	}

}
