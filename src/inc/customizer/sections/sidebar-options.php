<?php
/**
 * Prismleaf Customizer: Sidebar Options
 *
 * Registers the Sidebar Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_sidebar_options_section' ) ) {
	/**
	 * Register the Sidebar Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_sidebar_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_sidebar_options',
				'title'       => __( 'Sidebar', 'prismleaf' ),
				'description' => __( 'Configure left and right sidebar layout and styling.', 'prismleaf' ),
				'priority'    => 50,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_sidebar_heading_layout',
				'label'      => __( 'Sidebar Layout', 'prismleaf' ),
				'section'    => 'prismleaf_sidebar_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_swap',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Swap primary and secondary sidebar', 'prismleaf' ),
				'description'      => __( 'Moves primary from left to right and secondary from right to left when enabled.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'sidebar_swap',
				'default_fallback' => false,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_sidebar_heading_primary_layout',
				'label'      => __( 'Primary Layout', 'prismleaf' ),
				'section'    => 'prismleaf_sidebar_options',
				'priority'   => 2000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_primary_show',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Show primary sidebar', 'prismleaf' ),
				'description'      => __( 'Controls whether the primary sidebar is displayed.', 'prismleaf' ),
				'priority'         => 2010,
				'default_key'      => 'sidebar_primary_show',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_primary_contained',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Contain primary sidebar', 'prismleaf' ),
				'description'      => __( 'When enabled, the primary sidebar is rendered inside the inner frame.', 'prismleaf' ),
				'priority'         => 2020,
				'default_key'      => 'sidebar_primary_contained',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_sidebar_primary_layout_control_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_primary_floating',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Floating primary sidebar', 'prismleaf' ),
				'description'      => __( 'When disabled, the primary sidebar stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
				'priority'         => 2030,
				'default_key'      => 'sidebar_primary_floating',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_sidebar_primary_layout_control_active',
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_sidebar_heading_secondary_layout',
				'label'      => __( 'Secondary Layout', 'prismleaf' ),
				'section'    => 'prismleaf_sidebar_options',
				'priority'   => 3000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_show',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Show secondary sidebar', 'prismleaf' ),
				'description'      => __( 'Controls whether the secondary sidebar is displayed.', 'prismleaf' ),
				'priority'         => 3010,
				'default_key'      => 'sidebar_secondary_show',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_contained',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Contain secondary sidebar', 'prismleaf' ),
				'description'      => __( 'When enabled, the secondary sidebar is rendered inside the inner frame.', 'prismleaf' ),
				'priority'         => 3020,
				'default_key'      => 'sidebar_secondary_contained',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_sidebar_secondary_layout_control_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_floating',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Floating secondary sidebar', 'prismleaf' ),
				'description'      => __( 'When disabled, the secondary sidebar stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
				'priority'         => 3030,
				'default_key'      => 'sidebar_secondary_floating',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_sidebar_secondary_layout_control_active',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_sidebar_options_section' );
