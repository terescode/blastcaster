<?php

namespace Terescode\WordPress;

if ( ! interface_exists( __NAMESPACE__ . '\TcPlugin' ) ) {

	interface TcPlugin {

		public function init();

		public function load();

		public function activate();

		public function deactivate();

		public function get_plugin_id();

	}

}
