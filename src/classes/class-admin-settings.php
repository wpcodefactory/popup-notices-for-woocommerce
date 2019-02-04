<?php
/**
 * Pop-up Notices for WooCommerce (TTT) - Admin Settings
 *
 * @version 1.1.0
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
		}

		/**
		 * Handles admin settings regarding free plugin
		 *
		 * @version 1.1.0
		 * @since 1.0.2
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
				$settings[ $key ]['desc'] .= apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );
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

			// Hide default notices
			$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_hide_default_notices' ) ) );
			$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
			$settings[ $index ]['desc']                          .= apply_filters( 'ttt_pnwc_license_data', '', 'premium_info' );

			// Ignored Messages
			$index                                               = key( wp_list_filter( $settings, array( 'id' => 'ttt_pnwc_opt_ignore_msg_field' ) ) );
			//$settings[ $index ]['custom_attributes']['disabled'] = apply_filters( 'ttt_pnwc_license_data', '', 'disabled_attribute' );
			$settings[ $index ]['desc']                          .= apply_filters( 'ttt_pnwc_license_data', '', 'multiline_info' );


			return $settings;
		}

		/**
		 * Get sections
		 *
		 * @return array
		 */
		public function get_sections() {

			$sections = array(
				'' => __( 'General', 'popup-notices-for-woocommerce' ),
				//'second' => __( 'Section 2', 'popup-notices-for-woocommerce' )
			);

			return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
		}

		/**
		 * Get settings array
		 *
		 * @since 1.1.0
		 *
		 * @param string $current_section Optional. Defaults to empty string.
		 *
		 * @return array Array of settings
		 */
		public function get_settings( $current_section = '' ) {

			if ( 'second' == $current_section ) {

				/**
				 * Filter Plugin Section 2 Settings
				 *
				 * @since 1.0.0
				 *
				 * @param array $settings Array of the plugin settings
				 */
				/*$settings = apply_filters( 'myplugin_section2_settings', array(

					array(
						'name' => __( 'Group 1', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'myplugin_group1_options',
					),

					array(
						'type'    => 'checkbox',
						'id'      => 'myplugin_checkbox_1',
						'name'    => __( 'Do a thing?', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Enable to do something', 'popup-notices-for-woocommerce' ),
						'default' => 'no',
					),

					array(
						'type' => 'sectionend',
						'id'   => 'myplugin_group1_options'
					),

					array(
						'name' => __( 'Group 2', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'myplugin_group2_options',
					),

					array(
						'type'     => 'select',
						'id'       => 'myplugin_select_1',
						'name'     => __( 'What should happen?', 'popup-notices-for-woocommerce' ),
						'options'  => array(
							'something' => __( 'Something', 'popup-notices-for-woocommerce' ),
							'nothing'   => __( 'Nothing', 'popup-notices-for-woocommerce' ),
							'idk'       => __( 'IDK', 'popup-notices-for-woocommerce' ),
						),
						'class'    => 'wc-enhanced-select',
						'desc_tip' => __( 'Don\'t ask me!', 'popup-notices-for-woocommerce' ),
						'default'  => 'idk',
					),

					array(
						'type' => 'sectionend',
						'id'   => 'myplugin_group2_options'
					),

				) );*/

			} else {

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
						//'desc' => __( 'General Options', 'popup-notices-for-woocommerce' ),
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
					array(
						'type'          => 'checkbox',
						'id'            => 'ttt_pnwc_opt_hide_default_notices',
						'premium_field' => true,
						'name'          => __( 'Hide default notices', 'popup-notices-for-woocommerce' ),
						'desc'          => __( 'Hide default WooCommerce notices', 'popup-notices-for-woocommerce' ),
						'default'       => 'no',
					),
					array(
						'type'     => 'checkbox',
						'id'       => 'ttt_pnwc_opt_ajax',
						'name'     => __( 'AJAX pop-up', 'popup-notices-for-woocommerce' ),
						'desc'     => __( 'Display Pop-up notices from AJAX requests', 'popup-notices-for-woocommerce' ),
						'desc_tip' => __( 'Notices displayed without reloading the page.', 'popup-notices-for-woocommerce' ) . '<br />' . __( 'e.g Error notices displayed on cart update or if something goes wrong in checkout', 'popup-notices-for-woocommerce' ),
						'default'  => 'yes',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_general'
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

					// Ignore Messages
					array(
						'name'            => __( 'Ignore Messages', 'popup-notices-for-woocommerce' ),
						'type'            => 'title',
						'desc'            => __( "Messages or notices that will be ignored and will not be displayed inside the pop-up", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_ignore_msg',
					),
					array(
						'type'          => 'text',
						'id'            => 'ttt_pnwc_opt_ignore_msg_field',
						'name'          => __( 'Ignored Messages', 'popup-notices-for-woocommerce' ),
						'desc'          => '',
						//'desc' => __( 'HTML template reponsible for displaying the modal', 'popup-notices-for-woocommerce' ),
						//'css'      => 'min-height:223px;width:100%',
						//'default'  => $this->get_default_template(),
					),
					array(
						'name'            => __( 'Regular Expression', 'popup-notices-for-woocommerce' ),
						'type'            => 'checkbox',
						'desc'            => __( "Use Regular Expressions on your search", 'popup-notices-for-woocommerce' ),
						'id'              => 'ttt_pnwc_opt_ignore_msg_regex',
						'default'         => 'yes'
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
						'desc'            => __( "Notices will be kept in Browser's cookies trying to prevent messages from being displayed repeatedly inside popups", 'popup-notices-for-woocommerce' ),
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

				) );


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