<?php

require_once 'tests/stubs/basic-plugin-stub.php';
require_once 'tests/stubs/controller-stub.php';
require_once 'tests/stubs/renderer-stub.php';

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class BcControllerTest extends BcPhpUnitTestCase {

	/**
	 * Test get_plugin
	 */
	function test_get_plugin() {
		// @doubles
		$plugin_stub = new BcBasicPluginStub( 'a-plugin' );
		// @sut
		$controller = new BcControllerStub( $plugin_stub );

		// @test
		$this->assertEquals( $plugin_stub, $controller->get_plugin() );
	}

	/**
	 * Test install_admin_menus
	 */
	function test_render() {
		// @doubles
		$plugin_stub = new BcBasicPluginStub( 'a-plugin' );
		// @sut
		$controller = new BcControllerStub( $plugin_stub );

		// @expectations
		$this->expectOutputRegex( '/Hello, world[!]/' );

		// @test
		$controller->do_action();
	}

	/**
	 * Test install_admin_menus
	 */
	function test_render_should_invoke_custom_renderer_given_custom_renderer() {
		// @doubles
		$plugin_stub = new BcBasicPluginStub( 'a-plugin' );
		$renderer_stub = new BcRendererStub();
		// @sut
		$controller = new BcControllerStub( $plugin_stub, $renderer_stub );

		// @test
		$controller->do_action();

		// @assertiongs
		$this->assertEquals( true, $renderer_stub->render_called );
	}
}
