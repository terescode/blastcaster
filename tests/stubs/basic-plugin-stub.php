<?php

require_once 'includes/class-basic-plugin.php';

if ( ! class_exists( 'BcBasicPluginStub' ) ) {

	class BcBasicPluginStub extends BcBasicPlugin {
		public function __construct( $plugin_id ) {
			parent::__construct( $plugin_id );
		}
	}

}
