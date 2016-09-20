<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Blastcaster
 */

// load test dependencies
require_once 'vendor/autoload.php';

if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( $file ) {
		return dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	}
}

require_once 'includes/constants.php';

if ( ! class_exists( 'BcPhpUnitTestCase' ) ) {

	abstract class BcPhpUnitTestCase extends PHPUnit_Framework_TestCase {
		/**
		 * Test setup
		 */
		public function setUp() {
			\WP_Mock::setUp();
		}

		/**
		 * Teardown
		 */

		public function tearDown() {
			\WP_Mock::tearDown();
		}
	}
}
