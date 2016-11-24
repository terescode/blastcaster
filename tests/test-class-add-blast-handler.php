<?php

namespace Terescode\BlastCaster;

require_once 'tests/stub-translate.php';
require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/class-blast-dao.php';
require_once 'admin/class-add-blast-handler.php';

/**
 * Class BcAddBlastHandlerTest
 *
 * @package Blastcaster
 */

class BcAddBlastHandlerTest extends \BcPhpUnitTestCase {
	/**
	 * Test handle_error
	 */
	function test_handle_error_should_add_admin_notice_with_error_given_err_is_not_null() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'string' )
			->with( 'a.error.code' )
			->willReturn( 'a string' );
		$m_helper->expects( $this->once() )
			->method( 'add_admin_notice' )
			->with( 'a string' );

		// @exercise
		$controller = new BcAddBlastHandler( $m_helper, $m_dao );
		$controller->handle_error( 'a.error.code' );
	}

	/**
	 * Test handle
	 */
	function test_handle() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );

		// @exercise
		$controller = new BcAddBlastHandler( $m_helper, $m_dao );
		$controller->handle( [] );
	}
}
