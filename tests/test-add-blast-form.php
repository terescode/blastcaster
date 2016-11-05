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
		$wph = $this->mock( 'TcWpHelper' );

		$wph->expects( $this->once() )
			->method( 'admin_url' )
			->with( 'admin-post.php' );
		$wph->expects( $this->once() )
			->method( 'esc_html' );
		$wph->expects( $this->exactly( 3 ) )
			->method( 'wp_nonce_field' );
		$wph->expects( $this->exactly( 2 ) )
			->method( 'do_meta_boxes' )
			->withConsecutive(
				[ '', 'normal', null ],
				[ '', 'advanced', null ]
			);
		$wph->expects( $this->exactly( 1 ) )
			->method( 'submit_button' );
		$this->expectOutputRegex( '/<div id="poststuff">/' );

		// @test
		include( 'admin/views/add-blast-form.php' );
	}
}
