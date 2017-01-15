<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/interface-view.php';
require_once BC_PLUGIN_DIR . 'includes/class-blastcaster-strings.php';

use Terescode\WordPress\TcView;

if ( ! class_exists( __NAMESPACE__ . '\BcAddBlastPage' ) ) {

	class BcAddBlastPage implements TcView {
		const BC_ADD_BLAST_SCREEN_ID = 'bc_add_blast';
		const BC_ADD_BLAST_POST_ACTION = 'add_blast';

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
		 * Add Blast plugin hook suffix
		 *
		 * @var string
		 * @access private
		 */
		private $add_blast_hook_suffix;

		/**
		 * Add Blast Form helper
		 *
		 * @var object
		 * @access private
		 */
		private $blast_form_helper;

		/**
		 * The page data received from the POST or null decoded as a stdClass object.
		 *
		 * @var object
		 * @access private
		 */
		private $page_data;

		function __construct( $plugin_helper, $blast_form_helper ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
			$this->blast_form_helper = $blast_form_helper;
		}

		function add_page() {
			$this->add_blast_hook_suffix = $this->wph->add_posts_page(
				$this->plugin_helper->string( BcStrings::ABF_BLAST_PAGE_TITLE ),
				$this->plugin_helper->string( BcStrings::ABF_BLAST_MENU_TITLE ),
				'edit_posts',
				self::BC_ADD_BLAST_SCREEN_ID,
				array( $this, 'render' )
			);
			return $this->add_blast_hook_suffix;
		}

		function get_hook_suffix() {
			return $this->add_blast_hook_suffix;
		}

		function print_scripts() {
			$extra_data = [];

			$extra_data['page_data'] = ( $this->page_data ? $this->page_data : new \stdClass() );
			$extra_data['categories'] = $this->wph->get_categories( [ 'hide_empty' => false ] );
			$extra_data['tags'] = $this->wph->get_tags( [ 'hide_empty' => false ] );
			$this->blast_form_helper->forward_param( $extra_data, 'bc-add-title' );
			$this->blast_form_helper->forward_param( $extra_data, 'bc-add-desc' );
			$this->blast_form_helper->forward_param( $extra_data, 'bc-add-image-type' );
			$this->blast_form_helper->forward_param( $extra_data, 'bc-add-image-url' );

			$bc_data_js = $this->blast_form_helper->build_action_data(
				self::BC_ADD_BLAST_POST_ACTION,
				$extra_data
			);
			if ( ! $bc_data_js ) {
				$this->plugin_helper->add_admin_notice( BcStrings::ABF_BUILD_ACTION_DATA_FAILED );
				return;
			}
			echo '<script type="text/javascript">var terescode={"bc_data":' . $bc_data_js . '};</script>';
		}

		public function load_pagenow() {
			if ( isset( $_POST['pageData'] ) ) {
				$post_data = stripslashes( trim( $_POST['pageData'] ) );
				if ( ! empty( $post_data ) ) {
					$this->page_data = $this->decode_page_data( $post_data );
				}
			}
			$this->wph->wp_enqueue_style( 'bc-styles', BC_PLUGIN_URL . 'admin/css/bundle.css' );
			$this->wph->wp_enqueue_script( 'bc-scripts', BC_PLUGIN_URL . 'admin/js/bundle.js', [ 'jquery' ], false, true );
			$this->wph->add_action( 'admin_print_scripts-' . $this->get_hook_suffix(), array( $this, 'print_scripts' ) );
		}

		private function decode_page_data( $post_data ) {
			$json = json_decode( $post_data );
			if ( null != $json ) {
				return $json;
			}

			$json_error = json_last_error();
			$json_error_msg = '';

			if ( function_exists( 'json_last_error_msg' ) ) {
				$json_error_msg = json_last_error_msg();
			}

			$json_error_msg = sprintf(
				$this->plugin_helper->string( BcStrings::ABF_INVALID_PAGE_DATA ),
				$json_error,
				$json_error_msg
			);

			$this->plugin_helper->add_admin_notice( $json_error_msg );

			return null;
		}

		function render() {
			$this->plugin_helper->render( $this, 'admin/views/add-blast-page', 'edit_posts' );
		}

		function is_metabox_page() {
			return false;
		}

		function add_meta_boxes() {

		}

		function get_page_data() {
			return $this->page_data;
		}
	}

}
