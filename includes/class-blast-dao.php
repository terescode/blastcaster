<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-plugin-helper.php';
require_once BC_PLUGIN_DIR . 'includes/interface-blast-formatter.php';
require_once BC_PLUGIN_DIR . 'includes/class-blast.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

use Terescode\BlastCaster\BcBlast;

if ( ! class_exists( __NAMESPACE__ . '\BcBlastDao' ) ) {

	class BcBlastDao {

		/**
		 * The wp helper to use.
		 *
		 * @var object
		 * @access private
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

		private function add_featured_thumbnail( $post_id, $image_data ) {
			// $filename should be the path to a file in the upload directory.
			$filename = $image_data['file'];

			// The ID of the post this attachment is for.
			$parent_post_id = $post_id;

			// mime type
			$mime_type = $image_data['type'];

			// Get the path to the upload directory.
			$wp_upload_dir = $this->wph->wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $mime_type,
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			// Insert the attachment.
			$attach_id = $this->wph->wp_insert_attachment( $attachment, $filename, $parent_post_id, true );
			if ( $this->wph->is_wp_error( $attach_id ) ) {
				return $attach_id;
			}

			// Generate the metadata for the attachment, and update the database record.
			$attach_data = $this->wph->wp_generate_attachment_metadata( $attach_id, $filename );
			if ( ! $this->wph->wp_update_attachment_metadata( $attach_id, $attach_data ) ) {
				return false;
			}

			// Set the post thumbnail
			return $this->wph->set_post_thumbnail( $parent_post_id, $attach_id ) ? $post_id : false;
		}

		function create_post( BcBlast $blast ) {
			$post_content = $this->formatter->format( $blast );
			if ( ! $post_content ) {
				return false;
			}

			$postarr = [
				'post_title' => $blast->get_title(),
				'post_content' => $post_content,
				'post_status' => 'publish',
			];

			$categories = $blast->get_categories();
			if ( 0 < count( $categories ) ) {
				$postarr['post_category'] = $categories;
			}

			$post_id = $this->wph->wp_insert_post( $postarr, true );
			if ( $this->wph->is_wp_error( $post_id ) ) {
				return $post_id;
			}

			$image_data = $blast->get_image_data();
			return null !== $image_data  ?
				$this->add_featured_thumbnail( $post_id, $image_data ) :
				$post_id;
		}
	}
}
