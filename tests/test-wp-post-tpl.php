<?php

require_once 'includes/constants.php';
require_once 'includes/class-blast.php';

use Terescode\BlastCaster\BcBlast;

/**
 * Class WpAdminPluginTest
 *
 * @package Blastcaster
 */

class WpPostTplTest extends \BcPhpUnitTestCase {

	/**
	 * Test the default template
	 */
	public function test_default_template_outputs_description_no_image_description_no_url_given_blast() {
		// @setup
		$blast = new BcBlast( 'Trump wins big', 'In a stunning upset today, Trump will be the next president and will Make America Great Again!' );

		$this->expectOutputRegex( '/<p>In a stunning.+Again[!]<\/p>/s' );

		// @exercise
		include( 'admin/templates/wp-post-tpl.php' );
	}

	/**
	 * Test the default template
	 */
	public function test_default_template_outputs_image_description_no_url_given_blast() {
		// @setup
		$blast = new BcBlast( 'Trump wins big', 'In a stunning upset today, Trump will be the next president and will Make America Great Again!', [ 'url' => 'http://www.terescode.com/favico.ico' ] );

		$this->expectOutputRegex( '/<img src="http:\/\/www\.terescode\.com\/favico\.ico" width="100%" \/><p>In a stunning.+Again[!]<\/p>/s' );

		// @exercise
		include( 'admin/templates/wp-post-tpl.php' );
	}

	/**
	 * Test the default template
	 */
	public function test_default_template_outputs_image_description_url_no_prompt_given_blast() {
		// @setup
		$blast = new BcBlast( 'Trump wins big', 'In a stunning upset today, Trump will be the next president and will Make America Great Again!', [ 'url' => 'http://www.terescode.com/favico.ico' ], [], [], 'http://www.terescode.com' );

		$this->expectOutputRegex( '/<img src="http:\/\/www\.terescode\.com\/favico\.ico" width="100%" \/><p>In a stunning.+Again[!]<\/p><a href="http:\/\/www.terescode.com">http:\/\/www.terescode.com<\/a>/s' );

		// @exercise
		include( 'admin/templates/wp-post-tpl.php' );
	}
}
