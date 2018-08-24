<?php
/**
 * Pretty WooCommerce Notices (TTT) - Core Class
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PWCN;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PWCN\Core' ) ) {

	class Core {

		public $plugin_info = array();

		/**
		 * Call this method to get singleton
		 * @return Core
		 */
		public static function instance() {
			static $instance = false;
			if ( $instance === false ) {
				$instance = new static();
			}

			return $instance;
		}

		public function setup( $args ) {
			$args = wp_parse_args( $args, array(
				'path' => '' // __FILE__
			) );

			$this->plugin_info = $args;
		}

		/**
		 * Initializes
		 *
		 * @version 1.0.0
		 * @since 1.0.0
		 *
		 * @return Core
		 */
		public function init() {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'create_admin_settings' ), 15 );

			// Modal
			$modal = new Modal();
			$modal->init();
		}


		/**
		 * Creates admin settings
		 *
		 * @version 1.0.0
		 * @since 1.0.0
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function create_admin_settings( $settings ) {
			$settings[] = new Admin_Settings();

			return $settings;
		}
	}
}