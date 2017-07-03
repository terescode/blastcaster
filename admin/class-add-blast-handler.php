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
				isset( $data['bc-add-tag'] ) ? $data['bc-add-tag'] : array(),
				isset( $data['bc-add-link'] ) ? $data['bc-add-link'] : null,
				isset( $data['bc-add-link-text'] ) ? $data['bc-add-link-text'] : null,
				isset( $data['bc-add-link-intro'] ) ? $data['bc-add-link-intro'] : null
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

			$edit_link = '/wp-admin/post.php?post=' . $result . '&action=edit';
			$view_link = $this->wph->get_permalink( $result );

			$this->plugin_helper->add_admin_notice_raw(
				sprintf(
					'<span style="margin-right:16px;">%s</span><a href="%s">%s</a>&nbsp;|&nbsp;<a href="%s">%s</a>',
					$this->plugin_helper->string( BcStrings::ABF_BLAST_ADDED ),
					$view_link,
					$this->plugin_helper->string( BcStrings::ABF_VIEW_POST ),
					$edit_link,
					$this->plugin_helper->string( BcStrings::ABF_EDIT_POST )
				),
				TcPluginHelper::NOTICE_TYPE_UPDATED,
				true
			);
		}
	}

}
