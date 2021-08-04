<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Notices
 *
 * @version 1.3.2
 * @since   1.0.2
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Notices' ) ) {

	class Notices {

		/**
		 * @version 1.2.8
		 * @since   1.0.2
		 */
		public function init() {
			add_action( 'admin_init', array( $this, 'handle_notices' ) );
		}

		/**
		 * @version 1.3.2
		 * @since   1.2.8
		 */
		function handle_notices() {
			$promoting_notice = wpfactory_promoting_notice();
			$promoting_notice->set_args( array(
				'enable'                        => apply_filters( 'ttt_pnwc_license_data', true ),
				'template_variables'            => array(
					'%pro_version_url%'    => 'https://wpfactory.com/item/popup-notices-for-woocommerce/',
					'%plugin_icon_url%'    => 'https://ps.w.org/popup-notices-for-woocommerce/assets/icon-128x128.png?rev=1884298',
					'%pro_version_title%'  => __( 'Pop-up Notices for WooCommerce Pro', 'popup-notices-for-woocommerce' ),
					'%plugin_icon_style%'  => 'width:35px;margin-right:10px;vertical-align:middle',
					'%main_text%'          => __( 'Disabled options can be unlocked using <a href="%pro_version_url%" target="_blank"><strong>%pro_version_title%</strong></a>', 'popup-notices-for-woocommerce' ),
					'%btn_call_to_action%' => __( 'Upgrade to Pro version', 'popup-notices-for-woocommerce' ),
				),
				'url_requirements'              => array(
					'page_filename' => 'admin.php',
					'params'        => array( 'page' => 'wc-settings', 'tab' => 'ttt-pnwc' ),
				),
				'optimize_plugin_icon_contrast' => true
			) );
			$promoting_notice->init();
		}
	}
}