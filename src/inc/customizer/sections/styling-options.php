<?php
/**
 * Prismleaf Customizer: Styling
 *
 * Defines Theme Options → Styling controls that influence region styling:
 * - Content, Header, Footer, Left Sidebar, Right Sidebar.
 *
 * These controls register settings only. Rendering is handled by theme CSS
 * consuming computed variables output by the theme.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer controls for Theme Options → Global Style.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
if ( ! function_exists( 'prismleaf_customize_register_global_style' ) ) {
	function prismleaf_customize_register_global_style( $wp_customize ) {
	if ( ! $wp_customize instanceof WP_Customize_Manager ) {
		return;
	}

	if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
		return;
	}

		$wp_customize->add_section(
			'prismleaf_global',
			array(
				'title'       => __( 'Styling', 'prismleaf' ),
				'description' => __( 'Control the visual treatment of global layout regions. Use Default to preserve token-driven behavior. Leave colors blank to use token-driven defaults.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 30,
			)
		);

		$regions = array(
			'content'       => array(
				'label'       => __( 'Content', 'prismleaf' ),
				'description' => __( 'Controls the content region branding surface (.prismleaf-region-content).', 'prismleaf' ),
			),
			'header'        => array(
				'label'       => __( 'Header', 'prismleaf' ),
				'description' => __( 'Controls the header region surface.', 'prismleaf' ),
			),
			'footer'        => array(
				'label'       => __( 'Footer', 'prismleaf' ),
				'description' => __( 'Controls the footer region surface.', 'prismleaf' ),
			),
			'sidebar_left'  => array(
				'label'       => __( 'Left Sidebar', 'prismleaf' ),
				'description' => __( 'Controls the left sidebar region surface.', 'prismleaf' ),
			),
			'sidebar_right' => array(
				'label'       => __( 'Right Sidebar', 'prismleaf' ),
				'description' => __( 'Controls the right sidebar region surface.', 'prismleaf' ),
			),
		);

		foreach ( $regions as $key => $config ) {
			if ( ! isset( $config['label'] ) ) {
				continue;
			}

			$prefix = "prismleaf_global_{$key}_";

			$wp_customize->add_setting(
				"prismleaf_global_heading_{$key}",
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
					"prismleaf_global_heading_{$key}",
					array(
						'section'     => 'prismleaf_global',
						'label'       => $config['label'],
						'description' => isset( $config['description'] ) ? $config['description'] : '',
					)
				)
			);

			// Border style (optional; null means "use CSS default").
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
					'section'     => 'prismleaf_global',
					'label'       => __( 'Border style', 'prismleaf' ),
					'description' => __( 'Optional. Use Default to keep the theme token-driven border behavior.', 'prismleaf' ),
					'choices'     => array(
						''        => __( 'Default (use theme)', 'prismleaf' ),
						'none'    => __( 'None', 'prismleaf' ),
						'solid'   => __( 'Solid', 'prismleaf' ),
						'dashed'  => __( 'Dashed', 'prismleaf' ),
						'dotted'  => __( 'Dotted', 'prismleaf' ),
						'double'  => __( 'Double', 'prismleaf' ),
					),
				)
			);

			// Border color (optional; blank => null via sanitizer).
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
						'section'     => 'prismleaf_global',
						'label'       => __( 'Border color', 'prismleaf' ),
						'description' => __( 'Optional. Leave blank to use the theme default outline color.', 'prismleaf' ),
					)
				)
			);

			// Background (optional).
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
						'section'     => 'prismleaf_global',
						'label'       => __( 'Background', 'prismleaf' ),
						'description' => __( 'Optional. Leave blank to use token-driven defaults.', 'prismleaf' ),
					)
				)
			);

			// Foreground (optional).
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
						'section'     => 'prismleaf_global',
						'label'       => __( 'Foreground', 'prismleaf' ),
						'description' => __( 'Optional. Leave blank to use token-driven defaults.', 'prismleaf' ),
					)
				)
			);

			// Elevation (optional; includes explicit 0).
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
					'section'     => 'prismleaf_global',
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
}
add_action( 'customize_register', 'prismleaf_customize_register_global_style' );



