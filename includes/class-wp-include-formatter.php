<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-blast.php';
require_once BC_PLUGIN_DIR . 'includes/interface-blast-formatter.php';

if ( ! class_exists( __NAMESPACE__ . '\BcWpIncludeFormatter' ) ) {

	class BcWpIncludeFormatter implements BcBlastFormatter {
		private $template_path;

		function __construct( $template_path ) {
			$this->template_path = $template_path;
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedFormalParameter) because $blast can be used
		 * within the template.
		 */
		function format( BcBlast $blast ) {
			if ( is_file( $this->template_path ) ) {
				if ( ob_start() ) {
					$ret = include( $this->template_path );
					if ( 1 === $ret ) {
						$str = ob_get_clean();
						if ( $str ) {
							return $str;
						}
					}
					ob_end_clean();
				}
			}
			return false;
		}
	}

}
