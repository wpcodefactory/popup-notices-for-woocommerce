<?php
/**
 * WPFactory Cross-Selling - Recommendation Box tags.
 *
 * @version 1.0.4
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\Recommendation_Box_Tags' ) ) {

	/**
	 * WPF_Cross_Selling.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class Recommendation_Box_Tags {

		/**
		 * Tags.
		 *
		 * @since   1.0.4
		 *
		 * @var array
		 */
		protected $tags = array();

		/**
		 * get_product_categories.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 *
		 * @return array|array[]
		 */
		function get_tags() {
			$this->tags = array(
				array(
					'name' => __( 'Top picks', 'wpfactory-cross-selling' ),
					'slug' => 'top-picks',
				),
				array(
					'name' => __( 'Admin tools', 'wpfactory-cross-selling' ),
					'slug' => 'admin-tools',
				),
				array(
					'name' => __( 'Marketing', 'wpfactory-cross-selling' ),
					'slug' => 'marketing',
				),
				array(
					'name' => __( 'WP Utilities', 'wpfactory-cross-selling' ),
					'slug' => 'wp-utilities',
				)
			);

			return $this->tags;
		}
	}
}