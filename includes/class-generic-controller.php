<?php

namespace Terescode\WordPress;

require_once BC_PLUGIN_DIR . 'includes/interface-controller.php';
require_once BC_PLUGIN_DIR . 'includes/interface-view.php';
require_once BC_PLUGIN_DIR . 'includes/interface-action.php';

use Terescode\WordPress\TcController;
use Terescode\WordPress\TcView;
use Terescode\WordPress\TcAction;

if ( ! class_exists( __NAMESPACE__ . '\BcAddBlastController' ) ) {

	class TcGenericController implements TcController {
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
		 * Add Blast Form helper
		 *
		 * @var object
		 * @access private
		 */
		private $view;

		/**
		 * Add Blast Dao
		 *
		 * @var object
		 * @access private
		 */
		private $actions;

		function __construct( $plugin_helper, $view, $actions ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
			$this->view = $view;
			$this->actions = $actions;
		}

		function register_handlers() {
			foreach ( $this->actions as $action ) {
				$this->wph->add_action(
					'admin_post_' . $action->get_name(),
					array( $action, 'do_action' )
				);
			}
		}

		function register_menu() {
			$hook_suffix = $this->view->add_page();
			if ( $hook_suffix && $this->view->is_metabox_page() ) {
				$this->wph->add_action(
					'add_meta_boxes_' . $hook_suffix,
					array( $this->view, 'add_meta_boxes' )
				);
			}
			return $hook_suffix;
		}

		function load_pagenow() {
			if ( $this->view->is_metabox_page() ) {
				/* Fire metabox hooks */
				$this->wph->do_action( 'add_meta_boxes_' . $this->view->get_hook_suffix(), null );
				$this->wph->do_action( 'add_meta_boxes', $this->view->get_hook_suffix(), null );

				/* Ensure postbox is loaded */
				$this->wph->wp_enqueue_script( 'postbox' );
			}

			$this->view->load_pagenow();
		}

		function admin_head() {

		}

		function admin_footer() {
			if ( $this->view->is_metabox_page() ) {
				echo '<script type="text/javascript">jQuery(function () { postboxes.add_postbox_toggles(pagenow); });</script>';
			}
		}
	}
}
