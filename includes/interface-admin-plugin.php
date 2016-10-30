<?php

require_once BC_PLUGIN_DIR . 'includes/interface-plugin.php';

if ( ! interface_exists( 'TcAdminPlugin' ) ) {

	interface TcAdminPlugin extends TcPlugin {

		public function install_admin_menus();

	}

}
