<?php
/**
 * Prismleaf Customizer: Header Options
 *
 * Registers the Header Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_header_options_section' ) ) {
	/**
	 * Register the Header Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_header_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_header_options',
				'title'       => __( 'Header', 'prismleaf' ),
				'description' => __( 'Configure header layout and styling.', 'prismleaf' ),
				'priority'    => 30,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_header_heading_layout',
				'label'      => __( 'Layout', 'prismleaf' ),
				'section'    => 'prismleaf_header_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_show',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Show header', 'prismleaf' ),
				'description'      => __( 'Controls whether the header is displayed.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'header_show',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_contained',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Contain header', 'prismleaf' ),
				'description'      => __( 'When enabled, the header is rendered inside the inner frame.', 'prismleaf' ),
				'priority'         => 1020,
				'default_key'      => 'header_contained',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_header_layout_control_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_floating',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Floating header', 'prismleaf' ),
				'description'      => __( 'When disabled, the header stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
				'priority'         => 1030,
				'default_key'      => 'header_floating',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_header_layout_control_active',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_header_options_section' );
