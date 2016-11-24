<?php

namespace Terescode\BlastCaster;

if ( ! class_exists( __NAMESPACE__ . '\BcAddBlastPageHelper' ) ) {

	class BcAddBlastPageHelper {

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

		function __construct( $plugin_helper ) {
			$this->wph = $plugin_helper->get_wp_helper();
			$this->plugin_helper = $plugin_helper;
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedFormalParameter) because we never use $post
		 */
		function render_add_title_meta_box( $post, $metabox ) {
			$page_data = $metabox['args'][0]->get_page_data();
			$primary_title = $this->plugin_helper->param( 'bc-add-title-input' );
			if ( empty( $primary_title ) && null !== $page_data && isset( $page_data->titles ) ) {
				if ( 0 < count( $page_data->titles ) ) {
					$primary_title = $page_data->titles[0];
				}
			}
			echo '<textarea id="bc-add-title-input" name="bc-add-title-input" cols="80" rows="3"  class="large-text">' . $primary_title . '</textarea>';
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedFormalParameter) because we never use $post
		 */
		function render_add_category_meta_box( $post, $metabox ) {
			echo '<div id="bc-add-category-picker"></div>';
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedFormalParameter) because we never use $post
		 */
		function render_add_image_meta_box( $post, $metabox ) {
			$page_data = $metabox['args'][0]->get_page_data();
			$primary_image = BC_PLUGIN_URL . '/admin/images/noImage.png';
			if ( null !== $page_data && isset( $page_data->images ) ) {
				if ( 0 < count( $page_data->images ) ) {
					$primary_image = $page_data->images[0];
				}
			}
			echo '<div id="bc-add-image-picker">';
			// TODO: add checkbox for no image
			// initially show carousel of recommended images and image upload tab
			// if no recommended images show "no images to recommend"
			// if no image is selected, hide image controls - drawer up/down
			echo '<input type="hidden" name="bc-add-image-url" value="" />';
			echo '<img src="' . $primary_image . '" />';
			echo '</div>';
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedFormalParameter) because we never use $post
		 */
		function render_add_description_meta_box( $post, $metabox ) {
			$page_data = $metabox['args'][0]->get_page_data();
			$primary_desc = $this->plugin_helper->param( 'bc-add-desc-input' );
			if ( null !== $page_data && isset( $page_data->descriptions ) ) {
				if ( 0 < count( $page_data->descriptions ) ) {
					$primary_desc = $page_data->descriptions[0];
				}
			}
			echo '<textarea id="bc-add-desc-input" name="bc-add-desc-input" cols="80" rows="5"  class="large-text">' . $primary_desc . '</textarea>';
		}

		/**
		 * @SuppressWarnings(PHPMD.UnusedFormalParameter) because we never use $post
		 */
		function render_add_tag_meta_box( $post, $metabox ) {
			echo '<div id="bc-add-tag-picker"></div>';
		}
	}

}
