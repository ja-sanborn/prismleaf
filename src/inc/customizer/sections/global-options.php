<?php
/**
 * Prismleaf Customizer: Global Options
 *
 * Global theme options such as layout mode and palette overrides.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_customize_register_global_layout' ) ) {
	/**
	 * Register global layout controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_global_layout( $wp_customize ) {
		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_global_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		// Frames.
		$wp_customize->add_setting(
			'prismleaf_layout_heading_frames',
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
				'prismleaf_layout_heading_frames',
				array(
					'section' => $section_id,
					'label'   => __( 'Layout', 'prismleaf' ),
					'priority' => 1000,
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_framed',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => false,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_framed',
			array(
				'type'        => 'checkbox',
				'section'     => $section_id,
				'label'       => __( 'Use framed layout (desktop)', 'prismleaf' ),
				'description' => __( 'When enabled, the site uses a fixed frame with internal scrolling panels. On mobile, the layout always stacks and scrolls normally.', 'prismleaf' ),
				'priority'    => 1010,
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_global_layout' );

if ( ! function_exists( 'prismleaf_customize_register_global_branding' ) ) {
	/**
	 * Register branding-related Customizer settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_global_branding( $wp_customize ) {
		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_global_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$wp_customize->add_setting(
			'prismleaf_brand_heading_palette',
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
				'prismleaf_brand_heading_palette',
				array(
					'section' => $section_id,
					'label'   => __( 'Palette', 'prismleaf' ),
					'priority' => 2000,
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_brand_force_light',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => false,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_brand_force_light',
			array(
				'type'        => 'checkbox',
				'section'     => $section_id,
				'label'       => __( 'Force light mode', 'prismleaf' ),
				'description' => __( 'When enabled, the site is always rendered in light mode and dark palette controls are hidden.', 'prismleaf' ),
				'priority'    => 2010,
			)
		);

		$roles = array(
			'primary'   => array(
				'label'    => __( 'Primary', 'prismleaf' ),
				'priority' => 2020,
			),
			'secondary' => array(
				'label'    => __( 'Secondary', 'prismleaf' ),
				'priority' => 2030,
			),
			'tertiary'  => array(
				'label'    => __( 'Tertiary', 'prismleaf' ),
				'priority' => 2040,
			),
			'error'     => array(
				'label'    => __( 'Error', 'prismleaf' ),
				'priority' => 2050,
			),
			'warning'   => array(
				'label'    => __( 'Warning', 'prismleaf' ),
				'priority' => 2060,
			),
			'info'      => array(
				'label'    => __( 'Info', 'prismleaf' ),
				'priority' => 2070,
			),
		);

		foreach ( $roles as $role => $role_data ) {
			$setting_id = "prismleaf_brand_{$role}_light";

			$wp_customize->add_setting(
				$setting_id,
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
					$setting_id,
					array(
						'section'     => $section_id,
						'label'       => $role_data['label'],
						'description' => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
						'priority'    => $role_data['priority'],
					)
				)
			);
		}

		$wp_customize->add_setting(
			'prismleaf_neutral_light_background',
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
				'prismleaf_neutral_light_background',
				array(
					'section'     => $section_id,
					'label'       => __( 'Light background', 'prismleaf' ),
					'description' => __( 'Optional. Drives the light neutral surface palette.', 'prismleaf' ),
					'priority'    => 2080,
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_neutral_light_foreground',
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
				'prismleaf_neutral_light_foreground',
				array(
					'section'     => $section_id,
					'label'       => __( 'Light foreground', 'prismleaf' ),
					'description' => __( 'Optional. Drives the light neutral foreground palette.', 'prismleaf' ),
					'priority'    => 2090,
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_neutral_dark_background',
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
				'prismleaf_neutral_dark_background',
				array(
					'section'     => $section_id,
					'label'       => __( 'Dark background', 'prismleaf' ),
					'description' => __( 'Optional. Drives the dark neutral surface palette.', 'prismleaf' ),
					'priority'    => 2100,
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_neutral_dark_foreground',
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
				'prismleaf_neutral_dark_foreground',
				array(
					'section'     => $section_id,
					'label'       => __( 'Dark foreground', 'prismleaf' ),
					'description' => __( 'Optional. Drives the dark neutral foreground palette.', 'prismleaf' ),
					'priority'    => 2110,
				)
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_global_branding' );
add_action( 'customize_save_after', 'prismleaf_customize_save_brand_palettes' );

if ( ! function_exists( 'prismleaf_customize_save_brand_palettes' ) ) {
	/**
	 * Enforce brand save rules (no dark override without light).
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_save_brand_palettes( $manager ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return;
		}

		$roles = array( 'primary', 'secondary', 'tertiary', 'error', 'warning', 'info' );

		foreach ( $roles as $role ) {
			$light_id = "prismleaf_brand_{$role}_light";
			$dark_id  = "prismleaf_brand_{$role}_dark";

			$light_value = prismleaf_sanitize_optional_hex_color( get_theme_mod( $light_id, null ) );
			$dark_value  = prismleaf_sanitize_optional_hex_color( get_theme_mod( $dark_id, null ) );

			// If light is not set, dark overrides are removed.
			if ( null === $light_value ) {
				if ( null !== $dark_value ) {
					remove_theme_mod( $dark_id );
				}
				continue;
			}

			// Keep dark values in sync with light values.
			if ( $light_value !== $dark_value ) {
				set_theme_mod( $dark_id, $light_value );
			}
		}
	}
}

if ( ! function_exists( 'prismleaf_layout_body_classes' ) ) {
	/**
	 * Add layout-related body classes based on Customizer settings.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $classes Existing body classes.
	 * @return string[] Modified body classes.
	 */
	function prismleaf_layout_body_classes( $classes ) {
		$is_mobile = wp_is_mobile();

		$framed = prismleaf_get_theme_mod_bool( 'prismleaf_layout_framed', false );
		if ( $is_mobile ) {
			$framed = false;
		}

		$header_visible = prismleaf_get_theme_mod_bool( 'prismleaf_layout_header_visible', true );
		$footer_visible = prismleaf_get_theme_mod_bool( 'prismleaf_layout_footer_visible', true );

		$sidebar_left_visible  = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_left_visible', true );
		$sidebar_right_visible = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_right_visible', true );

		if ( function_exists( 'is_active_sidebar' ) ) {
			if ( ! is_active_sidebar( 'sidebar-left' ) ) {
				$sidebar_left_visible = false;
			}

			if ( ! is_active_sidebar( 'sidebar-right' ) ) {
				$sidebar_right_visible = false;
			}
		}

		if ( ! $header_visible ) {
			$classes[] = 'prismleaf-header-hidden';
		}

		if ( ! $footer_visible ) {
			$classes[] = 'prismleaf-footer-hidden';
		}

		if ( ! $sidebar_left_visible ) {
			$classes[] = 'prismleaf-sidebar-left-hidden';
		}

		if ( ! $sidebar_right_visible ) {
			$classes[] = 'prismleaf-sidebar-right-hidden';
		}

		$all_hidden = prismleaf_layout_all_regions_hidden();
		if ( $all_hidden ) {
			$classes[] = 'prismleaf-layout-all-hidden';
		}

		$classes[] = $framed ? 'prismleaf-layout-framed' : 'prismleaf-layout-not-framed';

		if ( $header_visible ) {
			$contained = $is_mobile ? false : prismleaf_get_theme_mod_bool( 'prismleaf_layout_header_contained', true );
			$classes[] = $contained ? 'prismleaf-header-contained' : 'prismleaf-header-not-contained';

			if ( ! $contained ) {
				$floating  = prismleaf_get_theme_mod_bool( 'prismleaf_layout_header_floating', true );
				$classes[] = $floating ? 'prismleaf-header-floating' : 'prismleaf-header-not-floating';
			}
		}

		if ( $footer_visible ) {
			$contained = $is_mobile ? false : prismleaf_get_theme_mod_bool( 'prismleaf_layout_footer_contained', true );
			$classes[] = $contained ? 'prismleaf-footer-contained' : 'prismleaf-footer-not-contained';

			if ( ! $contained ) {
				$floating  = prismleaf_get_theme_mod_bool( 'prismleaf_layout_footer_floating', true );
				$classes[] = $floating ? 'prismleaf-footer-floating' : 'prismleaf-footer-not-floating';
			}
		}

		if ( $sidebar_left_visible ) {
			$contained = $is_mobile ? false : prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_left_contained', true );
			$classes[] = $contained ? 'prismleaf-sidebar-left-contained' : 'prismleaf-sidebar-left-not-contained';

			if ( ! $contained ) {
				$floating  = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_left_floating', true );
				$classes[] = $floating ? 'prismleaf-sidebar-left-floating' : 'prismleaf-sidebar-left-not-floating';
			}
		}

		if ( $sidebar_right_visible ) {
			$contained = $is_mobile ? false : prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_right_contained', true );
			$classes[] = $contained ? 'prismleaf-sidebar-right-contained' : 'prismleaf-sidebar-right-not-contained';

			if ( ! $contained ) {
				$floating  = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_right_floating', true );
				$classes[] = $floating ? 'prismleaf-sidebar-right-floating' : 'prismleaf-sidebar-right-not-floating';
			}
		}

		return $classes;
	}
}
add_filter( 'body_class', 'prismleaf_layout_body_classes' );
