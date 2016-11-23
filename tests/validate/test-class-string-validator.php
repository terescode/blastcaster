<?php

namespace Terescode\WordPress;

require_once 'includes/constants.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/validate/class-string-validator.php';

/**
 * Class TcStringValidatorTest
 *
 * @package Blastcaster
 */

class TcStringValidatorTest extends \BcPhpUnitTestCase {
	function test_validate_should_return_code_given_param_returns_empty() {
		// @setup
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$data_map = [];

		// @exercise
		$validator = new TcStringValidator( $m_helper, 'foo', -1 );
		$ret = $validator->validate( $data_map );
		$this->assertEquals( -1, $ret );
	}

	function test_validate_should_set_value_and_return_null_given_param_returns_value() {
		// @setup
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$data_map = [];

		$m_helper->method( 'param' )
			->willReturn( 'bar' );

		// @exercise
		$validator = new TcStringValidator( $m_helper, 'foo', -1 );
		$ret = $validator->validate( $data_map );
		$this->assertNull( $ret );
		$this->assertTrue( isset( $data_map['foo'] ) );
		$this->assertEquals( 'bar', $data_map['foo'] );
	}
}
