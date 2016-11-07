<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-plugin-helper.php';
require_once BC_PLUGIN_DIR . 'includes/interface-blast-formatter.php';

if ( ! class_exists( 'BcBlastDao' ) ) {

	class BcBlastDao {

		/**
		 * The plugin helper to use.
		 *
		 * @var object
		 * @access protected
		 */
		private $plugin_helper;

		/**
		 * The wp helper to use.
		 *
		 * @var object
		 * @access private
		 */
		private $wph;

		/**
		 * The formatter to use.
		 *
		 * @var object
		 * @access private
		 */
		private $formatter;

		function __construct( $plugin_helper, BcBlastFormatter $formatter ) {
			$this->plugin_helper = $plugin_helper;
			$this->wph = $this->plugin_helper->get_wp_helper();
			$this->formatter = $formatter;
		}

		function create_post( BcBlast $blast ) {
			$post_content = $this->formatter->format( $blast );
			if ( ! $post_content ) {
				return false;
			}

			$postarr = [
				'post_title' => $blast->get_title(),
				'post_content' => $post_content,
			];

			return $this->wph->wp_insert_post( $postarr, true );
		}
	}
}
