<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-blastcaster-strings.php';

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

		private function sideload_media( $url ) {
			// Download file to temp dir
			$temp_file = $this->wph->download_url( $url, 5 );

			if ( $this->wph->is_wp_error( $temp_file ) ) {
				return $temp_file;
			}

			$url_path = $this->wph->wp_parse_url( $url, PHP_URL_PATH );
			if ( ! $url_path ) {
				return [
					'error' => $this->plugin_helper->string(
						BcStrings::ABF_INVALID_URL,
						[ $url ]
					),
				];
			}

			// Array based on $_FILE as seen in PHP file uploads
			$file = array(
				'name' => basename( $url_path ),
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

		private function upload_media( $file ) {
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
