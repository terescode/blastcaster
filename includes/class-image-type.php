<?php

namespace Terescode\BlastCaster;

if ( ! class_exists( __NAMESPACE__ . '\BcImageType' ) ) {
	class BcImageType {
		const BC_IMAGE_TYPE_NONE = 'none';
		const BC_IMAGE_TYPE_URL = 'url';
		const BC_IMAGE_TYPE_FILE = 'file';

		private static $types = [
			self::BC_IMAGE_TYPE_NONE => 1,
			self::BC_IMAGE_TYPE_URL => 1,
			self::BC_IMAGE_TYPE_FILE => 1,
		];
		private $type;

		private function __construct( $type ) {
			$this->type = $type;
		}

		function equals( $type ) {
			return $this->type === $type;
		}

		static function as_type( $type ) {
			if ( ! isset( self::$types[ $type ] ) ) {
				return null;
			}
			return new BcImageType( $type );
		}
	}
}
