<?php
/**
 * Prismleaf Customizer: Widget Styling
 *
 * Registers the widget styling section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_widget_options_section' ) ) {
	/**
	 * Register the Widget Styling section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_widget_options_section( $wp_customize ) {
		if ( ! $wp_customize->get_section( 'prismleaf_widget_options' ) ) {
			$wp_customize->add_section(
				'prismleaf_widget_options',
				array(
					'title'       => __( 'Widget Styling', 'prismleaf' ),
					'description' => __( 'Customize the default widget surface.', 'prismleaf' ),
					'panel'       => 'prismleaf_theme_options',
					'priority'    => 70,
				)
			);
		}

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_widget_heading_style',
				'label'      => __( 'Style', 'prismleaf' ),
				'section'    => 'prismleaf_widget_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_widget_border_corners',
				'section'          => 'prismleaf_widget_options',
				'label'            => __( 'Border corners', 'prismleaf' ),
				'description'      => __( 'Controls the roundness of widget corners.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'widget_border_corners',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_corners',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_widget_border_style',
				'section'          => 'prismleaf_widget_options',
				'label'            => __( 'Border style', 'prismleaf' ),
				'description'      => __( 'Sets the widget border line style.', 'prismleaf' ),
				'priority'         => 1020,
				'default_key'      => 'widget_border_style',
				'default_fallback' => 'solid',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_style',
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
				'source_setting_id'        => 'prismleaf_widget_border_color_source',
				'base_setting_id'          => 'prismleaf_widget_border_color_base',
				'palette_setting_id'       => 'prismleaf_widget_border_color_palette',
				'section'                  => 'prismleaf_widget_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1030,
				'source_default_key'       => 'widget_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'widget_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'widget_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_widget_background_color_source',
				'base_setting_id'          => 'prismleaf_widget_background_color_base',
				'palette_setting_id'       => 'prismleaf_widget_background_color_palette',
				'section'                  => 'prismleaf_widget_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1040,
				'source_default_key'       => 'widget_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'widget_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'widget_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_widget_elevation',
				'section'          => 'prismleaf_widget_options',
				'label'            => __( 'Elevation', 'prismleaf' ),
				'description'      => __( 'Sets the elevation level for widgets.', 'prismleaf' ),
				'priority'         => 1050,
				'default_key'      => 'widget_elevation',
				'default_fallback' => 'elevation-3',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_elevation',
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
				'setting_id' => 'prismleaf_widget_heading_custom',
				'label'      => __( 'Title Style', 'prismleaf' ),
				'section'    => 'prismleaf_widget_options',
				'priority'   => 2000,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_widget_title_alignment',
				'section'          => 'prismleaf_widget_options',
				'label'            => __( 'Alignment', 'prismleaf' ),
				'description'      => __( 'Text alignment for widget titles.', 'prismleaf' ),
				'priority'         => 2010,
				'default_key'      => 'widget_title_alignment',
				'default_fallback' => 'left',
				'sanitize_callback'=> 'prismleaf_sanitize_widget_title_alignment',
				'choices'          => array(
					'left'      => __( 'Left', 'prismleaf' ),
					'center'    => __( 'Center', 'prismleaf' ),
					'right'     => __( 'Right', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_widget_title_color_source',
				'base_setting_id'          => 'prismleaf_widget_title_color_base',
				'palette_setting_id'       => 'prismleaf_widget_title_color_palette',
				'section'                  => 'prismleaf_widget_options',
				'label'                    => __( 'Color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 2020,
				'source_default_key'       => 'widget_title_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'widget_title_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'widget_title_color_palette',
				'palette_default_fallback' => '',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_widget_options_section' );
