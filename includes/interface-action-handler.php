<?php

namespace Terescode\WordPress;

if ( ! interface_exists( __NAMESPACE__ . '\TcActionHandler' ) ) {

	interface TcActionHandler {

		public function handle( $data );

		public function handle_error( $err );

	}

}
