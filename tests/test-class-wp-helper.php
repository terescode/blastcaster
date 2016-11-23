<?php

namespace Terescode\WordPress;

// @SUT
require_once 'includes/class-wp-helper.php';

/**
 * Class TcPluginHelperTest
 *
 * @package Blastcaster
 */

class TcWpHelperTest extends \BcPhpUnitTestCase {

	/**
	 * Test setup
	 */
	public function setUp() {
		\WP_Mock::setUp();
	}

	/**
	 * Teardown
	 */

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * Test add_action
	 */
	function test_add_action_should_call_add_action_given_required_args() {
		// @setup
		$stub = new \stdClass;
		$wph = new TcWpHelper();

		\WP_Mock::expectActionAdded(
			'add_salt',
			array( $stub, 'added_salt' )
		);

		// @exercise
		$wph->add_action( 'add_salt', array( $stub, 'added_salt' ) );
	}

	/**
	 * Test add_action
	 */
	function test_add_action_should_call_add_action_given_optional_args() {
		// @setup
		$stub = new \stdClass;
		$wph = new TcWpHelper();

		\WP_Mock::expectActionAdded(
			'add_pepper',
			array( $stub, 'added_pepper' ),
			40,
			5
		);

		// @exercise
		$wph->add_action( 'add_pepper', array( $stub, 'added_pepper' ), 40, 5 );
	}

	/**
	 * Test register_activation_hook
	 */
	function test_register_activation_hook_should_call_register_activation_hook_given_args() {
		// @setup
		$stub = new \stdClass;
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'register_activation_hook', array(
			'times' => 1,
			'args' => array(
				'/path/to/basic-plugin.php',
				array( $stub, 'activate' ),
			),
		) );

