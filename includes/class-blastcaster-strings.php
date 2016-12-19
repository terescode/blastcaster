<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/interface-strings.php';

if ( ! class_exists( __NAMESPACE__ . '\BcStrings' ) ) {
	class BcStrings implements \Terescode\WordPress\TcStrings {
		// add blast label strings
		const ABF_BLAST_PAGE_TITLE = 'bc.abf.blast-page-title';
		const ABF_BLAST_MENU_TITLE = 'bc.abf.blast-menu-title';
		const ABF_BLAST_SCREEN_TITLE = 'bc.abf.blast-screen-title';
		const ABF_BLAST_BUTTON_LABEL = 'bc.abf.blast-button-label';
		const ABF_TITLE_LABEL = 'bc.abf.title-label';
		const ABF_CATEGORIES_LABEL = 'bc.abf.cat-label';
		const ABF_IMAGE_LABEL = 'bc.abf.img-label';
		const ABF_DESCRIPTION_LABEL = 'bc.abf.desc-label';
		const ABF_TAGS_LABEL = 'bc.abf.tags-label';
		// add blast form messages
		const ABF_INVALID_PAGE_DATA = 'bc.abf.inv-page-data';
		const ABF_NO_ACCESS = 'bc.abf.no-access';
		const ABF_MISSING_BLAST_TITLE = 'bc.abf.missing-title';
		const ABF_MISSING_BLAST_DESCRIPTION = 'bc.abf.missing-desc';
		const ABF_INVALID_BLAST_IMAGE_TYPE = 'bc.abf.invalid-image-type';
		const ABF_MISSING_BLAST_IMAGE_URL = 'bc.abf.missing-image-url';
		const ABF_MISSING_BLAST_IMAGE_FILE = 'bc.abf.missing-image-file';
		const ABF_BUILD_ACTION_DATA_FAILED = 'bc.abf.build-action-data-failed';
		const ABF_LOAD_MEDIA_FAILED = 'bc.abf.load-media-failed';

		private $bundle;

		function __construct() {
			$this->bundle = array(
				self::ABF_BLAST_PAGE_TITLE =>
					__( 'Add a blast', 'blastcaster' ),
				self::ABF_BLAST_MENU_TITLE =>
					__( 'Add a blast', 'blastcaster' ),
				self::ABF_BLAST_SCREEN_TITLE =>
					__( 'Add a blast', 'blastcaster' ),
				self::ABF_BLAST_BUTTON_LABEL =>
					__( 'Add a blast', 'blastcaster' ),
				self::ABF_TITLE_LABEL =>
					__( 'Title', 'blastcaster' ),
				self::ABF_CATEGORIES_LABEL =>
					__( 'Categories', 'blastcaster' ),
				self::ABF_IMAGE_LABEL =>
					__( 'Image', 'blastcaster' ),
				self::ABF_DESCRIPTION_LABEL =>
					__( 'Description', 'blastcaster' ),
				self::ABF_TAGS_LABEL =>
					__( 'Tags', 'blastcaster' ),
				self::ABF_INVALID_PAGE_DATA =>
					__( 'The page data received from the original source could not be decoded. (%1$d - %2$s)', 'blastcaster' ),
				self::ABF_NO_ACCESS =>
					__( 'You do not have access to add blasts.', 'blastcaster' ),
				self::ABF_MISSING_BLAST_TITLE =>
					__( 'Please provide a title for the blast.', 'blastcaster' ),
				self::ABF_MISSING_BLAST_DESCRIPTION =>
					__( 'Please provide a description for the blast.', 'blastcaster' ),
				self::ABF_INVALID_BLAST_IMAGE_TYPE =>
					__( 'Oops! The image type was missing. Try submitting your request again. If that does not help, head to the nearest administrator.', 'blastcaster' ),
				self::ABF_MISSING_BLAST_IMAGE_URL =>
					__( 'Please select an image for the blast.', 'blastcaster' ),
				self::ABF_MISSING_BLAST_IMAGE_FILE =>
					__( 'Please choose an image for the blast.', 'blastcaster' ),
				self::ABF_BUILD_ACTION_DATA_FAILED =>
					__( 'Oops! The action data could not be generated. Try submitting your request again. If that does not help, head to the nearest administrator.', 'blastcaster' ),
				self::ABF_LOAD_MEDIA_FAILED =>
					__( 'Oops! The image could not be loaded into the media gallery. Try submitting your request again. If that does not help, head to the nearest administrator.', 'blastcaster' ),
			);
		}

		function get_string( $code, $args = array() ) {
			if ( isset( $this->bundle[ $code ] ) ) {
				return vsprintf( $this->bundle[ $code ], $args );
			}
			return '';
		}
	}
}
