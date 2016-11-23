<?php

namespace Terescode\WordPress;

if ( ! interface_exists( __NAMESPACE__ . '\TcAction' ) ) {

	interface TcAction {

		public function get_name();

		public function do_action();

	}

}
