<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-blast.php';

if ( ! interface_exists( __NAMESPACE__ . '\BcBlastFormatter' ) ) {
	interface BcBlastFormatter {
		/**
		 * Formats the given blast for posting to a content marketing stream.
		 *
		 * @since 1.0.0
		 *
		 * @param BcBlast $blast blast to be formatted.
		 * @return string $blast formatted for posting.
		 */
		function format( BcBlast $blast );
	}
}
