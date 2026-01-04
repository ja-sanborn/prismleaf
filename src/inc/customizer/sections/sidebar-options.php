<?php
/**
 * Prismleaf Customizer: Sidebar Options
 *
 * Registers Customizer settings for sidebar layout and styling.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_customize_register_sidebar_layout' ) ) {
	/**
	 * Register sidebar layout controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_sidebar_layout( $wp_customize ) {
		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_sidebar_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$sidebars = array(
			'left'  => __( 'Left sidebar', 'prismleaf' ),
			'right' => __( 'Right sidebar', 'prismleaf' ),
		);

		$sidebar_headers = array(
			'left'  => __( 'Left Sidebar', 'prismleaf' ),
			'right' => __( 'Right Sidebar', 'prismleaf' ),
		);

		foreach ( $sidebars as $side => $label ) {
			$priority_base   = ( 'left' === $side ) ? 40 : 60;
			$visible_setting = "prismleaf_layout_sidebar_{$side}_visible";
			$width_setting   = "prismleaf_layout_sidebar_{$side}_width";
			$coll_setting    = "prismleaf_layout_sidebar_{$side}_collapsible";
			$cont_setting    = "prismleaf_layout_sidebar_{$side}_contained";

			$wp_customize->add_setting(
				"prismleaf_layout_heading_sidebar_{$side}",
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
					"prismleaf_layout_heading_sidebar_{$side}",
					array(
						'section'  => $section_id,
						'label'    => isset( $sidebar_headers[ $side ] ) ? $sidebar_headers[ $side ] : $label,
						'priority' => $priority_base,
					)
				)
			);

			$wp_customize->add_setting(
				$visible_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'wp_validate_boolean',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$visible_setting,
				array(
					'type'            => 'checkbox',
					'section'         => $section_id,
					/* translators: %s is sidebar name. */
					'label'           => sprintf( __( 'Show %s', 'prismleaf' ), $label ),
					'description'     => __( 'Controls whether this sidebar is displayed.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_not_all_hidden',
					'priority'        => $priority_base + 1,
				)
			);

			$wp_customize->add_setting(
				$cont_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'wp_validate_boolean',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$cont_setting,
				array(
					'type'            => 'checkbox',
					'section'         => $section_id,
					/* translators: %s is sidebar name. */
					'label'           => sprintf( __( 'Contain %s', 'prismleaf' ), $label ),
					'description'     => __(
						'When enabled, the sidebar is visually separated from the site edges. This option is disabled when using the framed layout.',
						'prismleaf'
					),
					'active_callback' => 'prismleaf_customize_callback_sidebar_contained_active',
					'priority'        => $priority_base + 2,
				)
			);

			$floating_setting = "prismleaf_layout_sidebar_{$side}_floating";

			$wp_customize->add_setting(
				$floating_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'wp_validate_boolean',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$floating_setting,
				array(
					'type'            => 'checkbox',
					'section'         => $section_id,
					/* translators: %s is sidebar name. */
					'label'           => sprintf( __( 'Floating %s', 'prismleaf' ), $label ),
					'description'     => __( 'When disabled, the sidebar stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_sidebar_floating_active',
					'priority'        => $priority_base + 3,
				)
			);

			$wp_customize->add_setting(
				$width_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => 300,
					'sanitize_callback' => 'prismleaf_sanitize_sidebar_width',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$width_setting,
				array(
					'type'            => 'number',
					'section'         => $section_id,
					/* translators: %s is sidebar name. */
					'label'           => sprintf( __( '%s width (desktop)', 'prismleaf' ), $label ),
					'description'     => __( 'Width in pixels on desktop layouts.', 'prismleaf' ),
					'input_attrs'     => array(
						'min'  => 200,
						'max'  => 400,
						'step' => 10,
					),
					'active_callback' => 'prismleaf_customize_callback_sidebar_visible',
					'priority'        => $priority_base + 4,
				)
			);

			$wp_customize->add_setting(
				$coll_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'wp_validate_boolean',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$coll_setting,
				array(
					'type'            => 'checkbox',
					'section'         => $section_id,
					/* translators: %s is sidebar name. */
					'label'           => sprintf( __( '%s collapsible on desktop', 'prismleaf' ), $label ),
					'description'     => __( 'When enabled, the sidebar can collapse. Collapsing does not apply to the stacked mobile layout.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_sidebar_visible',
					'priority'        => $priority_base + 5,
				)
			);
		}
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_sidebar_layout' );

if ( ! function_exists( 'prismleaf_customize_register_sidebar_style' ) ) {
	/**
	 * Register Customizer controls for sidebar styling.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_sidebar_style( $wp_customize ) {
		if ( ! $wp_customize instanceof WP_Customize_Manager ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_sidebar_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$regions = array(
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
						'section'     => $section_id,
						'label'       => $config['label'],
						'description' => $config['description'],
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
}
add_action( 'customize_register', 'prismleaf_customize_register_sidebar_style' );
