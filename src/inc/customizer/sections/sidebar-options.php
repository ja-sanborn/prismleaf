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
				'active_callback'  => 'prismleaf_is_sidebar_primary_control_active',
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
				'active_callback'  => 'prismleaf_is_sidebar_primary_control_active',
			)
		);

		prismleaf_add_number_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_primary_width',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Sidebar width', 'prismleaf' ),
				'description'      => __( 'Adjust the primary sidebar width in pixels.', 'prismleaf' ),
				'priority'         => 2040,
				'default_key'      => 'sidebar_primary_width',
				'default_fallback' => '260',
				'sanitize_callback'=> 'prismleaf_sanitize_sidebar_width_control',
				'active_callback'  => 'prismleaf_is_sidebar_primary_control_active',
				'input_attrs'      => array(
					'min' => 150,
					'max' => 300,
					'step'=> 1,
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id'      => 'prismleaf_sidebar_heading_primary_style',
				'label'           => __( 'Primary Style', 'prismleaf' ),
				'section'         => 'prismleaf_sidebar_options',
				'priority'        => 3000,
				'active_callback' => 'prismleaf_is_sidebar_primary_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_primary_border_corners',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Border corners', 'prismleaf' ),
				'description'      => __( 'Controls the roundness of the primary sidebar corners.', 'prismleaf' ),
				'priority'         => 3010,
				'default_key'      => 'sidebar_primary_border_corners',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_corners',
				'active_callback'  => 'prismleaf_is_sidebar_primary_control_active',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_primary_border_style',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Border style', 'prismleaf' ),
				'description'      => __( 'Sets the primary sidebar border line style.', 'prismleaf' ),
				'priority'         => 3020,
				'default_key'      => 'sidebar_primary_border_style',
				'default_fallback' => 'solid',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_style',
				'active_callback'  => 'prismleaf_is_sidebar_primary_control_active',
				'choices'          => array(
					'none'   => __( 'None', 'prismleaf' ),
					'solid'  => __( 'Solid', 'prismleaf' ),
					'dotted' => __( 'Dotted', 'prismleaf' ),
					'dashed' => __( 'Dashed', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_sidebar_primary_border_color_source',
				'base_setting_id'          => 'prismleaf_sidebar_primary_border_color_base',
				'palette_setting_id'       => 'prismleaf_sidebar_primary_border_color_palette',
				'section'                  => 'prismleaf_sidebar_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 3030,
				'active_callback'          => 'prismleaf_is_sidebar_primary_control_active',
				'source_default_key'       => 'sidebar_primary_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'sidebar_primary_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'sidebar_primary_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_sidebar_primary_background_color_source',
				'base_setting_id'          => 'prismleaf_sidebar_primary_background_color_base',
				'palette_setting_id'       => 'prismleaf_sidebar_primary_background_color_palette',
				'section'                  => 'prismleaf_sidebar_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 3040,
				'active_callback'          => 'prismleaf_is_sidebar_primary_background_control_active',
				'source_default_key'       => 'sidebar_primary_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'sidebar_primary_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'sidebar_primary_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_primary_elevation',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Elevation', 'prismleaf' ),
				'description'      => __( 'Sets the elevation level for the primary sidebar.', 'prismleaf' ),
				'priority'         => 3050,
				'default_key'      => 'sidebar_primary_elevation',
				'default_fallback' => 'elevation-2',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_elevation',
				'active_callback'  => 'prismleaf_is_sidebar_primary_control_active',
				'choices'          => array(
					'none'        => __( 'None', 'prismleaf' ),
					'elevation-1' => __( 'Elevation 1', 'prismleaf' ),
					'elevation-2' => __( 'Elevation 2', 'prismleaf' ),
					'elevation-3' => __( 'Elevation 3', 'prismleaf' ),
					'elevation-4' => __( 'Elevation 4', 'prismleaf' ),
					'elevation-5' => __( 'Elevation 5', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_sidebar_heading_secondary_layout',
				'label'      => __( 'Secondary Layout', 'prismleaf' ),
				'section'    => 'prismleaf_sidebar_options',
				'priority'   => 4000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_show',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Show secondary sidebar', 'prismleaf' ),
				'description'      => __( 'Controls whether the secondary sidebar is displayed.', 'prismleaf' ),
				'priority'         => 4010,
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
				'priority'         => 4020,
				'default_key'      => 'sidebar_secondary_contained',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_sidebar_secondary_control_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_floating',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Floating secondary sidebar', 'prismleaf' ),
				'description'      => __( 'When disabled, the secondary sidebar stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
				'priority'         => 4030,
				'default_key'      => 'sidebar_secondary_floating',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_sidebar_secondary_control_active',
			)
		);

		prismleaf_add_number_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_width',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Sidebar width', 'prismleaf' ),
				'description'      => __( 'Adjust the secondary sidebar width in pixels.', 'prismleaf' ),
				'priority'         => 4040,
				'default_key'      => 'sidebar_secondary_width',
				'default_fallback' => '200',
				'sanitize_callback'=> 'prismleaf_sanitize_sidebar_width_control',
				'active_callback'  => 'prismleaf_is_sidebar_secondary_control_active',
				'input_attrs'      => array(
					'min' => 150,
					'max' => 300,
					'step'=> 1,
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id'      => 'prismleaf_sidebar_heading_secondary_style',
				'label'           => __( 'Secondary Style', 'prismleaf' ),
				'section'         => 'prismleaf_sidebar_options',
				'priority'        => 5000,
				'active_callback' => 'prismleaf_is_sidebar_secondary_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_border_corners',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Border corners', 'prismleaf' ),
				'description'      => __( 'Controls the roundness of the secondary sidebar corners.', 'prismleaf' ),
				'priority'         => 5010,
				'default_key'      => 'sidebar_secondary_border_corners',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_corners',
				'active_callback'  => 'prismleaf_is_sidebar_secondary_control_active',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_border_style',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Border style', 'prismleaf' ),
				'description'      => __( 'Sets the secondary sidebar border line style.', 'prismleaf' ),
				'priority'         => 5020,
				'default_key'      => 'sidebar_secondary_border_style',
				'default_fallback' => 'solid',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_style',
				'active_callback'  => 'prismleaf_is_sidebar_secondary_control_active',
				'choices'          => array(
					'none'   => __( 'None', 'prismleaf' ),
					'solid'  => __( 'Solid', 'prismleaf' ),
					'dotted' => __( 'Dotted', 'prismleaf' ),
					'dashed' => __( 'Dashed', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_sidebar_secondary_border_color_source',
				'base_setting_id'          => 'prismleaf_sidebar_secondary_border_color_base',
				'palette_setting_id'       => 'prismleaf_sidebar_secondary_border_color_palette',
				'section'                  => 'prismleaf_sidebar_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 5030,
				'active_callback'          => 'prismleaf_is_sidebar_secondary_control_active',
				'source_default_key'       => 'sidebar_secondary_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'sidebar_secondary_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'sidebar_secondary_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_sidebar_secondary_background_color_source',
				'base_setting_id'          => 'prismleaf_sidebar_secondary_background_color_base',
				'palette_setting_id'       => 'prismleaf_sidebar_secondary_background_color_palette',
				'section'                  => 'prismleaf_sidebar_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 5040,
				'active_callback'          => 'prismleaf_is_sidebar_secondary_background_control_active',
				'source_default_key'       => 'sidebar_secondary_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'sidebar_secondary_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'sidebar_secondary_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_sidebar_secondary_elevation',
				'section'          => 'prismleaf_sidebar_options',
				'label'            => __( 'Elevation', 'prismleaf' ),
				'description'      => __( 'Sets the elevation level for the secondary sidebar.', 'prismleaf' ),
				'priority'         => 5050,
				'default_key'      => 'sidebar_secondary_elevation',
				'default_fallback' => 'elevation-2',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_elevation',
				'active_callback'  => 'prismleaf_is_sidebar_secondary_control_active',
				'choices'          => array(
					'none'        => __( 'None', 'prismleaf' ),
					'elevation-1' => __( 'Elevation 1', 'prismleaf' ),
					'elevation-2' => __( 'Elevation 2', 'prismleaf' ),
					'elevation-3' => __( 'Elevation 3', 'prismleaf' ),
					'elevation-4' => __( 'Elevation 4', 'prismleaf' ),
					'elevation-5' => __( 'Elevation 5', 'prismleaf' ),
				),
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_sidebar_options_section' );
