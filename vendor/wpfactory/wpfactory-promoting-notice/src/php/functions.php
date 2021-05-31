<?php
/**
 * WPFactory Promoting Notice - Functions.
 *
 * @version 1.0.1
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! function_exists( 'wpfactory_promoting_notice' ) ) {
	/**
	 * wpfactory_promoting_notice.
	 *
	 * @version 1.0.1
	 * @since   1.0.0
	 *
	 * @return \WPFactory\Promoting_Notice\Core
	 */
	function wpfactory_promoting_notice() {
		return new \WPFactory\Promoting_Notice\Core();
	}
}