		// @exercise
		$wph->register_activation_hook( '/path/to/basic-plugin.php', array( $stub, 'activate' ) );
	}

	/**
	 * Test register_deactivation_hook
	 */
	function test_register_deactivation_hook_should_call_register_deactivation_hook_given_args() {
		// @setup
		$stub = new \stdClass;
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'register_deactivation_hook', array(
			'times' => 1,
			'args' => array(
				'/path/to/basic-plugin.php',
				array( $stub, 'deactivate' ),
			),
		) );

		// @exercise
		$wph->register_deactivation_hook( '/path/to/basic-plugin.php', array( $stub, 'deactivate' ) );
	}

	/**
	 * Test load_plugin_textdomain
	 */
	function test_load_plugin_textdomain_should_call_load_plugin_textdomain_given_required_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'load_plugin_textdomain', array(
			'times' => 1,
			'args' => array( 'basic-plugin', false, false ),
		) );

		// @exercise
		$wph->load_plugin_textdomain( 'basic-plugin' );
	}

	/**
	 * Test load_plugin_textdomain
	 */
	function test_load_plugin_textdomain_should_call_load_plugin_textdomain_given_optional_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'load_plugin_textdomain', array(
			'times' => 1,
			'args' => array( 'basic-plugin', true, '/path/to/languages/' ),
		) );

		// @exercise
		$wph->load_plugin_textdomain( 'basic-plugin', true, '/path/to/languages/' );
	}

	/**
	 * Test load_plugin_textdomain
	 */
	function test_load_plugin_textdomain_should_return_true_given_load_plugin_textdomain_does() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'load_plugin_textdomain', array(
			'times' => 1,
			'args' => array( 'basic-plugin', false, false ),
			'return' => true,
		) );

		// @exercise
		$success = $wph->load_plugin_textdomain( 'basic-plugin' );
		$this->assertEquals( true, $success );
	}

	/**
	 * Test load_plugin_textdomain
	 */
	function test_load_plugin_textdomain_should_return_false_given_load_plugin_textdomain_does() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'load_plugin_textdomain', array(
			'times' => 1,
			'args' => array( 'basic-plugin', false, false ),
			'return' => false,
		) );

		// @exercise
		$success = $wph->load_plugin_textdomain( 'basic-plugin' );
		$this->assertEquals( false, $success );
	}


	/**
	 * Test wp_enqueue_script
	 */
	function test_wp_enqueue_script_should_call_wp_enqueue_script_given_required_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'wp_enqueue_script', array(
			'times' => 1,
			'args' => array( 'postbox', false, \WP_Mock\Functions::type( 'array' ), false, false ),
		) );

		// @exercise
		$wph->wp_enqueue_script( 'postbox' );
	}

	/**
	 * Test wp_enqueue_script
	 */
	function test_wp_enqueue_script_should_call_wp_enqueue_script_given_optional_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'wp_enqueue_script', array(
			'times' => 1,
			'args' => array( 'postbox', true, array( 'foo', 'bar' ), false, true ),
		) );

		// @exercise
		$wph->wp_enqueue_script( 'postbox', true, array( 'foo', 'bar' ), false, true );
	}

	/**
	 * Test esc_attr
	 */
	function test_esc_attr_should_call_esc_attr_and_return_text_given_esc_attr_does() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'esc_attr', array(
			'times' => 1,
			'args' => array( '<paprika>' ),
			'return' => '&lt;paprika&gt;',
		) );

		// @exercise
		$esc = $wph->esc_attr( '<paprika>' );
		$this->assertEquals( '&lt;paprika&gt;', $esc );
	}

	/**
	 * Test esc_html
	 */
	function test_esc_html_should_call_esc_html_and_return_text_given_esc_html_does() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'esc_html', array(
			'times' => 1,
			'args' => array( '<lemon-pepper>' ),
			'return' => '&lt;lemon-pepper&gt;',
		) );

		// @exercise
		$esc = $wph->esc_html( '<lemon-pepper>' );
		$this->assertEquals( '&lt;lemon-pepper&gt;', $esc );
	}

	/**
	 * Test esc_url
	 */
	function test_esc_url_should_call_esc_url_and_return_result_given_required_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'esc_url', array(
			'times' => 1,
			'args' => array(
				'http://www.google.com?test=I am a parameter',
				null,
				'display',
			),
			'return' => 'http://www.google.com?test=I%20am%20a%20parameter',
		) );

		// @exercise
		$esc = $wph->esc_url( 'http://www.google.com?test=I am a parameter' );
		$this->assertEquals( 'http://www.google.com?test=I%20am%20a%20parameter', $esc );
	}

	/**
	 * Test esc_url
	 */
	function test_esc_url_should_call_esc_url_and_return_result_given_optional_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( 'esc_url', array(
			'times' => 1,
			'args' => array(
				'http://www.google.com?test=I am a parameter',
				[ 'http', 'https' ],
				'display',
			),
			'return' => 'http://www.google.com?test=I%20am%20a%20parameter',
		) );

		// @exercise
		$esc = $wph->esc_url( 'http://www.google.com?test=I am a parameter', [ 'http', 'https' ] );
		$this->assertEquals( 'http://www.google.com?test=I%20am%20a%20parameter', $esc );
	}

	/**
	 * Test do_action
	 */
	function test_do_action_should_call_do_action_with_required_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::expectAction( 'stir_the_pot' );

		// @exercise
		$wph->do_action( 'stir_the_pot' );
	}

	/**
	 * Test do_action
	 */
	function test_do_action_should_call_do_action_with_1_optional_arg() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::expectAction( 'stir_the_pot', 'with_a_spoon' );

		// @exercise
		$wph->do_action( 'stir_the_pot', 'with_a_spoon' );
	}

	/**
	 * Test do_action
	 */
	function test_do_action_should_call_do_action_with_2_optional_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::expectAction( 'stir_the_pot', 'with_a_spoon', false );

		// @exercise
		$wph->do_action( 'stir_the_pot', 'with_a_spoon', false );
	}

	/**
	 * Test do_action
	 */
	function test_do_action_should_call_do_action_with_5_optional_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::expectAction( 'stir_the_pot', 'with_a_spoon', false, array( 'foo', 'bar' ), 25 );

		// @exercise
		$wph->do_action( 'stir_the_pot', 'with_a_spoon', false, array( 'foo', 'bar' ), 25 );
	}

	/**
	 * Test add_posts_page
	 */
	function test_add_posts_page_should_call_add_posts_page_given_required_args() {
		// @setup
		\WP_Mock::wpFunction( 'add_posts_page', array(
			'times' => 1,
			'args' => array(
				'Add Post Page',
				'Add post',
				'edit_posts',
				'menu_beetle_slug',
				'',
			),
			'return' => 'hook_suffix',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->add_posts_page( 'Add Post Page', 'Add post', 'edit_posts', 'menu_beetle_slug' );
	}

	/**
	 * Test add_posts_page
	 */
	function test_add_posts_page_should_call_add_posts_page_given_optional_args() {
		// @setup
		\WP_Mock::wpFunction( 'add_posts_page', array(
			'times' => 1,
			'args' => array(
				'Add Post Page',
				'Add post',
				'edit_posts',
				'menu_beetle_slug',
				array( 'foo', 'fighters' ),
			),
			'return' => 'hook_suffix',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->add_posts_page( 'Add Post Page', 'Add post', 'edit_posts', 'menu_beetle_slug', array( 'foo', 'fighters' ) );
	}

	/**
	 * Test add_posts_page
	 */
	function test_add_posts_page_should_return_false_given_add_posts_page_does() {
		// @setup
		\WP_Mock::wpFunction( 'add_posts_page', array(
			'times' => 1,
			'args' => array(
				'Add Post Page',
				'Add post',
				'edit_posts',
				'menu_beetle_slug',
				array( 'foo', 'bar' ),
			),
			'return' => false,
		) );

		// @exercise
		$wph = new TcWpHelper();
		$hook_suffix = $wph->add_posts_page( 'Add Post Page', 'Add post', 'edit_posts', 'menu_beetle_slug', array( 'foo', 'bar' ) );

		// @verify
		$this->assertFalse( $hook_suffix );
	}

	/**
	 * Test add_meta_box_posts_page
	 */
	function test_add_meta_box_should_call_add_meta_box_given_required_args() {
		// @setup
		\WP_Mock::wpFunction( 'add_meta_box', array(
			'times' => 1,
			'args' => array(
				'box_id',
				'Meta Box Title',
				array( 'foo', 'call_me_back' ),
				null,
				'advanced',
				'default',
				null,
			),
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->add_meta_box( 'box_id', 'Meta Box Title', array( 'foo', 'call_me_back' ) );
	}

	/**
	 * Test add_meta_box
	 */
	function test_add_meta_box_should_call_add_meta_box_given_optional_args() {
		// @setup
		\WP_Mock::wpFunction( 'add_meta_box', array(
			'times' => 1,
			'args' => array(
				'box_id',
				'Meta Box Title',
				array( 'foo', 'call_me_back' ),
				'screen_id',
				'normal',
				'high',
				array( 10, 'foo' ),
			),
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->add_meta_box( 'box_id', 'Meta Box Title', array( 'foo', 'call_me_back' ), 'screen_id', 'normal', 'high', array( 10, 'foo' ) );
	}

	/**
	 * Test current_user_can
	 */
	function test_current_user_can_should_call_current_user_can_and_return_true_given_current_user_can_with_required_args_does() {
		// @setup
		\WP_Mock::wpFunction( 'current_user_can', array(
			'times' => 1,
			'args' => array(
				'install_plugins',
			),
			'return' => true,
		) );

		// @exercise
		$wph = new TcWpHelper();
		$youcan = $wph->current_user_can( 'install_plugins' );
		$this->assertTrue( $youcan );
	}

	/**
	 * Test current_user_can
	 */
	function test_current_user_can_should_call_current_user_can_and_return_false_given_current_user_can_with_required_args_does() {
		// @setup
		\WP_Mock::wpFunction( 'current_user_can', array(
			'times' => 1,
			'args' => array(
				'install_plugins',
			),
			'return' => false,
		) );

		// @exercise
		$wph = new TcWpHelper();
		$youcan = $wph->current_user_can( 'install_plugins' );
		$this->assertFalse( $youcan );
	}

	/**
	 * Test current_user_can
	 */
	function test_current_user_can_should_call_current_user_can_given_optional_args() {
		// @setup
		\WP_Mock::wpFunction( 'current_user_can', array(
			'times' => 1,
			'args' => array(
				'install_plugins',
				123456789,
			),
			'return' => false,
		) );

		// @exercise
		$wph = new TcWpHelper();
		$youcan = $wph->current_user_can( 'install_plugins', 123456789 );
		$this->assertFalse( $youcan );
	}

	/**
	 * Test admin_url
	 */
	function test_admin_url_should_call_admin_url_and_return_value_given_required_args_and_returns_value() {
		// @setup
		\WP_Mock::wpFunction( 'admin_url', array(
			'times' => 1,
			'args' => array(
				'',
				'admin',
			),
			'return' => 'http://i.do.not.exist/index.html',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$url = $wph->admin_url();
		$this->assertEquals( 'http://i.do.not.exist/index.html', $url );
	}

	/**
	 * Test admin_url
	 */
	function test_admin_url_should_call_admin_url_and_return_value_given_optional_args_and_returns_value() {
		// @setup
		\WP_Mock::wpFunction( 'admin_url', array(
			'times' => 1,
			'args' => array(
				'admin-post.php',
				'https',
			),
			'return' => 'https://i.do.not.exist/index.html',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$url = $wph->admin_url( 'admin-post.php', 'https' );
		$this->assertEquals( 'https://i.do.not.exist/index.html', $url );
	}

	/**
	 * Test wp_nonce_field
	 */
	function test_wp_nonce_field_should_call_wp_nonce_field_and_return_value_given_required_args_and_returns_value() {
		// @setup
		\WP_Mock::wpFunction( 'wp_nonce_field', array(
			'times' => 1,
			'args' => array(
				-1,
				'_wpnonce',
				true,
				true,
			),
			'return' => 'uid.-1._wpnonce',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$nonce = $wph->wp_nonce_field();
		$this->assertEquals( 'uid.-1._wpnonce', $nonce );
	}

	/**
	 * Test wp_nonce_field
	 */
	function test_wp_nonce_field_should_call_wp_nonce_field_and_return_value_given_optional_args_and_returns_value() {
		// @setup
		\WP_Mock::wpFunction( 'wp_nonce_field', array(
			'times' => 1,
			'args' => array(
				'custom_action',
				'custom_action_nonce',
				false,
				false,
			),
			'return' => 'uid.custom_action.custom_action_nonce',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$nonce = $wph->wp_nonce_field( 'custom_action', 'custom_action_nonce', false, false );
		$this->assertEquals( 'uid.custom_action.custom_action_nonce', $nonce );
	}

	/**
	 * Test do_meta_boxes
	 */
	function test_do_meta_boxes_should_call_do_meta_boxes_and_return_value_given_do_meta_boxes_does() {
		// @setup
		\WP_Mock::wpFunction( 'do_meta_boxes', array(
			'times' => 1,
			'args' => array(
				'a_screen_id',
				'normal',
				null,
			),
			'return' => '<div>meta boxes</div>',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$html = $wph->do_meta_boxes( 'a_screen_id', 'normal', null );
		$this->assertEquals( '<div>meta boxes</div>', $html );
	}

	/**
	 * Test submit_button
	 */
	function test_submit_button_should_call_submit_button_with_required_args() {
		// @setup
		\WP_Mock::wpFunction( 'submit_button', array(
			'times' => 1,
			'args' => array(
				null,
				'primary',
				'submit',
				true,
				null,
			),
			'return' => '<input type="submit" name="submit" />',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$html = $wph->submit_button();
		$this->assertEquals( '<input type="submit" name="submit" />', $html );
	}

	/**
	 * Test submit_button
	 */
	function test_submit_button_should_call_submit_button_with_optional_args() {
		// @setup
		\WP_Mock::wpFunction( 'submit_button', array(
			'times' => 1,
			'args' => array(
				'the-action',
				'secondary',
				'click me',
				false,
				array( 'disabled' => 'disabled' ),
			),
			'return' => '<input type="submit" name="click me" class="secondary" value="the-action" />',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$html = $wph->submit_button( 'the-action', 'secondary', 'click me', false, array( 'disabled' => 'disabled' ) );
		$this->assertEquals( '<input type="submit" name="click me" class="secondary" value="the-action" />', $html );
	}

	/**
	 * Test wp_insert_post
	 */
	function test_wp_insert_post_should_call_wp_insert_post_and_return_0_if_wp_insert_post_does() {
		// @setup
		\WP_Mock::wpFunction( 'wp_insert_post', array(
			'times' => 1,
			'args' => array(
				array( 'foo' => 'bar', 'bar' => 'baz' ),
				false,
			),
			'return' => 0,
		) );

		// @exercise
		$wph = new TcWpHelper();
		$ret = $wph->wp_insert_post( [ 'foo' => 'bar', 'bar' => 'baz' ] );
		$this->assertEquals( 0, $ret );
	}

	/**
	 * Test wp_insert_post
	 */
	function test_wp_insert_post_should_call_wp_insert_post_and_return_wp_error_if_wp_insert_post_does() {
		// @setup
		wp_include_once( 'class-wp-error.php' );
		$m_error = $this->mock( 'WP_Error' );
		\WP_Mock::wpFunction( 'wp_insert_post', array(
			'times' => 1,
			'args' => array(
				array( 'foo' => 'bar', 'bar' => 'baz' ),
				true,
			),
			'return' => $m_error,
		) );

		// @exercise
		$wph = new TcWpHelper();
		$ret = $wph->wp_insert_post( [ 'foo' => 'bar', 'bar' => 'baz' ], true );
		$this->assertInstanceOf( 'WP_Error', $ret );
		$this->assertEquals( $m_error, $ret );
	}

	/**
	 * Test wp_insert_post
	 */
	function test_wp_insert_post_should_call_wp_insert_post_and_return_post_id_if_wp_insert_post_does() {
		// @setup
		\WP_Mock::wpFunction( 'wp_insert_post', array(
			'times' => 1,
			'args' => array(
				array( 'foo' => 'bar', 'bar' => 'baz' ),
				true,
			),
			'return' => 12345,
		) );

		// @exercise
		$wph = new TcWpHelper();
		$ret = $wph->wp_insert_post( [ 'foo' => 'bar', 'bar' => 'baz' ], true );
		$this->assertEquals( 12345, $ret );
	}

	/**
	 * Test sanitize_text_field
	 */
	function test_sanitize_text_field_should_call_sanitize_text_field_and_return_the_result() {
		// @setup
		\WP_Mock::wpFunction( 'sanitize_text_field', array(
			'times' => 1,
			'args' => array(
				'<script>This is some text probably from a text field</script>'
			),
			'return' => 'This is some text probably from a text field',
		) );

		// @exercise
		$wph = new TcWpHelper();
		$ret = $wph->sanitize_text_field( '<script>This is some text probably from a text field</script>' );
		$this->assertEquals( 'This is some text probably from a text field', $ret );
	}

	/**
	 * Test status_header
	 */
	function test_status_header_should_call_status_header_with_required_args() {
		// @setup
		\WP_Mock::wpFunction( 'status_header', array(
			'times' => 1,
			'args' => array(
				201,
				'',
			),
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->status_header( 201 );
	}

	/**
	 * Test status_header
	 */
	function test_status_header_should_call_status_header_with_optional_args() {
		// @setup
		\WP_Mock::wpFunction( 'status_header', array(
			'times' => 1,
			'args' => array(
				201,
				'Created',
			),
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->status_header( 201, 'Created' );
	}

	/**
	 * Test status_header
	 */
	function test_wp_safe_redirect_should_call_wp_safe_redirect_with_required_args() {
		// @setup
		\WP_Mock::wpFunction( 'wp_safe_redirect', array(
			'times' => 1,
			'args' => array(
				'http://local.wordpress.dev/edit.php?page=page',
				302,
			),
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->wp_safe_redirect( 'http://local.wordpress.dev/edit.php?page=page' );
	}

	/**
	 * Test status_header
	 */
	function test_wp_safe_redirect_should_call_wp_safe_redirect_with_optional_args() {
		// @setup
		\WP_Mock::wpFunction( 'wp_safe_redirect', array(
			'times' => 1,
			'args' => array(
				'http://local.wordpress.dev/edit.php?page=page',
				301,
			),
		) );

		// @exercise
		$wph = new TcWpHelper();
		$wph->wp_safe_redirect( 'http://local.wordpress.dev/edit.php?page=page', 301 );
	}
}
