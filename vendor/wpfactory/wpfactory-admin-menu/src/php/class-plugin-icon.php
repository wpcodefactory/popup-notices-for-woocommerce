<?php
/**
 * WPFactory Admin Menu - Plugin Icon.
 *
 * @version 1.0.6
 * @since   1.0.6
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Admin_Menu;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory\WPFactory_Admin_Menu\Plugin_Icon' ) ) {

	/**
	 * WPFactory Admin Menu.
	 *
	 * @version 1.0.6
	 * @since   1.0.6
	 */
	class Plugin_Icon {

		/**
		 * $args.
		 *
		 * @since 1.0.6
		 *
		 * @var array
		 */
		protected $args = array();

		/**
		 * get_plugin_icon_img_html.
		 *
		 * @version 1.0.6
		 * @since   1.0.6
		 *
		 * @return string
		 */
		function get_plugin_icon_img_html() {
			$args = $this->get_args();

			// Icon URL.
			if ( 'manual' === $args['get_url_method'] ) {
				$icon_url = $args['url'] ?? '';
			} elseif (
				'wporg_plugins_api' === $args['get_url_method'] &&
				! empty( $args['wporg_plugin_slug'] )
			) {
				$icon_url = $this->get_wporg_plugin_icon_url( $args['wporg_plugin_slug'] );
			}

			// Icon attributes.
			$width       = $args['width'] ?? '';
			$width_html  = ! empty( $width ) ? 'width="' . esc_attr( $width ) . '"' : '';
			$height      = $args['height'] ?? '';
			$height_html = ! empty( $height ) ? 'height="' . esc_attr( $height ) . '"' : '';
			$style       = $args['style'] ?? '';
			$style_html  = ! empty( $style ) ? 'style="' . esc_attr( $style ) . '"' : '';

			// Icon HTML.
			$icon_html = ! empty( $icon_url ) ? '<img ' . $style_html . ' class="wpfam-plugin-icon" src="' . esc_url( $icon_url ) . '" ' . $width_html . $height_html . '>' : '';

			return $icon_html;
		}

		/**
		 * get_args.
		 *
		 * @version 1.0.6
		 * @since   1.0.6
		 *
		 * @return array
		 */
		public function get_args(): array {
			return $this->args;
		}

		/**
		 * set_args.
		 *
		 * @version 1.0.6
		 * @since   1.0.6
		 *
		 * @param array $args
		 */
		public function set_args( $args = null ) {
			$this->args = wp_parse_args( $args, array(
				'url'               => '',
				'width'             => '',
				'height'            => '',
				'style'             => '',
				'get_url_method'    => 'manual', // wporg_plugins_api || manual
				'wporg_plugin_slug' => ''
			) );
			$this->args = $args;
		}

		/**
		 * get_wporg_plugin_icon_url.
		 *
		 * @version 1.0.6
		 * @since   1.0.6
		 *
		 * @param $plugin_slug
		 *
		 * @return mixed|string
		 */
		function get_wporg_plugin_icon_url( $plugin_slug ) {
			if ( ! function_exists( 'plugins_api' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}
			$transient_key = 'wpfam_plugin_icon_url_' . sanitize_key( $plugin_slug );
			$cached_url    = get_transient( $transient_key );

			if ( $cached_url !== false ) {
				return $cached_url;
			}

			// Fetch plugin info from WP.org
			$info = plugins_api( 'plugin_information', array(
				'slug'   => $plugin_slug,
				'fields' => array(
					'icons' => true,
				),
			) );

			if ( is_wp_error( $info ) || empty( $info->icons ) ) {
				return '';
			}

			$icon_url = $info->icons['svg'] ?? $info->icons['2x'] ?? $info->icons['1x'] ?? '';

			set_transient( $transient_key, $icon_url, MONTH_IN_SECONDS );

			return $icon_url;
		}
	}
}