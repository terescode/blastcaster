<?php

namespace Terescode\WordPress;

if ( ! interface_exists( __NAMESPACE__ . '\TcInputValidator' ) ) {
	interface TcInputValidator {
		function validate( &$map );
	}
}
