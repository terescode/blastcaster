<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/interface-plugin.php';

if ( ! interface_exists( __NAMESPACE__ . '\TcAdminPlugin' ) ) {

	interface TcAdminPlugin extends TcPlugin {

		public function install_admin_menus();

	}

}
