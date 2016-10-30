<?php

require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/interface-admin-plugin.php';
require_once 'admin/controllers/class-add-blast-controller.php';

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class BcAddBlastControllerTest extends BcPhpUnitTestCase {

	/**
	 * Test init @should call add_posts_page
	 */
	function test_init_should_return_false_given_add_posts_page_does() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( '__' )
			->willReturn( 'test' );
		$m_wph->method( 'add_posts_page' )
			->willReturn( false );

		// @exercise
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$hook_suffix = $controller->init();
		$this->assertFalse( $hook_suffix );
		$this->assertFalse( $controller->get_screen_id() );
	}

	/**
	 * Test init @should call add_posts_page
	 */
	function test_init_should_return_hook_suffix_and_add_meta_boxes_hook_given_add_posts_page_does() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = null;

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( '__' )
			->willReturn( 'test' );
		$m_wph->method( 'add_posts_page' )
			->willReturn( 'test_hook_suffix' );
		$m_wph->expects( $this->once() )
			->method( 'add_action' )
			->with(
				$this->equalTo( 'add_meta_boxes_test_hook_suffix' ),
				array( &$controller, 'add_blast_form_meta_boxes' )
			);

		// @exercise
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$hook_suffix = $controller->init();

		// @verify
		$this->assertEquals( 'test_hook_suffix', $hook_suffix );
		$this->assertEquals( 'test_hook_suffix', $controller->get_screen_id() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_not_set_page_data_given_no_post_data() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'render' )
			->willReturn( null );

		// @exercise
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$controller->do_add_blast();

		// @verify
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_not_set_page_data_given_empty_post_data() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );

		$_POST['pageData'] = '';
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'render' )
			->willReturn( null );

		// @exercise
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$controller->do_add_blast();

		// @verify
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_not_set_page_data_given_wsonly_post_data() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );

		$_POST['pageData'] = "\t\t\t\r\n\n\t     \t\t\r\n";
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'render' )
			->willReturn( null );

		// @exercise
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$controller->do_add_blast();

		// @verify
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_set_page_data_given_valid_post_data() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );

		$json_file = file_get_contents( 'tests/fixtures/sample.json', true );
		$_POST['pageData'] = $json_file;
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'render' )
			->willReturn( null );

		// @test
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$controller->do_add_blast();

		// @verify
		$page_data = $controller->get_page_data();
		$this->assertNotNull( $page_data );
		$this->assertNotNull( $page_data->urls );
		$this->assertNotNull( $page_data->images );
		$this->assertNotNull( $page_data->allImages );
		$this->assertNotNull( $page_data->titles );
		$this->assertNotNull( $page_data->descriptions );
		$this->assertNotNull( $page_data->tags );
		$this->assertEquals( 2, count( $page_data->titles ) );
	}

	/**
	 * Test do_add_blast
	 */
	function test_do_add_blast_should_add_admin_notice_given_invalid_post_data() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );

		$json_file = file_get_contents( 'tests/fixtures/sample-invalid.json', true );
		$_POST['pageData'] = $json_file;

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'render' )
			->willReturn( null );
		$m_wph->method( '__' )
			->willReturn( 'test' );
		$m_helper->expects( $this->once() )
			->method( 'add_admin_notice' )
			->with(
				$this->isType( 'string' )
			);

		// @exercise
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$controller->do_add_blast();

		// @verify
		$page_data = $controller->get_page_data();
		$this->assertNull( $controller->get_page_data() );
	}

	/**
	 * Test do_add_blast
	 */
	/*
	function test_do_add_blast_should_add_admin_notice_given_invalid_post_data_no_json_last_error_msg() {
		// TODO: currently no different than previous test.  Need to test function_exists == false,
		// maybe with namespace trick?

		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = new BcAddBlastController( $m_plugin, $m_helper );

		// @setup
		$json_file = file_get_contents( 'tests/fixtures/sample-invalid.json', true );
		$_POST['pageData'] = $json_file;

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( '__' )
			->willReturn( 'test' );
		$m_helper->expects( $this->once() )
			->method( 'add_admin_notice' )
			->with(
				$this->isType( 'string' )
			);

		// @exercise
		$controller->do_add_blast();

		// @verify
		$page_data = $controller->get_page_data();
		$this->assertNull( $controller->get_page_data() );

	}
	*/

	/**
	 * Test add blast boxes
	 */
	function test_add_blast_form_meta_boxes_should_call_add_meta_box() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = null;

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( '__' )
			->willReturn( 'test' );
		$m_wph->expects( $this->exactly( 5 ) )
			->method( 'add_meta_box' )
			->withConsecutive(
				[ $this->equalTo( 'bc-add-title-meta-box' ), $this->equalTo( 'test' ), $this->equalTo( array( &$controller, 'render_add_title_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ) ],
				[ $this->equalTo( 'bc-add-category-meta-box' ), $this->equalTo( 'test' ), $this->equalTo( array( &$controller, 'render_add_category_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ) ],
				[ $this->equalTo( 'bc-add-image-meta-box' ), $this->equalTo( 'test' ), $this->equalTo( array( &$controller, 'render_add_image_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ) ],
				[ $this->equalTo( 'bc-add-description-meta-box' ), $this->equalTo( 'test' ), $this->equalTo( array( &$controller, 'render_add_description_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ) ],
				[ $this->equalTo( 'bc-add-tag-meta-box' ), $this->equalTo( 'test' ), $this->equalTo( array( &$controller, 'render_add_tag_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ) ]
			);

		// @exercise
		$controller = new BcAddBlastController( $m_plugin, $m_helper );
		$controller->add_blast_form_meta_boxes();
	}

	/**
	 * Test render title input
	 */
	function test_render_add_title_meta_box_should_output_textarea() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = new BcAddBlastController( $m_plugin, $m_helper );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-title-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find title text area' );
			}
		);

		// @exercise
		$controller->render_add_title_meta_box();
	}

	/**
	 * Test render category picker
	 */
	function test_render_add_category_meta_box_should_output_picker() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = new BcAddBlastController( $m_plugin, $m_helper );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-category-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find category picker div' );
			}
		);

		// @exercise
		$controller->render_add_category_meta_box();
	}

	/**
	 * Test render image picker
	 */
	function test_render_add_image_meta_box_should_output_image() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = new BcAddBlastController( $m_plugin, $m_helper );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-image-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find image picker div' );
			}
		);

		// @exercise
		$controller->render_add_image_meta_box();
	}

	/**
	 * Test render description input
	 */
	function test_render_add_description_meta_box_should_output_textarea() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = new BcAddBlastController( $m_plugin, $m_helper );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-desc-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find description text area' );
			}
		);

		// @exercise
		$controller->render_add_description_meta_box();
	}

	/**
	 * Test render tag picker
	 */
	function test_render_add_tag_meta_box_should_output_picker() {
		// @setup
		$m_wph = $this->mock( 'TcWpHelper' );
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_plugin = $this->mock( 'TcAdminPlugin' );
		// @sut
		$controller = new BcAddBlastController( $m_plugin, $m_helper );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-tag-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find tag picker div' );
			}
		);

		// @exercise
		$controller->render_add_tag_meta_box();
	}
}
