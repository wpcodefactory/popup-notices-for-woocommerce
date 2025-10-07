<?php
/**
 * WPFactory Cross-Selling - Product Categories
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\Product_Categories' ) ) {

	/**
	 * WPF_Cross_Selling.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class Product_Categories {

		/**
		 * Product categories.
		 *
		 * @since   1.0.0
		 *
		 * @var array
		 */
		protected $product_categories = array();

		/**
		 * get_product_categories.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return array|array[]
		 */
		function get_product_categories() {
			$this->product_categories = array(
				array(
					'name' => 'Admin & Reporting',
					'slug' => 'admin-&-reporting',
				),
				array(
					'name' => 'Marketing & Promotion',
					'slug' => 'marketing-&-promotion',
				),
				array(
					'name' => 'Orders Restrictions',
					'slug' => 'orders-restrictions',
				),
				array(
					'name' => 'WordPress Utilities',
					'slug' => 'wordpress-utilities',
				),
			);

			return $this->product_categories;
		}
	}
}