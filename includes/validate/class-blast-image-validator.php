<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-image-type.php';
require_once BC_PLUGIN_DIR . 'includes/interface-input-validator.php';

use Terescode\WordPress\TcInputValidator;
use Terescode\BlastCaster\BcImageType;

if ( ! class_exists( __NAMESPACE__ . '\BcBlastImageValidator' ) ) {
	class BcBlastImageValidator implements TcInputValidator {

		private $plugin_helper;

		function __construct( $plugin_helper ) {
			$this->plugin_helper = $plugin_helper;
		}

		private function validate_image_url( &$map ) {
			$image = $this->plugin_helper->param( 'bc-add-image-url', 'url' );
			if ( empty( $image ) ) {
				return BcStrings::ABF_MISSING_BLAST_IMAGE_URL;
			}
			$map['image'] = $image;
			return null;
		}

		private function validate_upload( &$map ) {
			if ( ! isset( $_FILES['bc-add-image-file'] ) ) {
				return BcStrings::ABF_MISSING_BLAST_IMAGE_FILE;
			}

			$image = $_FILES['bc-add-image-file'];
			if ( is_uploaded_file( $image['tmp_name'] ) ) {
				$map['image'] = $image;
				return null;
			}

			return ! isset( $image['error'] )
				? BcStrings::ABF_MISSING_BLAST_IMAGE_FILE
				: BcStrings::ABF_UPLOAD_IMAGE_FAILED . '_' . $image['error'];
		}

		/**
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		function validate( &$map ) {
			$image_type = BcImageType::as_type( $this->plugin_helper->param( 'bc-add-image-type' ) );
			if ( null === $image_type ) {
				return BcStrings::ABF_INVALID_BLAST_IMAGE_TYPE;
			}

			if ( $image_type->equals( BcImageType::BC_IMAGE_TYPE_URL ) ) {
				return $this->validate_image_url( $map );
			}

			if ( $image_type->equals( BcImageType::BC_IMAGE_TYPE_FILE ) ) {
				return $this->validate_upload( $map );
			}

			return null;
		}
	}
}
