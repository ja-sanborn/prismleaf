<?php
/**
 * Prismleaf Customizer: Palette
 *
 * Defines Theme Options → Palette controls.
 *
 * Structure:
 * - Two sections: Light Palette, Dark Palette
 * - Roles: Primary, Secondary, Tertiary
 * - Base colors only (no “light base / dark base” wording in labels)
 * - Derived palette values are computed automatically
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Activate dark brand controls only when their light counterparts are set.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control Control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_brand_dark_role_enabled' ) ) {
	function prismleaf_customize_callback_brand_dark_role_enabled( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return false;
		}

		// Dark controls are disabled when the site is forced to light mode.
		$force_light = function_exists( 'prismleaf_customize_get_bool' )
			? prismleaf_customize_get_bool( $control->manager, 'prismleaf_brand_force_light', false )
			: (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_brand_force_light', false ) );

		if ( $force_light ) {
			return false;
		}

		$id = (string) $control->id;

		if ( ! preg_match( '/^prismleaf_brand_(?P<role>[a-z0-9_]+)_dark$/', $id, $matches ) ) {
			return false;
		}

		$role            = $matches['role'];
		$light_setting   = "prismleaf_brand_{$role}_light";
		$light_value     = null;
		$light_sanitized = null;

		$manager = $control->manager;
		$setting = ( $manager instanceof WP_Customize_Manager ) ? $manager->get_setting( $light_setting ) : null;

		if ( $setting ) {
			$light_value = $setting->post_value( null );
			if ( null === $light_value ) {
				$light_value = get_theme_mod( $light_setting, null );
			}
		} else {
			$light_value = get_theme_mod( $light_setting, null );
		}

		$light_sanitized = prismleaf_sanitize_optional_hex_color( $light_value );

		return null !== $light_sanitized;
	}
}

/**
 * Register branding-related Customizer settings and controls.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
if ( ! function_exists( 'prismleaf_customize_register_global_branding' ) ) {
	function prismleaf_customize_register_global_branding( $wp_customize ) {
	if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
		return;
	}

		$wp_customize->add_section(
			'prismleaf_branding',
			array(
				'title'       => __( 'Palette', 'prismleaf' ),
				'description' => __( 'Define the core brand colors used throughout the theme. Leave a color blank to keep the default. Shades and containers are generated automatically.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 10,
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
				'section'     => 'prismleaf_branding',
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
						'section' => 'prismleaf_branding',
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
					'section'     => 'prismleaf_branding',
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

/**
 * Enforce brand save rules (no dark override without light).
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $manager Customizer manager instance.
 * @return void
 */
if ( ! function_exists( 'prismleaf_customize_save_brand_palettes' ) ) {
	function prismleaf_customize_save_brand_palettes( $manager ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return;
		}

		$force_light = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_brand_force_light', false ) );

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



