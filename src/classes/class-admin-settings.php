<?php
/**
 * Popup Notices for WooCommerce (TTT) - Admin Settings
 *
 * @version 1.0.0
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
			$this->label = __( 'Popup Notices', 'popup-notices-for-woocommerce' );

			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
			add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
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
		 * @since 1.0.0
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
						'name' => __( 'Popup Notices General Options', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						//'desc' => __( 'General Options', 'popup-notices-for-woocommerce' ),
						'id'   => 'ttt_pnwc_opt_general',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_enable',
						'name'    => __( 'Enable Plugin', 'popup-notices-for-woocommerce' ),
						'desc'    => sprintf( __( 'Enables %s plugin', 'popup-notices-for-woocommerce' ), '<strong>' . __( 'Popup Notices for WooCommerce', 'popup-notices-for-woocommerce' ) . '</strong>' ),
						//'class'    => 'wc-enhanced-select',
						'default' => 'yes',
					),
					array(
						'type'              => 'checkbox',
						'id'                => 'ttt_pnwc_opt_hide_default_notices',
						'name'              => __( 'Hide default notices', 'popup-notices-for-woocommerce' ),
						'desc'              => __( 'Hides default WooCommerce notices', 'popup-notices-for-woocommerce' ),
						'custom_attributes' => apply_filters( 'ttt_pnwc_license_type_data', array( 'disabled' => 'disabled' ), 'disabled_attribute', 'free' ),
						'default'           => 'no',
					),
					array(
						'type'              => 'checkbox',
						'id'                => 'ttt_pnwc_opt_ajax',
						'name'              => __( 'AJAX Popup', 'popup-notices-for-woocommerce' ),
						'desc'              => __( 'Displays Popup notices from AJAX requests', 'popup-notices-for-woocommerce' ),
						'desc_tip'          => __( 'Notices displayed without reloading the page.', 'popup-notices-for-woocommerce' ) . '<br />' . __( 'e.g Error notices displayed on cart update or if something goes wrong in checkout', 'popup-notices-for-woocommerce' ),
						'custom_attributes' => apply_filters( 'ttt_pnwc_license_type_data', array( 'disabled' => 'disabled' ), 'disabled_attribute', 'free' ),
						'default'           => 'no',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_general'
					),

					// Notice Types
					array(
						'name' => __( 'Notice Types', 'popup-notices-for-woocommerce' ),
						'type' => 'title',
						'desc' => __( 'Notice types that can be displayed on Popups', 'popup-notices-for-woocommerce' ),
						'id'   => 'ttt_pnwc_opt_types',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_type_error_enable',
						'name'    => __( 'Enable error notices', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Enables error notices', 'popup-notices-for-woocommerce' ),
						'default' => 'yes',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_type_success_enable',
						'name'    => __( 'Enable success notices', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Enables success notices', 'popup-notices-for-woocommerce' ),
						'default' => 'yes',
					),
					array(
						'type'    => 'checkbox',
						'id'      => 'ttt_pnwc_opt_type_info_enable',
						'name'    => __( 'Enable info notices', 'popup-notices-for-woocommerce' ),
						'desc'    => __( 'Enables info notices', 'popup-notices-for-woocommerce' ),
						'default' => 'yes',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ttt_pnwc_opt_types'
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