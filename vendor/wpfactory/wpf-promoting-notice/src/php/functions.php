<?php
/**
 * WPF Promoting Notice - Functions
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! function_exists( 'wpf_promoting_notice' ) ) {
	/**
	 * wpf_promoting_notice
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return \WPFactory\Promoting_Notice\Core
	 */
	function wpf_promoting_notice() {
		return new \WPFactory\Promoting_Notice\Core();
	}
}