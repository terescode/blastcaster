<?php

namespace Terescode\WordPress;

// Include constants
require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/interface-view.php';
require_once 'includes/interface-strings.php';
// @SUT
require_once 'includes/class-plugin-helper.php';

/**
 * Class TcPluginHelperTest
 *
 * @package Blastcaster
 */

class TcPluginHelperTest extends \BcPhpUnitTestCase {

	/**
	 * Test init_plugin
	 */
	function test_init_plugin_should_register_hooks_and_call_actions() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );
		$m_plugin = $this->mock( 'Terescode\WordPress\TcPlugin' );

		$m_plugin->method( 'get_plugin_id' )
			->willReturn( 'basic-plugin' );
		$m_wph->expects( $this->once() )
			->method( 'register_activation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/basic-plugin.php' ),
				$this->equalTo( array( $m_plugin, 'activate' ) )
			);

		$m_wph->expects( $this->once() )
			->method( 'register_deactivation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/basic-plugin.php' ),
				$this->equalTo( array( $m_plugin, 'deactivate' ) )
			);

		$m_wph->expects( $this->once() )
			->method( 'add_action' )
			->with(
				$this->equalTo( 'plugins_loaded' ),
				$this->equalTo( array( $m_plugin, 'load' ) )
			);

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$helper->init_plugin( $m_plugin );
	}

	/**
	 * Test init_admin_plugin
	 */
	function test_init_admin_plugin_should_register_hooks_and_call_actions() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );
		$m_plugin = $this->mock( 'Terescode\WordPress\TcAdminPlugin' );

		$m_plugin->method( 'get_plugin_id' )
			->willReturn( 'admin-plugin' );
		$m_wph->expects( $this->once() )
			->method( 'register_activation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/admin-plugin.php' ),
				$this->equalTo( array( $m_plugin, 'activate' ) )
			);

		$m_wph->expects( $this->once() )
			->method( 'register_deactivation_hook' )
			->with(
				$this->equalTo( BC_PLUGIN_DIR . '/admin-plugin.php' ),
				$this->equalTo( array( $m_plugin, 'deactivate' ) )
			);

		$m_wph->expects( $this->exactly( 2 ) )
			->method( 'add_action' )
			->withConsecutive(
				[
					$this->equalTo( 'plugins_loaded' ),
					$this->equalTo( array( $m_plugin, 'load' ) ),
				],
				[
					$this->equalTo( 'admin_menu' ),
					$this->equalTo( array( $m_plugin, 'install_admin_menus' ) ),
				]
			);

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$helper->init_admin_plugin( $m_plugin );
	}

	/**
	 * Test load_textdomain
	 */
	function test_load_textdomain_should_call_load_plugin_textdomain() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );
		$m_plugin = $this->mock( 'Terescode\WordPress\TcPlugin' );

		$m_plugin->method( 'get_plugin_id' )
			->willReturn( 'basic-plugin' );
		$m_wph->expects( $this->once() )
			->method( 'load_plugin_textdomain' )
			->with(
				$this->equalTo( 'basic-plugin' ),
				$this->equalTo( false ),
				$this->equalTo( BC_PLUGIN_DIR . '/languages/' )
			);

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$helper->load_textdomain( $m_plugin );
	}

	/**
	 * Test plugin_file_name
	 */
	function test_plugin_file_name_should_return_expected_value_given_plugin_id() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );
		$m_plugin = $this->mock( 'Terescode\WordPress\TcPlugin' );

		$m_plugin->method( 'get_plugin_id' )
			->willReturn( 'basic-plugin' );

		// exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$actual = $helper->plugin_file_name( $m_plugin );

		// verify
		$this->assertEquals( BC_PLUGIN_DIR . '/basic-plugin.php', $actual );
	}

	/**
	 * Test admin_notices
	 */
	function test_admin_notices_should_output_correct_type_given_parameters() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

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
		$helper = new TcPluginHelper( $m_wph, $m_strings );
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
	 * Test add_admin_notice
	 */
	function test_add_admin_notice_should_add_admin_notices_action() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

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
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$helper->add_admin_notice( 'That is mangatsika cool', TcPluginHelper::NOTICE_TYPE_ERROR, true );
	}

	function test_get_wp_helper_should_return_helper_given_in_constructor() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$wph = $helper->get_wp_helper();

		// @verify
		$this->assertEquals( $m_wph, $wph );
	}

	/**
	 * Test render
	 */
	function test_render_should_output_hello_world_given_no_capability_check() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );
		$m_view = $this->mock( 'Terescode\WordPress\TcView' );

		$this->expectOutputRegex( '/Hello, world[!].+VIEW OK.+WPHELPER OK/' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph , $m_strings );
		$helper->render( $m_view, 'tests/fixtures/view' );
	}

	function test_render_should_output_hello_world_given_current_user_can_returns_true() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );
		$m_view = $this->mock( 'Terescode\WordPress\TcView' );

		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$this->expectOutputRegex( '/Hello, world[!].+VIEW OK.+WPHELPER OK/' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$helper->render( $m_view, 'tests/fixtures/view', 'install_plugins' );
	}

	function test_render_should_output_nothing_given_current_user_can_returns_false() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );
		$m_view = $this->mock( 'Terescode\WordPress\TcView' );

		$m_wph->method( 'current_user_can' )
			->willReturn( false );
		$this->expectOutputString( '' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$helper->render( $m_view, 'tests/fixtures/view', 'install_plugins' );
	}

	function test_param_should_return_null_given_no_post_param() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$val = $helper->param( 'foo' );

		// @verify
		$this->assertNull( $val );
	}

	function test_param_should_return_value_given_post_has_param() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		$_POST['foo'] = 'bar';

		$m_wph->method( 'sanitize_text_field' )
			->will( $this->returnArgument( 0 ) );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$val = $helper->param( 'foo' );

		// @verify
		$this->assertEquals( 'bar', $val );
	}

	function test_param_should_return_value_given_get_has_param() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		unset( $_POST['foo'] );
		$_GET['foo'] = 'bar';
		$m_wph->method( 'sanitize_text_field' )
			->will( $this->returnArgument( 0 ) );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$val = $helper->param( 'foo' );

		// @verify
		$this->assertEquals( 'bar', $val );
	}

	function test_param_should_return_array_given_param_is_array() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		$_POST['foo[]'] = array( 'bar', 'baz', 'bam' );
		$m_wph->method( 'sanitize_text_field' )
			->will( $this->returnArgument( 0 ) );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$val = $helper->param( 'foo[]' );

		// @verify
		$this->assertInternalType( 'array', $val );
		$this->assertEquals( 3, count( $val ) );
		$this->assertEquals( 'bar', $val[0] );
		$this->assertEquals( 'baz', $val[1] );
		$this->assertEquals( 'bam', $val[2] );
	}

	function test_param_should_call_sanitize_text_field_given_no_type() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		$_POST['foo'] = 'bar';

		$m_wph->expects( $this->once() )
			->method( 'sanitize_text_field' )
			->with( $this->equalTo( 'bar' ) )
			->willReturn( 'bar' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$val = $helper->param( 'foo' );

		// @verify
		$this->assertEquals( 'bar', $val );
	}

	function test_param_should_call_sanitize_text_field_given_text_type() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		$_POST['foo'] = 'bar';

		$m_wph->expects( $this->once() )
			->method( 'sanitize_text_field' )
			->with( $this->equalTo( 'bar' ) )
			->willReturn( 'bar' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$val = $helper->param( 'foo', 'text' );

		// @verify
		$this->assertEquals( 'bar', $val );
	}

	function test_string_should_call_string_with_required_args_and_return_value() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		$m_strings->expects( $this->once() )
			->method( 'get_string' )
			->with( $this->equalTo( 'bc.a.code' ), $this->equalTo( array() ) )
			->willReturn( 'a string' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$str = $helper->string( 'bc.a.code' );
		$this->assertEquals( 'a string', $str );
	}

	function test_string_should_call_string_with_optional_args_and_return_value() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_strings = $this->mock( 'Terescode\WordPress\TcStrings' );

		$m_strings->expects( $this->once() )
			->method( 'get_string' )
			->with( $this->equalTo( 'bc.a.code' ), $this->equalTo( array( 'foo' ) ) )
			->willReturn( 'a string' );

		// @exercise
		$helper = new TcPluginHelper( $m_wph, $m_strings );
		$str = $helper->string( 'bc.a.code', array( 'foo' ) );
		$this->assertEquals( 'a string', $str );
	}
}
