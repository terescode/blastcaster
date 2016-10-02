<?php

require_once 'includes/constants.php';

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class BlastCasterTest extends BcPhpUnitTestCase {

	/**
	 * Test setup
	 */
	public function setUp() {
	}

	/**
	 * Teardown
	 */

	public function tearDown() {
	}

	/**
	 * Test including the main plugin file should fail if WPINC is not set
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */

	public function test_include_plugin_file_should_fail_given_WPINC_not_set() {
		$ret = include_once( 'blastcaster.php' );
		$this->assertEquals( -1, $ret );
	}
	
	/**
	 * Test including the main plugin file should succeed
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */

	public function test_include_plugin_file_should_succeed_given_WPINC_is_set() {
		\WP_Mock::setUp();

		\WP_Mock::wpFunction( 'register_activation_hook', array(
			'times' => 1,
			'args' => array(
				\WP_Mock\Functions::type( 'string' ),
				\WP_Mock\Functions::type( 'array' ),
			),
		) );

		\WP_Mock::wpFunction( 'register_deactivation_hook', array(
			'times' => 1,
			'args' => array(
				\WP_Mock\Functions::type( 'string' ),
				\WP_Mock\Functions::type( 'array' ),
			),
		) );

		$ret = include_once( 'blastcaster.php' );
		$this->assertEquals( 1, $ret );

		\WP_Mock::tearDown();
	}
}
