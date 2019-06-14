<?php
/**
 * Popup Notices for WooCommerce (TTT) - Restrictive Loading Class
 *
 * @version 1.1.0
 * @since   1.1.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Restrictive_Loading' ) ) {
	class Restrictive_Loading {
		public static function is_current_page_allowed() {
			$pages_allowed = get_option( 'ttt_pnwc_opt_restrictive_loading_pages' );
			$allowed       = false;
			if ( empty( $pages_allowed ) ) {
				$allowed = true;
			} else {
				if ( is_page( $pages_allowed ) ) {
					$allowed = true;
				}
			}
			return apply_filters( 'ttt_pnwc_current_page_allowed', $allowed, $pages_allowed );
		}
	}
}