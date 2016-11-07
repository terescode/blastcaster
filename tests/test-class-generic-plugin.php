<?php

namespace Terescode\BlastCaster;

require_once 'includes/constants.php';
require_once 'includes/class-plugin-helper.php';
require_once 'includes/class-generic-plugin.php';

/**
 * Class TcGenericPluginTest
 *
 * @package Blastcaster
 */

class TcGenericPluginTest extends \BcPhpUnitTestCase {
	/**
	 * Test constructor and get_plugin_id
	 */
	function test_constructor_should_set_plugin_id_and_plugin_helper() {
		// @setup
		$m_helper = $this->mock( 'TcPluginHelper' );

		// @sut @exercise
		$plugin = new TcGenericPlugin( 'generic-plugin-id', $m_helper );

		// @verify
		$this->assertEquals( 'generic-plugin-id', $plugin->get_plugin_id() );
		$this->assertEquals( $m_helper, $plugin->get_plugin_helper() );
	}

	/**
	 * Test init
	 */
	function test_init_should_call_init_admin_plugin_with_self() {
		// @setup
		$m_helper = $this->mock( 'TcPluginHelper' );
		// @sut
		$plugin = new TcGenericPlugin( 'generic-plugin-id', $m_helper );

		$m_helper->expects( $this->once() )
			->method( 'init_admin_plugin' )
			->with( $this->equalTo( $plugin ) );

		// @exercise
		$plugin->init();
	}

	/**
	 * Test load
	 */
	function test_load_should_call_load_textdomain_with_self_and_register_handlers_given_1_handler() {
		// @setup
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_controller = $this->mock( 'TcController' );
		// @sut
		$plugin = new TcGenericPlugin( 'generic-plugin-id', $m_helper, [ $m_controller ] );

		$m_helper->expects( $this->once() )
			->method( 'load_textdomain' )
			->with( $this->equalTo( $plugin ) );
		$m_controller->expects( $this->once() )
			->method( 'register_handlers' );

		// @exercise
		$plugin->load();
	}

	/**
	 * Test load
	 */
	function test_load_should_call_load_textdomain_with_self_and_register_handlers_given_N_handlers() {
		$this->invoke_with_random_count( 5, 5, function ( $count ) {
			// @setup
			$m_helper = $this->mock( 'TcPluginHelper' );
			$m_controllers = array();
			for ( $i = 0; $i < $count; $i += 1 ) {
				$m_controllers[] = $this->mock( 'TcController' );
			}
			// @sut
			$plugin = new TcGenericPlugin( 'generic-plugin-id', $m_helper, $m_controllers );

			$m_helper->expects( $this->once() )
				->method( 'load_textdomain' )
				->with( $this->equalTo( $plugin ) );
			foreach ( $m_controllers as $controller ) {
				$controller->expects( $this->once() )
					->method( 'register_handlers' );
			}

			// @exercise
			$plugin->load();
		});
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_call_install_admin_menus_with_controllers_given_1_controller() {
		// @setup
		$m_helper = $this->mock( 'TcPluginHelper' );
		$m_controller = $this->mock( 'TcController' );
		// @sut
		$plugin = new TcGenericPlugin( 'generic-plugin-id', $m_helper, [ $m_controller ] );

		$m_helper->expects( $this->once() )
			->method( 'install_admin_menus' )
			->with( $this->equalTo( [ $m_controller ] ) );

		// @exercise
		$plugin->install_admin_menus();
	}

	/**
	 * Test install_admin_menus
	 */
	function test_install_admin_menus_should_call_install_admin_menus_with_controllers_given_N_controllers() {
		$this->invoke_with_random_count( 5, 5, function ( $count ) {
			// @setup
			$m_helper = $this->mock( 'TcPluginHelper' );
			$m_controllers = array();
			for ( $i = 0; $i < $count; $i += 1 ) {
				$m_controllers[] = $this->mock( 'TcController' );
			}
			// @sut
			$plugin = new TcGenericPlugin( 'generic-plugin-id', $m_helper, $m_controllers );

			$m_helper->expects( $this->once() )
				->method( 'install_admin_menus' )
				->with( $this->equalTo( $m_controllers ) );

			// @exercise
			$plugin->install_admin_menus();
		});
	}
}
