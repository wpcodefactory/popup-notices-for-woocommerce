<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Notices
 *
 * @version 1.0.4
 * @since   1.0.2
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Notices' ) ) {

	class Notices {
		public function init() {
			// Initializes WP Admin Notices
			add_action( 'wp_ajax_' . 'tttwpan_dismiss_persist', array( 'ThanksToIT\WPAN\Notices_Manager', 'ajax_dismiss' ) );
			add_action( 'activated_plugin', array( 'ThanksToIT\WPAN\Notices_Manager', 'set_activated_plugin' ) );
			add_action( 'upgrader_process_complete', array( 'ThanksToIT\WPAN\Notices_Manager', 'set_upgrader_process' ), 10, 2 );

			// Manages notices conditions
			add_action( 'admin_notices', array( $this, 'add_settings_page_notices' ) );
			add_action( 'admin_notices', array( $this, 'add_plugins_page_notices' ) );
		}

		public function get_feedback_notice_content() {
			return "<h3 class='title'>" . __( "Pop-up Notices Feedback", 'popup-notices-for-woocommerce' ) . "</h3>"
			       . "<p style='margin-bottom:15px;'>" .
			       sprintf( __( "Please consider <a href='%s' target='_blank'>leaving a review</a> if you are enjoying the plugin/support. It would be much appreciated :)", 'popup-notices-for-woocommerce' ), 'https://wordpress.org/support/plugin/popup-notices-for-woocommerce/reviews/#new-post' ) . ""
			       . "<br />" .
			       __( "Feel free to submit your ideas and suggestions too.", 'popup-notices-for-woocommerce' )
			       . "</p>";
		}

		public function get_premium_notice_content() {
			return "<h3 class='title'>" . __( "Pop-up Notices Premium", 'popup-notices-for-woocommerce' ) . "</h3>"
			       . "<p>"
			       . __( "Do you like the free version of this plugin?", 'popup-notices-for-woocommerce' )
			       . "<br />"
			       . sprintf( __( "Did you know we also have a <a href='%s' target='_blank'>Premium one</a>?", 'popup-notices-for-woocommerce' ), 'https://wpfactory.com/item/popup-notices-for-woocommerce/' )
			       . "</br>"
			       . "<h4>Check some of its features for now:</h4>"
			       . "<ul style='list-style:disc inside;'>"
			       . "<li>" . __( "Customize Pop-up style using the Customizer including Icons from FontAwesome", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Customize WooCommerce Messages", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Hide default notices", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Ignore messages", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Avoid repeated messages", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Load the plugin at some specific pages, like cart or checkout for example.", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Auto-close the popup after x seconds", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Play sounds when popup opens or closes", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<li>" . __( "Support", 'popup-notices-for-woocommerce' ) . "</li>"
			       . "<p style='margin-top:15px'>"
			       . __( "Buying it will allow you managing and customizing the popup entirely, helping maintaining the development of this plugin.", 'popup-notices-for-woocommerce' )
			       . "</br>"
			       . __( "And besides you aren't going to see these annoying messages anymore :)", 'popup-notices-for-woocommerce' )
			       . "</p>"
			       . sprintf( "<a style='display:inline-block;margin:15px 0 8px 0' target='_blank' class='button-primary' href='%s'>", 'https://wpfactory.com/item/popup-notices-for-woocommerce/' ) . __( "Upgrade to Premium version", 'popup-notices-for-woocommerce' ) . "</a>"
			       . "</ul>";
		}

		public function add_plugins_page_notices() {
			$notices_manager = \ThanksToIT\WPAN\get_notices_manager();
			$chance          = mt_rand( 0, 1 );

			if ( $chance == 0 ) {
				// Feedback notice
				$notices_manager->create_notice( array(
					'id'                   => 'ttt-pnwc-free-notice-plugin-activation',
					'content'              => $this->get_feedback_notice_content(),
					'dismissal_expiration' => MONTH_IN_SECONDS,
					'display_on'           => array(
						'activated_plugin' => array( 'popup-notices-for-woocommerce/popup-notices-for-woocommerce.php' ),
						'screen_id'        => array( 'plugins' ),
					),
				) );
				$notices_manager->create_notice( array(
					'id'                   => 'ttt-pnwc-free-notice-plugin-update',
					'content'              => $this->get_feedback_notice_content(),
					'dismissal_expiration' => MONTH_IN_SECONDS,
					'display_on'           => array(
						'updated_plugin' => array( 'popup-notices-for-woocommerce/popup-notices-for-woocommerce.php' ),
						'screen_id'      => array( 'plugins' ),
					),
				) );
			} else {
				// Premium notice
				$notices_manager->create_notice( array(
					'id'                   => 'ttt-pnwc-premium-info-plugin-activation',
					'content'              => $this->get_premium_notice_content(),
					'dismissal_expiration' => MONTH_IN_SECONDS,
					'display_on'           => array(
						'activated_plugin' => array( 'popup-notices-for-woocommerce/popup-notices-for-woocommerce.php' ),
						'screen_id'        => array( 'plugins' ),
					),
				) );
				$notices_manager->create_notice( array(
					'id'                   => 'ttt-pnwc-premium-info-plugin-update',
					'content'              => $this->get_premium_notice_content(),
					'dismissal_expiration' => MONTH_IN_SECONDS,
					'display_on'           => array(
						'updated_plugin' => array( 'popup-notices-for-woocommerce/popup-notices-for-woocommerce.php' ),
						'screen_id'      => array( 'plugins' ),
					),
				) );
			}
		}

		public function add_settings_page_notices() {
			$notices_manager = \ThanksToIT\WPAN\get_notices_manager();
			$chance          = mt_rand( 0, 1 );

			if ( $chance == 0 ) {
				// Feedback notice
				$notices_manager->create_notice( array(
					'id'          => 'ttt-pnwc-free-notice-settings-page',
					'content'     => $this->get_feedback_notice_content(),
					'display_on'  => array(
						'request' => array(
							array( 'key' => 'page', 'value' => 'wc-settings' ),
							array( 'key' => 'tab', 'value' => 'ttt-pnwc' ),
							array( 'key' => 'license', 'value' => 'free' ),
						)
					),
					'dismissible' => false
				) );
			} else {
				// Premium notice
				$notices_manager->create_notice( array(
					'id'          => 'ttt-pnwc-premium-info-settings-page',
					'content'     => $this->get_premium_notice_content(),
					'display_on'  => array(
						'request' => array(
							array( 'key' => 'page', 'value' => 'wc-settings' ),
							array( 'key' => 'tab', 'value' => 'ttt-pnwc' ),
							array( 'key' => 'license', 'value' => 'free' ),
						)
					),
					'dismissible' => false
				) );
			}
		}
	}
}