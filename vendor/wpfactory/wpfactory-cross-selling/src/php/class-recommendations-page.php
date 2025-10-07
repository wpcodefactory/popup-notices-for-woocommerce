<?php
/**
 * WPFactory Cross-Selling - Recommendations Page.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

use WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\Recommendations_Page' ) ) {

	/**
	 * WPF_Cross_Selling.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class Recommendations_Page {

		/**
		 * Recommendation submenu initialized.
		 *
		 * @since   1.0.0
		 *
		 * @var bool
		 */
		protected static $recommendations_submenu_initialized = false;

		/**
		 * Recommendations page slug.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		protected $recommendations_page_slug = 'wpfactory-cross-selling';

		/**
		 * WPFactory_Cross_Selling_Injector.
		 *
		 * @since 1.0.4
		 */
		use WPFactory_Cross_Selling_Injector;

		/**
		 * Initializes the class.
		 *
		 * @version 1.0.4
		 * @since 1.0.4
		 *
		 * @return void
		 */
		function init() {
			// WPFactory admin menu.
			WPFactory_Admin_Menu::get_instance();

			// Action links.
			if ( $this->get_wpfactory_cross_selling()->get_setup_args()['recommendations_page']['action_link']['enable'] ) {
				add_filter( 'plugin_action_links_' . $this->get_wpfactory_cross_selling()->get_plugin_basename(), array( $this, 'add_action_links' ) );
			}

			// Cross-selling submenu.
			add_action( 'admin_menu', array( $this, 'create_recommendations_submenu' ) );

			// Enqueue admin css.
			add_filter( 'wpfcs_enqueue_admin_css', array( $this, 'enqueue_wcfcs_css_on_recommendations_page' ) );
		}

		/**
		 * enqueue_wcfcs_css_on_recommendations_page.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @param $enqueue
		 *
		 * @return mixed|true
		 */
		function enqueue_wcfcs_css_on_recommendations_page( $enqueue ) {
			if ( isset( $_GET['page'] ) && $_GET['page'] === $this->recommendations_page_slug ) {
				$enqueue = true;
			}

			return $enqueue;
		}

		/**
		 * Adds action links.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function add_action_links( $links ) {
			$this->get_wpfactory_cross_selling()->localize();
			$setup_args     = $this->get_wpfactory_cross_selling()->get_setup_args();
			$action_link    = $setup_args['recommendations_page']['action_link'] ?? '';
			$label          = $action_link['label'] ?? '';
			$link           = admin_url( 'admin.php?page=' . $this->recommendations_page_slug );
			$target         = '_self';
			$custom_links[] = sprintf( '<a href="%s" target="%s">%s</a>', esc_url( $link ), sanitize_text_field( $target ), sanitize_text_field( $label ) );
			$links          = array_merge( $links, $custom_links );

			return $links;
		}

		/**
		 * Creates recommendations submenu.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @return void
		 */
		function create_recommendations_submenu() {
			if ( self::$recommendations_submenu_initialized ) {
				return;
			}
			self::$recommendations_submenu_initialized = true;

			// Gets params.
			$setup_args           = $this->get_wpfactory_cross_selling()->get_setup_args();
			$recommendations_page = $setup_args['recommendations_page'] ?? '';
			$page_title           = $recommendations_page['page_title'] ?? '';
			$menu_title           = $recommendations_page['menu_title'] ?? '';
			$capability           = $recommendations_page['menu_capability'] ?? '';
			$position             = $recommendations_page['menu_position'] ?? '';

			if ( empty( $capability ) ) {
				$capability = class_exists( 'WooCommerce' ) ? 'manage_woocommerce' : 'manage_options';
			}

			// Creates the submenu page.
			\add_submenu_page(
				WPFactory_Admin_Menu::get_instance()->get_menu_slug(),
				$page_title,
				$menu_title,
				$capability,
				$this->recommendations_page_slug,
				array( $this, 'render_cross_selling_page' ),
				$position
			);
		}

		/**
		 * Renders cross-selling page.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @return void
		 */
		function render_cross_selling_page() {
			$setup_args           = $this->get_wpfactory_cross_selling()->get_setup_args();
			$recommendations_page = $setup_args['recommendations_page'] ?? '';
			$page_title           = $recommendations_page['page_title'] ?? '';
			$categories           = $this->get_wpfactory_cross_selling()->product_categories->get_product_categories();
			$products             = $this->get_wpfactory_cross_selling()->products->get_products();
			?>
			<div class="wrap wpfcs">
				<h1><?php echo esc_html( $page_title ); ?></h1>
				<?php foreach ( $categories as $category_data ): ?>
					<h2 class="wpfcs-category"><?php echo esc_html( $category_data['name'] ); ?></h2>
					<?php foreach ( wp_list_filter( $products, array( 'category_slug' => $category_data['slug'] ) ) as $product_data ): ?>
						<?php echo $this->get_wpfactory_cross_selling()->get_template( 'recommendations-page-product.php', array(
							'product_data'            => $product_data,
							'free_version_installed'  => $this->get_wpfactory_cross_selling()->is_plugin_installed( $product_data['free_plugin_path'] ),
							'pro_version_installed'   => $this->get_wpfactory_cross_selling()->is_plugin_installed( $product_data['pro_plugin_path'] ),
							'free_plugin_install_url' => $this->get_wpfactory_cross_selling()->generate_free_plugin_install_url( $product_data['free_plugin_slug'] ),
							'pro_plugin_url'          => $product_data['pro_plugin_url']
						) ); ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}
}