<?php
/**
 * WPFactory Admin Menu
 *
 * @version 1.0.7
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Admin_Menu;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu' ) ) {

	/**
	 * WPFactory Admin Menu.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class WPFactory_Admin_Menu {

		use Singleton;

		/**
		 * Version.
		 *
		 * @since   1.0.1
		 *
		 * @var string
		 */
		protected $version = '1.0.8';

		/**
		 * Menu slug.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		protected $menu_slug = 'wpfactory';

		/**
		 * Page title.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		protected $page_title = '';

		/**
		 * Menu title.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		protected $menu_title = '';

		/**
		 * Capability.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		protected $capability = '';

		/**
		 * Icon URL.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		protected $icon_url = '';

		/**
		 * Position.
		 *
		 * @since   1.0.0
		 *
		 * @var string
		 */
		protected $position = 64;

		/**
		 * $wc_settings_menu_item_swapper.
		 *
		 * @since 1.0.0
		 *
		 * @var WC_Settings_Menu_Item_Swapper
		 */
		protected $wc_settings_menu_item_swapper = null;

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		protected function __construct() {
			$this->localize();

			// Setups add_menu_page params.
			$this->set_menu_title( __( 'WPFactory', 'wpfactory-admin-menu' ) );
			$this->set_page_title( __( 'WPFactory', 'wpfactory-admin-menu' ) );
			$this->set_icon_url( 'data:image/svg+xml;base64,PHN2ZyBpZD0iU3ZnanNTdmcxMDAxIiB3aWR0aD0iMjg4IiBoZWlnaHQ9IjI4OCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2ZXJzaW9uPSIxLjEiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWxuczpzdmdqcz0iaHR0cDovL3N2Z2pzLmNvbS9zdmdqcyI+PGRlZnMgaWQ9IlN2Z2pzRGVmczEwMDIiPjwvZGVmcz48ZyBpZD0iU3ZnanNHMTAwOCI+PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCA2MyA0OCIgd2lkdGg9IjI4OCIgaGVpZ2h0PSIyODgiPjxwYXRoIGZpbGw9InVybCgjYSkiIGQ9Ik0wIDQwLjYwMjRDMCAzOC42ODEzIDMuMDc4NjMgMzYuOTMxIDguMTI2MjEgMzUuNjE1N1YzNy41MjA3QzguMTI2MjEgMzkuOTc5OSAxMC4wMzE3IDQwLjMzNTMgMTEuOTAyNSA0MC45MjU0QzIxLjcyOTcgNDQuMDI1OSAzOS4zNTU4IDQzLjc3OTUgNTEuNTkyNSA0MC45MjU0QzUyLjczODMgNDAuNjU4MyA1NC4wODE0IDQwLjM4NDkgNTQuMDgxNCAzOC43NlYzNS42MTZDNTkuMTI4NiAzNi45MzEgNjIuMjA2NyAzOC42ODE2IDYyLjIwNjcgNDAuNjAyN0M2Mi4yMDY3IDQ0LjY4ODIgNDguMjgxNCA0Ny45OTk5IDMxLjEwMzggNDcuOTk5OUMxMy45MjU1IDQ3Ljk5OTMgMCA0NC42ODc5IDAgNDAuNjAyNFoiPjwvcGF0aD48cGF0aCBmaWxsPSJ1cmwoI2IpIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik00NC44MDggMjUuNTMwOEwzMy4zOTQ0IDE5LjU1MzRMMzMuMjA5NCAxOS40NTU2TDMwLjEyNSA0My4xNTA4QzM1LjYxOTkgNDMuMTc3IDQxLjMzOCA0Mi43NjE2IDQ2LjUxNzIgNDEuOTQ1OUw0NC44MDggMjUuNTMwOFoiIGNsaXAtcnVsZT0iZXZlbm9kZCI+PC9wYXRoPjxwYXRoIGZpbGw9IiM5ODk4OTgiIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTMzLjIxNzQgMTkuMDI2TDMzLjE5MjIgMTkuMjM3N0wyMy44NDI2IDE0LjI2MTRDMjMuMTU0MSAxMy44OTc3IDIwLjgzNjYgMTMuNzM1OCAyMC44MzY2IDE2LjE4OTVWMTkuNDI1OEwxMS45NzI3IDE0Ljc0NzJDMTAuNzk5NSAxNC4xODA2IDguMTI1IDE0LjU4NTMgOC4xMjUgMTcuMjAxNVYzNS42MTZDMTMuODEzMSAzNC4xMzQzIDIyLjAwMSAzMy4yMDQzIDMxLjEwMjYgMzMuMjA0M0MzMS4yMDQ3IDMzLjIwNDMgMzEuMzA2MiAzMy4yMDU1IDMxLjQwODMgMzMuMjA1NUwzMy4xNjcyIDE5LjU4MTRMMzMuMjE3NCAxOS4wMjZaIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGNsYXNzPSJjb2xvcjhBNjNDRCBzdmdTaGFwZSI+PC9wYXRoPjxwYXRoIGZpbGw9InVybCgjYykiIGQ9Ik01Mi4xNzYyIDI5LjI2OThMNDQuNzkwOCAyNS4zNjQ0TDQ0Ljc4NzEgMjUuMzI4OUw0NC44MDg3IDI1LjUzMDNMNDUuNjk3OCAzNC4wNjg0QzQ4Ljg1NTcgMzQuNDY4MiA1MS42OSAzNC45OTI5IDU0LjA4MTkgMzUuNjE2VjMyLjI3ODVDNTQuMDgxOSAzMC4xMzYzIDUyLjM3MzQgMjkuMzc0MyA1Mi4xNzYyIDI5LjI2OThaIj48L3BhdGg+PHBhdGggZmlsbD0idXJsKCNkKSIgZD0iTTQ0LjgwNzkgMjUuNTMwNEw0NC43ODYzIDI1LjMyODlMNDIuMTQ3MSAwLjg3Mjg4OEM0Mi4xMDUyIDAuNDI5MzA0IDQxLjY3NjQgMCA0MS4yNzYzIDBIMzYuMjQxN0MzNS44NjU2IDAgMzUuNDI5MiAwLjQxOTI3OCAzNS4zNzUxIDAuODM0NjA2TDMzLjIxODEgMTkuMDI1OEwzMy4xNjggMTkuNTgxNVYxOS41ODNMNDQuODE0IDI1LjU4NzJMNDQuODA3OSAyNS41MzA0WiI+PC9wYXRoPjxwYXRoIGZpbGw9InVybCgjZSkiIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTQ1LjY5NzMgMzQuMDY1N0w0Ni41MTQzIDQxLjkxMDdDNDguMjc5NyA0MS42Mjc2IDQ5Ljk4MjggNDEuMjk3NiA1MS41OTIzIDQwLjkyMjFDNTIuNzM4MSA0MC42NTUgNTQuMDgxMSA0MC4zODIyIDU0LjA4MTEgMzguNzU2N1YzNS42MTM0QzUxLjY4OTUgMzQuOTkwMiA0OC44NTUyIDM0LjQ2NDkgNDUuNjk3MyAzNC4wNjU3WiIgY2xpcC1ydWxlPSJldmVub2RkIj48L3BhdGg+PHBhdGggZmlsbD0idXJsKCNmKSIgZD0iTTMxLjQwODMgMzMuMjA2OEMzMS4zMDYyIDMzLjIwNjIgMzEuMjA0NyAzMy4yMDU2IDMxLjEwMjYgMzMuMjA1NkMyMi4wMDEgMzMuMjA1NiAxMy44MTMxIDM0LjEzNTYgOC4xMjUgMzUuNjE2N1YzNy41MjJDOC4xMjUgMzkuOTgxMiAxMC4wMzA1IDQwLjMzNjYgMTEuOTAxMyA0MC45MjY3QzE2LjY0OTYgNDIuNDI0NSAyMy4yMTk0IDQzLjE0MDYgMzAuMTIyNCA0My4xNTk1Ij48L3BhdGg+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJhIiB4MT0iMCIgeDI9IjYyLjIwNyIgeTE9IjQxLjgwNyIgeTI9IjQxLjgwNyIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPjxzdG9wIHN0b3AtY29sb3I9IiM5MTkxOTEiIGNsYXNzPSJzdG9wQ29sb3I0OERCRDcgc3ZnU2hhcGUiPjwvc3RvcD48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM3YTdhN2EiIGNsYXNzPSJzdG9wQ29sb3IwMUY0QTAgc3ZnU2hhcGUiPjwvc3RvcD48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0iYiIgeDE9IjI0LjE4MSIgeDI9IjQyLjgxNCIgeTE9IjI5LjMyNiIgeTI9IjM0LjI0OSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPjxzdG9wIHN0b3AtY29sb3I9IiM3YTdhN2EiIGNsYXNzPSJzdG9wQ29sb3IyQjQyQzkgc3ZnU2hhcGUiPjwvc3RvcD48c3RvcCBvZmZzZXQ9Ii4xOTEiIHN0b3AtY29sb3I9IiM3OTc5NzkiIGNsYXNzPSJzdG9wQ29sb3IyOTQ3Q0Egc3ZnU2hhcGUiPjwvc3RvcD48c3RvcCBvZmZzZXQ9Ii40MTQiIHN0b3AtY29sb3I9IiM3ODc4NzgiIGNsYXNzPSJzdG9wQ29sb3IyMjU1Q0Ugc3ZnU2hhcGUiPjwvc3RvcD48c3RvcCBvZmZzZXQ9Ii42NTQiIHN0b3AtY29sb3I9IiM3Njc2NzYiIGNsYXNzPSJzdG9wQ29sb3IxNzZERDUgc3ZnU2hhcGUiPjwvc3RvcD48c3RvcCBvZmZzZXQ9Ii45MDMiIHN0b3AtY29sb3I9IiM3MjcyNzIiIGNsYXNzPSJzdG9wQ29sb3IwNzhEREUgc3ZnU2hhcGUiPjwvc3RvcD48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM3MTcxNzEiIGNsYXNzPSJzdG9wQ29sb3IwMDlDRTIgc3ZnU2hhcGUiPjwvc3RvcD48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0iYyIgeDE9IjMyLjMxMyIgeDI9IjU0LjQxMSIgeTE9IjMwLjQ3MiIgeTI9IjMwLjQ3MiIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPjxzdG9wIHN0b3AtY29sb3I9IiM5OTk5OTkiIGNsYXNzPSJzdG9wQ29sb3I4QzYzQ0Ygc3ZnU2hhcGUiPjwvc3RvcD48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiM4NTg1ODUiIGNsYXNzPSJzdG9wQ29sb3IzODQ3RDMgc3ZnU2hhcGUiPjwvc3RvcD48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCBpZD0iZCIgeDE9IjMxLjgyNCIgeDI9IjUxLjc2NiIgeTE9IjEyLjc5NCIgeTI9IjEyLjc5NCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPjxzdG9wIG9mZnNldD0iLjA0NiIgc3RvcC1jb2xvcj0iIzk5OTk5OSIgY2xhc3M9InN0b3BDb2xvcjhDNjNDRiBzdmdTaGFwZSI+PC9zdG9wPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzg1ODU4NSIgY2xhc3M9InN0b3BDb2xvcjM4NDdEMyBzdmdTaGFwZSI+PC9zdG9wPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJlIiB4MT0iNDUuNjk3IiB4Mj0iNTQuMDgxIiB5MT0iMzcuOTg4IiB5Mj0iMzcuOTg4IiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agc3RvcC1jb2xvcj0iIzc1NzU3NSIgY2xhc3M9InN0b3BDb2xvcjA1OUFFNSBzdmdTaGFwZSI+PC9zdG9wPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzc0NzQ3NCIgY2xhc3M9InN0b3BDb2xvcjAxQUNFNyBzdmdTaGFwZSI+PC9zdG9wPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJmIiB4MT0iOC4xMjUiIHgyPSIzMS40MDgiIHkxPSIzOC4xODMiIHkyPSIzOC4xODMiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjNzY3Njc2IiBjbGFzcz0ic3RvcENvbG9yMUMzN0QxIHN2Z1NoYXBlIj48L3N0b3A+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjNzU3NTc1IiBjbGFzcz0ic3RvcENvbG9yMEU3MEREIHN2Z1NoYXBlIj48L3N0b3A+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PC9zdmc+PC9nPjwvc3ZnPg==' );

			// WPFactory admin page.
			add_action( 'admin_menu', array( $this, 'create_wpfactory_admin_menu' ), 9 );
		}

		/**
		 * Moves WooCommerce Settings tab to WPFactory menu as a submenu item.
		 *
		 * @version 1.0.7
		 * @since   1.0.0
		 *
		 * @param $args
		 *
		 * @return void
		 */
		public function move_wc_settings_tab_to_wpfactory_menu( $args = null ) {
			if ( is_null( $this->wc_settings_menu_item_swapper ) ) {
				$this->wc_settings_menu_item_swapper = new WC_Settings_Menu_Item_Swapper();
			}

			// Initial args.
			$args = wp_parse_args( $args, array(
				'wc_settings_tab_id' => '',
				'page_title'         => '',
				'menu_title'         => '',
				'capability'         => class_exists( 'WooCommerce' ) ? 'manage_woocommerce' : 'manage_options',
				'position'           => 30,
				'plugin_icon'        => array()
			) );
			if ( empty( $args['page_title'] ) ) {
				$args['page_title'] = $args['menu_title'];
			}

			// Admin menu.
			$replacement_menu_item_slug = 'admin.php?page=wc-settings&tab=' . $args['wc_settings_tab_id'];
			add_action( 'admin_menu', function () use ( $args, $replacement_menu_item_slug ) {
				\add_submenu_page(
					$this->menu_slug,
					$args['menu_title'],
					$args['menu_title'],
					$args['capability'],
					$replacement_menu_item_slug,
					'',
					$args['position']
				);
			} );

			// Setup plugin icon.
			$args['plugin_icon'] = wp_parse_args( $args['plugin_icon'], array(
				'wporg_plugin_slug' => '',
				'get_url_method'    => 'manual', // wporg_plugins_api || manual
				'url'               => '',
				'style'             => '',
				'width'             => '',
				'height'            => '36',
			) );

			// Swap.
			$this->wc_settings_menu_item_swapper->swap( array(
				'wc_settings_tab_id'         => $args['wc_settings_tab_id'],
				'replacement_menu_item_slug' => $replacement_menu_item_slug,
				'page_title'                 => $args['page_title'],
				'plugin_icon'                => $args['plugin_icon']
			) );

			// Init.
			$this->wc_settings_menu_item_swapper->init();
		}

		/**
		 * add_submenu_page.
		 *
		 * @version 1.0.2
		 * @since   1.0.2
		 *
		 * @param $page_title
		 * @param $menu_title
		 * @param $capability
		 * @param $menu_slug
		 * @param $callback
		 * @param $position
		 *
		 * @return void
		 */
		function add_submenu_page( $page_title, $menu_title, $capability, $menu_slug, $callback = '', $position = null ) {
			\add_submenu_page(
				$this->menu_slug,
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback,
				$position
			);
		}

		/**
		 * Creates WPFactory admin menu.
		 *
		 * @version 1.0.3
		 * @since   1.0.0
		 *
		 * @return void
		 */
		function create_wpfactory_admin_menu() {
			// Set capability.
			$this->set_capability( class_exists( 'WooCommerce' ) ? 'manage_woocommerce' : 'manage_options' );

			// Menu page.
			\add_menu_page(
				$this->get_page_title(),
				$this->get_menu_title(),
				$this->get_capability(),
				$this->get_menu_slug(),
				array( $this, 'render_page' ),
				$this->get_icon_url(),
				$this->get_position()
			);

			// Removes submenu page.
			add_action( 'admin_head', function () {
				remove_submenu_page( $this->menu_slug, $this->menu_slug );
			} );
		}

		/**
		 * Renders page.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return void
		 */
		function render_page() {

		}

		/**
		 * Localizes the plugin.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return void
		 */
		public function localize() {
			$domain = 'wpfactory-admin-menu';
			$locale = get_locale();
			$mofile = dirname( $this->get_library_file_path() ) . '/langs/' . $domain . '-' . $locale . '.mo';
			load_textdomain( $domain, $mofile );
		}

		/**
		 * get_menu_slug.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function get_menu_slug() {
			return $this->menu_slug;
		}

		/**
		 * set_menu_slug.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   string  $menu_slug
		 */
		public function set_menu_slug( $menu_slug ) {
			$this->menu_slug = $menu_slug;
		}

		/**
		 * get_page_title.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function get_page_title() {
			return $this->page_title;
		}

		/**
		 * set_page_title
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   string  $page_title
		 */
		public function set_page_title( $page_title ) {
			$this->page_title = $page_title;
		}

		/**
		 * get_menu_title.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function get_menu_title() {
			return $this->menu_title;
		}

		/**
		 * set_menu_title.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   string  $menu_title
		 */
		public function set_menu_title( $menu_title ) {
			$this->menu_title = $menu_title;
		}

		/**
		 * get_capability.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function get_capability() {
			return $this->capability;
		}

		/**
		 * set_capability.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   string  $capability
		 */
		public function set_capability( $capability ) {
			$this->capability = $capability;
		}

		/**
		 * get_icon_url.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function get_icon_url() {
			return $this->icon_url;
		}

		/**
		 * set_icon_url
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   string  $icon_url
		 */
		public function set_icon_url( $icon_url ) {
			$this->icon_url = $icon_url;
		}

		/**
		 * get_position.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return int|string
		 */
		public function get_position() {
			return $this->position;
		}

		/**
		 * set_position.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   int|string  $position
		 */
		public function set_position( $position ) {
			$this->position = $position;
		}

		/**
		 * get_version.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * get_file_path.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		function get_library_file_path() {
			return dirname( __FILE__, 2 );
		}
	}
}