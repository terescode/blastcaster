<?php

namespace Terescode\BlastCaster;

require_once 'includes/constants.php';
require_once 'admin/class-add-blast-page.php';
require_once 'admin/class-add-blast-page-helper.php';

/**
 * Class BcAddBlastPageHelperTest
 *
 * @package Blastcaster
 */

class BcAddBlastPageHelperTest extends \BcPhpUnitTestCase {

	/**
	 * Test render title input
	 */
	function test_render_add_title_meta_box_should_output_textarea() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-title-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find title text area' );
				$element = $elements->item( 0 );
				$this->assertEquals( '', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_title_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render title input
	 */
	function test_render_add_title_meta_box_should_output_textarea_with_no_title_given_titles_missing() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$page_data = json_decode( '{}' );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-title-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find title text area' );
				$element = $elements->item( 0 );
				$this->assertEquals( '', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_title_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render title input
	 */
	function test_render_add_title_meta_box_should_output_textarea_with_no_title_given_zero_titles() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$page_data = json_decode( '{"titles":[]}' );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-title-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find title text area' );
				$element = $elements->item( 0 );
				$this->assertEquals( '', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_title_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render title input
	 */
	function test_render_add_title_meta_box_should_output_textarea_with_title_given_valid_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$json_file = file_get_contents( 'tests/fixtures/sample.json', true );
		$page_data = json_decode( $json_file );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-title-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find title text area' );
				$element = $elements->item( 0 );
				$this->assertStringStartsWith( 'Carbon Fiber in Automotive Manufacturing', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_title_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render title input
	 */
	function test_render_add_title_meta_box_should_output_textarea_with_title_given_sticky_form_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'param' )
			->willReturn( 'a title' );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-title-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find title text area' );
				$element = $elements->item( 0 );
				$this->assertStringStartsWith( 'a title', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_title_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render category picker
	 */
	function test_render_add_category_meta_box_should_output_picker() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-category-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find category picker div' );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_category_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render image picker
	 */
	function test_render_add_image_meta_box_should_output_image() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-image-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find image picker div' );
				$element = $elements->item( 0 );
				$elements = $xpath->query( 'img', $element );
				$this->assertEquals( 1, $elements->length, 'Could not find primary image' );
				$element = $elements->item( 0 );
				$this->assertContains( 'noImage.png', $element->getAttribute( 'src' ) );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_image_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render image picker
	 */
	function test_render_add_image_meta_box_should_output_no_image_given_images_missing() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$page_data = json_decode( '{}' );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );

		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-image-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find image picker div' );
				$element = $elements->item( 0 );
				$elements = $xpath->query( 'img', $element );
				$this->assertEquals( 1, $elements->length, 'Could not find primary image' );
				$element = $elements->item( 0 );
				$this->assertContains( 'noImage.png', $element->getAttribute( 'src' ) );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_image_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render image picker
	 */
	function test_render_add_image_meta_box_should_output_no_image_given_zero_images() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$page_data = json_decode( '{ "images": [] }' );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );

		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-image-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find image picker div' );
				$element = $elements->item( 0 );
				$elements = $xpath->query( 'img', $element );
				$this->assertEquals( 1, $elements->length, 'Could not find primary image' );
				$element = $elements->item( 0 );
				$this->assertContains( 'noImage.png', $element->getAttribute( 'src' ) );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_image_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render image picker
	 */
	function test_render_add_image_meta_box_should_output_image_given_valid_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$json_file = file_get_contents( 'tests/fixtures/sample.json', true );
		$page_data = json_decode( $json_file );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );

		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-image-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find image picker div' );
				$element = $elements->item( 0 );
				$elements = $xpath->query( 'img', $element );
				$this->assertEquals( 1, $elements->length, 'Could not find primary image' );
				$element = $elements->item( 0 );
				$this->assertContains( 'carbon-fiber-market-2020.jpg', $element->getAttribute( 'src' ) );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_image_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render description input
	 */
	function test_render_add_description_meta_box_should_output_textarea() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-desc-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find description text area' );
				$element = $elements->item( 0 );
				$this->assertEquals( '', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_description_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render description input
	 */
	function test_render_add_description_meta_box_should_output_textarea_with_no_description_given_descriptions_missing() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$page_data = json_decode( '{}' );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-desc-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find description text area' );
				$element = $elements->item( 0 );
				$this->assertEquals( '', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_description_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render title input
	 */
	function test_render_add_description_meta_box_should_output_textarea_with_no_description_given_zero_descriptions() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$page_data = json_decode( '{"descriptions":[]}' );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-desc-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find description text area' );
				$element = $elements->item( 0 );
				$this->assertEquals( '', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_description_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render title input
	 */
	function test_render_add_description_meta_box_should_output_textarea_with_description_given_valid_post_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$json_file = file_get_contents( 'tests/fixtures/sample.json', true );
		$page_data = json_decode( $json_file );
		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_page->method( 'get_page_data' )
			->willReturn( $page_data );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-desc-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find description text area' );
				$element = $elements->item( 0 );
				$this->assertStringStartsWith( 'According to a newly published report from IHS Markit,', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_description_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render title input
	 */
	function test_render_add_description_meta_box_should_output_textarea_with_description_given_sticky_form_data() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$m_helper->method( 'param' )
			->willReturn( 'a description' );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//textarea[@id="bc-add-desc-input"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find description text area' );
				$element = $elements->item( 0 );
				$this->assertStringStartsWith( 'a description', $element->textContent );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_description_meta_box( $m_post, $m_metabox );
	}

	/**
	 * Test render tag picker
	 */
	function test_render_add_tag_meta_box_should_output_picker() {
		// @setup
		$m_wph = $this->mock( 'Terescode\WordPress\TcWpHelper' );
		$m_helper = $this->mock( 'Terescode\WordPress\TcPluginHelper' );
		$m_post = new \stdClass;
		$m_page = $this->mock( '\Terescode\BlastCaster\BcAddBlastPage' );
		$m_metabox = array( 'args' => array( $m_page ) );

		$m_helper->method( 'get_wp_helper' )
			->willReturn( $m_wph );
		$this->expect_html(
			function ( $result ) {
				$xpath = new \DOMXPath( $result );
				$elements = $xpath->query( '//div[@id="bc-add-tag-picker"]' );
				$this->assertEquals( 1, $elements->length, 'Could not find tag picker div' );
			}
		);

		// @exercise
		$helper = new BcAddBlastPageHelper( $m_helper );
		$helper->render_add_tag_meta_box( $m_post, $m_metabox );
	}
}
