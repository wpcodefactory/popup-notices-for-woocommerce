<?php
/**
 * Pretty WooCommerce Notices (TTT) - Admin Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PWCN;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PWCN\Admin_Settings' ) ) {

	class Admin_Settings extends \WC_Settings_Page {

		/**
		 * Setup settings class
		 *
		 * @since  1.0
		 */
		public function __construct() {

			$this->id    = 'pretty-wc-notices';
			$this->label = __( 'Notices', 'pretty-wc-notices-ttt' );

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
				''       => __( 'Section 1', 'pretty-wc-notices-ttt' ),
				'second' => __( 'Section 2', 'pretty-wc-notices-ttt' )
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
				$settings = apply_filters( 'myplugin_section2_settings', array(

					array(
						'name' => __( 'Group 1', 'pretty-wc-notices-ttt' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'myplugin_group1_options',
					),

					array(
						'type'    => 'checkbox',
						'id'      => 'myplugin_checkbox_1',
						'name'    => __( 'Do a thing?', 'pretty-wc-notices-ttt' ),
						'desc'    => __( 'Enable to do something', 'pretty-wc-notices-ttt' ),
						'default' => 'no',
					),

					array(
						'type' => 'sectionend',
						'id'   => 'myplugin_group1_options'
					),

					array(
						'name' => __( 'Group 2', 'pretty-wc-notices-ttt' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'myplugin_group2_options',
					),

					array(
						'type'     => 'select',
						'id'       => 'myplugin_select_1',
						'name'     => __( 'What should happen?', 'pretty-wc-notices-ttt' ),
						'options'  => array(
							'something' => __( 'Something', 'pretty-wc-notices-ttt' ),
							'nothing'   => __( 'Nothing', 'pretty-wc-notices-ttt' ),
							'idk'       => __( 'IDK', 'pretty-wc-notices-ttt' ),
						),
						'class'    => 'wc-enhanced-select',
						'desc_tip' => __( 'Don\'t ask me!', 'pretty-wc-notices-ttt' ),
						'default'  => 'idk',
					),

					array(
						'type' => 'sectionend',
						'id'   => 'myplugin_group2_options'
					),

				) );

			} else {

				/**
				 * Filter Plugin Section 1 Settings
				 *
				 * @since 1.0.0
				 *
				 * @param array $settings Array of the plugin settings
				 */
				$settings = apply_filters( 'myplugin_section1_settings', array(

					array(
						'name' => __( 'Important Stuff', 'pretty-wc-notices-ttt' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'myplugin_important_options',
					),

					array(
						'type'     => 'select',
						'id'       => 'myplugin_select_1',
						'name'     => __( 'Choose your favorite', 'pretty-wc-notices-ttt' ),
						'options'  => array(
							'vanilla'    => __( 'Vanilla', 'pretty-wc-notices-ttt' ),
							'chocolate'  => __( 'Chocolate', 'pretty-wc-notices-ttt' ),
							'strawberry' => __( 'Strawberry', 'pretty-wc-notices-ttt' ),
						),
						'class'    => 'wc-enhanced-select',
						'desc_tip' => __( 'Be honest!', 'pretty-wc-notices-ttt' ),
						'default'  => 'vanilla',
					),

					array(
						'type' => 'sectionend',
						'id'   => 'myplugin_important_options'
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