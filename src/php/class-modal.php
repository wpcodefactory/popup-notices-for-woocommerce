<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Modal
 *
 * @version 1.5.1
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\PNWC\Modal' ) ) {

	class Modal {

		/**
		 * Initializes
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function init() {
			add_action( 'wp_footer', array( $this, 'add_modal_html' ) );
			add_action( 'wp_footer', array( $this, 'add_audio_html' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_modal_scripts' ) );
			add_filter( 'ttt_pnwc_modal_template', array( $this, 'handle_outside_click' ), 20 );
			//add_filter( 'ttt_pnwc_modal_template', array( $this, 'replace_template_variables' ) );
		}

		/**
		 * handle_outside_click.
		 *
		 * @version 1.2.7
		 * @since   1.2.7
		 *
		 * @param $template
		 *
		 * @return null|string|string[]
		 */
		function handle_outside_click( $template ) {
			if (
				'yes' === get_option( 'ttt_pnwc_opt_prevent_closing_if_clicking_out', 'no' )
				&& preg_match( '/\<div.+class\=\"ttt-pnwc-overlay\".+\>/', $template, $output_array )
			) {
				$template = preg_replace( '/(\<div.+class\=\"ttt-pnwc-overlay\")(.+)?(\sdata-micromodal-close)/', '$1$2', $template );
			}

			return $template;
		}

		/**
		 * add_audio_html.
		 *
		 * @version 1.4.4
		 * @since   1.0.0
		 *
		 * @return void
		 */
		public function add_audio_html() {
			if ( 'yes' !== get_option( 'ttt_pnwc_opt_audio_enable', 'no' ) ) {
				return;
			}
			$plugin = \WPFactory\PNWC\Core::instance();
			echo '<iframe src="' . $plugin->get_plugin_url() . 'assets/audio/silence.mp3' . '" allow="autoplay" id="ttt-pnwc-audio" style="display:none"></iframe>';
		}

		/**
		 * Adds modal scripts
		 *
		 * @version 1.5.1
		 * @since   1.0.0
		 */
		public function add_modal_scripts() {
			if ( ! Restrictive_Loading::is_allowed_to_load() ) {
				return;
			}
			$plugin                    = \WPFactory\PNWC\Core::instance();
			$micromodal_loading_method = get_option( 'ttt_pnwc_opt_micromodal_load_method', 'externally' );
			$micromodal_path           = false;
			$js_ver                    = null;

			switch ( $micromodal_loading_method ) {
				case 'externally':
					$micromodal_path = 'https://unpkg.com/micromodal/dist/micromodal.min.js';
					break;
				case 'externally_jsdelivr':
					$micromodal_path = 'https://fastly.jsdelivr.net/npm/micromodal/dist/micromodal.min.js';
					break;
				case 'locally':
					$suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
					$plugin_url      = $plugin->get_plugin_url();
					$plugin_dir      = $plugin->get_plugin_dir();
					$js_file         = 'micromodal' . $suffix . '.js';
					$js_ver          = date( "ymd-Gis", filemtime( $plugin_dir . 'assets/vendor/micromodal/' . $js_file ) );
					$micromodal_path = $plugin_url . 'assets/vendor/micromodal/' . $js_file;
					break;
			}

			wp_register_script( 'ttt_pnwc_micromodal', $micromodal_path, array( 'jquery' ), $js_ver, true );
			wp_enqueue_script( 'ttt_pnwc_micromodal' );
		}

		/**
		 * Adds modal html
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public function add_modal_html() {
			if ( ! Restrictive_Loading::is_allowed_to_load() ) {
				return;
			}
			$template_obj     = new Template();
			$default_template = $template_obj->get_default_template();
			$template         = apply_filters( 'ttt_pnwc_modal_template', $default_template );
			$template         = $template_obj->replace_template_variables( $template );
			echo wp_kses( $template, wp_kses_allowed_html( 'post' ) );
		}
	}
}