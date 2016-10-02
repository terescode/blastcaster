<?php

require_once 'includes/constants.php';
require_once 'admin/controllers/class-bc-admin-controller.php';
require_once 'tests/stubs/basic-plugin-stub.php';
require_once 'tests/stubs/renderer-stub.php';

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class BcAdminControllerTest extends BcPhpUnitTestCase {

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_not_set_page_data_given_no_post_data() {
		// @doubles
		$plugin = new BcAdminPluginStub( 'plugin-1' );
		// @SUT
		$controller = new BcAdminController( $plugin, new BcRendererStub() );

		// @test
		$controller->do_add_blast();

		// @assertions
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_not_set_page_data_given_empty_post_data() {
		// @doubles
		$plugin = new BcAdminPluginStub( 'plugin-1' );
		// @SUT
		$controller = new BcAdminController( $plugin, new BcRendererStub() );

		// @setup
		$_POST['pageData'] = '';

		// @test
		$controller->do_add_blast();

		// @assertions
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_not_set_page_data_given_wsonly_post_data() {
		// @doubles
		$plugin = new BcAdminPluginStub( 'plugin-1' );
		// @SUT
		$controller = new BcAdminController( $plugin, new BcRendererStub() );

		// @setup
		$_POST['pageData'] = '\t\t\t\r\n\n\t     \t\t\r\n';

		// @test
		$controller->do_add_blast();

		// @assertions
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_set_page_data_given_valid_post_data() {
		// @doubles
		$plugin = new BcAdminPluginStub( 'plugin-1' );
		// @SUT
		$controller = new BcAdminController( $plugin, new BcRendererStub() );

		// @setup
		$json_file = file_get_contents( 'tests/fixtures/sample.json', true );
		$_POST['pageData'] = $json_file;

		// @test
		$controller->do_add_blast();

		// @assertions
		$page_data = $controller->get_page_data();
		$this->assertNotNull( $page_data );
		$this->assertNotNull( $page_data->urls );
		$this->assertNotNull( $page_data->images );
		$this->assertNotNull( $page_data->allImages );
		$this->assertNotNull( $page_data->titles );
		$this->assertNotNull( $page_data->descriptions );
		$this->assertNotNull( $page_data->tags );
		$this->assertEquals( 2, count( $page_data->titles ) );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_hook_admin_notices_given_invalid_post_data() {
		// @doubles
		$plugin = new BcAdminPluginStub( 'plugin-1' );
		// @SUT
		$controller = new BcAdminController( $plugin, new BcRendererStub() );

		// @setup
		$json_file = file_get_contents( 'tests/fixtures/sample-invalid.json', true );
		$_POST['pageData'] = $json_file;
		\WP_Mock::expectActionAdded( 'admin_notices', function () {} );

		// @test
		$controller->do_add_blast();

		// @assertions
		$page_data = $controller->get_page_data();
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_hook_admin_notices_given_invalid_post_data_no_json_last_error_msg() {
		// @doubles
		$plugin = new BcAdminPluginStub( 'plugin-1' );
		// @SUT
		$controller = new BcAdminController( $plugin, new BcRendererStub() );

		// @setup
		$json_file = file_get_contents( 'tests/fixtures/sample-invalid.json', true );
		$_POST['pageData'] = $json_file;
		\WP_Mock::expectActionAdded( 'admin_notices', function () {} );

		// @test

		$controller->do_add_blast();

		// @assertions
		$page_data = $controller->get_page_data();
		$this->assertNull( $controller->get_page_data() );
	}
}
