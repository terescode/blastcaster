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

		public function add_page() {
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

		public function load_pagenow() {
			// TODO: refactor as separate marshaller/converter class
			if ( isset( $_POST['pageData'] ) ) {
				$post_data = stripslashes( trim( $_POST['pageData'] ) );
				if ( ! empty( $post_data ) ) {
					$this->page_data = $this->decode_page_data( $post_data );
				}
			}
			/*
			$code = $this->plugin_helper->param( 'code' );
			if ( ! empty( $code ) ) {
			}
			*/
			$this->wph->wp_enqueue_script( 'jquery-ui-tabs' );
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

		public function is_metabox_page() {
			return true;
		}

		public function add_meta_boxes() {
			$this->wph->add_meta_box(
				'bc-add-title-meta-box',
				$this->plugin_helper->string( BcStrings::ABF_TITLE_LABEL ),
				array( $this->blast_form_helper, 'render_add_title_meta_box' ),
				$this->add_blast_hook_suffix,
				'normal',
				'default',
				array( $this )
			);
			$this->wph->add_meta_box(
				'bc-add-category-meta-box',
				$this->plugin_helper->string( BcStrings::ABF_CATEGORIES_LABEL ),
				array( $this->blast_form_helper, 'render_add_category_meta_box' ),
				$this->add_blast_hook_suffix,
				'normal',
				'default',
				array( $this )
			);
			$this->wph->add_meta_box(
				'bc-add-image-meta-box',
				$this->plugin_helper->string( BcStrings::ABF_IMAGE_LABEL ),
				array( $this->blast_form_helper, 'render_add_image_meta_box' ),
				$this->add_blast_hook_suffix,
				'normal',
				'default',
				array( $this )
			);
			$this->wph->add_meta_box(
				'bc-add-description-meta-box',
				$this->plugin_helper->string( BcStrings::ABF_DESCRIPTION_LABEL ),
				array( $this->blast_form_helper, 'render_add_description_meta_box' ),
				$this->add_blast_hook_suffix,
				'normal',
				'default',
				array( $this )
			);
			$this->wph->add_meta_box(
				'bc-add-tag-meta-box',
				$this->plugin_helper->string( BcStrings::ABF_TAGS_LABEL ),
				array( $this->blast_form_helper, 'render_add_tag_meta_box' ),
				$this->add_blast_hook_suffix,
				'normal',
				'default',
				array( $this )
			);
		}

		function get_page_data() {
			return $this->page_data;
		}
	}

}
