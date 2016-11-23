<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/interface-input-validator.php';

use Terescode\WordPress\TcInputValidator;

if ( ! class_exists( __NAMESPACE__ . '\TcCapabilityValidator' ) ) {
	class TcCapabilityValidator implements TcInputValidator {

		private $plugin_helper;
		private $capability;
		private $code;

		function __construct( $plugin_helper, $capability, $code ) {
			$this->plugin_helper = $plugin_helper;
			$this->capability = $capability;
			$this->code = $code;
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedFormalParameter) because we never use $map
		 */
		function validate( &$map ) {
			if ( ! $this->plugin_helper->get_wp_helper()->current_user_can( $this->capability ) ) {
				return $this->code;
			}
			return null;
		}
	}
}
