<?php

namespace Terescode\BlastCaster;

require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/class-wp-include-formatter.php';
require_once 'includes/class-blast-dao.php';

/**
 * Class BcBlastDaoTest
 *
 * @package Blastcaster
 */

class BcBlastDaoTest extends \BcPhpUnitTestCase {

	function test_create_post_should_return_false_given_formatter_fails() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_formatter = $this->mock( '\Terescode\BlastCaster\BcBlastFormatter' );
		$blast = new BcBlast( 'TDD is fun', 'TDD is test driven development!' );
		$dao = new BcBlastDao( $m_helper, $m_formatter );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_formatter->method( 'format' )
			->willReturn( false );

		// @exercise
		$ret = $dao->create_post( $blast );

		// @verify
		$this->assertFalse( $ret );
	}

	function test_create_post_should_return_wp_error_if_wp_insert_post_does() {
		// @setup
		wp_include_once( 'class-wp-error.php' );
		$m_error = $this->mock( 'WP_Error' );
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$formatter = new BcWpIncludeFormatter( 'tests/fixtures/sample-template.php' );
		$blast = new BcBlast( 'TDD is fun', 'TDD is test driven development!' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'wp_insert_post' )
			->willReturn( $m_error );

		// @exercise
		$dao = new BcBlastDao( $m_helper, $formatter );
		$ret = $dao->create_post( $blast );

		// @verify
		$this->assertInstanceOf( 'WP_Error', $ret );
	}

	function test_create_post_should_return_post_id_if_wp_insert_post_does() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$formatter = new BcWpIncludeFormatter( 'tests/fixtures/sample-template.php' );
		$blast = new BcBlast( 'TDD is fun', 'TDD is test driven development!' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'wp_insert_post' )
			->willReturn( 3456789 );

		// @exercise
		$dao = new BcBlastDao( $m_helper, $formatter );
		$ret = $dao->create_post( $blast );

		// @verify
		$this->assertEquals( 3456789, $ret );
	}
}
