<?php

if ( ! interface_exists( 'TcPlugin' ) ) {

	interface TcPlugin {

		public function init();

		public function load();

		public function activate();

		public function deactivate();

		public function get_plugin_id();

	}

}
