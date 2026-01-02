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

if ( ! function_exists( 'prismleaf_customize_callback_header_background_image_set' ) ) {
	/**
	 * Show header background display control only when an image is set.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_header_background_image_set( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return true;
		}

		$value = prismleaf_customize_get_string( $control->manager, 'prismleaf_header_background_image', '' );

		return '' !== trim( $value );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_header_background_display' ) ) {
	/**
	 * Sanitize the header background display option.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $value Background display value.
	 * @return string
	 */
	function prismleaf_sanitize_header_background_display( $value ) {
		$value   = (string) $value;
		$allowed = array( 'tiled', 'stretch', 'fill', 'auto' );

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return 'tiled';
	}
}

if ( ! function_exists( 'prismleaf_customize_register_global_style' ) ) {
	/**
	 * Register Customizer controls for Theme Options → Global Style.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
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
						''       => __( 'Default (use theme)', 'prismleaf' ),
						'none'   => __( 'None', 'prismleaf' ),
						'solid'  => __( 'Solid', 'prismleaf' ),
						'dashed' => __( 'Dashed', 'prismleaf' ),
						'dotted' => __( 'Dotted', 'prismleaf' ),
						'double' => __( 'Double', 'prismleaf' ),
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

			if ( 'header' === $key ) {
				$wp_customize->add_setting(
					'prismleaf_header_background_image',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => null,
						'sanitize_callback' => 'esc_url_raw',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'prismleaf_header_background_image',
						array(
							'section'     => 'prismleaf_global',
							'label'       => __( 'Header background image', 'prismleaf' ),
							'description' => __( 'Optional. Leave blank for no image.', 'prismleaf' ),
						)
					)
				);

				$wp_customize->add_setting(
					'prismleaf_header_background_display',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => 'tiled',
						'sanitize_callback' => 'prismleaf_sanitize_header_background_display',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_header_background_display',
					array(
						'type'            => 'select',
						'section'         => 'prismleaf_global',
						'label'           => __( 'Header background image display', 'prismleaf' ),
						'description'     => __( 'Choose how the header background image is drawn.', 'prismleaf' ),
						'choices'         => array(
							'tiled'   => __( 'Tiled', 'prismleaf' ),
							'stretch' => __( 'Stretch', 'prismleaf' ),
							'fill'    => __( 'Fill', 'prismleaf' ),
							'auto'    => __( 'Auto', 'prismleaf' ),
						),
						'active_callback' => 'prismleaf_customize_callback_header_background_image_set',
					)
				);
			}

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
