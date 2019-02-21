<?php
/**
 * WP Admin Notices TTWP - Notices Maanger
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Pablo S G Pacheco
 */

namespace ThanksToIT\WPAN;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\WPAN\Notices_Manager' ) ) {

	class Notices_Manager {

		public static $activated_plugins = array();
		public static $upgrader_process_object;
		public static $upgrader_process_options;

		public static function ajax_dismiss() {
			$ajax = new Ajax();
			$ajax->dismiss();
		}

		public function create_notice( $args ) {
			$args        = wp_parse_args( $args, array(
				'id'                   => '',
				'type'                 => 'notice-info', // | 'notice-warning' | 'notice-success' | 'notice-error' | 'notice-info',
				'dismissible'          => true,
				'dismissal_expiration' => 1 * MONTH_IN_SECONDS,
				'content'              => '',
				'enabled'              => true,
				'keep_active_on'       => array( 'activated_plugin', 'updated_plugin' ),
				'display_on'           => array(
					'request'          => array(//array( 'key' => 'show_notice', 'value' => '1' ),
					),
					'screen_id'        => array(), // array( 'plugins' ),
					'activated_plugin' => array(), // array( 'plugin-a' ),
					'updated_plugin'   => array(), //array( 'plugin-b' ),
				)
			) );
			$dismissible = $args['dismissible'];
			$expiration  = filter_var( $args['dismissal_expiration'], FILTER_SANITIZE_NUMBER_INT );

			// Notice
			$notice = new Notice( $args['id'] );
			$notice->dismiss_expiration = $expiration;
			$notice->set_manager( $this );
			$notice->set_content( $args['content'] );
			$notice->display_on( $args['display_on'] );
			$notice->set_type( $args['type'] );
			if ( empty( $expiration ) || ! $dismissible ) {
				$notice->dismissible_persistent = false;
				$notice->dismissible=false;
			}
			$notice->keep_active_on = $args['keep_active_on'];
			$notice->enable();
		}

		public static function set_activated_plugin( $plugin ) {
			$activated_plugins       = self::$activated_plugins;
			$activated_plugins[]     = $plugin;
			self::$activated_plugins = $activated_plugins;
			set_transient( 'tttwpan_activated_plugins', self::$activated_plugins, WEEK_IN_SECONDS );
		}

		public static function set_upgrader_process( $upgrader_object, $options ) {
			self::$upgrader_process_object  = $upgrader_object;
			self::$upgrader_process_options = $options;
			set_transient( 'tttwpan_upgrader_options', $options, WEEK_IN_SECONDS );
		}

		/**
		 * Call this method to get singleton
		 */
		public static function instance() {
			static $instance = false;
			if ( $instance === false ) {
				// Late static binding (PHP 5.3+)
				$instance = new static();
			}

			return $instance;
		}

	}
}