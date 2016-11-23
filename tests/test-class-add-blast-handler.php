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
	function test_handle_error_should_redirect_with_error_given_err_is_not_null() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->expects( $this->once() )
			->method( 'admin_url' )
			->with( $this->isType( 'string' ) )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastPage::BC_ADD_BLAST_SCREEN_ID
				. '&code=a.error.code'
			) );

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
