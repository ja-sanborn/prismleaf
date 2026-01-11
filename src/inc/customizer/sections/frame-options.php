<?php
/**
 * Prismleaf Customizer: Frame Options
 *
 * Registers the Frame Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_frame_options_section' ) ) {
	/**
	 * Register the Frame Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_frame_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_frame_options',
				'title'       => __( 'Frame', 'prismleaf' ),
				'description' => __( 'Frame layout and styling.', 'prismleaf' ),
				'priority'    => 20,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_frame_heading_layout',
				'label'      => __( 'Layout', 'prismleaf' ),
				'section'    => 'prismleaf_frame_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_global_framed_layout',
				'section'          => 'prismleaf_frame_options',
				'label'            => __( 'Use framed layout', 'prismleaf' ),
				'description'      => __( 'Desktop only. When enabled, the site uses a fixed frame with internal scrolling panels. Mobile always stacks and scrolls normally.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'global_framed_layout',
				'default_fallback' => false,
			)
		);

		prismleaf_add_number_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_frame_max_width',
				'section'          => 'prismleaf_frame_options',
				'label'            => __( 'Max width', 'prismleaf' ),
				'description'      => __( 'Sets the maximum content width for the frame.', 'prismleaf' ),
				'priority'         => 1020,
				'default_key'      => 'frame_max_width',
				'default_fallback' => '1480',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_max_width',
				'input_attrs'      => array(
					'min'  => 1000,
					'max'  => 2000,
					'step' => 1,
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_frame_heading_style',
				'label'      => __( 'Style', 'prismleaf' ),
				'section'    => 'prismleaf_frame_options',
				'priority'   => 2000,
				'active_callback' => 'prismleaf_is_frame_layout_control_active',
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_frame_show_background',
				'section'          => 'prismleaf_frame_options',
				'label'            => __( 'Show background', 'prismleaf' ),
				'description'      => __( 'When enabled, the frame surface is visible behind the layout regions.', 'prismleaf' ),
				'priority'         => 2010,
				'default_key'      => 'frame_show_background',
				'default_fallback' => true,
				'active_callback'  => 'prismleaf_is_frame_layout_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_frame_border_corners',
				'section'          => 'prismleaf_frame_options',
				'label'            => __( 'Border corners', 'prismleaf' ),
				'description'      => __( 'Controls the roundness of the frame corners.', 'prismleaf' ),
				'priority'         => 2020,
				'default_key'      => 'frame_border_corners',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_corners',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_frame_background_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_frame_border_style',
				'section'          => 'prismleaf_frame_options',
				'label'            => __( 'Border style', 'prismleaf' ),
				'description'      => __( 'Sets the frame border line style.', 'prismleaf' ),
				'priority'         => 2030,
				'default_key'      => 'frame_border_style',
				'default_fallback' => 'solid',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_style',
				'choices'          => array(
					'none'   => __( 'None', 'prismleaf' ),
					'solid'  => __( 'Solid', 'prismleaf' ),
					'dotted' => __( 'Dotted', 'prismleaf' ),
					'dashed' => __( 'Dashed', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_frame_background_control_active',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_frame_border_color_source',
				'base_setting_id'          => 'prismleaf_frame_border_color_base',
				'palette_setting_id'       => 'prismleaf_frame_border_color_palette',
				'section'                  => 'prismleaf_frame_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 2040,
				'active_callback'          => 'prismleaf_is_frame_background_control_active',
				'source_default_key'       => 'frame_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'frame_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'frame_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_frame_background_color_source',
				'base_setting_id'          => 'prismleaf_frame_background_color_base',
				'palette_setting_id'       => 'prismleaf_frame_background_color_palette',
				'section'                  => 'prismleaf_frame_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 2050,
				'active_callback'          => 'prismleaf_is_frame_background_control_active',
				'source_default_key'       => 'frame_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'frame_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'frame_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_frame_elevation',
				'section'          => 'prismleaf_frame_options',
				'label'            => __( 'Elevation', 'prismleaf' ),
				'description'      => __( 'Sets the elevation level for the frame background.', 'prismleaf' ),
				'priority'         => 2060,
				'default_key'      => 'frame_elevation',
				'default_fallback' => 'elevation-1',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_elevation',
				'choices'          => array(
					'none'        => __( 'None', 'prismleaf' ),
					'elevation-1' => __( 'Elevation 1', 'prismleaf' ),
					'elevation-2' => __( 'Elevation 2', 'prismleaf' ),
					'elevation-3' => __( 'Elevation 3', 'prismleaf' ),
					'elevation-4' => __( 'Elevation 4', 'prismleaf' ),
					'elevation-5' => __( 'Elevation 5', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_frame_background_control_active',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_frame_options_section' );
