<?php
/**
 * Prismleaf Customizer: Footer Options
 *
 * Registers the Footer Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_footer_options_section' ) ) {
	/**
	 * Register the Footer Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_footer_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_footer_options',
				'title'       => __( 'Footer', 'prismleaf' ),
				'description' => __( 'Configure footer layout and styling.', 'prismleaf' ),
				'priority'    => 40,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_footer_heading_layout',
				'label'      => __( 'Layout', 'prismleaf' ),
				'section'    => 'prismleaf_footer_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_show',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Show footer', 'prismleaf' ),
				'description'      => __( 'Controls whether the footer is displayed.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'footer_show',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_contained',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Contain footer', 'prismleaf' ),
				'description'      => __( 'When enabled, the footer is rendered inside the inner frame.', 'prismleaf' ),
				'priority'         => 1020,
				'default_key'      => 'footer_contained',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_footer_layout_control_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_floating',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Floating footer', 'prismleaf' ),
				'description'      => __( 'When disabled, the footer stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
				'priority'         => 1030,
				'default_key'      => 'footer_floating',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_footer_layout_control_active',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_footer_options_section' );
