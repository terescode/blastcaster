<?php
/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
if ( ! class_exists( 'TcWpHelper' ) ) {
	class TcWpHelper {
		function __construct() {
		}

		function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
			return add_action( $tag, $function_to_add, $priority, $accepted_args );
		}

		function register_activation_hook( $file, $callable ) {
			register_activation_hook( $file, $callable );
		}

		function register_deactivation_hook( $file, $callable ) {
			register_deactivation_hook( $file, $callable );
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		function load_plugin_textdomain( $domain, $deprecated = false, $plugin_rel_path = false ) {
			return load_plugin_textdomain( $domain, $deprecated, $plugin_rel_path );
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		function wp_enqueue_script( $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {
			wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		}

		function esc_attr( $text ) {
			return esc_attr( $text );
		}

		function esc_html( $text ) {
			return esc_html( $text );
		}

		function do_action( $tag, $arg = '' ) {
			$count = func_num_args();
			if ( 1 === $count ) {
				do_action( $tag );
				return;
			} elseif ( 2 === $count ) {
				do_action( $tag, $arg );
				return;
			}

			call_user_func_array( 'do_action', func_get_args() );
		}

		function add_posts_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
			return add_posts_page( $page_title, $menu_title, $capability, $menu_slug, $function );
		}

		/**
		 * @SuppressWarnings(PHPMD.ShortVariable) as this is a WP name we can't change, not ours
		 */
		function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null ) {
			add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
		}

		function current_user_can( $capability, $objid = null ) {
			if ( null === $objid ) {
				return current_user_can( $capability );
			}
			return current_user_can( $capability, $objid );
		}

		function admin_url( $path = '', $scheme = 'admin' ) {
			return admin_url( $path, $scheme );
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		function wp_nonce_field( $action = -1, $name = '_wpnonce', $referer = true, $echo = true ) {
			return wp_nonce_field( $action, $name, $referer, $echo );
		}

		function do_meta_boxes( $screen, $context, $object ) {
			return do_meta_boxes( $screen, $context, $object );
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		function submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null ) {
			return submit_button( $text, $type, $name, $wrap, $other_attributes );
		}

		/**
		 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
		 */
		function wp_insert_post( $postarr, $wp_error = false ) {
			return wp_insert_post( $postarr, $wp_error );
		}
	}
}
