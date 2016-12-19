<?php

namespace Terescode\BlastCaster;

require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/class-blast-dao.php';
require_once 'includes/class-media-loader.php';
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
		$m_loader = $this->mock( 'Terescode\BlastCaster\BcMediaLoader' );
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
		$controller = new BcAddBlastHandler( $m_helper, $m_loader, $m_dao );
		$controller->handle_error( 'a.error.code' );
	}

	/**
	 * Test handle
	 */
	function test_handle_calls_create_post_with_required_data_given_required_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_loader = $this->mock( 'Terescode\BlastCaster\BcMediaLoader' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_dao->expects( $this->once() )
			->method( 'create_post' )
			->with(
				$this->callback( function ( $subject ) {
					return $subject instanceof \Terescode\BlastCaster\BcBlast &&
						'An Article Title' === $subject->get_title() &&
						'This is some description of the article.' === $subject->get_description();
				} )
			);
		$m_helper->expects( $this->once() )
			->method( 'add_admin_notice' )
			->with(
				'Blast added!',
				\Terescode\WordPress\TcPluginHelper::NOTICE_TYPE_UPDATED,
				true
			);

		// @exercise
		$controller = new BcAddBlastHandler( $m_helper, $m_loader, $m_dao );
		$controller->handle( [
			'bc-add-title' => 'An Article Title',
			'bc-add-desc' => 'This is some description of the article.',
		] );
	}

	/**
	 * Test handle
	 */
	function test_handle_calls_create_post_with_optional_data_given_optional_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_loader = $this->mock( 'Terescode\BlastCaster\BcMediaLoader' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_loader->method( 'load_media' )
			->willReturn(
				[
					'file' => 'image.png',
					'url' => 'http://www.terescode.com/path/to/image.png',
					'type' => 'image/png',
				]
			);
		$m_dao->expects( $this->once() )
			->method( 'create_post' )
			->with(
				$this->callback( function ( $subject ) {
					$image_data = $subject->get_image_data();
					return $subject instanceof \Terescode\BlastCaster\BcBlast &&
						'An Article Title' === $subject->get_title() &&
						'This is some description of the article.' === $subject->get_description() && null !== $image_data &&
						3 === count( $image_data ) &&
						'image.png' === $image_data['file'] &&
						'http://www.terescode.com/path/to/image.png' === $image_data['url'] &&
						'image/png' === $image_data['type'];
				} )
			);
		$m_helper->expects( $this->once() )
			->method( 'add_admin_notice' )
			->with(
				'Blast added!',
				\Terescode\WordPress\TcPluginHelper::NOTICE_TYPE_UPDATED,
				true
			);

		// @exercise
		$controller = new BcAddBlastHandler( $m_helper, $m_loader, $m_dao );
		$controller->handle( [
			'bc-add-title' => 'An Article Title',
			'bc-add-desc' => 'This is some description of the article.',
			'image' => 'yada yada',
		] );
	}

	/**
	 * Test handle
	 */
	function test_handle_returns_error_given_media_loader_returns_error() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_loader = $this->mock( 'Terescode\BlastCaster\BcMediaLoader' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_loader->method( 'load_media' )
			->willReturn(
				[
					'error' => 'Could not load image!',
				]
			);
		$m_dao->expects( $this->never() )
			->method( 'create_post' );

		// @exercise
		$controller = new BcAddBlastHandler( $m_helper, $m_loader, $m_dao );
		$ret = $controller->handle( [
			'bc-add-title' => 'An Article Title',
			'bc-add-desc' => 'This is some description of the article.',
			'image' => 'yada yada',
		] );
		$this->assertEquals( \Terescode\BlastCaster\BcStrings::ABF_BUILD_ACTION_DATA_FAILED, $ret );
	}
}
