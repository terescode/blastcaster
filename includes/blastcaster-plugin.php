<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'includes/class-wp-helper.php';
require_once BC_PLUGIN_DIR . 'includes/class-plugin-helper.php';
require_once BC_PLUGIN_DIR . 'includes/class-blastcaster-strings.php';
require_once BC_PLUGIN_DIR . 'includes/class-generic-plugin.php';
require_once BC_PLUGIN_DIR . 'includes/class-generic-controller.php';
require_once BC_PLUGIN_DIR . 'includes/class-generic-action.php';
require_once BC_PLUGIN_DIR . 'includes/validate/class-capability-validator.php';
require_once BC_PLUGIN_DIR . 'includes/validate/class-string-validator.php';
require_once BC_PLUGIN_DIR . 'includes/validate/class-blast-image-validator.php';
require_once BC_PLUGIN_DIR . 'includes/class-blast-dao.php';
require_once BC_PLUGIN_DIR . 'includes/class-wp-include-formatter.php';
require_once BC_PLUGIN_DIR . 'includes/class-media-loader.php';
require_once BC_PLUGIN_DIR . 'admin/class-add-blast-page.php';
require_once BC_PLUGIN_DIR . 'admin/class-add-blast-page-helper.php';
require_once BC_PLUGIN_DIR . 'admin/class-add-blast-handler.php';

use Terescode\WordPress\TcWpHelper;
use Terescode\WordPress\TcPluginHelper;
use Terescode\WordPress\TcGenericPlugin;
use Terescode\WordPress\TcGenericController;
use Terescode\WordPress\TcGenericAction;
use Terescode\WordPress\TcCapabilityValidator;
use Terescode\WordPress\TcStringValidator;
use Terescode\BlastCaster\BcBlastImageValidator;
use Terescode\BlastCaster\BcStrings;
use Terescode\BlastCaster\BcAddBlastPage;
use Terescode\BlastCaster\BcAddBlastPageHelper;
use Terescode\BlastCaster\BcAddBlastHandler;
use Terescode\BlastCaster\BcBlastDao;
use Terescode\BlastCaster\BcWpIncludeFormatter;

if ( ! function_exists( __NAMESPACE__ . '\create_plugin' ) ) {
	function create_plugin() {
		$plugin_helper = new TcPluginHelper( new TcWpHelper(), new BcStrings() );
		return new TcGenericPlugin(
			BC_PLUGIN_ID,
			$plugin_helper,
			[
				new TcGenericController(
					$plugin_helper,
					new BcAddBlastPage(
						$plugin_helper,
						new BcAddBlastPageHelper( $plugin_helper )
					),
					[
						new TcGenericAction(
							$plugin_helper,
							BcAddBlastPage::BC_ADD_BLAST_POST_ACTION,
							[
								new TcCapabilityValidator( $plugin_helper, 'edit_posts', BcStrings::ABF_NO_ACCESS ),
								new TcStringValidator( $plugin_helper, 'bc-add-title', BcStrings::ABF_MISSING_BLAST_TITLE ),
								new TcStringValidator( $plugin_helper, 'bc-add-desc', BcStrings::ABF_MISSING_BLAST_DESCRIPTION ),
								new BcBlastImageValidator( $plugin_helper ),
							],
							new BcAddBlastHandler(
								$plugin_helper,
								new BcMediaLoader( $plugin_helper, BcAddBlastPage::BC_ADD_BLAST_POST_ACTION ),
								new BcBlastDao(
									$plugin_helper,
									new BcWpIncludeFormatter( BC_PLUGIN_DIR . 'admin/templates/wp-post-tpl.php' )
								)
							)
						),
					]
				),
			]
		);
	}
} // @codeCoverageIgnore because xdebug can't seem to see this line as not executable
