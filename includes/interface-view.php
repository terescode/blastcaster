<?php

namespace Terescode\WordPress;

if ( ! interface_exists( __NAMESPACE__ . '\TcView' ) ) {

	interface TcView {

		public function add_page();

		public function get_hook_suffix();

		public function load_pagenow();

		public function is_metabox_page();

		public function add_meta_boxes();

	}

}
