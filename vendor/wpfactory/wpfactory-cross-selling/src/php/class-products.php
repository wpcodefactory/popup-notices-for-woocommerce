<?php
/**
 * WPFactory Cross-Selling - Products
 *
 * @version 1.0.4
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\Products' ) ) {

	/**
	 * Products.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class Products {

		/**
		 * Products.
		 *
		 * @since   1.0.0
		 *
		 * @var array
		 */
		protected $products = array();

		/**
		 * get_products.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @return array[]
		 */
		function get_products() {
			$this->products = array(
				array(
					'name'                         => __( 'EAN Barcode Generator for WooCommerce: UPC, ISBN & GTIN Inventory', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Make Inventory Control a Breeze and Manage Your Products Seamlessly.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'admin-&-reporting',
					'icon_url'                     => 'https://ps.w.org/ean-for-woocommerce/assets/icon.svg',
					'tag_slug'                     => '',
					'free_plugin_path'             => 'ean-for-woocommerce/ean-for-woocommerce.php',
					'free_plugin_slug'             => 'ean-for-woocommerce',
					'pro_plugin_path'              => 'ean-for-woocommerce-pro/ean-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/ean-barcodes-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Wishlist for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Encourage More Purchases by Offering Easy Multi-Wishlist Creation and Sharing Features.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'tag_slug'                     => 'marketing',
					'icon_url'                     => 'https://ps.w.org/wish-list-for-woocommerce/assets/icon.svg?rev=3078494',
					'free_plugin_path'             => 'wish-list-for-woocommerce/wish-list-for-woocommerce.php',
					'free_plugin_slug'             => 'wish-list-for-woocommerce',
					'pro_plugin_path'              => 'wish-list-for-woocommerce-pro/wish-list-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/wish-list-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Min Max Step Quantity Limits Manager for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Control Product Quantities and make shopping perfectly tailored to your store\'s needs.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'orders-restrictions',
					'tag_slug'                     => 'top-picks',
					'icon_url'                     => 'https://ps.w.org/product-quantity-for-woocommerce/assets/icon.svg?rev=2970983',
					'free_plugin_path'             => 'product-quantity-for-woocommerce/product-quantity-for-woocommerce.php',
					'free_plugin_slug'             => 'product-quantity-for-woocommerce',
					'pro_plugin_path'              => 'product-quantity-for-woocommerce-pro/product-quantity-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/product-quantity-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Cost of Goods: Product Cost & Profit Calculator for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Understand your profits by accurately tracking costs. Make smarter decisions for your business and maximize your store\'s profitability with ease.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'admin-&-reporting',
					'tag_slug'                     => 'top-picks',
					'icon_url'                     => 'https://ps.w.org/cost-of-goods-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'cost-of-goods-for-woocommerce/cost-of-goods-for-woocommerce.php',
					'free_plugin_slug'             => 'cost-of-goods-for-woocommerce',
					'pro_plugin_path'              => 'cost-of-goods-for-woocommerce-pro/cost-of-goods-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/cost-of-goods-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Maximum Products per User for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Set maximum quantities based on your store\'s needs. Keep things fair, control stock, and manage sales your way!', 'wpfactory-cross-selling' ),
					'category_slug'                => 'orders-restrictions',
					'tag_slug'                     => 'must-have',
					'icon_url'                     => 'https://ps.w.org/maximum-products-per-user-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'maximum-products-per-user-for-woocommerce/maximum-products-per-user-for-woocommerce.php',
					'free_plugin_slug'             => 'maximum-products-per-user-for-woocommerce',
					'pro_plugin_path'              => 'maximum-products-per-user-for-woocommerce-pro/maximum-products-per-user-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/maximum-products-per-user-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Order Minimum/Maximum Amount Limits for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Control every order with customizable limits to optimize your sales strategy.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'orders-restrictions',
					'tag_slug'                     => 'top-picks',
					'icon_url'                     => 'https://ps.w.org/order-minimum-amount-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'order-minimum-amount-for-woocommerce/order-minimum-amount-for-woocommerce.php',
					'free_plugin_slug'             => 'order-minimum-amount-for-woocommerce',
					'pro_plugin_path'              => 'order-minimum-amount-for-woocommerce-pro/order-minimum-amount-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/order-minimum-maximum-amount-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'EU VAT Manager for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Validate VAT Numbers Automatically and Stay Compliant Across Europe. ensuring your customers have a seamless experience while you handle VAT like a pro.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'admin-&-reporting',
					'tag_slug'                     => 'admin-tools',
					'icon_url'                     => 'https://ps.w.org/eu-vat-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'eu-vat-for-woocommerce/eu-vat-for-woocommerce.php',
					'free_plugin_slug'             => 'eu-vat-for-woocommerce',
					'pro_plugin_path'              => 'eu-vat-for-woocommerce-pro/eu-vat-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/eu-vat-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Email Verification for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Secure Your WooCommerce Store by preventing fake accounts, and ensuring real customers with user-friendly email verification.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'admin-&-reporting',
					'tag_slug'                     => 'admin-tools',
					'icon_url'                     => 'https://ps.w.org/emails-verification-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'emails-verification-for-woocommerce/email-verification-for-woocommerce.php',
					'free_plugin_slug'             => 'emails-verification-for-woocommerce',
					'pro_plugin_path'              => 'email-verification-for-woocommerce-pro/email-verification-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/email-verification-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Additional Custom Emails & Recipients for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Level Up Your Store’s Communication and Boost Customer Satisfaction with Custom Email Notifications for WooCommerce.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'admin-&-reporting',
					'tag_slug'                     => 'admin-tools',
					'icon_url'                     => 'https://ps.w.org/custom-emails-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'custom-emails-for-woocommerce/custom-emails-for-woocommerce.php',
					'free_plugin_slug'             => 'custom-emails-for-woocommerce',
					'pro_plugin_path'              => 'custom-emails-for-woocommerce-pro/custom-emails-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/custom-emails-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory',
					'show_on_recommendations_page' => false,
				),
				array(
					'name'                         => __( 'Free Shipping Over Amount: Amount Left Tracker for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Unlock Higher Sales with Free Shipping Incentives.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'tag_slug'                     => 'admin-tools',
					'icon_url'                     => 'https://ps.w.org/amount-left-free-shipping-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'amount-left-free-shipping-woocommerce/left-to-free-shipping-for-woocommerce.php',
					'free_plugin_slug'             => 'amount-left-free-shipping-woocommerce',
					'pro_plugin_path'              => 'left-to-free-shipping-for-woocommerce-pro/left-to-free-shipping-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/amount-left-free-shipping-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Payment Methods by Product & Country for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Control payment methods to keep higher profit, boost conversions, and offer a better checkout experience.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'orders-restrictions',
					'icon_url'                     => 'https://ps.w.org/payment-gateways-per-product-categories-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'payment-gateways-per-product-categories-for-woocommerce/payment-gateways-per-product-for-woocommerce.php',
					'free_plugin_slug'             => 'payment-gateways-per-product-categories-for-woocommerce',
					'pro_plugin_path'              => 'payment-gateways-per-product-for-woocommerce-pro/payment-gateways-per-product-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Product XML Feeds for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Create unlimited product XML feeds using this feature-rich plugin, enabling you to generate, customize, and manage XML feeds based on merchant needs. Compatible with various platforms.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'admin-&-reporting',
					'icon_url'                     => 'https://ps.w.org/product-xml-feeds-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'product-xml-feeds-for-woocommerce/product-xml-feeds-for-woocommerce.php.php',
					'free_plugin_slug'             => 'product-xml-feeds-for-woocommerce',
					'pro_plugin_path'              => 'product-xml-feeds-for-woocommerce-pro/product-xml-feeds-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/product-xml-feeds-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Popups for WooCommerce: Cart, Add to Cart, Checkout Notices to Popups', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Capture customer attention with eye-catching, customizable popups messages.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'icon_url'                     => 'https://ps.w.org/popup-notices-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'popup-notices-for-woocommerce/popup-notices-for-woocommerce.php',
					'free_plugin_slug'             => 'popup-notices-for-woocommerce',
					'pro_plugin_path'              => 'popup-notices-for-woocommerce-pro/popup-notices-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/popup-notices-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'MSRP (RRP) Pricing for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Encourage Purchases by Displaying MSRP and Proving Your Prices Beat the Market.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'tag_slug'                     => 'marketing',
					'icon_url'                     => 'https://ps.w.org/msrp-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'msrp-for-woocommerce/msrp-for-woocommerce.php',
					'free_plugin_slug'             => 'msrp-for-woocommerce',
					'pro_plugin_path'              => 'msrp-for-woocommerce-pro/msrp-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/msrp-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'File Renaming on Upload – WordPress Plugin', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Elevate your WP media management with "Rename Media Files on Upload for WordPress" plugin.  Automatically rename media images & files based on rules, sanitizes filenames, and enriches SEO through smart naming conventions.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'wordpress-utilities',
					'tag_slug'                     => 'wp-utilities',
					'icon_url'                     => 'https://ps.w.org/file-renaming-on-upload/assets/icon.svg',
					'free_plugin_path'             => 'file-renaming-on-upload/file-renaming-on-upload.php',
					'free_plugin_slug'             => 'file-renaming-on-upload',
					'pro_plugin_path'              => 'file-renaming-on-upload-pro/file-renaming-on-upload-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/file-renaming-on-upload-wordpress-plugin/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Coupons & Add to Cart by URL for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Simplify Shopping with One-Click Coupons and Turn Links into Sales!', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'icon_url'                     => 'https://ps.w.org/url-coupons-for-woocommerce-by-algoritmika/assets/icon.svg',
					'free_plugin_path'             => 'url-coupons-for-woocommerce-by-algoritmika/url-coupons-woocommerce.php',
					'free_plugin_slug'             => 'url-coupons-for-woocommerce-by-algoritmika',
					'pro_plugin_path'              => 'url-coupons-woocommerce-pro/url-coupons-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/url-coupons-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Price by Quantity & Bulk Quantity Discounts for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Boost Larger Orders and Maximize Revenue with Dynamic Pricing.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'tag_slug'                     => 'marketing',
					'icon_url'                     => 'https://ps.w.org/wholesale-pricing-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'wholesale-pricing-woocommerce/wholesale-pricing-woocommerce.php',
					'free_plugin_slug'             => 'wholesale-pricing-woocommerce',
					'pro_plugin_path'              => 'wholesale-pricing-woocommerce-pro/wholesale-pricing-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/product-price-by-quantity-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Download Plugins and Themes from Dashboard', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Download your WordPress plugins and themes in ZIP files directly from admin dashboard, get any or all plugins & themes without FTP or cPanel access in a single click.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'wordpress-utilities',
					'tag_slug'                     => 'wp-utilities',
					'icon_url'                     => 'https://ps.w.org/download-plugins-dashboard/assets/icon.svg',
					'free_plugin_path'             => 'download-plugins-dashboard/download-plugins-from-dashboard.php',
					'free_plugin_slug'             => 'download-plugins-dashboard',
					'pro_plugin_path'              => 'download-plugins-from-dashboard-pro/download-plugins-from-dashboard-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/download-plugins-and-themes-from-dashboard/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Back Button Widget - WordPress Plugin', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Simplify navigation on your WordPress site with the "Back Button Widget" plugin, a light-weight & user-friendly tool to show a "Back" button anywhere on your website.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'wordpress-utilities',
					'icon_url'                     => 'https://ps.w.org/back-button-widget/assets/icon.svg',
					'free_plugin_path'             => 'back-button-widget/back-button-widget.php',
					'free_plugin_slug'             => 'back-button-widget',
					'pro_plugin_path'              => 'back-button-widget-pro/back-button-widget-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/back-button-widget-wordpress-plugin/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Slugs Manager: Delete Old Permalinks ', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Optimize Your Site Performance by Cleaning Up Old and Unused Permalinks Effortlessly.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'wordpress-utilities',
					'tag_slug'                     => 'wp-utilities',
					'icon_url'                     => 'https://ps.w.org/remove-old-slugspermalinks/assets/icon.svg',
					'free_plugin_path'             => 'remove-old-slugspermalinks/remove-old-slugs.php',
					'free_plugin_slug'             => 'remove-old-slugspermalinks',
					'pro_plugin_path'              => 'remove-old-slugs-pro/remove-old-slugs-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/slugs-manager-wordpress-plugin/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Name Your Price: Make Your Own Offer for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'A great way to engage shoppers and drive sales through customer-driven pricing.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'tag_slug'                     => 'marketing',
					'icon_url'                     => 'https://ps.w.org/price-offerings-for-woocommerce/assets/icon.svg',
					'free_plugin_path'             => 'price-offerings-for-woocommerce/price-offerings-for-woocommerce.php',
					'free_plugin_slug'             => 'price-offerings-for-woocommerce',
					'pro_plugin_path'              => 'price-offerings-for-woocommerce-pro/price-offerings-for-woocommerce-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/price-offers-for-woocommerce/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
				array(
					'name'                         => __( 'Remove Special Characters From Permalinks (URLs) for WordPress', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'A simple way to remove special characters from permalinks.', 'wpfactory-cross-selling' ),
					'category_slug'                => 'wordpress-utilities',
					'tag_slug'                     => 'wp-utilities',
					'icon_url'                     => 'https://ps.w.org/remove-special-characters-from-permalinks/assets/icon.svg',
					'free_plugin_path'             => 'remove-special-characters-from-permalinks/remove-special-characters-from-permalinks.php',
					'free_plugin_slug'             => 'remove-special-characters-from-permalinks',
					'pro_plugin_path'              => 'remove-special-characters-from-permalinks-pro/remove-special-characters-from-permalinks-pro.php',
					'pro_plugin_url'               => 'https://wpfactory.com/item/remove-special-characters-from-permalinks-wordpress-plugin/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory',
				),
				array(
					'name'                         => __( 'Product Filters for WooCommerce', 'wpfactory-cross-selling' ),
					'desc'                         => __( 'Filter products by price, categories, tags, and attributes, and enable or disable AJAX search. Take advantage of extensive sorting options and precisely adjust the price range', 'wpfactory-cross-selling' ),
					'category_slug'                => 'marketing-&-promotion',
					'icon_url'                     => 'https://ps.w.org/woo-product-filter/assets/icon-256x256.png',
					'tag_slug'                     => 'top-picks',
					'free_plugin_path'             => 'woo-product-filter/woo-product-filter.php',
					'free_plugin_slug'             => 'woo-product-filter',
					'pro_plugin_path'              => 'woo-filter-pro/woofilter-pro.php',
					'pro_plugin_url'               => 'https://woobewoo.com/product/woocommerce-filter/?utm_source=plugin&utm_medium=cross-selling&utm_campaign=wpfactory'
				),
			);

			return $this->products;
		}
	}
}