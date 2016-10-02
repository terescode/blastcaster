<?php

require_once 'includes/constants.php';
require_once 'tests/stubs/admin-plugin-stub.php';

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class WpAdminPluginTest extends BcPhpUnitTestCase {

	/**
	 * Test init
	 */
	function test_init_should_call_actions() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup

		\WP_Mock::wpFunction( 'register_activation_hook', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'string' ), array( $plugin, 'activate' ) ),
		) );

		\WP_Mock::wpFunction( 'register_deactivation_hook', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'string' ), array( $plugin, 'deactivate' ) ),
		) );

		\WP_Mock::expectActionAdded(
			'plugins_loaded',
			array( $plugin, 'load' )
		);

		\WP_Mock::expectActionAdded(
			'admin_menu',
			array( $plugin, 'install_admin_menus' )
		);

		// @test
		$plugin->init();
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_add_1_load_and_footer_actions_given_1_hookname() {
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );
		$plugin->menu_hooknames[] = 'hookname_1';

		foreach ( $plugin->menu_hooknames as $hookname ) {
			// Setup expectations
			\WP_Mock::expectActionAdded(
				'load-' . $hookname,
				function () {}
			);
			\WP_Mock::expectActionAdded(
				'admin_footer-' . $hookname,
				array( $plugin, 'add_script_in_footer' )
			);
		}

		$plugin->install_admin_menus();
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_add_N_load_and_footer_actions_given_N_hookname() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup
		$plugin->menu_hooknames[] = 'hookname_1';
		$plugin->menu_hooknames[] = 'hookname_2';
		$plugin->menu_hooknames[] = 'hookname_3';

		foreach ( $plugin->menu_hooknames as $hookname ) {
			// Setup expectations
			\WP_Mock::expectActionAdded(
				'load-' . $hookname,
				function () {}
			);
			\WP_Mock::expectActionAdded(
				'admin_footer-' . $hookname,
				array( $plugin, 'add_script_in_footer' )
			);
		}

		// @test
		$plugin->install_admin_menus();
	}

	/**
	 * Test add_screen_meta_boxes
	 */

	function test_add_screen_meta_boxes_should_call_add_meta_boxes_and_enqueue_script_given_hookname() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup
		$hookname = 'hookname_1';

		\WP_Mock::expectAction( 'add_meta_boxes_' . $hookname, array( null ) );
		\WP_Mock::expectAction( 'add_meta_boxes', $hookname, array( null ) );
		\WP_Mock::wpFunction( 'wp_enqueue_script', array(
			'times' => 1,
			'args' => array( 'postbox' ),
		) );

		// @test
		$plugin->add_screen_meta_boxes( $hookname );
	}

	/**
	 * Test anonymous function for load-{$pagenow} hook
	 */
	function test_load_pagenow_hook_should_call_add_screen_meta_boxes_given_hookname() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup
		$hookname = 'hookname_1';

		\WP_Mock::expectAction( 'add_meta_boxes_' . $hookname, array( null ) );
		\WP_Mock::expectAction( 'add_meta_boxes', $hookname, array( null ) );
		\WP_Mock::wpFunction( 'wp_enqueue_script', array(
			'times' => 1,
			'args' => array( 'postbox' ),
		) );

		// @test
		$lambda = $plugin->create_load_pagenow_hook_proxy( $hookname );
		$lambda();
	}

	/**
	 * Test add_script_in_footer
	 */

	function test_add_script_in_footer_should_output_script() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup
		$this->expectOutputString( $plugin->get_script_for_footer() );

		// @test
		$plugin->add_script_in_footer();
	}

	/**
	 * Test render_admin_notice
	 */

	function test_render_admin_notice_should_output_correct_types() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup
		\WP_Mock::wpFunction( 'esc_html', array(
			'times' => 4,
			'args' => array( \WP_Mock\Functions::type( 'string' ) ),
			'return_arg' => 0,
		) );
		\WP_Mock::wpFunction( 'esc_attr', array(
			'times' => 4,
			'args' => array( \WP_Mock\Functions::type( 'string' ) ),
			'return_arg' => 0,
		) );

		// @tests
		$this->expectOutputRegex( '/class="error/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$plugin->render_admin_notice( 'That is mangatsika cool' );

		$this->expectOutputRegex( '/class="updated/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$plugin->render_admin_notice( 'That is mangatsika cool', BcAdminPlugin::NOTICE_TYPE_UPDATED );

		$this->expectOutputRegex( '/class="update-nag/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$plugin->render_admin_notice( 'That is mangatsika cool', BcAdminPlugin::NOTICE_TYPE_NAG );

		$this->expectOutputRegex( '/class="error notice is-dismissable/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$plugin->render_admin_notice( 'That is mangatsika cool', BcAdminPlugin::NOTICE_TYPE_ERROR, true );
	}

	/**
	 * Test anonymous function for admin_notices hook
	 */

	function test_admin_notices_hook_should_call_render_admin_notice() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup
		\WP_Mock::wpFunction( 'esc_html', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'string' ) ),
			'return_arg' => 0,
		) );
		\WP_Mock::wpFunction( 'esc_attr', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'string' ) ),
			'return_arg' => 0,
		) );
		$this->expectOutputRegex( '/class="error/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );

		// @tests
		$lambda = $plugin->create_admin_notices_hook_proxy( 'That is mangatsika cool' );
		$lambda();
	}

	/**
	 * Test add_admin_notice
	 */

	function test_add_admin_notice_should_call_add_action() {
		// @sut
		$plugin = new BcAdminPluginStub( 'admin-plugin-stub' );

		// @setup
		\WP_Mock::expectActionAdded(
			'admin_notices',
			function () {}
		);

		// @test
		$plugin->add_admin_notice( 'That is mangatsika cool', BcAdminPlugin::NOTICE_TYPE_ERROR, true );
	}
}
