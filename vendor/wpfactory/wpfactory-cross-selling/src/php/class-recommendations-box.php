<?php
/**
 * WPFactory Cross-Selling - Recommendations Box.
 *
 * @version 1.0.5
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\WPFactory_Cross_Selling;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\WPFactory_Cross_Selling\Recommendations_Box' ) ) {

	/**
	 * WPF_Cross_Selling.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	class Recommendations_Box {

		/**
		 * WPFactory_Cross_Selling_Injector.
		 *
		 * @since 1.0.4
		 */
		use WPFactory_Cross_Selling_Injector;

		/**
		 * Initializes the class.
		 *
		 * @version 1.0.5
		 * @since   1.0.4
		 *
		 * @return void
		 */
		function init() {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();

			if ( ! $setup_args['recommendations_box']['enable'] ) {
				return;
			}

			// Enqueue admin css.
			add_filter( 'wpfcs_enqueue_admin_css', array( $this, 'enqueue_wcfcs_css_on_recommendations_box' ) );

			if (
				in_array( 'wc_settings_tab', $setup_args['recommendations_box']['position'] ) &&
				! empty( $setup_args['recommendations_box']['wc_settings_tab_id'] )
			) {
				// Wrap WC settings.
				add_action( 'woocommerce_settings_' . $setup_args['recommendations_box']['wc_settings_tab_id'], array( $this, 'wrap_wc_settings_start' ), 9 );
				add_action( 'woocommerce_settings_' . $setup_args['recommendations_box']['wc_settings_tab_id'], array( $this, 'wrap_wc_settings_end' ), 11 );

				// Render Recommendations box.
				add_action( 'woocommerce_settings_' . $setup_args['recommendations_box']['wc_settings_tab_id'], array( $this, 'render_recommendations_box' ), 15 );
			}
		}

		/**
		 * wrap_wc_settings_end.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 *
		 * @return void
		 */
		function wrap_wc_settings_end() {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();
			if ( ! $setup_args['recommendations_box']['enable'] ) {
				return;
			}
			echo '</div>';
		}

		/**
		 * wrap_wc_settings_start.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 *
		 * @return void
		 */
		function wrap_wc_settings_start() {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();
			if ( ! $setup_args['recommendations_box']['enable'] ) {
				return;
			}
			echo '<div class="wpfcs-wc-settings-wrapper">';
		}

		/**
		 * render_recommendations_box.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 *
		 * @return void
		 */
		function render_recommendations_box() {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();
			if ( ! $setup_args['recommendations_box']['enable'] ) {
				return;
			}

			// Products.
			$products = $this->get_wpfactory_cross_selling()->products->get_products();

			// Tags.
			$box_tags_class = new Recommendation_Box_Tags();
			$box_tags       = $box_tags_class->get_tags();

			// Banners.
			$banners = $this->get_wpfactory_cross_selling()->banners->get_banners();

			?>
			<div class="wpfcs-recommendations-box">
				<h3 class="wpfcs-recommendation-box-title">
					<?php _e( 'Recommended Plugins', 'wpfactory-cross-selling' ) ?>
				</h3>
				<div class="wpfcs-tabs-mechanism">
					<div class="wpfcs-tab-links">
						<?php foreach ( $box_tags as $tag ): ?>
							<a href="#wpfcs-<?php echo esc_attr( $tag['slug'] ) ?>"><?php echo esc_html( $tag['name'] ) ?></a>
						<?php endforeach; ?>
					</div>
					<?php foreach ( $box_tags as $tag ): ?>
						<div id="wpfcs-<?php echo esc_attr( $tag['slug'] ) ?>" class="wpfcs-tab-content">
							<?php foreach ( wp_list_filter( $products, array( 'tag_slug' => $tag['slug'] ) ) as $product_data ) : ?>
								<?php if ( ! in_array( plugin_basename( $setup_args['plugin_file_path'] ), array( $product_data['free_plugin_path'], $product_data['pro_plugin_path'], ) ) ) { ?>
									<div class="wpfcs-tab-content-item">
										<?php echo $this->get_wpfactory_cross_selling()->get_template( 'recommendation-box-item.php', array(
											'product_data'            => $product_data,
											'free_version_installed'  => $this->get_wpfactory_cross_selling()->is_plugin_installed( $product_data['free_plugin_path'] ),
											'pro_version_installed'   => $this->get_wpfactory_cross_selling()->is_plugin_installed( $product_data['pro_plugin_path'] ),
											'free_plugin_install_url' => $this->get_wpfactory_cross_selling()->generate_free_plugin_install_url( $product_data['free_plugin_slug'] ),
											'pro_plugin_url'          => $product_data['pro_plugin_url']
										) );
										?>
									</div>
								<?php } ?>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="wpfcs-banners">
					<?php foreach ( $banners as $banner ): ?>
						<div class="wpfcs-banner">
							<?php $banner_img_src = $banner['img_src'] ?>
							<a href="<?php echo esc_url( $banner['url'] ); ?>" target="<?php echo esc_attr( $banner['target'] ); ?>"><img src="<?php echo esc_attr( $banner_img_src ); ?>"/></a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
			$this->add_recommendation_box_js();
		}

		/**
		 * add_recommendation_box_js.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 *
		 * @return void
		 */
		function add_recommendation_box_js() {
			?>
			<script>
				function setActiveTab() {
					const links = document.querySelectorAll( '.wpfcs-tab-links a' );
					const contents = document.querySelectorAll( '.wpfcs-tab-content' );

					let hash = window.location.hash;
					let activeLink = null;
					let activeContent = null;

					// Find matching content
					if ( hash && document.querySelector( hash ) ) {
						activeLink = document.querySelector( `.wpfcs-tab-links a[href="${ hash }"]` );
						activeContent = document.querySelector( hash );
					}

					// Fallback to the first tab if hash is invalid or missing.
					if ( !activeLink || !activeContent ) {
						activeLink = links[ 0 ];
						activeContent = contents[ 0 ];
					}

					// Clear all actives.
					links.forEach( link => link.classList.remove( 'active' ) );
					contents.forEach( content => content.classList.remove( 'active' ) );

					// Activate the right ones.
					if ( activeLink ) activeLink.classList.add( 'active' );
					if ( activeContent ) activeContent.classList.add( 'active' );
				}

				window.addEventListener( 'load', setActiveTab );
				window.addEventListener( 'hashchange', setActiveTab );
			</script>
			<?php
		}

		/**
		 * enqueue_wcfcs_css_on_recommendations_box.
		 *
		 * @version 1.0.4
		 * @since   1.0.4
		 *
		 * @param $enqueue
		 *
		 * @return mixed|true
		 */
		function enqueue_wcfcs_css_on_recommendations_box( $enqueue ) {
			$setup_args = $this->get_wpfactory_cross_selling()->get_setup_args();

			if (
				$setup_args['recommendations_box']['enable'] &&
				in_array( 'wc_settings_tab', $setup_args['recommendations_box']['position'] ) &&
				isset( $_GET['page'] ) &&
				'wc-settings' === $_GET['page'] &&
				isset( $_GET['tab'] ) &&
				! empty( $setup_args['recommendations_box']['wc_settings_tab_id'] ) &&
				$setup_args['recommendations_box']['wc_settings_tab_id'] === $_GET['tab']
			) {
				$enqueue = true;
			}

			return $enqueue;
		}
	}
}