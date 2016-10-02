<?php
require_once( BC_PLUGIN_DIR . 'includes/class-controller.php' );

if ( ! class_exists( 'BcAdminController' ) ) {

	final class BcAdminController extends BcController {

		/**
		 * The page data received from the POST or null decoded as a stdClass object.
		 *
		 * @var object
		 * @access private
		 */
		private $page_data;

		function __construct( $plugin, $renderer = null ) {
			parent::__construct( $plugin, $renderer );
		}

		private function decode_page_data( $post_data ) {
			$json = json_decode( $post_data );
			if ( null != $json ) {
				return $json;
			} else {
				$json_error = json_last_error();
				$json_error_msg = '';

				if ( function_exists( 'json_last_error_msg' ) ) {
					$json_error_msg = json_last_error_msg();
				}

				$json_error_msg = sprintf(
					__( 'The page data received from the original source could not be decoded. (%1$d - %2$s)', 'blastcaster' ),
					$json_error,
					$json_error_msg
				);

				$this->plugin->add_admin_notice( $json_error_msg );

				return null;
			}
		}

		function do_add_blast() {
			if ( isset( $_POST['pageData'] ) ) {
				$post_data = stripslashes( trim( $_POST['pageData'] ) );
				if ( ! empty( $post_data ) ) {
					$this->page_data = $this->decode_page_data( $post_data );
				}
			}

			$this->render( 'admin/views/add-blast-form.php' );
		}

		function get_page_data() {
			return $this->page_data;
		}
	}

}
