<?php

/**
 * Class ConstantsTest
 *
 * @package Blastcaster
 */

class ConstantsTest extends BcPhpUnitTestCase {

	/**
	 * Test including the main plugin file
	 */

	public function test_constants_defined() {
		require_once( 'includes/constants.php' );
		$this->assertNotEmpty( 'BC_PLUGIN_ID' );
		$this->assertNotEmpty( 'BC_PLUGIN_DIR' );
	}
}
