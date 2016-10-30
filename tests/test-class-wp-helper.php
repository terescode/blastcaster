<?php

// @SUT
require_once 'includes/class-wp-helper.php';

/**
 * Class TcPluginHelperTest
 *
 * @package Blastcaster
 */

class TcWpHelperTest extends BcPhpUnitTestCase {

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
		$stub = new stdClass;
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
		$stub = new stdClass;
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
		$stub = new stdClass;
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
		$stub = new stdClass;
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
	 * Test __
	 */
	function test____should_call____and_return_text_given____does_with_required_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( '__', array(
			'times' => 1,
			'args' => array(
				'translate this',
				'default',
			),
			'return' => 'siht etalsnart',
		) );

		// @exercise
		$esc = $wph->__( 'translate this' );
		$this->assertEquals( 'siht etalsnart', $esc );
	}

	/**
	 * Test __
	 */
	function test____should_call____and_return_text_given____does_with_optional_args() {
		// @setup
		$wph = new TcWpHelper();

		\WP_Mock::wpFunction( '__', array(
			'times' => 1,
			'args' => array(
				'translate this',
				'basic-plugin',
			),
			'return' => 'siht etalsnart',
		) );

		// @exercise
		$esc = $wph->__( 'translate this', 'basic-plugin' );
		$this->assertEquals( 'siht etalsnart', $esc );
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
	 * Test addadd_meta_box_posts_page
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
}
