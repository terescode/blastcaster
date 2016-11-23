<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/interface-action.php';

use Terescode\WordPress\TcAction;

if ( ! class_exists( __NAMESPACE__ . '\TcGenericAction' ) ) {

	class TcGenericAction implements TcAction {

		/**
		 * The action name.
		 *
		 * @var string
		 * @access private
		 */
		private $name;

		/**
		 * The wp helper to use.
		 *
		 * @var object
		 * @access private
		 */
		private $wph;

		/**
		 * The plugin helper to use.
		 *
		 * @var object
		 * @access private
		 */
		private $plugin_helper;

		private $input_validators;
		private $action_handler;


		function __construct( $plugin_helper, $name, $input_validators, $action_handler ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
			$this->name = $name;
			$this->input_validators = $input_validators;
			$this->action_handler = $action_handler;
		}

		public function get_name() {
			return $this->name;
		}

		function do_action() {
			$data = [];

			foreach ( $this->input_validators as $validator ) {
				$err = $validator->validate( $data );
				if ( $err ) {
					$this->action_handler->handle_error( $err );
				}
			}

			$err = $this->action_handler->handle( $data );
			if ( $err ) {
				$this->action_handler->handle_error( $err );
			}
		}
	}

}
