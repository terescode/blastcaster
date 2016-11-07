<?php

namespace Terescode\BlastCaster;

if ( ! class_exists( 'BcBlast' ) ) {
	class BcBlast {
		private $title;
		private $description;
		private $image;
		private $categories;
		private $tags;

		function __construct( $title, $description, $image = null, $categories = array(), $tags = array() ) {
			$this->title = $title;
			$this->description = $description;
			$this->image = $image;
			$this->categories = $categories;
			$this->tags = $tags;
		}

		function get_title() {
			return $this->title;
		}

		function get_description() {
			return $this->description;
		}

		function get_image() {
			return $this->image;
		}

		function get_categories() {
			return $this->categories;
		}

		function get_tags() {
			return $this->tags;
		}

		function set_title( $title ) {
			$this->title = $title;
		}

		function set_description( $description ) {
			$this->description = $description;
		}

		function set_image( $image ) {
			$this->image = $image;
		}

		function set_categories( $categories ) {
			$this->categories = $categories;
		}

		function set_tags( $tags ) {
			$this->tags = $tags;
		}
	}
}
