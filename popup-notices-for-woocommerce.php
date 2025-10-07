<?php
/**
 * Plugin Name: Popups for WooCommerce: Add to Cart, Checkout & More
 * Plugin URI: https://wordpress.org/plugins/popup-notices-for-woocommerce
 * Description: Turn your WooCommerce Notices into Popups
 * Version: 1.5.3
 * Author: WPFactory
 * Author URI: https://wpfactory.com
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: popup-notices-for-woocommerce
 * Domain Path: /src/languages
 * WC requires at least: 3.0.0
 * WC tested up to: 10.2
 * Requires Plugins: woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

// Handle is_plugin_active function.
if ( ! function_exists( 'ttt_pnwc_is_plugin_active' ) ) {
	/**
	 * ttt_pnwc_is_plugin_active.
	 *
	 * @version 1.3.5
	 * @since   1.3.5
	 */
	function ttt_pnwc_is_plugin_active( $plugin ) {
		return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', ( array ) get_option( 'active_plugins', array() ) ) ) ||
				( is_multisite() && array_key_exists( $plugin, ( array ) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
		);
	}
}

// Check for active plugins.
if (
    ! ttt_pnwc_is_plugin_active( 'woocommerce/woocommerce.php' ) ||
    (
        'popup-notices-for-woocommerce.php' === basename( __FILE__ ) &&
        ttt_pnwc_is_plugin_active( 'popup-notices-for-woocommerce-pro/popup-notices-for-woocommerce-pro.php' ) &&
        ! empty( $wp_plugin_dir = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, trailingslashit( WP_PLUGIN_DIR ) ) ) &&
        ! empty( $plugin_parent_dir = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, trailingslashit( dirname( __FILE__, 2 ) ) ) ) &&
        $plugin_parent_dir === $wp_plugin_dir
    )
) {
	if ( class_exists( '\WPFactory\PNWC\Core' ) ) {
		$plugin = \WPFactory\PNWC\Core::instance();
		if ( method_exists( $plugin, 'set_free_version_filesystem_path' ) ) {
			$plugin->set_free_version_filesystem_path( __FILE__ );
		}
	}
    return;
}

// Composer.
if ( ! class_exists( '\WPFactory\PNWC\Core' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}

// Autoloader.
$autoloader = new WPFactory\WPFactory_Autoloader\WPFactory_Autoloader();
$autoloader->add_namespace( 'WPFactory\PNWC', plugin_dir_path( __FILE__ ) . '/src/php' );
do_action( 'pnwc_autoloader', $autoloader );
$autoloader->init();

// Starts plugin.
$plugin = \WPFactory\PNWC\Core::instance();
$plugin->setup( array(
    'path' => __FILE__
) );
if ( true === apply_filters( 'ttt_pnwc_init', true ) ) {
    $plugin->init();
}