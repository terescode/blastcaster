<?php

namespace Terescode\BlastCaster;

if ( ! class_exists( __NAMESPACE__ . '\BcAddBlastPageHelper' ) ) {

	class BcAddBlastPageHelper {

		/**
		 * The wp helper to use.
		 *
		 * @var object
		 * @access protected
		 */
		private $wph;

		/**
		 * The plugin helper to use.
		 *
		 * @var object
		 * @access protected
		 */
		private $plugin_helper;

		function __construct( $plugin_helper ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
		}

		function build_action_data( $action, $extra_data = array() ) {
			$bc_data = new \stdClass();
			$bc_data->action = $action;
			$bc_data->action_nonce = $this->wph->wp_create_nonce( $action );
			foreach ( $extra_data as $key => $val ) {
				$bc_data->{$key} = $val;
			}
			return json_encode( $bc_data );
		}

		function forward_param( &$data, $param, $key = null ) {
			$val = $this->plugin_helper->param( $param );
			if ( ! empty( $val ) ) {
				if ( null === $key ) {
					$key = $param;
				}
				$data[ $key ] = $val;
			}
		}
	}

}
