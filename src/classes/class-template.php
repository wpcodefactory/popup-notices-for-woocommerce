<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Template Class
 *
 * @version 1.1.0
 * @since   1.1.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Template' ) ) {

	class Template {
		public function get_default_template() {
			return '<div class="ttt-pnwc-modal micromodal-slide" id="ttt-pnwc-notice" aria-hidden="true">
	<div class="ttt-pnwc-overlay" tabindex="-1" data-micromodal-close>
		<div class="ttt-pnwc-container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
			<div class="ttt-pnwc-wrapper">
				<header class="ttt-pnwc-header">{header_content}</header>
				<div class="ttt-pnwc-content" id="modal-1-content" data-content="true"></div>
				<footer class="ttt-pnwc-footer">{footer_content}</footer>
			</div>
		</div>
	</div>
</div>';
		}

		public function replace_template_variables( $template ) {
			$template = str_replace( "{header_content}", apply_filters( 'ttt_pnwc_header_content', '<button class="ttt-pnwc-close" aria-label="Close modal" data-micromodal-close></button>' ), $template );
			$template = str_replace( "{footer_content}", apply_filters( 'ttt_pnwc_footer_content', '' ), $template );
			//error_log(print_r($template,true));
			return $template;
		}
	}
}
