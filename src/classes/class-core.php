<?php
/**
 * Popup Notices for WooCommerce (TTT) - Core Class
 *
 * @version 1.0.1
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Core' ) ) {

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

		/**
		 * Setups plugin
		 *
		 * @version 1.0.0
		 * @since 1.0.0
		 *
		 * @param $args
		 */
		public function setup( $args ) {
			$args = wp_parse_args( $args, array(
				'path' => '' // __FILE__
			) );

			$this->plugin_info = $args;
		}

		/**
		 * Gets plugin url
		 *
		 * @version 1.0.0
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_plugin_url() {
			$path = $this->plugin_info['path'];

			return plugin_dir_url( $path );
		}

		/**
		 * Gets plugins dir
		 *
		 * @version 1.0.0
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_plugin_dir() {
			$path = $this->plugin_info['path'];

			return untrailingslashit( plugin_dir_path( $path ) ) . DIRECTORY_SEPARATOR;;
		}

		/**
		 * Initializes
		 *
		 * @version 1.0.1
		 * @since 1.0.0
		 *
		 * @return Core
		 */
		public function init() {
			$this->set_admin();

			if ( 'yes' === get_option( 'ttt_pnwc_opt_enable', 'yes' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );

				// Modal
				$modal = new Modal();
				$modal->init();
			}
		}

		/**
		 * Sets admin
		 * @version 1.0.1
		 * @since 1.0.1
		 */
		private function set_admin() {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'create_admin_settings' ), 15 );

			// Add settings link on plugins page
			$path = $this->plugin_info['path'];
			add_filter( 'plugin_action_links_' . plugin_basename( $path ), array( $this, 'add_action_links' ) );
		}

		/**
		 * Adds action links
		 *
		 * @version 1.0.1
		 * @since 1.0.1
		 *
		 * @param $links
		 *
		 * @return array
		 */
		public function add_action_links( $links ) {
			$mylinks = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=ttt-pnwc' ) . '">Settings</a>',
			);

			if ( true === apply_filters( 'ttt_pnwc_license_type_data', true, 'is_free', 'free' ) ) {
				$mylinks[] = '<a href="https://wpfactory.com/item/popup-notices-for-woocommerce/">' . __( 'Unlock All', 'product-input-fields-for-woocommerce' ) . '</a>';
			}

			return array_merge( $mylinks, $links );
		}

		/**
		 * Adds scripts
		 * @version 1.0.1
		 * @since   1.0.0
		 */
		public function add_scripts() {
			$plugin     = \ThanksToIT\PNWC\Core::instance();
			$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$plugin_dir = $plugin->get_plugin_dir();
			$plugin_url = $plugin->get_plugin_url();

			// Main css file
			$css_file = 'src/assets/dist/frontend/css/ttt-pnwc' . $suffix . '.css';
			$css_ver  = date( "ymd-Gis", filemtime( $plugin_dir . $css_file ) );
			wp_register_style( 'ttt-pnwc', $plugin_url . $css_file, array(), $css_ver );
			wp_enqueue_style( 'ttt-pnwc' );

			// Main js file
			$js_file = 'src/assets/dist/frontend/js/ttt-pnwc' . $suffix . '.js';
			$js_ver  = date( "ymd-Gis", filemtime( $plugin_dir . $js_file ) );
			wp_register_script( 'ttt-pnwc', $plugin_url . $js_file, array( 'jquery' ), $js_ver, true );
			wp_enqueue_script( 'ttt-pnwc' );

			// Localize script
			$localize_script = array(
				'types' => array(
					'error'   => get_option( 'ttt_pnwc_opt_type_error_enable', 'yes' ),
					'info'    => get_option( 'ttt_pnwc_opt_type_info_enable', 'yes' ),
					'success' => get_option( 'ttt_pnwc_opt_type_success_enable', 'yes' ),
				)
			);
			wp_localize_script( 'ttt-pnwc', 'ttt_pnwc_info', apply_filters( 'ttt_pnwc_localize_script', $localize_script ) );

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