<?php
/**
 * Plugin Name: Pop-up Notices for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/popup-notices-for-woocommerce
 * Description: Turn your WooCommerce Notices into Popups
 * Version: 1.3.2
 * Author: Thanks to IT
 * Author URI: https://github.com/thanks-to-it
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: popup-notices-for-woocommerce
 * Domain Path: /src/languages
 * WC requires at least: 3.0.0
 * WC tested up to: 5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Autoloader
$autoloader = new WPFactory\WPFactory_Autoloader\WPFactory_Autoloader();
$autoloader->add_namespace( 'ThanksToIT\PNWC', plugin_dir_path( __FILE__ ) . '/src/php' );
$autoloader->init();

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