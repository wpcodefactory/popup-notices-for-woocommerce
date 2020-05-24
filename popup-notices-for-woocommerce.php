<?php
/**
 * Plugin Name: Pop-up Notices for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/popup-notices-for-woocommerce
 * Description: Turn your WooCommerce Notices into Popups
 * Version: 1.2.0
 * Author: Thanks to IT
 * Author URI: https://github.com/thanks-to-it
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: popup-notices-for-woocommerce
 * Domain Path: /src/languages
 * WC requires at least: 3.0.0
 * WC tested up to: 4.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

require_once "vendor/autoload.php";

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	return;
}

$plugin = \ThanksToIT\PNWC\Core::instance();
$plugin->setup( array(
	'path' => __FILE__
) );
if ( true === apply_filters( 'ttt_pnwc_init', true ) ) {
	$plugin->init();
}