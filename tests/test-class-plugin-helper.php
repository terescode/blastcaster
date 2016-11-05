<?php

// Include constants
require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/interface-controller.php';
// @SUT
require_once 'includes/class-plugin-helper.php';

/**
 * Class TcPluginHelperTest
 *
 * @package Blastcaster
 */

class TcPluginHelperTest extends BcPhpUnitTestCase {

	/**
	 * Test init_plugin
	 */
	function test_init_plugin_should_register_hooks_and_call_actions() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$plugin = $this->mock( 'TcPlugin' );

		$plugin->method( 'get_plugin_id' )
			->willReturn( 'basic-plugin' );
		$m_wph->expects( $this->once() )
			->method( 'register_activation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/basic-plugin.php' ),
				$this->equalTo( array( $plugin, 'activate' ) )
			);

		$m_wph->expects( $this->once() )
			->method( 'register_deactivation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/basic-plugin.php' ),
				$this->equalTo( array( $plugin, 'deactivate' ) )
			);

		$m_wph->expects( $this->once() )
			->method( 'add_action' )
			->with(
				$this->equalTo( 'plugins_loaded' ),
				$this->equalTo( array( $plugin, 'load' ) )
			);

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->init_plugin( $plugin );
	}

	/**
	 * Test init_admin_plugin
	 */
	function test_init_admin_plugin_should_register_hooks_and_call_actions() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$plugin = $this->mock( 'TcAdminPlugin' );

		$plugin->method( 'get_plugin_id' )
			->willReturn( 'admin-plugin' );
		$m_wph->expects( $this->once() )
			->method( 'register_activation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/admin-plugin.php' ),
				$this->equalTo( array( $plugin, 'activate' ) )
			);

		$m_wph->expects( $this->once() )
			->method( 'register_deactivation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/admin-plugin.php' ),
				$this->equalTo( array( $plugin, 'deactivate' ) )
			);

		$m_wph->expects( $this->exactly( 2 ) )
			->method( 'add_action' )
			->withConsecutive(
				[
					$this->equalTo( 'plugins_loaded' ),
					$this->equalTo( array( $plugin, 'load' ) ),
				],
				[
					$this->equalTo( 'admin_menu' ),
					$this->equalTo( array( $plugin, 'install_admin_menus' ) ),
				]
			);

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->init_admin_plugin( $plugin );
	}

	/**
	 * Test load_textdomain
	 */
	function test_load_textdomain_should_call_load_plugin_textdomain() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$plugin = $this->mock( 'TcPlugin' );

		$plugin->method( 'get_plugin_id' )
			->willReturn( 'basic-plugin' );
		$m_wph->expects( $this->once() )
			->method( 'load_plugin_textdomain' )
			->with(
				$this->equalTo( 'basic-plugin' ),
				$this->equalTo( false ),
				$this->equalTo( BC_PLUGIN_DIR . '/languages/' )
			);

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->load_textdomain( $plugin );
	}

	/**
	 * Test plugin_file_name
	 */
	function test_plugin_file_name_should_return_expected_value_given_plugin_id() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$plugin = $this->mock( 'TcPlugin' );
		$plugin->method( 'get_plugin_id' )
			->willReturn( 'basic-plugin' );

		// exercise
		$helper = new TcPluginHelper( $m_wph );
		$actual = $helper->plugin_file_name( $plugin );

		// verify
		$this->assertEquals( BC_PLUGIN_DIR . '/basic-plugin.php', $actual );
	}


	/**
	 * Test load_{$pagenow} hook
	 */
	function test_load_pagenow_should_call_add_meta_boxes_and_enqueue_script_given_hookname() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$hookname = 'hookname_1';

		$m_wph->expects( $this->exactly( 2 ) )
			->method( 'do_action' )
			->withConsecutive(
				[
					$this->equalTo( 'add_meta_boxes_hookname_1' ),
					$this->equalTo( null ),
				],
				[
					$this->equalTo( 'add_meta_boxes' ),
					$this->equalTo( $hookname ),
					$this->equalTo( null ),
				]
			);

		$m_wph->expects( $this->once() )
			->method( 'wp_enqueue_script' )
			->with(
				$this->equalTo( 'postbox' )
			);

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->load_pagenow( $hookname );
	}

	/**
	 * Test admin_notices
	 */
	function test_admin_notices_should_output_correct_type_given_parameters() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );

		$m_wph->expects( $this->exactly( 4 ) )
			->method( 'esc_html' )
			->with( $this->equalTo( 'That is mangatsika cool' ) )
			->will( $this->returnArgument( 0 ) );

		$m_wph->expects( $this->exactly( 4 ) )
			->method( 'esc_attr' )
			->withConsecutive(
				[ TcPluginHelper::NOTICE_TYPE_ERROR ],
				[ TcPluginHelper::NOTICE_TYPE_UPDATED ],
				[ TcPluginHelper::NOTICE_TYPE_NAG ],
				[ TcPluginHelper::NOTICE_TYPE_ERROR ]
			)
			->will( $this->returnArgument( 0 ) );

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$this->expectOutputRegex( '/class="error/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$helper->admin_notices( 'That is mangatsika cool', TcPluginHelper::NOTICE_TYPE_ERROR, false );

		$this->expectOutputRegex( '/class="updated/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$helper->admin_notices( 'That is mangatsika cool', TcPluginHelper::NOTICE_TYPE_UPDATED, false );

		$this->expectOutputRegex( '/class="update-nag/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$helper->admin_notices( 'That is mangatsika cool', TcPluginHelper::NOTICE_TYPE_NAG, false );

		$this->expectOutputRegex( '/class="error notice is-dismissable/' );
		$this->expectOutputRegex( '/That is mangatsika cool/' );
		$helper->admin_notices( 'That is mangatsika cool', TcPluginHelper::NOTICE_TYPE_ERROR, true );
	}

	/**
	 * Test add_postbox_script_in_footer
	 */
	function test_add_postbox_script_in_footer_should_output_script() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );

		$this->expect_html(
			function ( $result ) {
				// @verify
				$xpath = new DOMXPath( $result );
				$elements = $xpath->query( '//script' );
				$this->assertEquals( 1, $elements->length, 'Should only be one script in output!' );
				$node = $elements->item( 0 );
				$this->assertEquals( XML_ELEMENT_NODE, $node->nodeType );
				$this->assertRegExp( '/postboxes\.add_postbox_toggles/', $node->textContent );
			}
		);

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->add_postbox_script_in_footer();
	}

	/**
	 * Test add_admin_notice
	 */
	function test_add_admin_notice_should_add_admin_notices_action() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );

		$m_wph->expects( $this->once() )
			->method( 'add_action' )
			->with(
				$this->equalTo( 'admin_notices' ),
				$this->callback( function( $subject ) {
				  	if ( is_callable( $subject ) &&
						$subject[0] instanceof TcCallbackWrapper ) {
						$wrapper = $subject[0];
						$callable = $wrapper->get_callable();
						$args = $wrapper->get_args();
						return is_array( $callable ) &&
							$callable[0] instanceof TcPluginHelper &&
							'admin_notices' === $callable[1] &&
							is_array( $args ) &&
							3 === count( $args ) &&
							'That is mangatsika cool' === $args[0] &&
							TcPluginHelper::NOTICE_TYPE_ERROR === $args[1] &&
							true === $args[2];
					} else {
						return false;
					}
				})
			);

		// @test
		$helper = new TcPluginHelper( $m_wph );
		$helper->add_admin_notice( 'That is mangatsika cool', TcPluginHelper::NOTICE_TYPE_ERROR, true );
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_add_zero_hooks_given_zero_controllers() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$controllers = array();
		$hooknames = null;

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$hooknames = $helper->install_admin_menus( $controllers );

		// @verify
		$this->assertNotNull( $hooknames );
		$this->assertInternalType( 'array', $hooknames );
		$this->assertEquals( 0, count( $hooknames ) );
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_add_zero_hooks_given_controller_init_returns_falsy() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$controllers = array();
		$hooknames = null;

		$controller = $this->mock( 'TcController' );
		$controller->expects( $this->once() )
			->method( 'init' )
			->will( $this->returnValue( false ) );
		$controllers[] = $controller;

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$hooknames = $helper->install_admin_menus( $controllers );

		// @verify
		$this->assertNotNull( $hooknames );
		$this->assertInternalType( 'array', $hooknames );
		$this->assertEquals( 0, count( $hooknames ) );
	}

	/**
	 * Helper function to test admin_menus
	 */
	function install_admin_menus_should_add_N_hooks_given_N_controllers( $count ) {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$hooknames = null;
		$controllers = array();
		$helper = null;
		$add_action_expects = array();
		for ( $idx = 0; $idx < $count; $idx += 1 ) {
			$hookname = 'test_hookname_' . $idx;
			$controller = $this->mock( 'TcController' );
			$controller->expects( $this->once() )
				->method( 'init' )
				->will( $this->returnValue( $hookname ) );
			$controllers[] = $controller;
			$add_action_expects[] = array(
				$this->equalTo( 'load-' . $hookname ),
				$this->callback( function ( $subject ) {
					return is_callable( $subject ) &&
						$subject[0] instanceof TcCallbackWrapper;
				}),
			);
			$add_action_expects[] = array(
				$this->equalTo( 'admin_footer-' . $hookname ),
				$this->equalTo( array( &$helper, 'add_postbox_script_in_footer' ) ),
			);
		}

		$builder = $m_wph->expects( $this->exactly( 2 * $count ) )
			->method( 'add_action' );

		call_user_func_array( array( $builder, 'withConsecutive' ), $add_action_expects );

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$hooknames = $helper->install_admin_menus( $controllers );

		// @verify
		$this->assertNotNull( $hooknames );
		$this->assertInternalType( 'array', $hooknames );
		$this->assertEquals( $count, count( $hooknames ) );
		for ( $idx = 0; $idx < $count; $idx += 1 ) {
			$this->assertEquals( 'test_hookname_' . $idx, $hooknames[ $idx ] );
		}
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_add_1_hook_given_1_controller() {
		$this->invoke_with_random_count(
			1,
			1,
			array( $this, 'install_admin_menus_should_add_N_hooks_given_N_controllers' )
		);
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_add_N_hooks_given_N_controllers() {
		$this->invoke_with_random_count(
			5,
			10,
			array( $this, 'install_admin_menus_should_add_N_hooks_given_N_controllers' )
		);
	}

	function test_get_wp_helper_should_return_helper_given_in_constructor() {
		$m_wph = $this->mock( 'TcWpHelper' );
		// @sut
		$helper = new TcPluginHelper( $m_wph );

		// @exercise
		$wph = $helper->get_wp_helper();

		// @verify
		$this->assertEquals( $m_wph, $wph );
	}

	/**
	 * Test render
	 */
	function test_render_should_output_hello_world_given_no_capability_check() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$controller = $this->mock( 'TcController' );

		$this->expectOutputRegex( '/Hello, world[!].+CONTROLLER OK.+WPHELPER OK/' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->render( $controller, 'tests/fixtures/view' );
	}

	function test_render_should_output_hello_world_given_current_user_can_returns_true() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$controller = $this->mock( 'TcController' );

		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$this->expectOutputRegex( '/Hello, world[!].+CONTROLLER OK.+WPHELPER OK/' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->render( $controller, 'tests/fixtures/view', 'install_plugins' );
	}

	function test_render_should_output_nothing_given_current_user_can_returns_false() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$controller = $this->mock( 'TcController' );

		$m_wph->method( 'current_user_can' )
			->willReturn( false );
		$this->expectOutputString( '' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph );
		$helper->render( $controller, 'tests/fixtures/view', 'install_plugins' );
	}
}
