<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/interface-action-handler.php';
require_once BC_PLUGIN_DIR . 'includes/class-blastcaster-strings.php';
require_once BC_PLUGIN_DIR . 'includes/class-plugin-helper.php';
require_once BC_PLUGIN_DIR . 'includes/class-blast.php';
require_once BC_PLUGIN_DIR . 'admin/class-add-blast-page.php';

use Terescode\WordPress\TcPluginHelper;
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
		 * Media loader
		 *
		 * @var object
		 * @access private
		 */
		private $media_loader;

		/**
		 * Add Blast Dao
		 *
		 * @var object
		 * @access private
		 */
		private $blast_dao;

		function __construct( $plugin_helper, $media_loader, $blast_dao ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
			$this->media_loader = $media_loader;
			$this->blast_dao = $blast_dao;
		}

		private function create_blast( $data, $image_data ) {
			return new BcBlast(
				$data['bc-add-title'],
				$data['bc-add-desc'],
				$image_data,
				isset( $data['bc-add-cat'] ) ? $data['bc-add-cat'] : array(),
				isset( $data['bc-add-tax'] ) ? $data['bc-add-tax'] : array()
			);
		}

		function handle_error( $err ) {
			$this->plugin_helper->add_admin_notice(
				$this->plugin_helper->string( $err )
			);
		}

		function handle( $data ) {
			$image_data = null;

			// TODO: The load_media should return either a WPError on error
			// TODO: Need to be able to return errors with codes and args
			if ( isset( $data['image'] ) ) {
				$image_data = $this->media_loader->load_media( $data['image'] );
				if ( $this->wph->is_wp_error( $image_data )
					|| isset( $image_data['error'] ) ) {
					return BcStrings::ABF_BUILD_ACTION_DATA_FAILED;
				}
			}

			$result = $this->blast_dao->create_post(
				$this->create_blast( $data, $image_data )
			);
			if ( $this->wph->is_wp_error( $result ) ) {
				return BcStrings::ABF_INSERT_POST_FAILED;
			}

			$this->plugin_helper->add_admin_notice(
				'Blast added!',
				TcPluginHelper::NOTICE_TYPE_UPDATED,
				true
			);
		}
	}

}
