<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/interface-action-handler.php';
require_once BC_PLUGIN_DIR . 'admin/class-add-blast-page.php';

use Terescode\WordPress\TcActionHandler;

if ( ! class_exists( __NAMESPACE__ . '\BcAddBlastHandler' ) ) {

	class BcAddBlastHandler implements TcActionHandler {
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

		/**
		 * Add Blast Dao
		 *
		 * @var object
		 * @access private
		 */
		private $blast_dao;

		function __construct( $plugin_helper, $blast_dao ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
			$this->blast_dao = $blast_dao;
		}

		function handle_error( $err ) {
			$this->plugin_helper->add_admin_notice(
				$this->plugin_helper->string( $err )
			);
		}

		function handle( $data ) {
			$this->wph->status_header( 200 );
		}
	}

}
