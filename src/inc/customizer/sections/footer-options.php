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
				'title'       => __( 'Footer Styling', 'prismleaf' ),
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
				'active_callback'  => 'prismleaf_is_footer_control_active',
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
				'active_callback'  => 'prismleaf_is_footer_control_active',
			)
		);

		prismleaf_add_number_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_height',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Footer height', 'prismleaf' ),
				'description'      => __( 'Specify a fixed height in pixels. Leave blank for automatic height.', 'prismleaf' ),
				'priority'         => 1040,
				'default_key'      => 'footer_height',
				'default_fallback' => '',
				'sanitize_callback'=> 'prismleaf_sanitize_footer_height',
				'active_callback'  => 'prismleaf_is_footer_control_active',
				'input_attrs'      => array(
					'min' => 32,
					'max' => 600,
					'step'=> 1,
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id'      => 'prismleaf_footer_heading_style',
				'label'           => __( 'Style', 'prismleaf' ),
				'section'         => 'prismleaf_footer_options',
				'priority'        => 2000,
				'active_callback' => 'prismleaf_is_footer_control_active',
			)
		);

		prismleaf_add_background_image_control(
			$wp_customize,
			array(
				'section'                    => 'prismleaf_footer_options',
				'label'                      => __( 'Background Image', 'prismleaf' ),
				'description'                => __( 'Optional background for the footer area.', 'prismleaf' ),
				'priority'                   => 2010,
				'setting_base'               => 'prismleaf_footer_background',
				'active_callback'            => 'prismleaf_is_footer_control_active',
				'image_default_key'          => 'footer_background_image',
				'repeat_default_key'         => 'footer_background_image_repeat',
				'position_x_default_key'     => 'footer_background_image_position_x',
				'position_y_default_key'     => 'footer_background_image_position_y',
				'size_default_key'           => 'footer_background_image_size',
				'attachment_default_key'     => 'footer_background_image_attachment',
				'preset_default_key'         => 'footer_background_image_preset',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_border_corners',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Border corners', 'prismleaf' ),
				'description'      => __( 'Controls the roundness of the footer corners.', 'prismleaf' ),
				'priority'         => 2020,
				'default_key'      => 'footer_border_corners',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_corners',
				'active_callback'  => 'prismleaf_is_footer_control_active',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_border_style',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Border style', 'prismleaf' ),
				'description'      => __( 'Sets the footer border line style.', 'prismleaf' ),
				'priority'         => 2030,
				'default_key'      => 'footer_border_style',
				'default_fallback' => 'solid',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_style',
				'active_callback'  => 'prismleaf_is_footer_control_active',
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
				'source_setting_id'        => 'prismleaf_footer_border_color_source',
				'base_setting_id'          => 'prismleaf_footer_border_color_base',
				'palette_setting_id'       => 'prismleaf_footer_border_color_palette',
				'section'                  => 'prismleaf_footer_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 2040,
				'active_callback'          => 'prismleaf_is_footer_control_active',
				'source_default_key'       => 'footer_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'footer_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'footer_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_footer_background_color_source',
				'base_setting_id'          => 'prismleaf_footer_background_color_base',
				'palette_setting_id'       => 'prismleaf_footer_background_color_palette',
				'section'                  => 'prismleaf_footer_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 2050,
				'active_callback'          => 'prismleaf_is_footer_background_control_active',
				'source_default_key'       => 'footer_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'footer_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'footer_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_elevation',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Elevation', 'prismleaf' ),
				'description'      => __( 'Sets the elevation level for the footer.', 'prismleaf' ),
				'priority'         => 2060,
				'default_key'      => 'footer_elevation',
				'default_fallback' => 'elevation-2',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_elevation',
				'active_callback'  => 'prismleaf_is_footer_control_active',
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
				'setting_id' => 'prismleaf_footer_heading_content',
				'label'      => __( 'Content', 'prismleaf' ),
				'section'    => 'prismleaf_footer_options',
				'priority'   => 3000,
				'active_callback' => 'prismleaf_is_footer_control_active',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_widget_alignment',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Widget alignment', 'prismleaf' ),
				'description'      => __( 'Controls alignment of the footer widget row. Stretch fills the available width.', 'prismleaf' ),
				'priority'         => 3010,
				'default_key'      => 'footer_widget_alignment',
				'default_fallback' => 'center',
				'sanitize_callback'=> 'prismleaf_sanitize_footer_widget_alignment',
				'active_callback'  => 'prismleaf_is_footer_control_active',
				'choices'          => array(
					'left'    => __( 'Left', 'prismleaf' ),
					'center'  => __( 'Center', 'prismleaf' ),
					'right'   => __( 'Right', 'prismleaf' ),
					'stretch' => __( 'Stretch', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_footer_widget_color_source',
				'base_setting_id'          => 'prismleaf_footer_widget_color_base',
				'palette_setting_id'       => 'prismleaf_footer_widget_color_palette',
				'section'                  => 'prismleaf_footer_options',
				'label'                    => __( 'Widget foreground color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 3020,
				'active_callback'          => 'prismleaf_is_footer_control_active',
				'source_default_key'       => 'footer_widget_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'footer_widget_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'footer_widget_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_footer_copyright_color_source',
				'base_setting_id'          => 'prismleaf_footer_copyright_color_base',
				'palette_setting_id'       => 'prismleaf_footer_copyright_color_palette',
				'section'                  => 'prismleaf_footer_options',
				'label'                    => __( 'Copyright foreground color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 3030,
				'active_callback'          => 'prismleaf_is_footer_control_active',
				'source_default_key'       => 'footer_copyright_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'footer_copyright_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'footer_copyright_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_text_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_footer_copyright_text',
				'section'          => 'prismleaf_footer_options',
				'label'            => __( 'Copyright text', 'prismleaf' ),
				'description'      => __( 'Optional. Leave blank to use the site name and current year.', 'prismleaf' ),
				'priority'         => 3040,
				'default_key'      => 'footer_copyright_text',
				'default_fallback' => '',
				'control_type'     => 'textarea',
				'active_callback'  => 'prismleaf_is_footer_control_active',
			)
		);

	}
}
add_action( 'customize_register', 'prismleaf_register_footer_options_section' );
