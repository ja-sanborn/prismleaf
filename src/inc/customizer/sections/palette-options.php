<?php
/**
 * Prismleaf Customizer: Palette Options
 *
 * Registers the Palette Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_palette_options_section' ) ) {
	/**
	 * Register the Palette Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_palette_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_palette_options',
				'title'       => __( 'Palette Colors', 'prismleaf' ),
				'description' => __( 'Configure the theme color palette.', 'prismleaf' ),
				'priority'    => 10,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_palette_heading_theme_mode',
				'label'      => __( 'Theme Mode', 'prismleaf' ),
				'section'    => 'prismleaf_palette_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_palette_theme_mode',
				'section'           => 'prismleaf_palette_options',
				'label'             => __( 'Select Mode', 'prismleaf' ),
				'description'       => __( 'Choose whether the theme follows the visitor system preference or enforces a light or dark appearance.', 'prismleaf' ),
				'priority'          => 1010,
				'default_key'       => 'palette_theme_mode',
				'default_fallback'  => 'system',
				'sanitize_callback' => 'prismleaf_sanitize_theme_mode',
				'choices'           => array(
					'system' => __( 'System (Default)', 'prismleaf' ),
					'light'  => __( 'Light Override', 'prismleaf' ),
					'dark'   => __( 'Dark Override', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_palette_show_theme_switch',
				'section'          => 'prismleaf_palette_options',
				'label'            => __( 'Show theme switcher', 'prismleaf' ),
				'description'      => __( 'Display the theme mode toggle button in the header.', 'prismleaf' ),
				'priority'         => 1020,
				'default_key'      => 'palette_show_theme_switch',
				'default_fallback' => true,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_palette_heading_colors',
				'label'      => __( 'Colors', 'prismleaf' ),
				'section'    => 'prismleaf_palette_options',
				'priority'   => 2000,
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_primary_base',
				'palette_setting_id' => 'prismleaf_palette_primary_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Primary', 'prismleaf' ),
				'priority'           => 2010,
				'default_key'        => 'palette_primary_base',
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_secondary_base',
				'palette_setting_id' => 'prismleaf_palette_secondary_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Secondary', 'prismleaf' ),
				'priority'           => 2020,
				'default_key'        => 'palette_secondary_base',
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_tertiary_base',
				'palette_setting_id' => 'prismleaf_palette_tertiary_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Tertiary', 'prismleaf' ),
				'priority'           => 2030,
				'default_key'        => 'palette_tertiary_base',
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_error_base',
				'palette_setting_id' => 'prismleaf_palette_error_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Error', 'prismleaf' ),
				'priority'           => 2040,
				'default_key'        => 'palette_error_base',
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_warning_base',
				'palette_setting_id' => 'prismleaf_palette_warning_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Warning', 'prismleaf' ),
				'priority'           => 2050,
				'default_key'        => 'palette_warning_base',
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_info_base',
				'palette_setting_id' => 'prismleaf_palette_info_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Info', 'prismleaf' ),
				'priority'           => 2060,
				'default_key'        => 'palette_info_base',
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_neutral_light_base',
				'palette_setting_id' => 'prismleaf_palette_neutral_light_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Neutral Light', 'prismleaf' ),
				'priority'           => 2070,
				'default_key'        => 'palette_neutral_light_base',
			)
		);

		prismleaf_add_palette_preview_control_with_defaults(
			$wp_customize,
			array(
				'setting_id'         => 'prismleaf_palette_neutral_dark_base',
				'palette_setting_id' => 'prismleaf_palette_neutral_dark_values',
				'section'            => 'prismleaf_palette_options',
				'label'              => __( 'Neutral Dark', 'prismleaf' ),
				'priority'           => 2080,
				'default_key'        => 'palette_neutral_dark_base',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_palette_options_section' );
