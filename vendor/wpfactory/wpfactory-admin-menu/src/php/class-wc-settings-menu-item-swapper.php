<?php
/**
 * WPFactory Admin Menu - WooCommerce Settings Menu Item Swapper.
 *
 * @version 1.0.8
 * @since   1.0.1
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Admin_Menu;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory\WPFactory_Admin_Menu\WC_Settings_Menu_Item_Swapper' ) ) {

	/**
	 * WPFactory Admin Menu.
	 *
	 * @version 1.0.1
	 * @since   1.0.1
	 */
	class WC_Settings_Menu_Item_Swapper {

		/**
		 * $args.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		protected $args = array();

		/**
		 * Initialized.
		 *
		 * @since 1.0.0
		 *
		 * @var bool
		 */
		protected $initialized = false;

		/**
		 * swap.
		 *
		 * @param $args
		 *
		 * @version 1.0.6
		 * @since   1.0.0
		 *
		 * @return void
		 */
		function swap( $args = null ) {
			$args = wp_parse_args( $args, array(
				'wc_settings_tab_id'         => '',
				'replacement_menu_item_slug' => '',
				'page_title'                 => '',
				'plugin_icon'                => array(),
				'plugin_slug'                => '',
			) );
			$args['plugin_icon'] = wp_parse_args( $args['plugin_icon'], array(
				'wporg_plugin_slug' => '',
				'get_url_method'    => 'manual', // wporg_plugins_api || manual
				'url'               => '',
				'style'             => '',
				'width'             => '',
				'height'            => '36',
			) );
			$this->args[ $args['replacement_menu_item_slug'] ] = $args;
		}

		/**
		 * Initializes.
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 *
		 * @return void
		 */
		function init() {
			if ( $this->initialized ) {
				return;
			}
			$this->initialized = true;

			// Replaces WC Settings Menu Item.
			add_filter( 'parent_file', array( $this, 'replace_wc_settings_menu_item' ) );

			// Hide Current plugin Settings tab.
			add_action( 'admin_head', array( $this, 'hide_plugin_settings_tab' ) );

			// Hides WC Settings tabs.
			add_action( 'admin_head', array( $this, 'hide_wc_settings_tabs' ) );

			// Add page title.
			add_action( 'all_admin_notices', array( $this, 'add_page_title' ) );
		}

		/**
		 * Adds page title.
		 *
		 * @version 1.0.8
		 * @since   1.0.1
		 *
		 * @return void
		 */
		function add_page_title() {
			if (
				isset( $_GET['page'] ) &&
				'wc-settings' === $_GET['page'] &&
				isset( $_GET['tab'] ) &&
				! empty( $found_items = wp_list_filter( $this->args, array( 'wc_settings_tab_id' => $_GET['tab'] ) ) )
			) {
				$first_item = reset( $found_items );
				$page_title = $first_item['page_title'];

				// Plugin icon.
				$plugin_icon = new Plugin_Icon();
				$plugin_icon_args = $first_item['plugin_icon'];
				$plugin_icon->set_args( $plugin_icon_args );
				$plugin_icon_html = $plugin_icon->get_plugin_icon_img_html();

				// Page title.
				if ( ! empty( $page_title ) ) {
					echo '<div class="woocommerce-layout__header wpfam-woocommerce-layout__header"><div class="wpfam-plugin-title-wrapper"><h1 class="wpfam-plugin-title">' . $plugin_icon_html . esc_html( $page_title ) . '</h1></div></div>';
				}
			}
		}

		/**
		 * replace_wc_settings_menu_item.
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 *
		 * @param $file
		 *
		 * @return mixed
		 */
		function replace_wc_settings_menu_item( $file ) {
			global $plugin_page;
			if (
				'wc-settings' === $plugin_page &&
				isset( $_GET['tab'] ) &&
				! empty( $found_items = wp_list_filter( $this->args, array( 'wc_settings_tab_id' => $_GET['tab'] ) ) )
			) {
				$first_item                 = reset( $found_items );
				$replacement_menu_item_slug = $first_item['replacement_menu_item_slug'];
				$plugin_page                = $replacement_menu_item_slug;
			}

			return $file;
		}

		/**
		 * Hides plugin settings tab from WooCommerce settings page.
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 *
		 * @return void
		 */
		function hide_plugin_settings_tab() {
			global $plugin_page;
			if (
				'wc-settings' === $plugin_page &&
				(
					! isset( $_GET['tab'] ) ||
					( isset( $_GET['tab'] ) && empty( $found_items = wp_list_filter( $this->args, array( 'wc_settings_tab_id' => $_GET['tab'] ) ) ) )
				)
			) {
				$tab_ids          = array_column( $this->args, 'wc_settings_tab_id' );
				$css_selector_arr = array();
				foreach ( $tab_ids as $tab ) {
					$css_selector_arr[] = '.wrap.woocommerce .nav-tab-wrapper a[href*="tab=' . $tab . '"]';
				}

				?>
				<style>
					<?php echo implode(', ', $css_selector_arr)?>
					{
						display: none;
					}
				</style>
				<?php
			}
		}

		/**
		 * Hides WooCommerce settings tabs when accessing the plugin settings page.
		 *
		 * @version 1.0.8
		 * @since   1.0.1
		 *
		 * @return void
		 */
		function hide_wc_settings_tabs() {
			global $plugin_page;
			if (
				'wc-settings' === $plugin_page &&
				isset( $_GET['tab'] ) &&
				! empty( $found_items = wp_list_filter( $this->args, array( 'wc_settings_tab_id' => $_GET['tab'] ) ) )
			) {
				$show_current_plugin_tab = false;
				$tab_ids          = array_column( $this->args, 'wc_settings_tab_id' );
				$css_selector_arr = array();
				if ( $show_current_plugin_tab ) {
					foreach ( $tab_ids as $tab ) {
						$css_selector_arr[] = '.wrap.woocommerce .nav-tab-wrapper a[href*="tab=' . $tab . '"]';
					}
				}
				?>
				<style>
					<?php if( $show_current_plugin_tab ): ?>
					.wrap.woocommerce .nav-tab-wrapper a {
						display: none;
					}

					<?php else: ?>
					.nav-tab-wrapper.woo-nav-tab-wrapper{
						display: none !important;
					}

					h1.wpfam-plugin-title{
						padding: 0 0 0 30px;
						font-weight: 590;
						font-size: 16px;
						color: #070707;
						display: flex;
						align-items: center;
					}

					.wpfam-plugin-title-wrapper{
						display: flex;
						align-items: center;
						min-height: 60px;
					}

					.wpfam-plugin-title-wrapper .notice{
						display:none;
					}

					.wrap.woocommerce{
						margin-top:60px;
					}

					body.woocommerce_page_wc-settings #mainform{
						padding-top:24px;
					}

					.wpfam-plugin-icon{
						margin-right:5px;
					}

					<?php endif; ?>
					<?php echo implode(', ', $css_selector_arr)?>
					{
						display: block
					}

					#wpbody {
						margin-top: 0
					}

					.woocommerce-layout {
						display: none
					}
				</style>
				<script>
					// Replicates WooCommerce mechanism of adding `is-scrolled` class on `wpfam-woocommerce-layout__header`.
					window.addEventListener( 'load', function () {
						let isScrolling;
						const el = document.querySelector( '.wpfam-woocommerce-layout__header' );
						window.addEventListener( 'scroll', function () {
							if ( window.scrollY > 0 ) {
								el.classList.add( 'is-scrolled' );
							} else {
								el.classList.remove( 'is-scrolled' );
							}
						} );
					} );
				</script>
				<?php
			}
		}
	}
}