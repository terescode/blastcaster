<?php

require_once 'tests/stub-translate.php';
require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';

/**
 * Class BcRendererTest
 *
 * @package Blastcaster
 */

class AddBlastFormTest extends BcPhpUnitTestCase {

	/**
	 * Test render
	 */
	function test_render() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );

		$m_wph->expects( $this->once() )
			->method( 'admin_url' )
			->with( 'admin-post.php' );
		$m_wph->expects( $this->once() )
			->method( 'esc_html' );
		$m_wph->expects( $this->once() )
			->method( 'esc_url' );
		$m_wph->expects( $this->exactly( 3 ) )
			->method( 'wp_nonce_field' );
		$m_wph->expects( $this->exactly( 2 ) )
			->method( 'do_meta_boxes' )
			->withConsecutive(
				[ '', 'normal', null ],
				[ '', 'advanced', null ]
			);
		$m_wph->expects( $this->exactly( 1 ) )
			->method( 'submit_button' );
		$this->expectOutputRegex( '/<form name="blastcaster-form"/' );

		// @test
		$wph = $m_wph;
		$plugin_helper = $m_helper;
		include( 'admin/views/add-blast-page.php' );
	}
}
