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

		/**
		 * @SuppressWarnings(PHPMD.StaticAccess)
		 */
		function validate( &$map ) {
			$image_type = BcImageType::as_type( $this->plugin_helper->param( 'bc-add-image-type' ) );
			if ( null === $image_type ) {
				return BcStrings::ABF_INVALID_BLAST_IMAGE_TYPE;
			}

			if ( $image_type->equals( BcImageType::BC_IMAGE_TYPE_URL ) ) {
				$image = $this->plugin_helper->param( 'bc-add-image-url', 'url' );
				if ( empty( $image ) ) {
					return BcStrings::ABF_MISSING_BLAST_IMAGE_URL;
				}
				$map['image'] = $image;
			} elseif ( $image_type->equals( BcImageType::BC_IMAGE_TYPE_FILE ) ) {
				$image = isset( $_FILES['bc-add-image-file'] ) ? $_FILES['bc-add-image-file'] : null;
				if ( null === $image || ! is_uploaded_file( $image['tmp_name'] ) ) {
					return BcStrings::ABF_MISSING_BLAST_IMAGE_FILE;
				}
				$map['image'] = $image;
			}
			return null;
		}
	}
}
