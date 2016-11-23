<?php

namespace Terescode\WordPress;

if ( ! interface_exists( __NAMESPACE__ . '\TcStrings' ) ) {

	interface TcStrings {
		function get_string( $code, $args = array() );
	}

}
