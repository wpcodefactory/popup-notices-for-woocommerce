<?php
/**
 * WPFactory Cross-Selling - Singleton.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Admin_Menu;

defined( 'ABSPATH' ) || exit;

if ( ! trait_exists( 'WPFactory\WPFactory_Admin_Menu\Singleton' ) ) {

	trait Singleton {

		/**
		 * Instances.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @var array
		 */
		private static $instances = [];

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		protected function __construct() {
			// Prevent creating a new instance externally
		}

		/**
		 * Clone.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return void
		 */
		protected function __clone() {
			// Prevent cloning the instance
		}

		/**
		 * get_instance.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return mixed|static
		 */
		public static function get_instance() {
			$class = static::class;
			if ( ! isset( self::$instances[ $class ] ) ) {
				self::$instances[ $class ] = new static();
			}

			return self::$instances[ $class ];
		}
	}

}