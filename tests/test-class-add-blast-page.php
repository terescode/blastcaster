<?php

namespace Terescode\BlastCaster;

require_once 'tests/stub-translate.php';
require_once 'includes/constants.php';
require_once 'includes/class-wp-helper.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/class-blast-dao.php';
require_once 'admin/class-add-blast-page-helper.php';
require_once 'admin/class-add-blast-page.php';

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class BcAddBlastPageTest extends \BcPhpUnitTestCase {

	/**
	 * Test init @should call add_posts_page
	 */
	function test_add_page_should_return_false_given_add_posts_page_does() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'add_posts_page' )
			->willReturn( false );

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$hook_suffix = $view->add_page();
		$this->assertFalse( $hook_suffix );
		$this->assertFalse( $view->get_hook_suffix() );
	}

	/**
	 * Test init @should call add_posts_page
	 */
	function test_add_page_should_return_hook_suffix_given_add_posts_page_returns_hook_suffix() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'add_posts_page' )
			->willReturn( 'test_hook_suffix' );

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$hook_suffix = $view->add_page();

		// @verify
		$this->assertEquals( 'test_hook_suffix', $hook_suffix );
		$this->assertEquals( 'test_hook_suffix', $view->get_hook_suffix() );
	}

	/**
	 * Test render_add_blast
	 */
	function test_load_pagenow_should_not_set_page_data_given_no_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$view->load_pagenow();

		// @verify
		$this->assertNull( $view->get_page_data() );
	}

	/**
	 * Test render_add_blast
	 */
	function test_load_pagenow_should_not_set_page_data_given_empty_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$_POST['pageData'] = '';
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$view->load_pagenow();

		// @verify
		$this->assertNull( $view->get_page_data() );
	}

	/**
	 * Test render_add_blast
	 */
	function test_load_pagenow_should_not_set_page_data_given_wsonly_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$_POST['pageData'] = "\t\t\t\r\n\n\t     \t\t\r\n";
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$view->load_pagenow();

		// @verify
		$this->assertNull( $view->get_page_data() );
	}

	/**
	 * Test render_add_blast
	 */
	function test_load_pagenow_should_set_page_data_given_valid_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$json_file = file_get_contents( 'tests/fixtures/sample.json', true );
		$_POST['pageData'] = $json_file;
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );

		// @test
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$view->load_pagenow();

		// @verify
		$page_data = $view->get_page_data();
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
	 * Test render_add_blast
	 */
	function test_load_pagenow_should_add_admin_notice_given_invalid_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$json_file = file_get_contents( 'tests/fixtures/sample-invalid.json', true );
		$_POST['pageData'] = $json_file;

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->expects( $this->once() )
			->method( 'add_admin_notice' )
			->with(
				$this->isType( 'string' )
			);

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$view->load_pagenow();

		// @verify
		$this->assertNull( $view->get_page_data() );
	}

	/**
	 * Test render
	 */
	function test_render_should_call_render() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$json_file = file_get_contents( 'tests/fixtures/sample-invalid.json', true );
		$_POST['pageData'] = $json_file;

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->expects( $this->once() )
			->method( 'render' )
			->with(
				$this->isInstanceOf( '\Terescode\BlastCaster\BcAddBlastPage' ),
				'admin/views/add-blast-page',
				'edit_posts'
			);

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$view->render();
	}

	/**
	 * Test is_metabox_page
	 */
	function test_is_metabox_page_should_return_true() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$this->assertTrue( $view->is_metabox_page() );
	}

	/**
	 * Test add blast boxes
	 */
	function test_add_meta_boxes_should_call_add_meta_box() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_page_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastPageHelper' );
		// @sut
		$view = null;

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'string' )
			->willReturn( 'translated string' );
		$m_wph->expects( $this->exactly( 5 ) )
			->method( 'add_meta_box' )
			->withConsecutive(
				[ $this->equalTo( 'bc-add-title-meta-box' ), $this->isType( 'string' ), $this->equalTo( array( $m_page_helper, 'render_add_title_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ), $this->equalTo( 'default' ), $this->equalTo( array( &$view ) ) ],
				[ $this->equalTo( 'bc-add-category-meta-box' ), $this->isType( 'string' ), $this->equalTo( array( $m_page_helper, 'render_add_category_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ), $this->equalTo( 'default' ), $this->equalTo( array( &$view ) ) ],
				[ $this->equalTo( 'bc-add-image-meta-box' ), $this->isType( 'string' ), $this->equalTo( array( $m_page_helper, 'render_add_image_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ), $this->equalTo( 'default' ), $this->equalTo( array( &$view ) ) ],
				[ $this->equalTo( 'bc-add-description-meta-box' ), $this->isType( 'string' ), $this->equalTo( array( $m_page_helper, 'render_add_description_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ), $this->equalTo( 'default' ), $this->equalTo( array( &$view ) ) ],
				[ $this->equalTo( 'bc-add-tag-meta-box' ), $this->isType( 'string' ), $this->equalTo( array( $m_page_helper, 'render_add_tag_meta_box' ) ), $this->equalTo( null ), $this->equalTo( 'normal' ), $this->equalTo( 'default' ), $this->equalTo( array( &$view ) ) ]
			);

		// @exercise
		$view = new BcAddBlastPage( $m_helper, $m_page_helper );
		$view->add_meta_boxes();
	}

	/**
	 * Test do_add_blast
	 */
	/*function test_do_add_blast_should_redirect_with_error_given_no_access() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_form_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastFormHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->expects( $this->once() )
			->method( 'current_user_can' )
			->with( $this->equalTo( 'edit_posts' ) )
			->willReturn( false );
		$m_wph->expects( $this->once() )
			->method( 'admin_url' )
			->with( $this->isType( 'string' ) )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastPage::BC_ADD_BLAST_SCREEN_ID
				. '&code=' . BcStrings::ABF_NO_ACCESS
			) );

		// @exercise
		$controller = new BcAddBlastController( $m_helper, $m_form_helper, $m_dao );
		$controller->do_add_blast();
	}*/

	/**
	 * Test do_add_blast
	 */
	/*function test_do_add_blast_should_redirect_with_error_given_missing_title() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_form_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastFormHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$m_wph->method( 'admin_url' )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastController::BC_ADD_BLAST_SCREEN_ID
				. '&code=' . BcStrings::ABF_MISSING_BLAST_TITLE
			) );

		// @exercise
		$controller = new BcAddBlastController( $m_helper, $m_form_helper, $m_dao );
		$controller->do_add_blast();
	}*/

	/**
	 * Test do_add_blast
	 */
	/*function test_do_add_blast_should_redirect_with_error_given_missing_description() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_form_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastFormHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$m_helper->method( 'param' )
			->will( $this->onConsecutiveCalls(
				'a title',
				null
			));
		$m_wph->method( 'admin_url' )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastController::BC_ADD_BLAST_SCREEN_ID
				. '&code=' . BcStrings::ABF_MISSING_BLAST_DESCRIPTION
			) );

		// @exercise
		$controller = new BcAddBlastController( $m_helper, $m_form_helper, $m_dao );
		$controller->do_add_blast();
	}*/

	/**
	 * Test do_add_blast
	 */
	/*function test_do_add_blast_should_redirect_with_error_given_missing_image_type() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_form_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastFormHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$m_helper->method( 'param' )
			->will( $this->onConsecutiveCalls(
				'a title',
				'a description',
				null
			));
		$m_wph->method( 'admin_url' )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastController::BC_ADD_BLAST_SCREEN_ID
				. '&code=' . BcStrings::ABF_INVALID_BLAST_IMAGE_TYPE
			));

		// @exercise
		$controller = new BcAddBlastController( $m_helper, $m_form_helper, $m_dao );
		$controller->do_add_blast();
	}*/

	/**
	 * Test do_add_blast
	 */
	/*function test_do_add_blast_should_redirect_with_error_given_invalid_image_type() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_form_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastFormHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$m_helper->method( 'param' )
			->will( $this->onConsecutiveCalls(
				'a title',
				'a description',
				'not_a_type'
			));
		$m_wph->method( 'admin_url' )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastController::BC_ADD_BLAST_SCREEN_ID
				. '&code=' . BcStrings::ABF_INVALID_BLAST_IMAGE_TYPE
			) );

		// @exercise
		$controller = new BcAddBlastController( $m_helper, $m_form_helper, $m_dao );
		$controller->do_add_blast();
	}*/

	/**
	 * Test do_add_blast
	 */
	/*function test_do_add_blast_should_redirect_with_error_given_missing_image_url() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_form_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastFormHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$m_helper->method( 'param' )
			->will( $this->onConsecutiveCalls(
				'a title',
				'a description',
				'url',
				null
			));
		$m_wph->method( 'admin_url' )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastController::BC_ADD_BLAST_SCREEN_ID
				. '&code=' . BcStrings::ABF_MISSING_BLAST_IMAGE_URL
			) );

		// @exercise
		$controller = new BcAddBlastController( $m_helper, $m_form_helper, $m_dao );
		$controller->do_add_blast();
	}*/

	/**
	 * Test do_add_blast
	 */
	/*function test_do_add_blast_should_redirect_with_error_given_missing_image_upload() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_form_helper = $this->mock( 'Terescode\BlastCaster\BcAddBlastFormHelper' );
		$m_dao = $this->mock( 'Terescode\BlastCaster\BcBlastDao' );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_wph->method( 'current_user_can' )
			->willReturn( true );
		$m_helper->method( 'param' )
			->will( $this->onConsecutiveCalls(
				'a title',
				'a description',
				'file',
				null
			));
		$m_wph->method( 'admin_url' )
			->will( $this->returnArgument( 0 ) );
		$m_wph->expects( $this->once() )
			->method( 'wp_safe_redirect' )
			->with( $this->equalTo(
				'edit.php?page='
				. BcAddBlastController::BC_ADD_BLAST_SCREEN_ID
				. '&code=' . BcStrings::ABF_MISSING_BLAST_IMAGE_FILE
			) );

		// @exercise
		$controller = new BcAddBlastController( $m_helper, $m_form_helper, $m_dao );
		$controller->do_add_blast();
	}*/
}
