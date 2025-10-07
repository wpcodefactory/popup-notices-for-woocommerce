<?php
/**
 * WPFactory Cross-Selling
 *
 * @version 1.0.5
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

use WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling' ) ) {

	/**
	 * WPF_Cross_Selling.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class WPFactory_Cross_Selling {

		/**
		 * Version.
		 *
		 * @since   1.0.1
		 *
		 * @var string
		 */
		protected $version = '1.0.6';

		/**
		 * Setup args.
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		protected $setup_args = array();

		/**
		 * Products.
		 *
		 * @since   1.0.0
		 *
		 * @var Products
		 */
		public $products;

		/**
		 * Banners.
		 *
		 * @since   1.0.4
		 *
		 * @var Banners
		 */
		public $banners;

		/**
		 * Product categories.
		 *
		 * @since   1.0.0
		 *
		 * @var Product_Categories
		 */
		public $product_categories;

		/**
		 * Initialized.
		 *
		 * @since   1.0.0
		 *
		 * @var bool
		 */
		protected $initialized = false;

		/**
		 * Setups the class.
		 *
		 * @version 1.0.5
		 * @since   1.0.0
		 *
		 * @param $args
		 *
		 * @return void
		 */
		function setup( $args = null ) {
			$this->localize();

			$args = wp_parse_args( $args, array(
				'plugin_file_path'     => '',
				'recommendations_page' => array(),
				'recommendations_box'  => array(),
			) );

			// Recommendations page.
			$args['recommendations_page'] = wp_parse_args( $args['recommendations_page'], array(
				'page_title'      => __( 'WPFactory Recommendations', 'wpfactory-cross-selling' ),
				'menu_title'      => __( 'Recommendations', 'wpfactory-cross-selling' ),
				'menu_capability' => '',
				'menu_position'   => 2,
				'action_link'     => array()
			) );

			// Recommendations page action link.
			$args['recommendations_page']['action_link'] = wp_parse_args( $args['recommendations_page']['action_link'], array(
				'enable' => true,
				'label'  => __( 'Recommendations', 'wpfactory-cross-selling' ),
			) );

			// Recommendations box.
			$args['recommendations_box'] = wp_parse_args( $args['recommendations_box'], array(
				'enable'             => true,
				'position'           => array( 'wc_settings_tab' ),
				'wc_settings_tab_id' => '',
			) );

			// Library file path.
			$args['library_file_path'] = __FILE__;
			$args['library_root_path'] = plugin_dir_path( dirname( __FILE__ ) );

			$this->setup_args = $args;
		}

		/**
		 * Initializes the class.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @return void
		 */
		function init() {
			if ( $this->initialized ) {
				return;
			}
			$this->initialized = true;

			// Products.
			$this->products = new Products();

			// Banners.
			$this->banners = new Banners();
			$this->banners->set_wpfactory_cross_selling( $this );

			// Product Categories.
			$this->product_categories = new Product_Categories();

			// Recommendation page.
			$recommendations_page = new Recommendations_Page();
			$recommendations_page->set_wpfactory_cross_selling( $this );
			$recommendations_page->init();

			// Recommendation box.
			$recommendations_box = new Recommendations_Box();
			$recommendations_box->set_wpfactory_cross_selling( $this );
			$recommendations_box->init();

			// Enqueues admin syles.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
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
			$domain = 'wpfactory-cross-selling';
			$locale = get_locale();
			$mofile = dirname( $this->get_library_file_path() ) . '/langs/' . $domain . '-' . $locale . '.mo';
			load_textdomain( $domain, $mofile );
		}

		/**
		 * Runs the add_action() callback if the hook_name is the current_filter.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $hook_name
		 * @param $callback
		 * @param $priority
		 * @param $accepted_args
		 *
		 * @return void
		 */
		function add_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
			if ( $hook_name === current_filter() ) {
				$callback();
			} else {
				add_action( $hook_name, $callback, $priority, $accepted_args );
			}
		}

		/**
		 * Enqueues admin syles.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 *
		 * @return void
		 */
		function enqueue_admin_styles() {
			if ( ! apply_filters( 'wpfcs_enqueue_admin_css', false ) ) {
				return;
			}
			$suffix        = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$css_file_path = untrailingslashit( plugin_dir_path( $this->get_library_file_path() ) ) . '/assets/css/admin' . $suffix . '.css';
			$css_file_url  = untrailingslashit( plugin_dir_url( $this->get_library_file_path() ) ) . '/assets/css/admin' . $suffix . '.css';
			$version       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? filemtime( $css_file_path ) : $this->version;
			wp_enqueue_style( 'wpfactory-cross-selling', $css_file_url, array(), $version );
		}

		/**
		 * Generates plugin install url.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $plugin_slug
		 *
		 * @return string
		 */
		function generate_free_plugin_install_url( $plugin_slug ) {
			$nonce       = wp_create_nonce( 'install-plugin_' . $plugin_slug );
			$install_url = add_query_arg(
				array(
					'action'   => 'install-plugin',
					'plugin'   => $plugin_slug,
					'_wpnonce' => $nonce
				),
				admin_url( 'update.php' )
			);

			return $install_url;
		}

		/**
		 * get_template
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $template_name
		 * @param $args
		 *
		 * @return false|string
		 */
		function get_template( $template_name, $args = array() ) {
			$template_path = plugin_dir_path( $this->get_library_file_path() ) . 'templates/' . $template_name;
			if ( file_exists( $template_path ) ) {
				ob_start();
				foreach ( $args as $key => $value ) {
					$$key = $value;
				}
				include $template_path;
				$content = ob_get_clean();

				return $content;
			} else {
				return '<p>Template not found.</p>';
			}
		}

		/**
		 * is_plugin_installed.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $plugin_slug
		 *
		 * @return bool
		 */
		function is_plugin_installed( $plugin_slug ) {
			$all_plugins = get_plugins();

			return isset( $all_plugins[ $plugin_slug ] );
		}

		/**
		 * get_setup_args.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return array
		 */
		public function get_setup_args() {
			return $this->setup_args;
		}

		/**
		 * get_file_path.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		function get_plugin_file_path() {
			$setup_args = $this->get_setup_args();

			return $setup_args['plugin_file_path'];
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

		/**
		 * get_basename.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		function get_plugin_basename() {
			$file_path = $this->get_plugin_file_path();

			return plugin_basename( $file_path );
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

	}
}