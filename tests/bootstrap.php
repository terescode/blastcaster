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

if ( ! function_exists( 'plugin_dir_url' ) ) {
	function plugin_dir_url( $file ) {
		return 'http://local.wordpress.dev/wp-content/plugins/' . BC_PLUGIN_ID;
	}
}

if ( ! function_exists( 'wp_include_once' ) ) {
	function wp_include_once( $file ) {
		if ( file_exists( WP_INCLUDES_PATH . $file ) ) {
			include_once( WP_INCLUDES_PATH . $file );
		} else {
			throw new Exception( 'Can\'t find wp include ' . WP_INCLUDES_PATH . $file );
		}
	}
}


if ( ! class_exists( 'BcPhpUnitTestCase' ) ) {

	abstract class BcPhpUnitTestCase extends PHPUnit_Framework_TestCase {
		/**
		 * Expect HTML callback
		 *
		 * @var boolean
		 * @access private
		 */
		private $expect_html_callback = null;

		/**
		 * Test setup
		 */
		public function setUp() {
			//\WP_Mock::setUp();
		}

		/**
		 * Teardown
		 */

		public function tearDown() {
			//\WP_Mock::tearDown();
		}

		public function expect_html( $callback ) {
			$this->expect_html_callback = $callback;
		}

		public function hasExpectationOnOutput() {
			return parent::hasExpectationOnOutput() || null !== $this->expect_html_callback;
		}

		public function runBare() {
			parent::runBare();

			if ( null !== $this->expect_html_callback ) {
				$actual_output = $this->getActualOutput();

				// set user error handling
				$prev_flag = libxml_use_internal_errors( true );

				// load the document
				$doc = new DOMDocument();
				$doc->loadHTML( $actual_output );

				// Fail if any parsing errors
				$errors = libxml_get_errors();
				$message = null;
				if ( 0 !== count( $errors ) ) {
					$msgs = array();
					foreach ( $errors as $err ) {
						$msgs[] = $err->message;
					}
					$message = join( '\n', $msgs );
				}
				$this->assertEmpty( $errors, $message );

				// verify the output
				call_user_func( $this->expect_html_callback, $doc );

				// clear errors
				libxml_clear_errors();

				// reset flag
				libxml_use_internal_errors( $prev_flag );

				// clear callback
				//$this->expect_html_callback = null;
			}
		}

		public function invoke_with_random_count( $times, $max, $func ) {
			for ( $idx = 0; $idx < $times; $idx += 1 ) {
				$rand = rand( 1, $max );
				$func( $rand );
			}
		}

		public function mock( $class ) {
			return $this->getMockBuilder( $class )
				->disableOriginalConstructor()
				->getMock();
		}
	}
}

