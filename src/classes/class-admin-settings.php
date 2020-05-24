<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Admin Settings
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PNWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PNWC\Admin_Settings' ) ) {

	class Admin_Settings extends \WC_Settings_Page {

		/**
		 * Setup settings class
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id    = 'ttt-pnwc';
			$this->label = __( 'Pop-up Notices', 'popup-notices-for-woocommerce' );

			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
			add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
			add_filter( 'ttt_pnwc_settings_general', array( $this, 'handle_admin_license_settings' ), PHP_INT_MAX );

			// Allow regex values using allow_raw_values
			add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'sanitize_raw_values' ), 10, 3 );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output_raw_values' ), 1 );

		}

		/**
		 * Outputs raw values on 'allow_raw_values' fields
		 *
		 * @version 1.1.7
		 * @since 1.1.2
		 */
		public function output_raw_values() {
			global $current_section;
			$settings   = $this->get_settings( $current_section );
			$raw_values = wp_list_filter( $settings, array( 'allow_raw_values' => true ) );
			$new_values = array();
			foreach ( $raw_values as $key => $field ) {
				if ( preg_match( '/^.*\[\d{0,3}\]$/', $field['id'] ) ) {
					$arr                        = explode( "[", $field['id'] );
					$new_option_id              = $arr[0];
					$new_option                 = get_option( $new_option_id );
					$option_index_matches       = preg_match( '/\[(.*?)\]/', $field['id'], $match );
					$new_option_value           = isset( $match[1] ) ? $new_option[ $match[1] ] : $field['default'];
					$new_values[ $field['id'] ] = html_entity_decode( $new_option_value );
				} else {
					$new_values[ $field['id'] ] = html_entity_decode( get_option( $field['id'], isset( $field['default'] ) ? $field['default'] : '' ) );
				}
			}
			?>
			<script>
				let lrv = {
					newValues:<?php echo wp_json_encode( $new_values )?>,
					init: function () {
						var newValues = this.newValues;
						Object.keys(newValues).map(function (objectKey, index) {
							var value = newValues[objectKey];
							document.getElementById(objectKey).value = value;
						});
					}
				};
				document.addEventListener('DOMContentLoaded', function () {
					lrv.init();
				});
			</script>
			<?php
		}

		/**
		 * Sanitizes raw values on 'allow_raw_values' fields with htmlentities()
		 *
		 * @version 1.1.2
		 * @since 1.1.2
		 *
		 * @param $value
		 * @param $option
		 * @param $raw_value
		 *
		 * @return string
		 */
		public function sanitize_raw_values( $value, $option, $raw_value ) {
			if ( ! isset( $option['allow_raw_values'] ) ) {
				return $value;
			}
			$value = htmlentities( $raw_value );
			return $value;
		}

		/**
		 * Handles admin settings regarding free plugin
		 *
		 * @version 1.2.0
		 * @since   1.0.2
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function handle_admin_license_settings( $settings ) {
			if ( true !== apply_filters( 'ttt_pnwc_license_data', '', 'is_free' ) ) {
				return $settings;
			}

			// Premium Info
			$premium_info             = apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );

			// Add info on premium sections
			$premium_sections = wp_list_filter( $settings, array( 'premium_section' => true ) );
			foreach ( $premium_sections as $key => $section ) {				
				//$settings[ $key ]['desc'] = empty( $settings[ $key ]['desc'] ) ? $premium_info : $settings[ $key ]['desc'] . '  ' . $premium_info;
			}

			// Disable premium fields
			$fields = wp_list_filter( $settings, array( 'premium_field' => true ) );
			foreach ( $fields as $key => $field ) {
				if ( $field['type'] == 'text' || $field['type'] == 'textarea' ) {
					$settings[ $key ]['custom_attributes']['readonly'] = apply_filters( 'ttt_pnwc_license_data', '', 'readonly_attribute' );
				} else {
					$settings[ $key ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
				}
			}

			// General
			$index                      = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_general' ) ) );
			$settings[ $index ]['desc'] .= "  ".apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );

			// Hide default notices
			/*$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_hide_default_notices' ) ) );
			$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
			$settings[ $index ]['desc']                          .= "  ".apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );*/

			// Prevent Scrolling
			$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_prevent_scroll' ) ) );
			$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
			//$settings[ $index ]['desc']                          .= "  ".apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );

			// Prevent Scrolling
			$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_auto_close_time' ) ) );
			$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
			//$settings[ $index ]['desc']                          .= "  ".apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );

			// Ignored Messages
			$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_ignore_msg_field' ) ) );
			//$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
			//$settings[ $index ]['desc']                          .= apply_filters( 'ttt_pnwc_license_data', '', 'multiline_info' );

			// Ignored Messages regex
			$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_ignore_msg_regex' ) ) );
			//$settings[ $index ]['desc']                          .= "  ".apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );

			// Restrictive Loading
			//$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_restrictive_loading_pages' ) ) );
			//$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
			//$settings[ $index ]['desc']                          .= "  ".apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );




			return $settings;
		}

		/**
		 * Get sections
		 *
		 * @return array
		 */
		public function get_sections() {

			$sections = array(
				''         => __( 'General', 'popup-notices-for-woocommerce' ),
				//'messages' => __( 'Messages', 'popup-notices-for-woocommerce' )
			);

			return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
		}

		/**
		 * Get settings array
		 *
		 * @since 1.2.0
		 *
		 * @param string $current_section Optional. Defaults to empty string.
		 *
		 * @return array Array of settings
		 */
		public function get_settings( $current_section = '' ) {



			//if ( 'messages' == $current_section ) {

				/**
				 * Filter Plugin Section 2 Settings
				 *
				 * @since 1.0.0
				 *
				 * @param array $settings Array of the plugin settings
				 */
				/*$settings = apply_filters( 'ttt_pnwc_settings_messages', array(

					array(
						'name' => __( 'Message Customization', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'ttt_pnwc_opt_message_customization',
					),

					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_message_customization_enable',
						'name'    => __( 'Customize Messages', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Customize Notice messages', 'popup-notices-for-woocommerce' ),
						'default' => 'no',
					),

					array(
						'type'              => 'number',
						'id'                => 'ttt_pnwc_opt_message_customization_amount',
						'name'              => __( 'Amount', 'popup-notices-for-woocommerce' ),
						'desc'              => __( 'Number of messages you want to customize', 'popup-notices-for-woocommerce' ),
						'custom_attributes' => array( 'min' => 1 ),
						'default'           => 1,
					),

					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_message_customization'
					),

					array(
						'name' => __( 'Messages', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'ttt_pnwc_opt_custom_messages',
					),

					apply_filters( 'ttt_pnwc_custom_messages', array() ),

					// Dynamic messages

					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_custom_messages'
					),

				) );*/

			//} else {
			if ( $current_section == '' ) {

				/**
				 * Filter Plugin Section 1 Settings
				 *
				 * @since 1.0.0
				 *
				 * @param array $settings Array of the plugin settings
				 */
				$settings = apply_filters( 'ttt_pnwc_settings_general', array(

					array(
						'name' => __( 'Pop-up Notices General Options', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'premium_info'=>true,
						//'desc' => __( 'General Options', 'popup-notices-for-woocommerce' ),
						'desc'=>'',
						'id'   => 'ttt_pnwc_opt_general',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_enable',
						'name'    => __( 'Enable Plugin', 'popup-notices-for-woocommerce' ),
						'desc'    => sprintf( __( 'Enable %s plugin', 'popup-notices-for-woocommerce' ), '<strong>' . __( 'Pop-up Notices for WooCommerce', 'popup-notices-for-woocommerce' ) . '</strong>' ),
						//'class'    => 'wc-enhanced-select',
						'default' => 'yes',
					),
					/*array(
						'type'          => 'checkbox',
						'id'            => 'ttt_pnwc_opt_hide_default_notices',
						'premium_field' => true,
						'name'          => __( 'Hide default notices', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Hide default WooCommerce notices', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),*/
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_prevent_scroll',
						'name'          => __( 'Prevent Scrolling', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Prevent scrolling when WooCommerce displays notices', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'Only works for AJAX notices.', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type'     => 'checkbox',
						'id'       => 'ttt_pnwc_opt_ajax',
						'name'     => __( 'AJAX pop-up', 'popup-notices-for-woocommerce' ),
						'desc'     => __( 'Display Pop-up notices from AJAX requests', 'popup-notices-for-woocommerce' ),
						'desc_tip' => __( 'Notices displayed without reloading the page.', 'popup-notices-for-woocommerce' ) . '<br />' . __( 'e.g Error notices displayed on cart update or if something goes wrong in checkout.', 'popup-notices-for-woocommerce' ),
						'default'  => 'yes',
					),
					array(
						'type'          => 'number',
						'id'            => 'ttt_pnwc_opt_auto_close_time',
						'name'          => __( 'Auto-Close Time', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Auto-closes the popup after x seconds.', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'Leave it empty to disable auto-close.', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'default'       => 'yes',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_general'
					),

					// Notice Types
					array(
						'name' => __( 'Restrictive Loading', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'premium_section' => true,
						'desc' => __( 'Load the plugin at some specific moment or place', 'popup-notices-for-woocommerce' ),
						'id'   => 'ttt_pnwc_opt_restrictive_loading',
					),
					array(
						'name'     => __( 'Page(s)', 'popup-notices-for-woocommerce' ),
						'desc_tip' => __( 'Leave it empty to load the plugin in all pages', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'type'     => 'multiselect',
						'class'    => 'chosen_select',
						'options'  => wp_list_pluck( get_pages( array(
							'post_status' => 'publish'
						) ), 'post_title', 'ID' ),
						'id'       => 'ttt_pnwc_opt_restrictive_loading_pages',
					),
					array(
						'name'     => __( 'Conditional(s)', 'popup-notices-for-woocommerce' ),
						'desc_tip' => __( 'Load the plugin if some specific condition is true, including WooCommerce conditions, like if current page is product page and so on', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'type'     => 'multiselect',
						'class'    => 'chosen_select',
						'options'  => apply_filters( 'ttt_pnwc_conditionals_options', array() ),
						'id'       => 'ttt_pnwc_opt_restrictive_loading_conditionals',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_restrictive_loading'
					),

					// Notice Types
					array(
						'name' => __( 'Notice Types', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'desc' => __( 'Notice types that can be displayed on Pop-ups', 'popup-notices-for-woocommerce' ),
						'id'   => 'ttt_pnwc_opt_types',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_type_error_enable',
						'name'    => __( 'Enable error notices', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Enable error notices', 'popup-notices-for-woocommerce' ),
						'default' => 'yes',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_type_success_enable',
						'name'    => __( 'Enable success notices', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Enable success notices', 'popup-notices-for-woocommerce' ),
						'default' => 'yes',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_type_info_enable',
						'name'    => __( 'Enable info notices', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Enable info notices', 'popup-notices-for-woocommerce' ),
						'default' => 'yes',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_types'
					),

					// Notice Hiding
					array(
						'name' => __( 'Notice Hiding', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'premium_section' => true,
						'desc' => __( 'Hide default WooCommerce Notices', 'popup-notices-for-woocommerce' ),
						'id'   => 'ttt_pnwc_opt_notice_hiding',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_hide_error_enable',
						'name'          => __( 'Hide Error notices', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Hide error notices', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_hide_success_enable',
						'name'          => __( 'Hide Success notices', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Hide success notices', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_hide_info_enable',
						'name'          => __( 'Hide info notices', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Hide info notices', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_notice_hiding'
					),

					// Ignore Messages
					array(
						'name'            => __( 'Ignore Messages', 'popup-notices-for-woocommerce' ),
						'type'            => 'title',
						'desc'            => __( "Messages or notices that will be ignored and will not be displayed inside the Pop-up", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_ignore_msg',
					),
					array(
						'type'                    => 'text',
						'allow_raw_values'        => true,
						'premium_multiline_field' => true,
						'id'                      => 'ttt_pnwc_opt_ignore_msg_field',
						'name'                    => __( 'Ignored Messages', 'popup-notices-for-woocommerce' ),
						'desc'                    => '',
						'default'                 => '<p></p>',
						'css'                     => 'width:100%',

						//'desc' => __( 'HTML template reponsible for displaying the modal', 'popup-notices-for-woocommerce' ),
						//'css'      => 'min-height:223px;width:100%',
						//'default'  => $this->get_default_template(),
					),
					array(
						'name'            => __( 'Regular Expression', 'popup-notices-for-woocommerce' ),
						'premium_field'   => true,
						'type'            => 'checkbox',
						'desc'            => __( "Use Regular Expressions in your search", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_ignore_msg_regex',
						'default'         => 'no'
					),
					array(
						'name'            => __( 'Regular Expression Flags', 'popup-notices-for-woocommerce' ),
						'type'            => 'text',
						'desc'            => __( "Flags used on Regular Expression", 'popup-notices-for-woocommerce' ),
						'desc_tip'        => __( "Requires Regular Expression to be enabled", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_ignore_msg_regex_f',
						'default'         => 'i'
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_ignore_msg'
					),

					// Custom style
					array(
						'name'            => __( 'Custom style', 'popup-notices-for-woocommerce' ),
						'premium_section' => true,
						'type'            => 'title',
						'desc'            => __( 'Style the pop-up using the Customizer</a>', 'popup-notices-for-woocommerce' ),
						//'desc' => sprintf( __( 'Style the pop-up using the <a href="%s">Customizer</a>', 'popup-notices-for-woocommerce' ), add_query_arg( array( 'autofocus[panel]' => 'ttt_pnwc' ), admin_url( 'customize.php' ) ) ),
						'id'              => 'ttt_pnwc_opt_style',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_style_enabled',
						'name'          => __( 'Enable Custom Style', 'popup-notices-for-woocommerce' ),
						'desc'          => sprintf( __( 'Enable pop-up custom style using the <a href="%s">Customizer</a>', 'popup-notices-for-woocommerce' ), add_query_arg( array( 'autofocus[panel]' => 'ttt_pnwc' ), admin_url( 'customize.php' ) ) ),
						'default'       => 'yes'
					),
					array(
						'type'          => 'checkbox',
						'id'            => 'ttt_pnwc_opt_fa',
						'premium_field' => true,
						'name'          => __( 'Use Font Awesome', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Check if you want to choose icons from FontAwesome', 'popup-notices-for-woocommerce' ),
						'default'       => 'no'
					),
					array(
						'type'          => 'text',
						'id'            => 'ttt_pnwc_opt_fa_url',
						'premium_field' => true,
						'name'          => __( 'Font Awesome URL', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'Leave it empty if you are already using Font Awesome from somewhere else and do not want to load it twice', 'popup-notices-for-woocommerce' ),
						'default'       => '//use.fontawesome.com/releases/v5.5.0/css/all.css'
					),
					array(
						'type'          => 'textarea',
						'id'            => 'ttt_pnwc_opt_modal_template',
						'premium_field' => true,
						'name'          => __( 'Modal Template', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'HTML template reponsible for displaying the modal', 'popup-notices-for-woocommerce' ),
						'css'           => 'min-height:223px;width:100%',
						'default'       => $this->get_default_template(),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_style'
					),

					// Cookie
					array(
						'name'            => __( 'Cookies', 'popup-notices-for-woocommerce' ),
						'premium_section' => true,
						'type'            => 'title',
						'desc'            => __( "Notices will be kept in Browser's cookies trying to prevent duplicated messages from being displayed repeatedly inside popups", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_cookie',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_cookie_enabled',
						'name'          => __( 'Enable', 'popup-notices-for-woocommerce' ),
						'desc'          => __( "Save messages in Browser's Cookies", 'popup-notices-for-woocommerce' ),
						'default'       => 'no'
					),
					array(
						'type'              => 'number',
						'premium_field'     => true,
						'id'                => 'ttt_pnwc_opt_cookie_time',
						'name'              => __( 'Expiration Time', 'popup-notices-for-woocommerce' ),
						'desc'              => __( "Time in Hours messages will be kept in Cookies", 'popup-notices-for-woocommerce' ),
						'default'           => 0.5,
						'custom_attributes' => array(
							'step' => '.01'
						)
					),
					array(
						'type'          => 'select',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_cookie_msg_origin',
						'name'          => __( 'Message Origin', 'popup-notices-for-woocommerce' ),
						'desc'          => __( "Type of messages that will be kept on Cookies, static or dynamic, i.e created on ajax", 'popup-notices-for-woocommerce' ),
						'options'       => array(
							'static'  => __( 'Static', 'popup-notices-for-woocommerce' ),
							'dynamic' => __( 'Dynamic', 'popup-notices-for-woocommerce' ),
							'all'     => __( 'All', 'popup-notices-for-woocommerce' ),
						),
						'default'       => 'static'
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_cookie'
					),

					// Sounds
					array(
						'name'            => __( 'Audio', 'popup-notices-for-woocommerce' ),
						'premium_section' => true,
						'type'            => 'title',
						'desc'            => __( "Play Sounds", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_audio',
					),
					array(
						'name'            => __( 'Enable', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'type'            => 'checkbox',
						'desc'            => __( "Enable Audio", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_audio_enable',
						'default'         => 'no'
					),
					array(
						'type'          => 'text',
						'id'            => 'ttt_pnwc_opt_audio_opening',
						'premium_field' => true,
						'name'          => __( 'Opening', 'popup-notices-for-woocommerce' ),
						'desc'      => __( 'Sound URL when Pop-up opens', 'popup-notices-for-woocommerce' ),
						'default'       => ''
					),
					array(
						'type'          => 'text',
						'id'            => 'ttt_pnwc_opt_audio_closing',
						'premium_field' => true,
						'name'          => __( 'Closing', 'popup-notices-for-woocommerce' ),
						'desc'      => __( 'Sound URL when Pop-up closes', 'popup-notices-for-woocommerce' ),
						'default'       => ''
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_audio'
					),

				) );


			} else {
				$settings = apply_filters( "ttt_pnwc_settings_{$current_section}", array() );
			}

			/**
			 * Filter MyPlugin Settings
			 *
			 * @since 1.0.0
			 *
			 * @param array $settings Array of the plugin settings
			 */
			return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );

		}

		public function get_default_template() {
			$template_obj = new Template();
			return $template_obj->get_default_template();
		}

		/**
		 * Output the settings
		 *
		 * @since 1.0
		 */
		public function output() {

			global $current_section;

			$settings = $this->get_settings( $current_section );
			\WC_Admin_Settings::output_fields( $settings );
		}


		/**
		 * Save settings
		 *
		 * @since 1.0
		 */
		public function save() {

			global $current_section;

			$settings = $this->get_settings( $current_section );
			\WC_Admin_Settings::save_fields( $settings );
		}
	}
}