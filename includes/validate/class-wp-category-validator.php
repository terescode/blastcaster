<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/class-blastcaster-strings.php';
require_once BC_PLUGIN_DIR . 'includes/interface-input-validator.php';

use Terescode\WordPress\TcInputValidator;
use Terescode\BlastCaster\BcStrings;

if ( ! class_exists( __NAMESPACE__ . '\TcWpCategoryValidator' ) ) {
	class TcWpCategoryValidator implements TcInputValidator {

		private $plugin_helper;
		private $name;
		private $code;
		private $tax_type;

		function __construct( $plugin_helper, $name, $code = null, $tax_type = 'category' ) {
			$this->plugin_helper = $plugin_helper;
			$this->name = $name;
			$this->code = $code;
			$this->tax_type = $tax_type;
		}

		function validate( &$map ) {
			$categories = $this->plugin_helper->param( $this->name, 'term' );
			if ( null === $categories ) {
				return ( $this->code ? $this->code : null );
			}

			if ( ! is_array( $categories ) ) {
				return BcStrings::ABF_INVALID_CATEGORY_TYPE;
			}

			if ( 'post_tag' !== $this->tax_type ) {
				foreach ( $categories as $catid ) {
					if ( ! is_numeric( $catid ) ) {
						return BcStrings::ABF_INVALID_CATEGORY;
					}
				}
			}

			$map[ $this->name ] = $categories;
			return null;
		}
	}
}
