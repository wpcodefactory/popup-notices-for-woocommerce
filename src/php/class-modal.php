<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Modal
 *
 * @version 1.2.7
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Modal' ) ) {

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
				$template = preg_replace('/(\<div.+class\=\"ttt-pnwc-overlay\")(.+)?(\sdata-micromodal-close)/', '$1$2', $template);
			}
			return $template;
		}

		public function add_audio_html() {
			if ( 'yes' !== get_option( 'ttt_pnwc_opt_audio_enable','no' ) ) {
				return;
			}
			$plugin = \ThanksToIT\PNWC\Core::instance();
			echo '<iframe src="' . $plugin->get_plugin_url() . 'src/assets/dist/frontend/audio/silence.mp3' . '" allow="autoplay" id="ttt-pnwc-audio" style="display:none"></iframe>';
		}

		/*public function replace_template_variables( $template ) {
			$template_obj     = new Template();
			$default_template = $template_obj->replace_template_variables( $template );
			return $default_template;
		}*/

		/**
		 * Adds modal scripts
		 *
		 * @version 1.2.5
		 * @since   1.0.0
		 */
		public function add_modal_scripts() {
			if ( ! Restrictive_Loading::is_allowed_to_load() ) {
				return;
			}
			$plugin                    = \ThanksToIT\PNWC\Core::instance();
			$micromodal_loading_method = get_option( 'ttt_pnwc_opt_micromodal_load_method', 'externally' );

			if ( 'externally' == $micromodal_loading_method ) {
				$path = $plugin->plugin_info['path'];
				wp_register_script( 'ttt_pnwc_micromodal', 'https://unpkg.com/micromodal/dist/micromodal.min.js', array( 'jquery' ), false, true );
				wp_enqueue_script( 'ttt_pnwc_micromodal' );
			} elseif ( 'locally' == $micromodal_loading_method ) {
				$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				$plugin_url = $plugin->get_plugin_url();
				$plugin_dir = $plugin->get_plugin_dir();
				$js_file = 'src/assets/dist/frontend/js/vendor/micromodal' . $suffix . '.js';
				$js_ver  = date( "ymd-Gis", filemtime( $plugin_dir . $js_file ) );
				wp_register_script( 'ttt_pnwc_micromodal', $plugin_url . $js_file, array( 'jquery' ), $js_ver, true );
				wp_enqueue_script( 'ttt_pnwc_micromodal' );
			}
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