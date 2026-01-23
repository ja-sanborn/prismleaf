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
				'title'       => __( 'Header Styling', 'prismleaf' ),
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
				'active_callback'  => 'prismleaf_is_header_control_active',
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
				'active_callback'  => 'prismleaf_is_header_control_active',
			)
		);

		prismleaf_add_number_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_height',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Header height', 'prismleaf' ),
				'description'      => __( 'Specify a fixed height in pixels. Leave blank for automatic height.', 'prismleaf' ),
				'priority'         => 1040,
				'default_key'      => 'header_height',
				'default_fallback' => '',
				'sanitize_callback'=> 'prismleaf_sanitize_header_height',
				'active_callback'  => 'prismleaf_is_header_control_active',
				'input_attrs'      => array(
					'min' => 32,
					'max' => 300,
					'step'=> 1,
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id'      => 'prismleaf_header_heading_style',
				'label'           => __( 'Style', 'prismleaf' ),
				'section'         => 'prismleaf_header_options',
				'priority'        => 2000,
				'active_callback' => 'prismleaf_is_header_control_active',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_header_background_color_source',
				'base_setting_id'          => 'prismleaf_header_background_color_base',
				'palette_setting_id'       => 'prismleaf_header_background_color_palette',
				'section'                  => 'prismleaf_header_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 2010,
				'active_callback'          => 'prismleaf_is_header_background_control_active',
				'source_default_key'       => 'header_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'header_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'header_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_background_image_control(
			$wp_customize,
			array(
				'section'                    => 'prismleaf_header_options',
				'label'                      => __( 'Background Image', 'prismleaf' ),
				'description'                => __( 'Optional background for the footer area.', 'prismleaf' ),
				'priority'                   => 2020,
				'setting_base'               => 'prismleaf_header_background',
				'active_callback'            => 'prismleaf_is_header_control_active',
				'image_default_key'          => 'header_background_image',
				'repeat_default_key'         => 'header_background_image_repeat',
				'position_x_default_key'     => 'header_background_image_position_x',
				'position_y_default_key'     => 'header_background_image_position_y',
				'size_default_key'           => 'header_background_image_size',
				'attachment_default_key'     => 'header_background_image_attachment',
				'preset_default_key'         => 'header_background_image_preset',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_border_corners',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Border corners', 'prismleaf' ),
				'description'      => __( 'Controls the roundness of the header corners.', 'prismleaf' ),
				'priority'         => 2030,
				'default_key'      => 'header_border_corners',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_corners',
				'active_callback'  => 'prismleaf_is_header_control_active',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_border_style',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Border style', 'prismleaf' ),
				'description'      => __( 'Sets the header border line style.', 'prismleaf' ),
				'priority'         => 2040,
				'default_key'      => 'header_border_style',
				'default_fallback' => 'solid',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_style',
				'active_callback'  => 'prismleaf_is_header_control_active',
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
				'source_setting_id'        => 'prismleaf_header_border_color_source',
				'base_setting_id'          => 'prismleaf_header_border_color_base',
				'palette_setting_id'       => 'prismleaf_header_border_color_palette',
				'section'                  => 'prismleaf_header_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 2050,
				'active_callback'          => 'prismleaf_is_header_control_active',
				'source_default_key'       => 'header_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'header_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'header_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_elevation',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Elevation', 'prismleaf' ),
				'description'      => __( 'Sets the elevation level for the header.', 'prismleaf' ),
				'priority'         => 2060,
				'default_key'      => 'header_elevation',
				'default_fallback' => 'elevation-2',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_elevation',
				'active_callback'  => 'prismleaf_is_header_control_active',
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
				'setting_id' => 'prismleaf_header_heading_content',
				'label'      => __( 'Content', 'prismleaf' ),
				'section'    => 'prismleaf_header_options',
				'priority'   => 3000,
				'active_callback' => 'prismleaf_is_header_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_icon_position',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Icon position', 'prismleaf' ),
				'description'      => __( 'Show the site icon on the left or right of the header, or hide it entirely.', 'prismleaf' ),
				'priority'         => 3010,
				'default_key'      => 'header_icon_position',
				'default_fallback' => 'left',
				'sanitize_callback'=> 'prismleaf_sanitize_header_icon_position',
				'choices'          => array(
					'none'  => __( 'None', 'prismleaf' ),
					'left'  => __( 'Left', 'prismleaf' ),
					'right' => __( 'Right', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_header_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_icon_size',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Icon size', 'prismleaf' ),
				'description'      => __( 'Adjust the diameter of the site icon.', 'prismleaf' ),
				'priority'         => 3020,
				'default_key'      => 'header_icon_size',
				'default_fallback' => 'medium',
				'sanitize_callback'=> 'prismleaf_sanitize_header_icon_size',
				'choices'          => array(
					'small'  => __( 'Small', 'prismleaf' ),
					'medium' => __( 'Medium', 'prismleaf' ),
					'large'  => __( 'Large', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_header_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_header_icon_shape',
				'section'          => 'prismleaf_header_options',
				'label'            => __( 'Icon shape', 'prismleaf' ),
				'description'      => __( 'Choose the corner treatment for the site icon.', 'prismleaf' ),
				'priority'         => 3030,
				'default_key'      => 'header_icon_shape',
				'default_fallback' => 'circle',
				'sanitize_callback'=> 'prismleaf_sanitize_header_icon_shape',
				'choices'          => array(
					'square'  => __( 'Square', 'prismleaf' ),
					'rounded' => __( 'Rounded', 'prismleaf' ),
					'circle'  => __( 'Circle', 'prismleaf' ),
				),
				'active_callback'  => 'prismleaf_is_header_control_active',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_header_options_section' );
