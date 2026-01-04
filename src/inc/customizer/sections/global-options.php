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

if ( ! function_exists( 'prismleaf_customize_callback_brand_dark_role_enabled' ) ) {
	/**
	 * Activate dark brand controls only when their light counterparts are set.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_brand_dark_role_enabled( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return false;
		}

		// Dark controls are disabled when the site is forced to light mode.
		$force_light = function_exists( 'prismleaf_customize_get_bool' )
			? prismleaf_customize_get_bool( $control->manager, 'prismleaf_brand_force_light', false )
			: prismleaf_get_theme_mod_bool( 'prismleaf_brand_force_light', false );

		if ( $force_light ) {
			return false;
		}

		$id = (string) $control->id;

		if ( ! preg_match( '/^prismleaf_brand_(?P<role>[a-z0-9_]+)_dark$/', $id, $matches ) ) {
			return false;
		}

		$role        = $matches['role'];
		$light_value = null;

		$manager = $control->manager;
		$setting = ( $manager instanceof WP_Customize_Manager ) ? $manager->get_setting( "prismleaf_brand_{$role}_light" ) : null;

		if ( $setting ) {
			$light_value = $setting->post_value( null );
			if ( null === $light_value ) {
				$light_value = get_theme_mod( "prismleaf_brand_{$role}_light", null );
			}
		} else {
			$light_value = get_theme_mod( "prismleaf_brand_{$role}_light", null );
		}

		$light_value = is_string( $light_value ) ? trim( $light_value ) : '';

		return '' !== $light_value;
	}
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
				'priority'    => 10,
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
				'priority'    => 5,
			)
		);

		$schemes = array(
			'light' => __( 'Light palette', 'prismleaf' ),
			'dark'  => __( 'Dark palette', 'prismleaf' ),
		);

		$roles = array(
			'primary'   => __( 'Primary', 'prismleaf' ),
			'secondary' => __( 'Secondary', 'prismleaf' ),
			'tertiary'  => __( 'Tertiary', 'prismleaf' ),
			'error'     => __( 'Error', 'prismleaf' ),
			'warning'   => __( 'Warning', 'prismleaf' ),
			'info'      => __( 'Info', 'prismleaf' ),
		);

		foreach ( $schemes as $scheme => $scheme_label ) {
			$wp_customize->add_setting(
				"prismleaf_brand_heading_{$scheme}",
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
					"prismleaf_brand_heading_{$scheme}",
					array(
						'section' => $section_id,
						'label'   => ( 'dark' === $scheme ) ? __( 'Dark', 'prismleaf' ) : __( 'Light', 'prismleaf' ),
					)
				)
			);

			foreach ( $roles as $role => $role_label ) {
				$setting_id = "prismleaf_brand_{$role}_{$scheme}";

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

				$control_args = array(
					'section'     => $section_id,
					/* translators: 1: Color role label. 2: Palette scheme label. */
					'label'       => sprintf( __( '%1$s (%2$s)', 'prismleaf' ), $role_label, $scheme_label ),
					'description' => ( 'dark' === $scheme )
						? __( 'Optional. Only available after the matching light color is set.', 'prismleaf' )
						: __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				);

				// Dark overrides cannot be set unless Light is already overridden.
				if ( 'dark' === $scheme ) {
					$control_args['active_callback'] = 'prismleaf_customize_callback_brand_dark_role_enabled';
				}

				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize,
						$setting_id,
						$control_args
					)
				);
			}
		}
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

		$force_light = prismleaf_get_theme_mod_bool( 'prismleaf_brand_force_light', false );

		$roles = array( 'primary', 'secondary', 'tertiary', 'error', 'warning', 'info' );

		foreach ( $roles as $role ) {
			$light_id = "prismleaf_brand_{$role}_light";
			$dark_id  = "prismleaf_brand_{$role}_dark";

			$light_value = prismleaf_sanitize_optional_hex_color( get_theme_mod( $light_id, null ) );
			$dark_value  = prismleaf_sanitize_optional_hex_color( get_theme_mod( $dark_id, null ) );

			// If light is not set, dark overrides are not allowed.
			if ( null === $light_value && null !== $dark_value ) {
				remove_theme_mod( $dark_id );
			}

			// Force-light mode removes any dark overrides for consistency.
			if ( $force_light && null !== $dark_value ) {
				remove_theme_mod( $dark_id );
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
