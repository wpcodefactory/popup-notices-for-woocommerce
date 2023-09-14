<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Core Class
 *
 * @version 1.4.5
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\PNWC\Core' ) ) {

	class Core {

		public $plugin_info = array();
		public $modal;

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
		 * Setups plugin.
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
		 * Gets plugin url.
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
		 * Gets plugins dir.
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
		 * Initializes.
		 *
		 * @version 1.3.7
		 * @since   1.0.0
		 *
		 * @return Core
		 */
		public function init() {
		    $this->handle_localization();
			$this->set_admin();
			$this->set_wp_admin_notices();
			add_action( 'template_redirect', array( $this, 'add_license_query_string_on_admin_settings' ) );

			if ( 'yes' === get_option( 'ttt_pnwc_opt_enable', 'yes' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );

				// Modal
				$modal = new Modal();
				$modal->init();
				$this->modal = $modal;

				add_filter( 'ttt_pnwc_localize_script', array( $this, 'localize_js_options' ) );
				add_action( 'admin_init', array( $this, 'add_license_query_string_on_admin_settings' ), 1 );
				add_filter( 'ttt_pnwc_license_data', array( $this, 'setup_license_data' ), 10, 2 );
				add_action( 'admin_head', array( $this, 'admin_style' ) );

				// Loads plugin by device type.
				add_filter( 'ttt_pnwc_is_allowed_to_load', array( $this, 'load_plugin_by_device_type' ) );

				// Hide WooCommerce Notices
				add_action( 'wp_enqueue_scripts', array( $this, 'hide_woocommerce_notices' ) );
			}
		}

		/**
		 * get_hidden_notices_selector.
		 *
		 * @version 1.3.7
		 * @since   1.3.7
		 */
		function get_hidden_notices_selector() {
			$error_notice_selector   = apply_filters( 'ttt_pnwc_notice_selector', '.woocommerce-error', 'error_wrapper' );
			$success_notice_selector = apply_filters( 'ttt_pnwc_notice_selector', '.woocommerce-message', 'success' );
			$info_notice_selector    = apply_filters( 'ttt_pnwc_notice_selector', '.woocommerce-info', 'info' );
			$notices_style_arr       = array();
			if ( 'yes' === get_option( 'ttt_pnwc_opt_hide_error_enable', 'no' ) ) {
				$notices_style_arr[] = $error_notice_selector;
			}
			if ( 'yes' === get_option( 'ttt_pnwc_opt_hide_success_enable', 'no' ) ) {
				$notices_style_arr[] = $success_notice_selector;
			}
			if ( 'yes' === get_option( 'ttt_pnwc_opt_hide_info_enable', 'no' ) ) {
				$notices_style_arr[] = $info_notice_selector;
			}
			if ( count( $notices_style_arr ) > 0 ) {
				$style = implode( ", ", $notices_style_arr );
				return $style;
			}
			return '';
		}

		/**
		 * Hides default WooCommerce Notices
		 *
		 * @version 1.3.7
		 * @since   1.0.0
		 */
		public function hide_woocommerce_notices() {
			$notices_selector = $this->get_hidden_notices_selector();
			if ( ! empty( $notices_selector ) ) {
				$style = $notices_selector . "{display:none !important}";
				wp_add_inline_style( 'ttt-pnwc', $style );
			}
		}

		/**
		 * is_mobile.
		 *
		 * @see http://detectmobilebrowsers.com/.
		 *
		 * @version 1.3.6
		 * @since   1.3.6
		 *
		 * @return bool
		 */
		function is_mobile() {
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			return preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) );
		}

		/**
		 * is_desktop.
		 *
		 * @version 1.3.6
		 * @since   1.3.6
		 *
		 * @return bool
		 */
		function is_desktop() {
			return ! $this->is_mobile();
		}

		/**
		 * load_plugin_by_device_type.
		 *
		 * @version 1.3.6
		 * @since   1.3.6
		 *
		 * @param $is_allowed
		 *
		 * @return bool
		 */
		function load_plugin_by_device_type( $is_allowed ) {
			if ( empty( $device_types_allowed = get_option( 'ttt_pnwc_opt_allowed_device_types', array() ) ) ) {
				return $is_allowed;
			}
			foreach ( $device_types_allowed as $device_allowed ) {
				if ( call_user_func( array( $this, 'is_' . $device_allowed ) ) ) {
					return $is_allowed;
				}
			}
			return false;
		}

		/**
		 * admin_style.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function admin_style() {
			if (
				! isset( $_REQUEST['tab'] ) ||
				! isset( $_REQUEST['page'] ) ||
				$_REQUEST['tab'] != 'ttt-pnwc' ||
				$_REQUEST['page'] != 'wc-settings'
			) {
				return;
			}
			?>
            <style>
                .ttt-wpan-premium {
                    background: #e8e8e8;
                    padding: 5px 10px 7px 8px;
                    color: #888;
                    font-size: 13px;
                    vertical-align: middle;
                    /*margin: 0 0 0 10px;*/
                }
            </style>
			<?php
		}

		/**
		 * setup license data.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $value
		 * @param string $data_type
		 *
		 * @return bool|string
		 */
		public function setup_license_data( $value, $data_type = 'is_free' ) {
			switch ( $data_type ) {
				case 'disabled_attribute':
					$value = 'disabled';
					//$value = array( 'disabled' => 'disabled' );
				break;
				case 'readonly_attribute':
					$value = 'readonly';
					//$value = array( 'disabled' => 'disabled' );
				break;
				case 'multiline_info':
					$value = '<span class="ttt-wpan-premium">'.sprintf( __( "The <a target='_blank' href='%s'>Premium</a> version will unlock a textarea field adding possibility for multiple values", 'popup-notices-for-woocommerce' ), 'https://wpfactory.com/item/popup-notices-for-woocommerce/' ).'</span>';
					//$value = 'readonly';
					//$value = array( 'disabled' => 'disabled' );
				break;
				case 'premium_info':
					$value = '<span class="ttt-wpan-premium pnwc-inline-message" style="margin-top:3px;"><i style="margin-right:2px" class="pnwc-icon dashicons-before dashicons-awards"></i>' . sprintf( __( 'Disabled options can be unlocked using the <a href="%s" target="_blank">Pro version</a>', 'popup-notices-for-woocommerce' ), 'https://wpfactory.com/item/popup-notices-for-woocommerce/' ) . '</span>';
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
		 * Adds query string on admin settings regarding free plugin.
		 *
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

			if ( ! empty( $_REQUEST['section'] ) ) {
				$new_url = add_query_arg(
					array( 'section' => $_REQUEST['section'] ),
					$new_url
				);
			}

			wp_redirect( admin_url( $new_url ), 301 );
			exit;
		}

		/**
		 * Sets wp admin notices.
		 *
		 * @version 1.0.2
		 * @since 1.0.2
		 */
		private function set_wp_admin_notices() {
			$notices = new Notices();
			$notices->init();
		}

		/**
		 * Sets admin.
		 *
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
		 * Adds action links.
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
		 * Adds scripts.
         *
		 * @version 1.4.5
		 * @since   1.0.0
		 */
		public function add_scripts() {
			if ( ! Restrictive_Loading::is_allowed_to_load() ) {
				return;
			}
			$plugin     = \WPFactory\PNWC\Core::instance();
			$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$plugin_dir = $plugin->get_plugin_dir();
			$plugin_url = $plugin->get_plugin_url();

			// Main css file
			$css_file = 'pnwc-frontend' . $suffix . '.css';
			$css_ver  = date( "ymd-Gis", filemtime( $plugin_dir . 'assets/css/' . $css_file ) );
			wp_register_style( 'ttt-pnwc', $plugin_url . 'assets/css/' . $css_file, array(), $css_ver );
			wp_enqueue_style( 'ttt-pnwc' );

			// Main js file
			$js_file = 'pnwc-frontend' . $suffix . '.js';
			$js_ver  = date( "ymd-Gis", filemtime( $plugin_dir . 'assets/js/' . $js_file ) );
			wp_register_script( 'ttt-pnwc', $plugin_url . 'assets/js/' . $js_file, array(
				'jquery',
				'ttt_pnwc_micromodal'
			), $js_ver, true );
			wp_enqueue_script( 'ttt-pnwc' );
			wp_add_inline_script( 'ttt-pnwc', 'const ttt_pnwc_info = ' . json_encode( apply_filters( 'ttt_pnwc_localize_script', array(
					'themeURI'           => get_theme_file_uri(),
					'pluginURL'          => untrailingslashit( plugin_dir_url( $this->plugin_info['path'] ) ),
					'icon_default_class' => 'default-icon',
					'error_icon_class'   => '',
					'info_icon_class'    => '',
					'success_icon_class' => '',
					'click_inside_close' => get_option( 'ttt_pnwc_opt_click_inside_close', 'no' ),
					'modulesRequired'    => apply_filters( 'ttt_pnwc_frontend_js_modules_required', array() )
				) ) ), 'before'
			);
		}

		/**
		 * Handle Localization.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 */
		public function handle_localization(){
			$domain = 'popup-notices-for-woocommerce';
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
			if ( $loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . 'plugins' . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' ) ) {
				return $loaded;
			} else {
				load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->plugin_info['path'] ) ) . '/src/languages/' );
			}
		}

		/**
		 * Passes admin settings to JS.
		 *
		 * @version 1.2.5
		 * @since   1.0.2
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function localize_js_options( $data ) {
			if ( ! Restrictive_Loading::is_allowed_to_load() ) {
				return;
			}

		    // Notice Types
			$data['types'] = array(
				'error'   => get_option( 'ttt_pnwc_opt_type_error_enable', 'yes' ),
				'info'    => get_option( 'ttt_pnwc_opt_type_info_enable', 'yes' ),
				'success' => get_option( 'ttt_pnwc_opt_type_success_enable', 'yes' ),
			);

			// Ajax opt
			$data['ajax_opt'] = get_option( 'ttt_pnwc_opt_ajax', 'yes' );

			// Cookie opt
			$data['cookie_opt']['enabled']        = 'no';
			$data['cookie_opt']['time']           = 0.5;
			$data['cookie_opt']['message_origin'] = 'static';

			// Auto-close
			$data['auto_close_time'] = 0;
			$data['auto_close_types'] = array();

			// Ignored Messages
			$ignored_messages_field               = html_entity_decode( get_option( 'ttt_pnwc_opt_ignore_msg_field', '<p></p>' ) );
			$data['ignored_msg']['field']         = ! empty( $ignored_messages_field ) ? explode( "\n", str_replace( "\r", "", $ignored_messages_field ) ) : '';
			$data['ignored_msg']['search_method'] = get_option( 'ttt_pnwc_opt_ignore_search_method', 'full_comparison' );
			$data['ignored_msg']['regex_flags']   = get_option( 'ttt_pnwc_opt_ignore_msg_regex_f', 'i' );

			// Audio
			$data['audio']['enabled'] = 'no';
			$data['audio']['opening'] = '';
			$data['audio']['closing'] = ''; //'http://freesound.org/data/previews/220/220170_4100837-lq.mp3';

			return $data;
		}

		/**
		 * Creates admin settings.
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