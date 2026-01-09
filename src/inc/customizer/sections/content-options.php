<?php
/**
 * Prismleaf Customizer: Content Options
 *
 * Registers Customizer settings for content styling.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_customize_register_content_style' ) ) {
	/**
	 * Register Customizer controls for content styling.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_content_style( $wp_customize ) {
		if ( ! $wp_customize instanceof WP_Customize_Manager ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_content_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$prefix = 'prismleaf_global_content_';

		$wp_customize->add_setting(
			'prismleaf_global_heading_content',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new Prismleaf_Customize_Section_Header_Control(
				$wp_customize,
				'prismleaf_global_heading_content',
				array(
					'section'     => $section_id,
					'label'       => __( 'Content', 'prismleaf' ),
					'description' => __( 'Controls the content region branding surface (.prismleaf-region-content).', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			$prefix . 'border_style',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_sanitize_border_style_or_empty',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$prefix . 'border_style',
			array(
				'type'        => 'select',
				'section'     => $section_id,
				'label'       => __( 'Border style', 'prismleaf' ),
				'description' => __( 'Optional. Use Default to keep the theme token-driven border behavior.', 'prismleaf' ),
				'choices'     => array(
					''       => __( 'Default (use theme)', 'prismleaf' ),
					'none'   => __( 'None', 'prismleaf' ),
					'solid'  => __( 'Solid', 'prismleaf' ),
					'dashed' => __( 'Dashed', 'prismleaf' ),
					'dotted' => __( 'Dotted', 'prismleaf' ),
					'double' => __( 'Double', 'prismleaf' ),
				),
			)
		);

		$wp_customize->add_setting(
			$prefix . 'border_color',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_customize_sanitize_optional_hex_color_empty_ok',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$prefix . 'border_color',
				array(
					'section'     => $section_id,
					'label'       => __( 'Border color', 'prismleaf' ),
					'description' => __( 'Optional. Leave blank to use the theme default outline color.', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			$prefix . 'background',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_customize_sanitize_optional_hex_color_empty_ok',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$prefix . 'background',
				array(
					'section'     => $section_id,
					'label'       => __( 'Background', 'prismleaf' ),
					'description' => __( 'Optional. Leave blank to use token-driven defaults.', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			$prefix . 'foreground',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_customize_sanitize_optional_hex_color_empty_ok',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$prefix . 'foreground',
				array(
					'section'     => $section_id,
					'label'       => __( 'Foreground', 'prismleaf' ),
					'description' => __( 'Optional. Leave blank to use token-driven defaults.', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			$prefix . 'elevation',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_sanitize_elevation_0_3',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			$prefix . 'elevation',
			array(
				'type'        => 'select',
				'section'     => $section_id,
				'label'       => __( 'Elevation', 'prismleaf' ),
				'description' => __( 'Optional. Use Default to keep theme tokens. Set None to explicitly remove elevation effects (no shadow, no glow).', 'prismleaf' ),
				'choices'     => array(
					'' => __( 'Default (use theme)', 'prismleaf' ),
					0  => __( 'None', 'prismleaf' ),
					1  => __( 'Elevation 1', 'prismleaf' ),
					2  => __( 'Elevation 2', 'prismleaf' ),
					3  => __( 'Elevation 3', 'prismleaf' ),
				),
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_content_style' );
