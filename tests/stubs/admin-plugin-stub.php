<?php

require_once 'admin/class-admin-plugin.php';

if ( ! class_exists( 'BcAdminPluginStub' ) ) {

	class BcAdminPluginStub extends BcAdminPlugin {
		public $menu_hooknames = array();

		public function __construct( $plugin_id ) {
			parent::__construct( $plugin_id );
		}

		public function register_admin_menus() {
			return $this->menu_hooknames;
		}

		public function create_load_pagenow_hook_proxy( $hookname ) {
			return $this->create_load_pagenow_hook( $hookname );
		}

		public function create_admin_notices_hook_proxy( $notice, $type = self::NOTICE_TYPE_ERROR, $dismissable = true ) {
			return $this->create_admin_notices_hook( $notice, $type, $dismissable );
		}
	}

}
