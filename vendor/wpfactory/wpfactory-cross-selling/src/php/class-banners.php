<?php
/**
 * WPFactory Cross-Selling - Banners
 *
 * @version 1.0.8
 * @since   1.0.7
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

use WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\Banners' ) ) {

	/**
	 * Banners.
	 *
	 * @version 1.0.7
	 * @since   1.0.7
	 */
	class Banners {

		/**
		 * WPFactory_Cross_Selling_Injector.
		 *
		 * @since 1.0.7
		 */
		use WPFactory_Cross_Selling_Injector;

		/**
		 * Initialized.
		 *
		 * @since   1.0.7
		 *
		 * @var bool
		 */
		protected static $initialized = false;

		/**
		 * $get_dashboard_banner_ajax_action.
		 *
		 * @since   1.0.7
		 *
		 * @var string
		 */
		protected $get_dashboard_banner_ajax_action = 'wpfcs_get_dashboard_banner';

		/**
		 * $close_dashboard_banner_ajax_action.
		 *
		 * @since   1.0.7
		 *
		 * @var string
		 */
		protected $close_dashboard_banner_ajax_action = 'wpfcs_close_dashboard_banner';

		/**
		 * Initializes the class.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return void
		 */
		function init() {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();
			if (
				$setup_args['banners']['enable'] &&
				! self::$initialized
			) {
				self::$initialized = true;
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dynamic_style' ), 11 );
				add_filter( 'wpfcs_enqueue_admin_css', array( $this, 'enqueue_cross_selling_admin_css' ) );
				add_action( 'admin_notices', array( $this, 'display_dashboard_banner_wrapper' ) );
				add_action( 'wp_ajax_' . $this->get_dashboard_banner_ajax_action, array( $this, 'get_dashboard_banner_ajax_action' ) );
				add_action( 'wp_ajax_' . $this->close_dashboard_banner_ajax_action, array( $this, 'close_dashboard_banner_ajax_action' ) );
			}
		}

		/**
		 * enqueue_dynamic_style.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return void
		 */
		function enqueue_dynamic_style() {
			if (
				$this->can_display_banner_at_current_location() &&
				! $this->dashboard_banner_should_remain_closed()
			) {
				$close_btn_right_or_left = true === is_rtl() ? 'left' : 'right';
				$right_or_left_style     = $close_btn_right_or_left . ': -12px;';
				$css                     = "
				.wpfcs-dashboard-banner-close-btn {
					{$right_or_left_style};					
				}
			";
				wp_add_inline_style( 'wpfactory-cross-selling', $css );
			}
		}

		/**
		 * enqueue_cross_selling_admin_css.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @param $enqueue
		 *
		 * @return true
		 */
		function enqueue_cross_selling_admin_css( $enqueue ) {
			if (
				$this->can_display_banner_at_current_location() &&
				! $this->dashboard_banner_should_remain_closed()
			) {
				$enqueue = true;
			}

			return $enqueue;
		}

		/**
		 * get_advanced_ads_group.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return mixed|void|null
		 */
		function get_advanced_ads_group( $group_name ) {
			$response = wp_remote_get( 'https://wpfactory.com/wp-json/advanced-ads/v1/groups' );
			if ( ! is_wp_error( $response ) ) {
				$body   = wp_remote_retrieve_body( $response );
				$data   = json_decode( $body, true );
				$result = wp_list_filter( $data, [
					'name' => $group_name
				] );
				if ( empty( $result ) ) {
					return null;
				}

				return array_shift( $result );
			}

			return null;
		}

		/**
		 * get_banners_from_advanced_ads_group.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return mixed|void
		 */
		function get_banners_from_advanced_ads_group( $advaced_ads_group ) {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();
			$content    = array();

			$advaced_ads_group_sanitized = sanitize_key( $advaced_ads_group );
			$transient_name              = "wpfcs_banners_from_advanced_ads_group_{$advaced_ads_group_sanitized}";

			if ( 'advanced_ads' === $setup_args['banners']['get_banner_method'] ) {
				if ( false !== $setup_args['banners']['banner_cache_duration'] && false !== ( $cached_content = get_transient( $transient_name ) ) ) {
					return $cached_content;
				}
				$group = $this->get_advanced_ads_group( $advaced_ads_group );
				if ( ! is_null( $group ) && isset( $group['ads'] ) ) {
					if ( ! empty( $ads = $group['ads'] ) && is_array( $ads ) ) {
						foreach ( $ads as $ad_id ) {
							$response = wp_remote_get( 'https://wpfactory.com/wp-json/advanced-ads/v1/ads/' . $ad_id );

							if ( ! is_wp_error( $response ) ) {
								$body = wp_remote_retrieve_body( $response );
								$data = json_decode( $body, true );
								if ( isset( $data['expiration_date'] ) && ! empty( $expiration_date = $data['expiration_date'] ) ) {
									if ( $expiration_date < current_time( 'timestamp' ) ) {
										continue;
									}
								}
								if ( isset( $data['content'] ) && ! empty( $data['content'] ) ) {
									$content[] = $data['content'];
								}
							}
						}
					}

					if ( false !== $setup_args['banners']['banner_cache_duration'] ) {
						set_transient( $transient_name, $content, $setup_args['banners']['banner_cache_duration'] );
					}

					return $content;
				}
			}

			return null;
		}

		/**
		 * display_dashboard_banner.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return void
		 */
		function display_dashboard_banner_wrapper() {
			if (
				! $this->can_display_banner_at_current_location() ||
				$this->dashboard_banner_should_remain_closed()
			) {
				return;
			}
			$banner = '<div class="wpfcs-dashboard-banner-wrapper"></div>';
			echo wp_kses_post( $banner );
			echo $this->get_dashboard_banner_js();
		}

		/**
		 * get_dashboard_banner_ajax_action.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return void
		 */
		function get_dashboard_banner_ajax_action() {
			if ( false !== check_ajax_referer( 'wpfcs-get-dashboard-banner', 'banner_nonce' ) ) {
				$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();
				$banners    = $this->get_banners_from_advanced_ads_group( $setup_args['banners']['advanced_ads_setup']['dashboard_banner_group_name'] );
				if ( ! is_null( $banners ) ) {
					if ( isset( $_REQUEST['is_recommendations_page'] ) && true === filter_var( $_REQUEST['is_recommendations_page'], FILTER_VALIDATE_BOOLEAN ) ) {
						$output = $this->render_dashboard_banner( $banners, false );
					} else {
						$output = $this->dashboard_banner_should_remain_closed() ? '' : $this->render_dashboard_banner( $banners );
					}
				} else {
					$output = '';
				}
				wp_send_json_success( array(
					'banner_data' => wp_kses_post( $output ),
				) );
			}
		}

		/**
		 * render_dashboard_banner.
		 *
		 * @version 1.0.8
		 * @since   1.0.7
		 *
		 * @param $banners_arr
		 *
		 * @return string
		 */
		function render_dashboard_banner( $banners_arr, $add_close_button = true ) {
			$banners_html = '';
			if ( ! empty( $banners_arr ) ) {
				$banners_html .= '<div class="wrap">';
				foreach ( $banners_arr as $banner ) {
					$close_button = $add_close_button ? '<button type="button" aria-label="' . __( 'Close', 'wpfactory-cross-selling' ) . '" class="wpfcs-dashboard-banner-close-btn"><div class="dashicons-before dashicons-no"></div></button>' : '';
					$banners_html .= '<div class="wpfcs-dashboard-banner"><div class="wpfcs-dashboard-banner-inner">' . wp_kses_post( $this->force_target_blank_on_html( $banner ) ) . $close_button . '</div></div>';
					break;
				}
				$banners_html .= '</div>';
			}

			return $banners_html;
		}

		/**
		 * dashboard_banner_should_remain_closed.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return bool
		 */
		function dashboard_banner_should_remain_closed() {
			if ( $this->is_recommendations_page() ) {
				return false;
			}

			$user_id    = get_current_user_id();
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();

			if ( ! $user_id ) {
				return false;
			}

			$closed_at = (int) get_user_meta( $user_id, 'wpfcs_dashboard_banner_closed_time', true );

			if ( ! $closed_at ) {
				return false;
			}

			return ( time() - $closed_at ) < $setup_args['banners']['banner_dismiss_duration'];
		}

		/**
		 * close_dashboard_banner_ajax_action.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return void
		 */
		function close_dashboard_banner_ajax_action() {
			if ( false !== check_ajax_referer( 'wpfcs-get-dashboard-banner', 'banner_nonce' ) ) {
				update_user_meta( get_current_user_id(), 'wpfcs_dashboard_banner_closed_time', time() );
				wp_send_json_success();
			}
		}

		/**
		 * can_display_banner_at_this_location.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return false|void
		 */
		function can_display_banner_at_current_location() {
			return true;
		}

		/**
		 * is_recommendations_page.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return bool
		 */
		function is_recommendations_page() {
			return is_admin() && filter_input( INPUT_GET, 'page' ) === 'wpfactory-cross-selling';
		}

		/**
		 * get_dashboard_banner_js.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return false|string
		 */
		function get_dashboard_banner_js() {
			ob_start();
			$php_to_js = array(
				'banner_nonce'            => wp_create_nonce( 'wpfcs-get-dashboard-banner' ),
				'get_banner_action'       => $this->get_dashboard_banner_ajax_action,
				'close_banner_action'     => $this->close_dashboard_banner_ajax_action,
				'banner_wrapper_selector' => '.wpfcs-dashboard-banner-wrapper',
				'close_button_selector'   => '.wpfcs-dashboard-banner-close-btn',
				'is_recommendations_page' => $this->is_recommendations_page()
			);
			?>
			<script>
				// Gets banner.
				jQuery( function ( $ ) {
					let dataFromPHP = <?php echo wp_json_encode( $php_to_js );?>;
					dataFromPHP.action = dataFromPHP.get_banner_action;
					$.post( ajaxurl, dataFromPHP ).done( res => {
						if ( res.data.banner_data?.trim() ) {
							jQuery( dataFromPHP.banner_wrapper_selector ).html( res.data.banner_data )
						}
					} );
				} );

				// Closes banner.
				jQuery( function ( $ ) {
					let dataFromPHP = <?php echo wp_json_encode( $php_to_js );?>;
					dataFromPHP.action = dataFromPHP.close_banner_action;
					$( document ).on( 'click', dataFromPHP.close_button_selector, function () {
						$( dataFromPHP.banner_wrapper_selector ).fadeOut( 300, function () {
							$( this ).remove();
						} );
						$.post( ajaxurl, dataFromPHP );
					} );
				} );
			</script>
			<?php
			return ob_get_clean();
		}

		/**
		 * get_products.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @return array[]
		 */
		function get_recommendation_tab_banners() {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();
			$banners    = $this->get_banners_from_advanced_ads_group( $setup_args['banners']['advanced_ads_setup']['recommendations_group_name'] );

			return $banners;
		}

		/**
		 * render_recommendation_tab_banners.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @param $banners
		 *
		 * @return false|string
		 */
		function render_recommendation_tab_banners( $banners ) {
			if ( empty( $banners ) || ! is_array( $banners ) ) {
				return '';
			}
			ob_start();
			?>
			<?php foreach ( $banners as $banner ): ?>
				<div class="wpfcs-banner">
					<?php echo wp_kses_post( $this->force_target_blank_on_html( $banner ) ); ?>
				</div>
			<?php endforeach; ?>
			<?php
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

		/**
		 * force_target_blank_on_html.
		 *
		 * @version 1.0.7
		 * @since   1.0.7
		 *
		 * @param $raw_string
		 *
		 * @return false|string
		 */
		function force_target_blank_on_html( $raw_string ) {
			$html = html_entity_decode( $raw_string, ENT_QUOTES, 'UTF-8' );
			libxml_use_internal_errors( true );

			$dom = new \DOMDocument();
			$dom->loadHTML( $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

			foreach ( $dom->getElementsByTagName( 'a' ) as $a ) {
				$a->setAttribute( 'target', '_blank' );
				$a->setAttribute( 'rel', 'noopener noreferrer' );
			}

			$html = $dom->saveHTML();

			return $html;
		}

	}
}