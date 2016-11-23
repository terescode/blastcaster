<?php

namespace Terescode\WordPress;

if ( ! class_exists( __NAMESPACE__ . '\TcCallbackWrapper' ) ) {
	class TcCallbackWrapper {

		private $callable;
		private $args;

		function __construct() {
			$count = func_num_args();
			if ( 1 <= $count ) {
				$this->callable = func_get_arg( 0 );
			}
			if ( 2 <= $count ) {
				$this->args = array_slice( func_get_args(), 1 );
			} else {
				$this->args = array();
			}
		}

		function call() {
			if ( is_callable( $this->callable ) ) {
				return call_user_func_array( $this->callable, $this->args );
			} else {
				return false;
			}
		}

		function get_callable() {
			return $this->callable;
		}

		function get_args() {
			return $this->args;
		}
	}
}
