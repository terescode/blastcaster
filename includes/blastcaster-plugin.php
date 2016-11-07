<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-wp-helper.php';
require_once BC_PLUGIN_DIR . 'includes/class-plugin-helper.php';
require_once BC_PLUGIN_DIR . 'includes/class-generic-plugin.php';
require_once BC_PLUGIN_DIR . 'admin/controllers/class-add-blast-controller.php';
require_once BC_PLUGIN_DIR . 'admin/class-add-blast-form-helper.php';

if ( ! function_exists( __NAMESPACE__ . '\create_plugin' ) ) {
	function create_plugin() {
		$plugin_helper = new \TcPluginHelper( new \TcWpHelper() );
		return new TcGenericPlugin(
			BC_PLUGIN_ID,
			$plugin_helper,
			[ new BcAddBlastController( $plugin_helper, new BcAddBlastFormHelper() ) ]
		);
	}
}
