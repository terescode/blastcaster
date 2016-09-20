<?php

require_once 'admin/class-admin-plugin.php';

if ( ! class_exists( BcAdminPluginStub ) ) {

	class BcAdminPluginStub extends BcAdminPlugin {
		public $menu_hooknames = array();

		public function __construct( $plugin_id ) {
			parent::__construct( $plugin_id );
		}

		public function register_admin_menus() {
			return $this->menu_hooknames;
		}
	}

}
