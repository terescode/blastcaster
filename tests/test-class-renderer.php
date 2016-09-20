<?php

require_once 'tests/stubs/basic-plugin-stub.php';
require_once 'tests/stubs/controller-stub.php';

/**
 * Class BcRendererTest
 *
 * @package Blastcaster
 */

class BcRendererTest extends BcPhpUnitTestCase {

	/**
	 * Test render
	 */
	function test_render() {
		// @doubles
		$plugin = new BcBasicPluginStub( 'plugin-1' );
		$controller = new BcControllerStub( $plugin );
		// @sut
		$renderer = new BcRenderer();

		// @expectations
		$this->expectOutputRegex( '/Hello, world[!]/' );

		// @test
		$renderer->render( $controller, 'tests/view.php' );
	}
}
