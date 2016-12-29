<?php

namespace Terescode\WordPress;

require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/class-blastcaster-strings.php';
require_once 'includes/validate/class-wp-category-validator.php';

use Terescode\BlastCaster\BcStrings;

/**
 * Class TcStringValidatorTest
 *
 * @package Blastcaster
 */

class TcCapabilityValidatorTest extends \BcPhpUnitTestCase {
	function test_validate_should_return_null_given_empty_param() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$data_map = [];

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'param' )
			->willReturn( null );

		// @exercise
		$validator = new TcWpCategoryValidator( $m_helper, 'foo' );
		$ret = $validator->validate( $data_map );
		$this->assertNull( $ret );
		$this->assertFalse( isset( $data_map['foo'] ) );
	}

	function test_vaildate_should_return_code_given_empty_param_and_code() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$data_map = [];

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'param' )
			->willReturn( null );

		// @exercise
		$validator = new TcWpCategoryValidator( $m_helper, 'foo', -1 );
		$ret = $validator->validate( $data_map );
		$this->assertEquals( -1, $ret );
		$this->assertFalse( isset( $data_map['foo'] ) );
	}

	function test_validate_should_return_code_given_param_not_array() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$data_map = [];

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'param' )
			->willReturn( 'a string' );

		// @exercise
		$validator = new TcWpCategoryValidator( $m_helper, 'foo', -1 );
		$ret = $validator->validate( $data_map );
		$this->assertEquals( BcStrings::ABF_INVALID_CATEGORY_TYPE, $ret );
		$this->assertFalse( isset( $data_map['foo'] ) );
	}

	function test_validate_should_return_code_given_param_not_numeric() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$data_map = [];

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'param' )
			->willReturn( [ 'han solo', 'c-3po' ] );

		// @exercise
		$validator = new TcWpCategoryValidator( $m_helper, 'foo', -1 );
		$ret = $validator->validate( $data_map );
		$this->assertEquals( BcStrings::ABF_INVALID_CATEGORY, $ret );
		$this->assertFalse( isset( $data_map['foo'] ) );
	}

	function test_validate_should_set_value_and_return_null_given_param_is_valid() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$data_map = [];

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'param' )
			->willReturn( [ '1', '12345', '6789' ] );

		// @exercise
		$validator = new TcWpCategoryValidator( $m_helper, 'foo' );
		$ret = $validator->validate( $data_map );
		$this->assertNull( $ret );
		$this->assertTrue( isset( $data_map['foo'] ) );
		$foo = $data_map['foo'];
		$this->assertEquals( 3, count( $foo ) );
		$this->assertEquals( 1, $foo[0] );
		$this->assertEquals( 12345, $foo[1] );
		$this->assertEquals( 6789, $foo[2] );

	}
}
