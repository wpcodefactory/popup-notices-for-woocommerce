<?php
/**
 * WPFactory Promoting Notice - Functions.
 *
 * @version 1.0.5
 * @since   1.0.0
 * @author  WPFactory
 */

namespace WPFactory\Promoting_Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WPFactory\Promoting_Notice' ) ) {
	class Core {

		private $args = array();

		/**
		 * set_args.
		 *
		 * @version 1.0.5
		 * @since   1.0.0
		 *
		 * @param array $args
		 */
		public function set_args( $args = null ) {
			// General args
			$args = wp_parse_args( $args, array(
				'enable'                                 => true,
				'notice_template'                        => '<div id="message" class="%notice_class%"><p class="wpfactory-pan-p">%content_template%</p></div>',
				'lib_dirname'                            => dirname( __FILE__, 2 ),
				'highlight_notice_on_disabled_opt_click' => true,
				'template_variables'                     => array(),
				'url_requirements'                       => array(
					'page_filename' => 'admin.php',
					'params'        => array(),
				),
				'display_action'                         => $this->get_default_display_action( $args ),
				'optimize_plugin_icon_contrast'          => false
			) );
			// Template variables
			$args['template_variables'] = wp_parse_args( $args['template_variables'], array(
				'%notice_class%'         => 'wpfactory-promoting-notice notice notice-info inline',
				'%pro_version_title%'    => __( 'Awesome plugin Pro', 'wpfactory-promoting-notice' ),
				'%pro_version_url%'      => 'https://wpfactory.com/item/awesome-plugin/',
				'%plugin_icon_url%'      => 'https://pluginfactory-tastystakes.netdna-ssl.com/img/site/plugin_icon.png',
				'%plugin_icon_style%'    => 'width:39px;margin-right:10px;vertical-align:middle',
				'%btn_icon_class%'       => 'wpfactory-pan-btn-icon dashicons-before dashicons-unlock',
				'%btn_icon_style%'       => 'position:relative;top:3px;margin:0 2px 0 -2px;',
				'%btn_style%'            => 'vertical-align:middle;display:inline-block;margin:0',
				'%btn_call_to_action%'   => __( 'Upgrade to Pro version', 'wpfactory-promoting-notice' ),
				'%main_text%'            => __( 'Disabled options can be unlocked using <a href="%pro_version_url%" target="_blank"><strong>%pro_version_title%</strong></a>', 'wpfactory-promoting-notice' ),
				'%main_text_style%'      => 'vertical-align: middle;margin:0 14px 0 0;',
				'%content_template%'     => '<img class="wpfactory-pan-plugin-icon" src="%plugin_icon_url%"/>' .
				                            '<span class="wpfactory-pan-main-text">%main_text%</span>' .
				                            '<a target="_blank" class="wpfactory-pan-button button-primary" href="%pro_version_url%"><i class="%btn_icon_class%"></i>%btn_call_to_action%</a>',
			) );
			// Set args
			$this->args = $args;
		}

		/**
		 * get_default_display_action.
		 *
		 * @version 1.0.3
		 * @since   1.0.3
		 *
		 * @param $args
		 *
		 * @return string
		 */
		function get_default_display_action( $args ) {
			$action_hook = 'admin_notices';
			if (
				isset( $args['url_requirements']['params']['page'] )
				&& ! empty( $page = $args['url_requirements']['params']['page'] )
				&& isset( $args['url_requirements']['params']['tab'] )
				&& ! empty( $tab = $args['url_requirements']['params']['tab'] )
				&& 'wc-settings' === $page
			) {
				$action_hook = 'woocommerce_sections_' . $tab;
			}
			return $action_hook;
		}

		/**
		 * init.
		 *
		 * @version 1.0.4
		 * @since   1.0.0
		 */
		function init() {
			$args = $this->get_args();
			if ( empty( $args['enable'] ) ) {
				return;
			}
			global $pagenow;
			if (
				! empty( $args['url_requirements'] )
				&& ! empty( $page_filename = $args['url_requirements']['page_filename'] )
				&& ! empty( $url_params = $args['url_requirements']['params'] )
				&& ! empty( $pagenow )
				&& $pagenow == $page_filename
				&& array_intersect_assoc( $url_params, $_GET ) === $url_params
			) {
				add_action( 'admin_head', array( $this, 'create_style' ) );
				add_action( 'admin_head', array( $this, 'highlight_notice_on_disabled_setting_click' ) );
				add_action( $args['display_action'], array( $this, 'create_notice' ) );
			}
		}

		/**
		 * decode_template_variables.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $template_variables
		 *
		 * @return array
		 */
		function decode_template_variables( $template_variables ) {
			$levels = 2;
			for ( $i = 1; $i <= $levels; $i ++ ) {
				$template_variables = array_map( function ( $v ) use ( $template_variables ) {
					return str_replace( array_keys( $template_variables ), $template_variables, $v );
				}, $template_variables );
			}
			return $template_variables;
		}

		/**
		 * create_notice.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function create_notice() {
			$args               = $this->get_args();
			$notice             = $args['notice_template'];
			$template_variables = $this->decode_template_variables( $args['template_variables'] );
			$notice             = str_replace( array_keys( $template_variables ), $template_variables, $notice );
			echo $notice;
		}

		/**
		 * highlight_premium_notice_on_disabled_setting_click.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function highlight_notice_on_disabled_setting_click() {
			$args = $this->get_args();
			if ( !( $args['highlight_notice_on_disabled_opt_click'] ) ) {
				return;
			}
			?>
			<script>
				jQuery(document).ready(function ($) {
					jQuery(document).ready(function ($) {
						let highlighter = {
							targetClass: '.wpfactory-promoting-notice',
							highlight: function () {
								window.scrollTo({
									top: 0,
									behavior: 'smooth'
								});
								setTimeout(function () {
									$(highlighter.targetClass).addClass('wpfactory-pan-blink');
								}, 300);
								setTimeout(function () {
									$(highlighter.targetClass).removeClass('wpfactory-pan-blink');
								}, 3000);
							}
						};

						function createDisabledElem() {
							$(".form-table *:disabled,.form-table *[readonly],.form-table .select2-container--disabled").each(function () {
								$(this).parent().css({
									"position": "relative"
								});
								let position = $(this).position();
								position.top = $(this)[0].offsetTop;
								let disabledDiv = $("<div class='wpfactory-pan-disabled wpfactory-pan-highlight-premium-notice'></div>").insertAfter($(this));
								disabledDiv.css({
									"position": "absolute",
									"left": position.left,
									"top": position.top,
									"width": $(this).outerWidth(),
									"height": $(this).outerHeight(),
									"cursor": 'pointer'
								});
							});
						}

						createDisabledElem();
						$("label:has(input:disabled),label:has(input[readonly])").addClass('wpfactory-pan-highlight-premium-notice');
						$(".wpfactory-pan-highlight-premium-notice, .select2-container--disabled").on('click', highlighter.highlight);
					});
				});
			</script>
			<?php
		}

		/**
		 * create_style.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function create_style() {
			$args            = $this->get_args();
			$notice_selector = $this->get_notice_selector();
			$image_rendering_style = '
			image-rendering: -moz-crisp-edges;
			image-rendering:   -o-crisp-edges;
			image-rendering: -webkit-optimize-contrast;
			image-rendering: crisp-edges;
			-ms-interpolation-mode: nearest-neighbor;';
			$image_rendering_style = $args['optimize_plugin_icon_contrast'] ? $image_rendering_style : '';
			?>
			<style>
				.wpfactory-pan-blink {
					animation: alg-dtwp-blink 1s;
					animation-iteration-count: 3;
				}

				@keyframes alg-dtwp-blink {
					50% {
						background-color: #ececec;
					}
				}

				<?php echo esc_attr($notice_selector); ?>
				.wpfactory-pan-plugin-icon {
				<?php echo $image_rendering_style; ?>
				<?php echo esc_attr($args['template_variables']['%plugin_icon_style%']); ?>
				}

				<?php echo esc_attr($notice_selector); ?>
				.wpfactory-pan-main-text {
				<?php echo esc_attr($args['template_variables']['%main_text_style%']); ?>
				}

				<?php echo esc_attr($notice_selector); ?>
				.wpfactory-pan-button {
				<?php echo esc_attr($args['template_variables']['%btn_style%']); ?>
				}

				<?php echo esc_attr($notice_selector); ?>
				.wpfactory-pan-btn-icon {
				<?php echo esc_attr($args['template_variables']['%btn_icon_style%']); ?>
				}

				<?php echo esc_attr($notice_selector); ?>
				.wpfactory-pan-p {
					margin: 5px 0
				}
			</style>
			<?php
		}

		/**
		 * converts array to string.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $arr
		 * @param array $args
		 *
		 * @return string
		 */
		function convert_array_to_string( $arr, $args = array() ) {
			$args            = wp_parse_args( $args, array(
				'glue'          => ', ',
				'item_template' => '{value}' //  {key} and {value} allowed
			) );
			$transformed_arr = array_map( function ( $key, $value ) use ( $args ) {
				$item = str_replace( array( '{key}', '{value}' ), array( $key, $value ), $args['item_template'] );
				return $item;
			}, array_keys( $arr ), $arr );
			return implode( $args['glue'], $transformed_arr );
		}

		/**
		 * get_args.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @return array
		 */
		public function get_args() {
			return $this->args;
		}

		/**
		 * get_notice_selector.
		 *
		 * @version 1.0.3
		 * @since   1.0.0
		 *
		 * @return string
		 */
		function get_notice_selector() {
			$args     = $this->get_args();
			$selector = '.wpfactory-promoting-notice';
			return $selector;
		}


	}
}