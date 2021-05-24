<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Notices
 *
 * @version 1.2.3
 * @since   1.0.2
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Notices' ) ) {

	class Notices {
		public function init() {
			require_once( WP_PLUGIN_DIR . '/wpf-promoting-notice/vendor/autoload.php' );
			$promoting_notice = new \WPFactory\Promoting_Notice\Core();
			$promoting_notice->set_args( array(
				'enable'                 => apply_filters( 'ttt_pnwc_license_data', true ),
				'template_variables'     => array(
					'%pro_version_url%'   => 'https://wpfactory.com/item/popup-notices-for-woocommerce/',
					'%plugin_icon_url%'   => 'https://ps.w.org/popup-notices-for-woocommerce/assets/icon-128x128.png?rev=1884298',
					'%pro_version_title%' => __( 'Pop-up Notices for WooCommerce Pro', 'popup-notices-for-woocommerce' ),
				),
				'woocommerce_section_id' => 'ttt-pnwc',
			) );
			$promoting_notice->init();
		}
	}
}