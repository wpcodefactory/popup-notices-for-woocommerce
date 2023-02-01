<?php
/**
 * Popup Notices for WooCommerce (TTT) - Restrictive Loading Class
 *
 * @version 1.1.5
 * @since   1.1.5
 * @author  WPFactory
 */

namespace WPFactory\PNWC;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\PNWC\Restrictive_Loading' ) ) {
	class Restrictive_Loading {
		public static function is_allowed_to_load() {
			return apply_filters( 'ttt_pnwc_is_allowed_to_load', true );
		}
	}
}