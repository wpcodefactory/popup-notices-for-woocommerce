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
		 * @version 1.0.2
		 * @since 1.0.0
		 *
		 * @return Core
		 */
		public function init() {
			$this->set_admin();
			$this->set_wp_admin_notices();
			add_action( 'template_redirect', array( $this, 'add_license_query_string_on_admin_settings' ) );

			if ( 'yes' === get_option( 'ttt_pnwc_opt_enable', 'yes' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );

				// Modal
				$modal = new Modal();
				$modal->init();

				//add_filter( 'ttt_pnwc_localize_script', array( $this, 'localize_js_options' ) );

				add_action( 'admin_init', array( $this, 'add_license_query_string_on_admin_settings' ), 1 );

				add_filter( 'ttt_pnwc_license_data', array( $this, 'setup_license_data' ), 10, 2 );

				add_action('admin_head',array($this,'admin_style'));
			}
		}

		public function admin_style(){
			if(
				!isset($_REQUEST['tab']) ||
				!isset($_REQUEST['page']) ||
				$_REQUEST['tab']!='ttt-pnwc' ||
				$_REQUEST['page']!='wc-settings'
			){
				return;
			}
			?>
			<style>
			.ttt-wpan-premium{
				background:#e8e8e8;
				padding:4px 9px 6px;
				display:inline-block;
				margin:5px 0;
				color:#999;
				font-size:13px;
			}
			</style>
			<?php

		}

		public function setup_license_data( $value, $data_type = 'is_free' ) {
			switch ( $data_type ) {
				case 'disabled_attribute':
					$value = array( 'disabled' => 'disabled' );
				break;
				case 'premium_info':
					$value = '<span class="ttt-wpan-premium">'.sprintf( __( "Unlock it using the <a target='_blank' href='%s'>Premium</a> version", 'popup-notices-for-woocommerce' ), 'https://wpfactory.com/item/popup-notices-for-woocommerce/' ).'</span>';
				break;
				/*case 'customizer_popup_panel_url':
					$query['autofocus[panel]'] = 'ttt_pnwc';
					$panel_link = add_query_arg( $query, admin_url( 'customize.php' ) );
					//$value = admin_url( 'customize.php' );
					$value = $panel_link;
				break;*/
				default:
					$value = true;
				break;
			}

			return $value;
		}

		/**
		 * Adds query string on admin settings regarding free plugin
		 * @version 1.0.2
		 * @since 1.0.2
		 */
		public function add_license_query_string_on_admin_settings() {
			if (
				! is_admin() ||
				true !== apply_filters( 'ttt_pnwc_license_data', true, 'is_free' ) ||
				! isset( $_REQUEST['page'] ) ||
				! isset( $_REQUEST['tab'] ) ||
				$_REQUEST['page'] != 'wc-settings' ||
				$_REQUEST['tab'] != 'ttt-pnwc' ||
				( isset( $_REQUEST['license'] ) && $_REQUEST['license'] == 'free' )
			) {
				return;
			}

			$new_url = add_query_arg(
				array( 'license' => 'free' ),
				'admin.php?page=wc-settings&tab=ttt-pnwc'
			);

			wp_redirect( admin_url( $new_url ), 301 );
			exit;
		}

		/**
		 * Sets admin
		 * @version 1.0.2
		 * @since 1.0.2
		 */
		private function set_wp_admin_notices() {
			$notices = new Notices();
			$notices->init();
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

			//if ( true === apply_filters( 'ttt_pnwc_license_data', true, 'is_free') ) {
			$mylinks[] = '<a href="https://wpfactory.com/item/popup-notices-for-woocommerce/">' . __( 'Unlock All', 'product-input-fields-for-woocommerce' ) . '</a>';

			//}

			return array_merge( $mylinks, $links );
		}

		/**
		 * Adds scripts
		 * @version 1.0.2
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
			$localize_script = array();
			wp_localize_script( 'ttt-pnwc', 'ttt_pnwc_info', apply_filters( 'ttt_pnwc_localize_script', $localize_script ) );

		}

		/**
		 * Passes admin settings to JS
		 *
		 * @version 1.0.2
		 * @since 1.0.2
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function localize_js_options( $data ) {
			$data['types']    = array(
				'error'   => get_option( 'ttt_pnwc_opt_type_error_enable', 'yes' ),
				'info'    => get_option( 'ttt_pnwc_opt_type_info_enable', 'yes' ),
				'success' => get_option( 'ttt_pnwc_opt_type_success_enable', 'yes' ),
			);
			$data['ajax_opt'] = get_option( 'ttt_pnwc_opt_ajax', 'no' );

			return $data;
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