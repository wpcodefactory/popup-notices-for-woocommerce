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

// Handle is_plugin_active function.
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Check for active plugins.
if (
	! is_plugin_active( 'woocommerce/woocommerce.php' ) ||
	( 'popup-notices-for-woocommerce.php' === basename( __FILE__ ) && is_plugin_active( 'popup-notices-for-woocommerce-pro/popup-notices-for-woocommerce-pro.php' ) )
) {
	return;
}

// Composer.
if ( ! class_exists( '\ThanksToIT\PNWC\Core' ) ) :
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
endif;

// Autoloader.
$autoloader = new WPFactory\WPFactory_Autoloader\WPFactory_Autoloader();
$autoloader->add_namespace( 'ThanksToIT\PNWC', plugin_dir_path( __FILE__ ) . '/src/php' );
do_action( 'pnwc_autoloader', $autoloader );
$autoloader->init();

$plugin = \ThanksToIT\PNWC\Core::instance();
$plugin->setup( array(
	'path' => __FILE__
) );
if ( true === apply_filters( 'ttt_pnwc_init', true ) ) {
	$plugin->init();
}