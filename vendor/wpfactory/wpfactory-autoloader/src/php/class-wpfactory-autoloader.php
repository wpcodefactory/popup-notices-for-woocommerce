<?php
/**
 * WPFactory Autoloader.
 *
 * @version 1.0.5
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
		 * @version 1.0.4
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

			// Normalize namespace prefix.
			$prefix = trim( $prefix, '\\' );

			// Normalize the base directory with a trailing separator.
			$base_dir = rtrim( $base_dir, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

			// Initialize the namespace prefix array.
			if ( isset( $this->prefixes[ $prefix ] ) === false ) {
				$this->prefixes[ $prefix ] = array();
			}

			// Retain the base directory for the namespace prefix.
			if ( $prepend ) {
				array_unshift( $this->prefixes[ $prefix ], $base_dir );
			} else {
				array_push( $this->prefixes[ $prefix ], $base_dir );
			}
		}

		/**
		 * get_source_prefix.
		 *
		 * @version 1.0.5
		 * @since   1.0.3
		 *
		 * @param $class
		 *
		 * @return int|string
		 */
		function get_source_prefix( $class ) {
			$best_match           = '';
			$longest_match_length = 0;
			foreach ( array_keys( $this->prefixes ) as $item ) {
				if ( strpos( $class, $item ) === 0 ) {
					$length = strlen( $item );
					if ( $length > $longest_match_length ) {
						$longest_match_length = $length;
						$best_match           = $item;
					}
				}
			}

			return $best_match;
		}

		/**
		 * Loads the class file for a given class name.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @param string $class The fully-qualified class name.
		 *
		 * @return mixed The mapped file name on success, or boolean false on
		 * failure.
		 */
		public function load_class( $class ) {
			if ( empty( $source_prefix = $this->get_source_prefix( $class ) ) ) {
				return false;
			}

			$relative_class = str_replace( $source_prefix, '', $class );
			$mapped_file    = $this->load_mapped_file( $source_prefix, $relative_class );
			if ( $mapped_file ) {
				return true;
			}

			// Never found a mapped file.
			return false;
		}

		/**
		 * Load the mapped file for a namespace prefix and relative class.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @param string $prefix The namespace prefix.
		 * @param string $relative_class The relative class name.
		 *
		 * @return mixed Boolean false if no mapped file can be loaded, or the
		 * name of the mapped file that was loaded.
		 */
		protected function load_mapped_file( $prefix, $relative_class ) {
			if ( isset( $this->prefixes[ $prefix ] ) === false ) {
				return false;
			}
			foreach ( $this->prefixes[ $prefix ] as $base_dir ) {
				$possible_class_prefixes = array( 'class', 'trait', 'interface' );
				$file_exists             = false;
				foreach ( $possible_class_prefixes as $possible_prefix ) {
					$filename = $this->get_filename_from_relative_class( $relative_class, $possible_prefix );
					$file     = $base_dir . str_replace( '\\', DIRECTORY_SEPARATOR, $filename );
					if ( file_exists( $file ) ) {
						require $file;
						return true;
						break;
					}
				}
			}

			// Never found it.
			return false;
		}

		/**
		 * Get relative file path.
		 *
		 * @version 1.0.3
		 * @since   1.0.1
		 *
		 * @param $relative_class
		 * @param $possible_prefix
		 *
		 * @return string
		 */
		protected function get_filename_from_relative_class( $relative_class, $possible_prefix ) {
			$relative_class = str_replace( '_', '-', strtolower( $relative_class ) );
			$pieces         = explode( '\\', $relative_class );
			$last           = array_pop( $pieces );
			$last           = $possible_prefix . '-' . $last . '.php';
			$pieces[]       = $last;
			return implode( DIRECTORY_SEPARATOR, $pieces );
		}
	}
}