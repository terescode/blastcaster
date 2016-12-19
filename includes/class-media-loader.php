<?php

namespace Terescode\BlastCaster;

if ( ! interface_exists( __NAMESPACE__ . '\BcMediaLoader' ) ) {

	class BcMediaLoader {
		/**
		 * The wp helper to use.
		 *
		 * @var object
		 * @access protected
		 */
		private $wph;

		/**
		 * The plugin helper to use.
		 *
		 * @var object
		 * @access protected
		 */
		private $plugin_helper;

		private $action;

		function __construct( $plugin_helper, $action = null ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
			$this->action = $action;
		}

		function sideload_media( $url ) {
			// Download file to temp dir
			$temp_file = $this->wph->download_url( $url, 5 );

			if ( ! $this->wph->is_wp_error( $temp_file ) ) {

				// Array based on $_FILE as seen in PHP file uploads
				$file = array(
					'name' => basename( $url ),
					'tmp_name' => $temp_file,
					'error' => 0,
					'size' => filesize( $temp_file ),
				);

				$overrides = array(
					'test_form' => false,
					'test_size' => true,
				);

				// Move the temporary file into the uploads directory
				return $this->wph->wp_handle_sideload( $file, $overrides );
			}
		}

		function upload_media( $file ) {
			// Move the temporary file into the uploads directory
			return $this->wph->wp_handle_upload(
				$file,
				[
					'action' => $this->action,
				]
			);
		}

		function load_media( $media ) {
			return is_string( $media ) ?
				$this->sideload_media( $media ) :
				$this->upload_media( $media );
		}
	}
}
