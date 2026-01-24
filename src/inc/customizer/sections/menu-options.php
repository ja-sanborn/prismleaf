<?php
/**
 * Prismleaf Customizer: Menu Styling
 *
 * Registers the Menu Styling section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_menu_options_section' ) ) {
	/**
	 * Register the Menu Styling section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_menu_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_menu_options',
				'title'       => __( 'Menu Styling', 'prismleaf' ),
				'description' => __( 'Control the appearance of the primary, secondary, and mobile menus.', 'prismleaf' ),
				'priority'    => 80,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_menu_primary_heading',
				'label'      => __( 'Primary Menu', 'prismleaf' ),
				'section'    => 'prismleaf_menu_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_menu_primary_background_color_source',
				'base_setting_id'          => 'prismleaf_menu_primary_background_color_base',
				'palette_setting_id'       => 'prismleaf_menu_primary_background_color_palette',
				'section'                  => 'prismleaf_menu_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1010,
				'source_default_key'       => 'primary_menu_background_color_source',
				'source_default_fallback'  => 'default',
				'base_default_key'         => 'primary_menu_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'primary_menu_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_menu_primary_foreground_color_source',
				'base_setting_id'          => 'prismleaf_menu_primary_foreground_color_base',
				'palette_setting_id'       => 'prismleaf_menu_primary_foreground_color_palette',
				'section'                  => 'prismleaf_menu_options',
				'label'                    => __( 'Foreground color', 'prismleaf' ),
				'description'              => __( 'Overrides the link color. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1020,
				'source_default_key'       => 'primary_menu_foreground_color_source',
				'source_default_fallback'  => 'default',
				'base_default_key'         => 'primary_menu_foreground_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'primary_menu_foreground_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_primary_strip',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Menu strip', 'prismleaf' ),
				'description'      => __( 'Uses the background color as a full-width strip when enabled.', 'prismleaf' ),
				'priority'         => 1030,
				'default_key'      => 'primary_menu_strip',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_primary_button_corners',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Button corners', 'prismleaf' ),
				'description'      => __( 'Adjust the button treatment when the strip is not displayed.', 'prismleaf' ),
				'priority'         => 1040,
				'default_key'      => 'primary_menu_button_corners',
				'default_fallback' => 'Square',
				'sanitize_callback'=> 'prismleaf_sanitize_menu_button_corners',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
					'Pill'   => __( 'Pill', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_primary_menu_button_corners_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_primary_divider',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Menu divider', 'prismleaf' ),
				'description'      => __( 'Insert a divider between menu items when the strip is active.', 'prismleaf' ),
				'priority'         => 1050,
				'default_key'      => 'primary_menu_divider',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_primary_menu_divider_active',
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_menu_secondary_heading',
				'label'      => __( 'Secondary Menu', 'prismleaf' ),
				'section'    => 'prismleaf_menu_options',
				'priority'   => 1200,
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_menu_secondary_background_color_source',
				'base_setting_id'          => 'prismleaf_menu_secondary_background_color_base',
				'palette_setting_id'       => 'prismleaf_menu_secondary_background_color_palette',
				'section'                  => 'prismleaf_menu_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1210,
				'source_default_key'       => 'secondary_menu_background_color_source',
				'source_default_fallback'  => 'default',
				'base_default_key'         => 'secondary_menu_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'secondary_menu_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_menu_secondary_foreground_color_source',
				'base_setting_id'          => 'prismleaf_menu_secondary_foreground_color_base',
				'palette_setting_id'       => 'prismleaf_menu_secondary_foreground_color_palette',
				'section'                  => 'prismleaf_menu_options',
				'label'                    => __( 'Foreground color', 'prismleaf' ),
				'description'              => __( 'Overrides the link color. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1220,
				'source_default_key'       => 'secondary_menu_foreground_color_source',
				'source_default_fallback'  => 'default',
				'base_default_key'         => 'secondary_menu_foreground_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'secondary_menu_foreground_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_secondary_strip',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Menu strip', 'prismleaf' ),
				'description'      => __( 'Uses the background color as a full-width strip when enabled.', 'prismleaf' ),
				'priority'         => 1230,
				'default_key'      => 'secondary_menu_strip',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_secondary_button_corners',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Button corners', 'prismleaf' ),
				'description'      => __( 'Adjust the button treatment when the strip is not displayed.', 'prismleaf' ),
				'priority'         => 1240,
				'default_key'      => 'secondary_menu_button_corners',
				'default_fallback' => 'Square',
				'sanitize_callback'=> 'prismleaf_sanitize_menu_button_corners',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
					'Pill'   => __( 'Pill', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_secondary_menu_button_corners_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_secondary_divider',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Menu divider', 'prismleaf' ),
				'description'      => __( 'Insert a divider between menu items when the strip is active.', 'prismleaf' ),
				'priority'         => 1250,
				'default_key'      => 'secondary_menu_divider',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_secondary_menu_divider_active',
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id'      => 'prismleaf_menu_mobile_heading',
				'label'           => __( 'Mobile Menu', 'prismleaf' ),
				'section'         => 'prismleaf_menu_options',
				'priority'        => 3000,
				'description'     => __( 'Controls the mobile flyout menu.', 'prismleaf' ),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_menu_mobile_background_color_source',
				'base_setting_id'          => 'prismleaf_menu_mobile_background_color_base',
				'palette_setting_id'       => 'prismleaf_menu_mobile_background_color_palette',
				'section'                  => 'prismleaf_menu_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 3010,
				'source_default_key'       => 'mobile_menu_background_color_source',
				'source_default_fallback'  => 'default',
				'base_default_key'         => 'mobile_menu_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'mobile_menu_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_menu_mobile_foreground_color_source',
				'base_setting_id'          => 'prismleaf_menu_mobile_foreground_color_base',
				'palette_setting_id'       => 'prismleaf_menu_mobile_foreground_color_palette',
				'section'                  => 'prismleaf_menu_options',
				'label'                    => __( 'Foreground color', 'prismleaf' ),
				'description'              => __( 'Overrides the link color. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 3020,
				'source_default_key'       => 'mobile_menu_foreground_color_source',
				'source_default_fallback'  => 'default',
				'base_default_key'         => 'mobile_menu_foreground_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'mobile_menu_foreground_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_mobile_panel',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Menu panel', 'prismleaf' ),
				'description'      => __( 'Use the background color for the overlay panel when enabled.', 'prismleaf' ),
				'priority'         => 3030,
				'default_key'      => 'mobile_menu_panel',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_mobile_button_corners',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Button corners', 'prismleaf' ),
				'description'      => __( 'Adjust the button treatment when the panel is not displayed.', 'prismleaf' ),
				'priority'         => 3040,
				'default_key'      => 'mobile_menu_button_corners',
				'default_fallback' => 'Square',
				'sanitize_callback'=> 'prismleaf_sanitize_menu_button_corners',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
					'Pill'   => __( 'Pill', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_menu_mobile_divider',
				'section'          => 'prismleaf_menu_options',
				'label'            => __( 'Menu divider', 'prismleaf' ),
				'description'      => __( 'Insert a divider between menu items when the panel is active.', 'prismleaf' ),
				'priority'         => 3050,
				'default_key'      => 'mobile_menu_divider',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_mobile_menu_divider_active',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_menu_options_section' );
