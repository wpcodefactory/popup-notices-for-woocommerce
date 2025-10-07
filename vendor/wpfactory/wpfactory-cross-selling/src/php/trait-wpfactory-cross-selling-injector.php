<?php
/**
 * WP Plugin Base Injector.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


if ( ! trait_exists( 'WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling_Injector' ) ) {

	trait WPFactory_Cross_Selling_Injector {

		/**
		 * $wpfactory_cross_selling.
		 *
		 * @since 1.0.0
		 */
		protected $wpfactory_cross_selling;

		/**
		 * get_wpfactory_cross_selling.
		 *
		 * @since 1.0.0
		 *
		 * @return WPFactory_Cross_Selling
		 */
		public function get_wpfactory_cross_selling() {
			return $this->wpfactory_cross_selling;
		}

		/**
		 * set_wpfactory_cross_selling.
		 *
		 * @since 1.0.0
		 *
		 * @param   WPFactory_Cross_Selling  $wpfactory_cross_selling
		 */
		public function set_wpfactory_cross_selling( $wpfactory_cross_selling ) {
			$this->wpfactory_cross_selling = $wpfactory_cross_selling;
		}

	}
}