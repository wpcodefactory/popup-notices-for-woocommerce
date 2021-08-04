<?php
/**
 * WPFactory Autoloader.
 *
 * @version 1.0.1
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Autoloader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Autoloader' ) ) {

	/**
	 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
	 */
	class WPFactory_Autoloader {
		/**
		 * An associative array where the key is a namespace prefix and the value
		 * is an array of base directories for classes in that namespace.
		 *
		 * @since   1.0.0
		 *
		 * @var array
		 */
		protected $prefixes = array();

		/**
		 * Aargs.
		 *
		 * @var array
		 */
		protected $args = array();

		/**
		 * Register loader with SPL autoloader stack.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param null $args
		 *
		 * @return void
		 */
		public function init( $args = null ) {
			$this->args = wp_parse_args( $args, array(
				'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG
			) );
			spl_autoload_register( array( $this, 'load_class' ) );
		}

		/**
		 * Adds a base directory for a namespace prefix.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param string $prefix The namespace prefix.
		 * @param string $base_dir A base directory for class files in the
		 * namespace.
		 * @param bool $prepend If true, prepend the base directory to the stack
		 * instead of appending it; this causes it to be searched first rather
		 * than last.
		 *
		 * @return void
		 */
		public function add_namespace( $prefix, $base_dir, $prepend = false ) {

			// normalize namespace prefix
			$prefix = trim( $prefix, '\\' ) . '\\';

			// normalize the base directory with a trailing separator
			$base_dir = rtrim( $base_dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

			// initialize the namespace prefix array
			if ( isset( $this->prefixes[ $prefix ] ) === false ) {
				$this->prefixes[ $prefix ] = array();
			}

			// retain the base directory for the namespace prefix
			if ( $prepend ) {
				array_unshift( $this->prefixes[ $prefix ], $base_dir );
			} else {
				array_push( $this->prefixes[ $prefix ], $base_dir );
			}
		}

		/**
		 * Loads the class file for a given class name.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param string $class The fully-qualified class name.
		 *
		 * @return mixed The mapped file name on success, or boolean false on
		 * failure.
		 */
		public function load_class( $class ) {
			// the current namespace prefix
			$prefix = $class;

			// work backwards through the namespace names of the fully-qualified
			// class name to find a mapped file name
			while ( false !== $pos = strrpos( $prefix, '\\' ) ) {

				// retain the trailing namespace separator in the prefix
				$prefix = substr( $class, 0, $pos + 1 );

				// the rest is the relative class name
				$relative_class = substr( $class, $pos + 1 );

				// try to load a mapped file for the prefix and relative class
				$mapped_file = $this->load_mapped_file( $prefix, $relative_class );
				if ( $mapped_file ) {
					return $mapped_file;
				}

				// remove the trailing namespace separator for the next iteration
				// of strrpos()
				$prefix = rtrim( $prefix, '\\' );
			}

			// never found a mapped file
			return false;
		}

		/**
		 * Load the mapped file for a namespace prefix and relative class.
		 *
		 * @version 1.0.1
		 * @since   1.0.0
		 *
		 * @param string $prefix The namespace prefix.
		 * @param string $relative_class The relative class name.
		 *
		 * @return mixed Boolean false if no mapped file can be loaded, or the
		 * name of the mapped file that was loaded.
		 */
		protected function load_mapped_file( $prefix, $relative_class ) {
			// are there any base directories for this namespace prefix?
			if ( isset( $this->prefixes[ $prefix ] ) === false ) {
				return false;
			}

			// look through base directories for this namespace prefix
			foreach ( $this->prefixes[ $prefix ] as $base_dir ) {
				$filename = $this->get_filename_from_relative_class( $relative_class );
				$file     = $base_dir . str_replace( '\\', DIRECTORY_SEPARATOR, $filename );

				// if the mapped file exists, require it
				if ( $this->require_file( $file ) ) {
					// yes, we're done
					return $file;
				} else {
					if ( $this->args['debug'] ) {
						error_log( sprintf( __( 'WPFactory_Autoloader couldn\'t find file %s relative to class %s', 'wpfactory-autoloader' ), $file, $relative_class ) );
					}
				}
			}

			// never found it
			return false;
		}

		/**
		 * Get relative file path.
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 *
		 * @param $relative_class
		 *
		 * @return string
		 */
		protected function get_filename_from_relative_class( $relative_class ) {
			$relative_class = str_replace( '_', '-', strtolower( $relative_class ) );
			$pieces         = explode( '\\', $relative_class );
			$last           = array_pop( $pieces );
			$last           = 'class-' . $last . '.php';
			$pieces[]       = $last;
			return implode( DIRECTORY_SEPARATOR, $pieces );
		}

		/**
		 * If a file exists, require it from the file system.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param string $file The file to require.
		 *
		 * @return bool True if the file exists, false if not.
		 */
		protected function require_file( $file ) {
			if ( file_exists( $file ) ) {
				require $file;
				return true;
			}
			return false;
		}
	}
}