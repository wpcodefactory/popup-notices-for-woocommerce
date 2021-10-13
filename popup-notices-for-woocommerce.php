<?php
/**
 * Plugin Name: Pop-up Notices for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/popup-notices-for-woocommerce
 * Description: Turn your WooCommerce Notices into Popups
 * Version: 1.3.5
 * Author: Thanks to IT
 * Author URI: https://github.com/thanks-to-it
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: popup-notices-for-woocommerce
 * Domain Path: /src/languages
 * WC requires at least: 3.0.0
 * WC tested up to: 5.8
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
    return;
}

// Composer.
if ( ! class_exists( '\ThanksToIT\PNWC\Core' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}

// Autoloader.
$autoloader = new WPFactory\WPFactory_Autoloader\WPFactory_Autoloader();
$autoloader->add_namespace( 'ThanksToIT\PNWC', plugin_dir_path( __FILE__ ) . '/src/php' );
do_action( 'pnwc_autoloader', $autoloader );
$autoloader->init();

// Starts plugin.
$plugin = \ThanksToIT\PNWC\Core::instance();
$plugin->setup( array(
    'path' => __FILE__
) );
if ( true === apply_filters( 'ttt_pnwc_init', true ) ) {
    $plugin->init();
}