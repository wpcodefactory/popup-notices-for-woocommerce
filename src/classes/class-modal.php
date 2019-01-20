<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Modal
 *
 * @version 1.1.0
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
			add_action( 'wp_enqueue_scripts', array( $this, 'add_modal_scripts' ) );
			//add_filter( 'ttt_pnwc_modal_template', array( $this, 'replace_template_variables' ) );
		}

		/*public function replace_template_variables( $template ) {
			$template_obj     = new Template();
			$default_template = $template_obj->replace_template_variables( $template );
			return $default_template;
		}*/

		/**
		 * Adds modal scripts
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_modal_scripts() {
			$plugin = \ThanksToIT\PNWC\Core::instance();
			$path   = $plugin->plugin_info['path'];
			wp_enqueue_script( 'ttt_pnwc_micromodal', '//unpkg.com/micromodal/dist/micromodal.min.js' );
		}

		/**
		 * Adds modal html
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public function add_modal_html() {
			$template_obj     = new Template();
			$default_template = $template_obj->get_default_template();
			$template         = apply_filters( 'ttt_pnwc_modal_template', $default_template );
			$template         = $template_obj->replace_template_variables( $template );
			echo $template;
		}
	}
}