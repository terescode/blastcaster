<?php

require_once 'includes/constants.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/class-blastcaster-plugin.php';

/**
 * Class BlastCasterPluginTest
 *
 * @package Blastcaster
 */

class BlastCasterPluginTest extends BcPhpUnitTestCase {
	/**
	 * Test constructor and get_plugin_id
	 */
	function test_constructor_should_set_bc_plugin_id_and_helper() {
		// @setup
		$helper = $this->mock( 'TcPluginHelper' );

		// @sut @exercise
		$plugin = new BlastCasterPlugin( $helper );

		// @verify
		$this->assertEquals( BlastCasterPlugin::BC_PLUGIN_ID, $plugin->get_plugin_id() );
	}

	/**
	 * Test init
	 */
	function test_init_should_call_init_admin_plugin_with_self() {
		// @setup
		$helper = $this->mock( 'TcPluginHelper' );
		// @sut
		$plugin = new BlastCasterPlugin( $helper );

		$helper->expects( $this->once() )
			->method( 'init_admin_plugin' )
			->with( $this->equalTo( $plugin ) );

		// @exercise
		$plugin->init();
	}

	/**
	 * Test load
	 */
	function test_load_should_call_load_textdomain_with_self() {
		// @setup
		$helper = $this->mock( 'TcPluginHelper' );
		// @sut
		$plugin = new BlastCasterPlugin( $helper );

		$helper->expects( $this->once() )
			->method( 'load_textdomain' )
			->with( $this->equalTo( $plugin ) );

		// @exercise
		$plugin->load();
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_call_install_admin_menus_with_add_blast_controller() {
		// @setup
		$helper = $this->mock( 'TcPluginHelper' );
		// @sut
		$plugin = new BlastCasterPlugin( $helper );

		$helper->expects( $this->once() )
			->method( 'install_admin_menus' )
			->with( $this->callback( function ( $subject ) {
				return is_array( $subject ) &&
					1 === count( $subject ) &&
					$subject[0] instanceof BcAddBlastController;
			}));

		// @exercise
		$plugin->install_admin_menus();
	}

	/**
	 * Test install_admin_menus
	 */
	function test_com_terescode_create_blastcaster_should_return_initialized_plugin() {
		// @exercise
		$plugin = com_terescode_create_blastcaster();

		// @verify
		$this->assertNotNull( $plugin );
		$this->assertInstanceOf( 'BlastCasterPlugin', $plugin );
	}
}
