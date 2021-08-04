<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Admin Settings
 *
 * @version 1.3.2
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
		 * @version 1.2.4
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
		}

		/**
		 * Sanitizes raw values on 'allow_raw_values' fields with htmlentities()
		 *
		 * @version 1.2.5
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
			$value = wp_kses_post( trim( $raw_value ) );
			return $value;
		}

		/**
		 * Handles admin settings regarding free plugin
		 *
		 * @version 1.3.2
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

			// Prevent Scrolling
			$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_prevent_scroll' ) ) );
			$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );

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
		 * @since 1.3.2
		 *
		 * @param string $current_section Optional. Defaults to empty string.
		 *
		 * @return array Array of settings
		 */
		public function get_settings( $current_section = '' ) {
			//if ( 'messages' == $current_section ) {
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
						'type'     => 'checkbox',
						'id'       => 'ttt_pnwc_opt_ajax',
						'name'     => __( 'AJAX pop-up', 'popup-notices-for-woocommerce' ),
						'desc'     => __( 'Display Pop-up notices from AJAX requests', 'popup-notices-for-woocommerce' ),
						'desc_tip' => __( 'Notices displayed without reloading the page.', 'popup-notices-for-woocommerce' ) . '<br />' . __( 'e.g Error notices displayed on cart update or if something goes wrong in checkout.', 'popup-notices-for-woocommerce' ),
						'default'  => 'yes',
					),
					array(
						'type'     => 'checkbox',
						'id'       => 'ttt_pnwc_opt_click_inside_close',
						'name'     => __( 'Close on click inside', 'popup-notices-for-woocommerce' ),
						'desc'     => __( 'Close popup if a button or a link is clicked inside the popup', 'popup-notices-for-woocommerce' ),
						'default'  => 'no',
					),
					array(
						'type'     => 'checkbox',
						'id'       => 'ttt_pnwc_opt_prevent_closing_if_clicking_out',
						'name'     => __( 'Prevent closing if clicking outside', 'popup-notices-for-woocommerce' ),
						'desc'     => __( 'Prevent closing the popup when clicking on the overlay outside the popup', 'popup-notices-for-woocommerce' ),
						'desc_tip' => sprintf( __( 'If it doesn\'t work, try to remove the %s attribute from the %s div from the %s option.', 'popup-notices-for-woocommerce' ), '<code>data-micromodal-close</code>', '<code>ttt-pnwc-overlay</code>', '"' . __( 'Modal template', 'popup-notices-for-woocommerce' ) . '"' ),
						'default'  => 'no',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_prevent_scroll',
						'name'          => __( 'Prevent scrolling', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Prevent scrolling when WooCommerce displays notices', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'Only works for AJAX notices.', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type'          => 'select',
						'id'            => 'ttt_pnwc_opt_micromodal_load_method',
						'name'          => __( 'Micromodal loading method', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'How the micromodal library is going to be loaded.', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'Micromodal is the library responsible for the popups.', 'popup-notices-for-woocommerce' ),
						'default'       => 'externally',
						'options'       => array(
							'externally' => __( 'Externally from unpkg.com', 'popup-notices-for-woocommerce' ),
							'locally'    => __( 'Locally', 'popup-notices-for-woocommerce' ),
						),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_general'
					),

					// Auto close
					array(
						'name' => __( 'Auto-close', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'premium_info'=>true,
						'desc' => __( 'Auto-closes the popup after x seconds.', 'popup-notices-for-woocommerce' ),
						'id'   => 'ttt_pnwc_opt_general',
					),
					array(
						'type'          => 'number',
						'id'            => 'ttt_pnwc_opt_auto_close_time',
						'name'          => __( 'Auto-close time', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Auto-closes the popup after x seconds.', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'Leave it empty to disable auto-close.', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'default'       => 'yes',
					),
					array(
						'name'          => __( 'Notice types', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'Only pop-ups containing at least one of the selected notice types will auto-close.', 'popup-notices-for-woocommerce' ).'<br />'.__( 'If empty, the auto-close will work regardless of the notice type.', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'type'          => 'multiselect',
						'class'         => 'chosen_select',
						'options'       => array(
							'error'   => __( 'Error', 'popup-notices-for-woocommerce' ),
							'success' => __( 'Success', 'popup-notices-for-woocommerce' ),
							'info'    => __( 'Info', 'popup-notices-for-woocommerce' ),
						),
						'id'            => 'ttt_pnwc_opt_auto_close_types',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_autoclose'
					),

					// Notice Types
					array(
						'name' => __( 'Restrictive Loading', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'premium_section' => true,
						'desc' => __( 'Load the plugin at some specific moment or place.', 'popup-notices-for-woocommerce' ),
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
						'name' => __( 'Notice types', 'popup-notices-for-woocommerce' ),
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
						'name' => __( 'Notice hiding', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'premium_section' => true,
						'desc' => __( 'Hide default WooCommerce Notices.', 'popup-notices-for-woocommerce' ),
						'id'   => 'ttt_pnwc_opt_notice_hiding',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_hide_error_enable',
						'name'          => __( 'Hide error notices', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Hide error notices', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_hide_success_enable',
						'name'          => __( 'Hide success notices', 'popup-notices-for-woocommerce' ),
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

					// AJAX Add to cart notice
					array(
						'name'            => __( 'AJAX add to cart notice', 'popup-notices-for-woocommerce' ),
						'type'            => 'title',
						'premium_section' => true,
						'desc'            => sprintf( __( 'By default, WooCommerce doesn\'t display the notice when a product has been <a href="%s">added to cart via AJAX on archive pages</a>.', 'popup-notices-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=products' ) ) . '<br />' .
						                     __( 'Below are the options of how you can enable and setup it.', 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_ajax_add_to_cart_notice',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_ajax_add_to_cart_notice_enable',
						'name'          => __( 'AJAX add to cart notice', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Display AJAX add to cart notice', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type'          => 'checkbox',
						'id'            => 'ttt_pnwc_opt_ajax_add_to_cart_notice_wrapper_smart',
						'name'          => __( 'Notices wrapper - Smart find', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Try to find the notices wrapper automatically', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => __( 'If it doesn\'t work, please disable it and use one of the options below.', 'popup-notices-for-woocommerce' ),
						'default'       => 'yes',
					),
					array(
						'type'          => 'text',
						'id'            => 'ttt_pnwc_opt_ajax_add_to_cart_notice_wrapper_hook',
						'name'          => __( 'Notices wrapper - Action hook', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Add the notice wrapper manually using a action hook.', 'popup-notices-for-woocommerce' ),
						'default'       => '',
					),
					array(
						'type'          => 'text',
						'id'            => 'ttt_pnwc_opt_ajax_add_to_cart_notice_wrapper_selector',
						'name'          => __( 'Notices wrapper - Selector', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Add the notice wrapper manually by specifying a DOM selector.', 'popup-notices-for-woocommerce' ),
						'desc_tip'      => sprintf( __( 'Probably %s would be a good guess.', 'popup-notices-for-woocommerce' ), '<code>' . '.woocommerce-notices-wrapper' . '</code>' ),
						'default'       => '',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_ajax_add_to_cart_notice'
					),

					// Ignore Messages
					array(
						'name'            => __( 'Ignore messages', 'popup-notices-for-woocommerce' ),
						'type'            => 'title',
						'desc'            => __( "Messages or notices that will be ignored and will not be displayed inside the Pop-up.", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_ignore_msg',
					),
					array(
						'name'     => __( 'Search method', 'popup-notices-for-woocommerce' ),
						'type'     => 'select',
						'desc'     => __( "The method used to check the message", 'popup-notices-for-woocommerce' ),
						'desc_tip' => __( "If you only need to check part of the message use \"Partial comparison\". Use \"Full comparison\" to check the whole message.", 'popup-notices-for-woocommerce' ),
						'id'       => 'ttt_pnwc_opt_ignore_search_method',
						'options'  => array(
							'partial_comparison' => __( 'Partial comparison', 'popup-notices-for-woocommerce' ),
							'full_comparison'    => __( 'Full comparison', 'popup-notices-for-woocommerce' ),
							'regex'              => __( 'Regular expression', 'popup-notices-for-woocommerce' ),
						),
						'default'  => 'partial_comparison',
						'class'    => 'wc-enhanced-select',
					),
					array(
						'type'                    => 'text',
						'allow_raw_values'        => true,
						'premium_multiline_field' => true,
						'id'                      => 'ttt_pnwc_opt_ignore_msg_field',
						'name'                    => __( 'Ignored messages', 'popup-notices-for-woocommerce' ),
						'desc'                    => __( '"Full comparison" search method requires to add the message here exactly as it is originally, including the HTML', 'popup-notices-for-woocommerce' ),
						'desc_tip'                => __( 'Add multiple messages on pro version. Leave it empty to disable.', 'popup-notices-for-woocommerce' ),
						'default'                 => '<p></p>',
						'css'                     => 'width:100%',
					),
					/*array(
						'name'          => __( 'Regular expression', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'type'          => 'checkbox',
						'desc'          => __( "Use Regular Expressions in your search", 'popup-notices-for-woocommerce' ),
						'desc_tip'      => sprintf( __( "If enabled, you don't need to add messages on the %s option exactly as they are originally. Only part of them is enough.", 'popup-notices-for-woocommerce' ), '<strong>Ignored messages</strong>' ),
						'id'            => 'ttt_pnwc_opt_ignore_msg_regex',
						'default'       => 'no'
					),*/
					array(
						'name'            => __( 'Regular expression flags', 'popup-notices-for-woocommerce' ),
						'type'            => 'text',
						'desc'            => __( "Flags used on Regular Expression", 'popup-notices-for-woocommerce' ),
						'desc_tip'        => __( "Requires Regular expression search method", 'popup-notices-for-woocommerce' ),
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
						'desc'            => __( 'Style the pop-up using the Customizer.', 'popup-notices-for-woocommerce' ),
						//'desc' => sprintf( __( 'Style the pop-up using the <a href="%s">Customizer</a>', 'popup-notices-for-woocommerce' ), add_query_arg( array( 'autofocus[panel]' => 'ttt_pnwc' ), admin_url( 'customize.php' ) ) ),
						'id'              => 'ttt_pnwc_opt_style',
					),
					array(
						'type'          => 'checkbox',
						'premium_field' => true,
						'id'            => 'ttt_pnwc_opt_style_enabled',
						'name'          => __( 'Enable custom style', 'popup-notices-for-woocommerce' ),
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
						'name'          => __( 'Modal template', 'popup-notices-for-woocommerce' ),
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
						'desc'            => __( "Notices will be kept in Browser's cookies trying to prevent duplicated messages from being displayed repeatedly inside popups.", 'popup-notices-for-woocommerce' ),
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
						'name'              => __( 'Expiration time', 'popup-notices-for-woocommerce' ),
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
						'name'          => __( 'Message origin', 'popup-notices-for-woocommerce' ),
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
						'desc'            => __( "Play sounds.", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_audio',
					),
					array(
						'name'            => __( 'Enable', 'popup-notices-for-woocommerce' ),
						'premium_field' => true,
						'type'            => 'checkbox',
						'desc'            => __( "Enable audio", 'popup-notices-for-woocommerce' ),
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