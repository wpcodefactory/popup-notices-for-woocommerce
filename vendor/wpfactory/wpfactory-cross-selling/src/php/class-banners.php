<?php
/**
 * WPFactory Cross-Selling - Products
 *
 * @version 1.0.4
 * @since   1.0.4
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\Banners' ) ) {

	/**
	 * Banners.
	 *
	 * @version 1.0.4
	 * @since   1.0.4
	 */
	class Banners {

		/**
		 * WPFactory_Cross_Selling_Injector.
		 *
		 * @since 1.0.4
		 */
		use WPFactory_Cross_Selling_Injector;

		/**
		 * Products.
		 *
		 * @since   1.0.4
		 *
		 * @var array
		 */
		protected $banners = array();

		/**
		 * get_products.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 *
		 * @return array[]
		 */
		function get_banners() {
			$this->banners = array(
				array(
					'url'     => 'https://wpfactory.com/item/all-plugin-access/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory',
					'target'  => '_blank',
					'img_src' => plugins_url( 'assets/img/lifetime-access-banner.png', $this->get_wpfactory_cross_selling()->get_setup_args()['library_root_path'] ),
				),
			);

			return $this->banners;
		}
	}
}