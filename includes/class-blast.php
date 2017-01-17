<?php

namespace Terescode\BlastCaster;

if ( ! class_exists( __NAMESPACE__ . '\BcBlast' ) ) {
	class BcBlast {
		private $title;
		private $description;
		private $image_data;
		private $categories;
		private $tags;
		private $url;
		private $prompt;

		function __construct( $title, $description, $image_data = null, $categories = array(), $tags = array(), $url = null, $prompt = null ) {
			$this->title = $title;
			$this->description = $description;
			$this->image_data = $image_data;
			$this->categories = $categories;
			$this->tags = $tags;
			$this->url = $url;
			$this->prompt = $prompt;
		}

		function get_title() {
			return $this->title;
		}

		function get_description() {
			return $this->description;
		}

		function get_image_data() {
			return $this->image_data;
		}

		function get_categories() {
			return $this->categories;
		}

		function get_tags() {
			return $this->tags;
		}

		function get_url() {
			return $this->url;
		}

		function get_prompt() {
			return $this->prompt;
		}

		function set_title( $title ) {
			$this->title = $title;
		}

		function set_description( $description ) {
			$this->description = $description;
		}

		function set_image_data( $image_data ) {
			$this->image_data = $image_data;
		}

		function set_categories( $categories ) {
			$this->categories = $categories;
		}

		function set_tags( $tags ) {
			$this->tags = $tags;
		}

		function set_url( $url ) {
			$this->url = $url;
		}

		function set_prompt( $prompt ) {
			$this->prompt = $prompt;
		}
	}
}
