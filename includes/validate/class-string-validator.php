<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/interface-input-validator.php';

use Terescode\WordPress\TcInputValidator;

if ( ! class_exists( __NAMESPACE__ . '\TcStringValidator' ) ) {
	class TcStringValidator implements TcInputValidator {

		private $plugin_helper;
		private $name;
		private $code;
		private $type;

		function __construct( $plugin_helper, $name, $code = null, $type = 'text' ) {
			$this->plugin_helper = $plugin_helper;
			$this->name = $name;
			$this->code = $code;
			$this->type = $type;
		}

		function validate( &$map ) {
			$string = $this->plugin_helper->param( $this->name, $this->type );
			if ( empty( $string ) ) {
				return ( $this->code ? $this->code : null );
			}
			$map[ $this->name ] = $string;
			return null;
		}
	}
}
