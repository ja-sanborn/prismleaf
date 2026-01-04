<?php
/**
 * Prismleaf Customizer: Footer Options
 *
 * Registers Customizer settings for footer layout, styling, and content.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_customize_register_footer_layout' ) ) {
	/**
	 * Register footer layout controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_footer_layout( $wp_customize ) {
		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_footer_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		// Footer.
		$wp_customize->add_setting(
			'prismleaf_layout_heading_footer',
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
				'prismleaf_layout_heading_footer',
				array(
					'section' => $section_id,
					'label'   => __( 'Layout', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_footer_visible',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_footer_visible',
			array(
				'type'            => 'checkbox',
				'section'         => $section_id,
				'label'           => __( 'Show footer', 'prismleaf' ),
				'description'     => __( 'Controls whether the footer is displayed.', 'prismleaf' ),
				'active_callback' => 'prismleaf_customize_callback_not_all_hidden',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_footer_contained',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_footer_contained',
			array(
				'type'            => 'checkbox',
				'section'         => $section_id,
				'label'           => __( 'Contain footer', 'prismleaf' ),
				'description'     => __(
					'When enabled (non-framed desktop), the footer is rendered inside the main content area. This option is disabled when using the framed layout.',
					'prismleaf'
				),
				'active_callback' => 'prismleaf_customize_callback_footer_contained_active',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_footer_floating',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_footer_floating',
			array(
				'type'            => 'checkbox',
				'section'         => $section_id,
				'label'           => __( 'Floating footer', 'prismleaf' ),
				'description'     => __( 'When disabled, the footer stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
				'active_callback' => 'prismleaf_customize_callback_footer_floating_active',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_footer_height',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'prismleaf_sanitize_footer_height',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_footer_height',
			array(
				'type'            => 'number',
				'section'         => $section_id,
				'label'           => __( 'Footer height', 'prismleaf' ),
				'description'     => __( 'Optional. Leave blank to use the theme default. Values 0-15 are treated as auto height; 16-240 are fixed heights.', 'prismleaf' ),
				'input_attrs'     => array(
					'min'  => 0,
					'max'  => 240,
					'step' => 1,
				),
				'active_callback' => 'prismleaf_customize_callback_footer_visible',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_footer_layout' );

if ( ! function_exists( 'prismleaf_customize_register_footer_style' ) ) {
	/**
	 * Register Customizer controls for footer styling.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_footer_style( $wp_customize ) {
		if ( ! $wp_customize instanceof WP_Customize_Manager ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_footer_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$prefix = 'prismleaf_global_footer_';

		$wp_customize->add_setting(
			'prismleaf_global_heading_footer',
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
				'prismleaf_global_heading_footer',
				array(
					'section'     => $section_id,
					'label'       => __( 'Footer', 'prismleaf' ),
					'description' => __( 'Controls the footer region surface.', 'prismleaf' ),
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
add_action( 'customize_register', 'prismleaf_customize_register_footer_style' );

if ( ! function_exists( 'prismleaf_sanitize_footer_widget_alignment' ) ) {
	/**
	 * Sanitize footer widget alignment.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Alignment value.
	 * @return string
	 */
	function prismleaf_sanitize_footer_widget_alignment( $value ) {
		$value   = (string) $value;
		$allowed = array( 'left', 'center', 'right', 'stretch' );

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return 'center';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_footer_copyright_text' ) ) {
	/**
	 * Sanitize footer copyright text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	function prismleaf_sanitize_footer_copyright_text( $value ) {
		$value = is_string( $value ) ? $value : '';

		return wp_kses_post( $value );
	}
}

if ( ! function_exists( 'prismleaf_customize_register_footer' ) ) {
	/**
	 * Register Footer settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_footer( $wp_customize ) {
		if ( ! ( $wp_customize instanceof WP_Customize_Manager ) ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_footer_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$wp_customize->add_setting(
			'prismleaf_footer_widget_alignment',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => 'center',
				'sanitize_callback' => 'prismleaf_sanitize_footer_widget_alignment',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_footer_widget_alignment',
			array(
				'type'        => 'select',
				'section'     => $section_id,
				'label'       => __( 'Widget alignment', 'prismleaf' ),
				'description' => __( 'Controls alignment of the footer widget row. Stretch fills the available width.', 'prismleaf' ),
				'choices'     => array(
					'left'    => __( 'Left', 'prismleaf' ),
					'center'  => __( 'Center', 'prismleaf' ),
					'right'   => __( 'Right', 'prismleaf' ),
					'stretch' => __( 'Stretch', 'prismleaf' ),
				),
			)
		);

		$wp_customize->add_setting(
			'prismleaf_footer_copyright_text',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'prismleaf_sanitize_footer_copyright_text',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_footer_copyright_text',
			array(
				'type'        => 'text',
				'section'     => $section_id,
				'label'       => __( 'Copyright text', 'prismleaf' ),
				'description' => __( 'Optional. Leave blank to use the site name and current year.', 'prismleaf' ),
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_footer' );
