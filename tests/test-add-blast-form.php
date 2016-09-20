<?php

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
		// @doubles
		$plugin = new BcBasicPluginStub( 'plugin-1' );
		$controller = new BcControllerStub( $plugin );

		// @setup
		\WP_Mock::wpFunction( 'screen_icon', array(
			'times' => 1,
			'args' => array(),
		) );

		\WP_Mock::wpFunction( 'esc_html_e', array(
			'times' => 1,
			'args' => array( '*', '*' ),
		) );

		\WP_Mock::wpFunction( 'wp_nonce_field', array(
			'times' => 1,
			'args' => array( 'meta-box-order', 'meta-box-order-nonce', false ),
		) );

		\WP_Mock::wpFunction( 'wp_nonce_field', array(
			'times' => 1,
			'args' => array( 'closedpostboxes', 'closedpostboxesnonce', false ),
		) );

		\WP_Mock::wpFunction( 'do_meta_boxes', array(
			'times' => 1,
			'args' => array( '', 'normal', null ),
		) );

		\WP_Mock::wpFunction( 'do_meta_boxes', array(
			'times' => 1,
			'args' => array( '', 'advanced', null ),
		) );
		$this->expectOutputRegex( '/<div id="poststuff">/' );

		// @test
		include( 'admin/views/add-blast-form.php' );
	}
}
