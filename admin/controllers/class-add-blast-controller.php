<?php
require_once BC_PLUGIN_DIR . 'includes/interface-controller.php';

if ( ! class_exists( 'BcAddBlastController' ) ) {

	final class BcAddBlastController implements TcController {
		const BC_ADD_BLAST_SCREEN_ID = 'bc_add_blast';

		/**
		 * The plugin that created the controller.
		 *
		 * @var object
		 * @access protected
		 */
		private $plugin;

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
		private $add_blast_screen_id;

		/**
		 * The page data received from the POST or null decoded as a stdClass object.
		 *
		 * @var object
		 * @access private
		 */
		private $page_data;

		function __construct( $plugin, $plugin_helper ) {
			$this->plugin = $plugin;
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
		}

		function init() {
			$this->add_blast_screen_id = $this->wph->add_posts_page(
				$this->wph->__( 'Add a blast', 'blastcaster' ),
				$this->wph->__( 'Add a blast', 'blastcaster' ),
				'edit_posts',
				self::BC_ADD_BLAST_SCREEN_ID,
				array( $this, 'do_add_blast' )
			);
			if ( $this->add_blast_screen_id ) {
				$this->wph->add_action(
					'add_meta_boxes_' . $this->add_blast_screen_id,
					array( $this, 'add_blast_form_meta_boxes' )
				);
			}
			return $this->add_blast_screen_id;
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
				$this->wph->__( 'The page data received from the original source could not be decoded. (%1$d - %2$s)', 'blastcaster' ),
				$json_error,
				$json_error_msg
			);

			$this->plugin_helper->add_admin_notice( $json_error_msg );

			return null;
		}

		function do_add_blast() {
			if ( isset( $_POST['pageData'] ) ) {
				$post_data = stripslashes( trim( $_POST['pageData'] ) );
				if ( ! empty( $post_data ) ) {
					$this->page_data = $this->decode_page_data( $post_data );
				}
			}

			$this->plugin_helper->render( $this, 'admin/views/add-blast-form' );
		}

		function add_blast_form_meta_boxes() {
			$this->wph->add_meta_box(
				'bc-add-title-meta-box',
				$this->wph->__( 'Title', 'blastcaster' ),
				array( $this, 'render_add_title_meta_box' ),
				$this->add_blast_screen_id,
				'normal'
			);
			$this->wph->add_meta_box(
				'bc-add-category-meta-box',
				$this->wph->__( 'Categories', 'blastcaster' ),
				array( $this, 'render_add_category_meta_box' ),
				$this->add_blast_screen_id,
				'normal'
			);
			$this->wph->add_meta_box(
				'bc-add-image-meta-box',
				$this->wph->__( 'Image', 'blastcaster' ),
				array( $this, 'render_add_image_meta_box' ),
				$this->add_blast_screen_id,
				'normal'
			);
			$this->wph->add_meta_box(
				'bc-add-description-meta-box',
				$this->wph->__( 'Description', 'blastcaster' ),
				array( $this, 'render_add_description_meta_box' ),
				$this->add_blast_screen_id,
				'normal'
			);
			$this->wph->add_meta_box(
				'bc-add-tag-meta-box',
				$this->wph->__( 'Tags', 'blastcaster' ),
				array( $this, 'render_add_tag_meta_box' ),
				$this->add_blast_screen_id,
				'normal'
			);
		}

		function render_add_title_meta_box() {
			echo '<textarea id="bc-add-title-input" name="bc-add-title-input" cols="80" rows="3"  class="large-text"></textarea>';
		}

		function render_add_category_meta_box() {
			echo '<div id="bc-add-category-picker"></div>';
		}

		function render_add_image_meta_box() {
			echo '<div id="bc-add-image-picker"></div>';
		}

		function render_add_description_meta_box() {
			echo '<textarea id="bc-add-desc-input" name="bc-add-desc-input" cols="80" rows="5"  class="large-text"></textarea>';
		}

		function render_add_tag_meta_box() {
			echo '<div id="bc-add-tag-picker"></div>';
		}

		function get_page_data() {
			return $this->page_data;
		}

		function get_screen_id() {
			return $this->add_blast_screen_id;
		}
	}

}
