<?php
require_once( BC_PLUGIN_DIR . 'admin/class-admin-plugin.php' );
require_once( BC_PLUGIN_DIR . 'admin/controllers/class-bc-admin-controller.php' );

if ( ! class_exists( 'BlastCasterPlugin' ) ) {

	final class BlastCasterPlugin extends BcAdminPlugin {
		const BC_PLUGIN_ID = 'blastcaster';
		const BC_ADD_BLAST_SCREEN_ID = 'bc_add_blast';

		/**
		 * Admin controller
		 *
		 * @var object
		 * @access private
		 */
		private $admin_controller;

		public function __construct() {
			parent::__construct( self::BC_PLUGIN_ID );
		}

		function register_admin_menus() {
			$hooknames = array();
			$this->admin_controller = new BcAdminController( $this );
			$hooknames[] = add_posts_page(
				__( 'Add a blast', 'blastcaster' ),
				__( 'Add a blast', 'blastcaster' ),
				'edit_posts',
				self::BC_ADD_BLAST_SCREEN_ID,
				array( $this->admin_controller, 'do_add_blast' )
			);
			return $hooknames;
		}

		static function run() {
			$blastcaster_plugin = new BlastCasterPlugin();
			$blastcaster_plugin->init();
			return $blastcaster_plugin;
		}
	}

}
