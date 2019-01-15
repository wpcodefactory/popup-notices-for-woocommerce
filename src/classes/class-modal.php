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
		}

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
			$default_template =
				'<div class="ttt-pnwc-modal micromodal-slide" id="ttt-pnwc-notice" aria-hidden="true">
	<div class="ttt-pnwc-overlay" tabindex="-1" data-micromodal-close>
		<div class="ttt-pnwc-container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
			<div class="ttt-pnwc-wrapper">
				<header class="ttt-pnwc-header">
					<button class="ttt-pnwc-close" aria-label="Close modal" data-micromodal-close></button>
				</header>
				<div class="ttt-pnwc-content" id="modal-1-content" data-content="true"></div>
			</div>
		</div>
	</div>
</div>';
			$template         = apply_filters( 'ttt_pnwc_modal_template', $default_template );
			echo $template;
		}
	}
}