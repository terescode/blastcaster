<?php

namespace Terescode\BlastCaster;

if ( ! class_exists( __NAMESPACE__ . '\BcBlast' ) ) {
	class BcBlast {
		private $title;
		private $description;
		private $image_data;
		private $categories;
		private $tags;
		private $link;
		private $link_text;
		private $link_intro;

		function __construct( $title, $description, $image_data = null, $categories = array(), $tags = array(), $link = null, $link_text = null, $link_intro = null ) {
			$this->title = $title;
			$this->description = $description;
			$this->image_data = $image_data;
			$this->categories = $categories;
			$this->tags = $tags;
			$this->link = $link;
			$this->link_text = $link_text;
			$this->link_intro = $link_intro;
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

		function get_link() {
			return $this->link;
		}

		function get_link_text() {
			return $this->link_text;
		}

		function get_link_intro() {
			return $this->link_intro;
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

		function set_link( $link ) {
			$this->link = $link;
		}

		function set_link_text( $link_text ) {
			$this->link_text = $link_text;
		}

		function set_link_intro( $link_intro ) {
			$this->link_intro = $link_intro;
		}
	}
}
