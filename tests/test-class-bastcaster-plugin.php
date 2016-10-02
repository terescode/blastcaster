<?php

require_once 'includes/constants.php';
require_once 'admin/class-blastcaster-plugin.php';

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class BlastCasterPluginTest extends BcPhpUnitTestCase {

	/**
	 * Test install_admin_menus
	 */
	function test_register_admin_menus_should_call_add_posts_page() {
		// @sut
		$plugin = new BlastCasterPlugin();

		// @setup
		\WP_Mock::wpFunction( '__', array(
			'times' => 2,
			'args' => array(
				\WP_Mock\Functions::type( 'string' ),
				\WP_Mock\Functions::type( 'string' ),
			),
			'return' => 'test',
		) );

		\WP_Mock::wpFunction( 'add_posts_page', array(
			'times' => 1,
			'args' => array(
				\WP_Mock\Functions::type( 'string' ),
				\WP_Mock\Functions::type( 'string' ),
				'edit_posts',
				BlastCasterPlugin::BC_ADD_BLAST_SCREEN_ID,
				\WP_Mock\Functions::type( 'array' ),
			),
		) );

		// @test
		$plugin->register_admin_menus();
	}

	/**
	 * Test run
	 */
	function test_run_should_call_init_and_return() {
		// @sut @test
		$plugin = BlastCasterPlugin::run();

		// @assertions
		$this->assertNotNull( $plugin );
		$this->assertEquals( BlastCasterPlugin::BC_PLUGIN_ID, $plugin->get_plugin_id() );
	}
}
