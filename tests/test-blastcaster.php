<?php

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class BlastCasterTest extends BcPhpUnitTestCase {

	/**
	 * Test including the main plugin file
	 */

	public function test_include_plugin_file_should_succeed() {

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

		include_once( 'blastcaster.php' );
		$this->assertTrue( true );
	}
}
