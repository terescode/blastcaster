<?php

require_once 'tests/stubs/basic-plugin-stub.php';

/**
 * Class BcBasicPluginTest
 *
 * @package Blastcaster
 */

class BcBasicPluginTest extends BcPhpUnitTestCase {

	/**
	 * Test constructor
	 */
	function test_construct_should_return_initialized_object() {
		// @sut
		$plugin = new BcBasicPluginStub( 'basic-plugin-stub' );

		// @assertions
		$this->assertEquals( 'basic-plugin-stub', $plugin->get_plugin_id() );
	}

	/**
	 * Test init
	 */
	function test_init_should_register_hooks_and_call_actions() {
		// @sut
		$plugin = new BcBasicPluginStub( 'admin-plugin-stub' );

		// @setup
		\WP_Mock::wpFunction( 'register_activation_hook', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'string' ), array( $plugin, 'activate' ) ),
		) );

		\WP_Mock::wpFunction( 'register_deactivation_hook', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'string' ), array( $plugin, 'deactivate' ) ),
		) );

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			array( $plugin, 'load' )
		);

		// @test
		$plugin->init();
	}

	/**
	 * Test init_plugin
	 */
	function test_load_plugin_should_call_load_text_domain() {
		// @sut
		$plugin = new BcBasicPluginStub( 'admin-plugin-stub' );

		// @setup
		\WP_Mock::wpFunction( 'load_plugin_textdomain', array(
			'times' => 1,
			'args' => array( 'admin-plugin-stub', false, \WP_Mock\Functions::type( 'string' ) ),
		) );

		// @test
		$plugin->load();
	}
}
