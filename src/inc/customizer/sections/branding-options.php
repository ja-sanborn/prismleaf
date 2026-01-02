<?php
/**
 * Prismleaf Customizer: Branding
 *
 * Defines Theme Options → Branding controls.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_customize_callback_site_metadata_tagline_visible' ) ) {
	/**
	 * Show tagline position options only when the tagline is visible.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_site_metadata_tagline_visible( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return true;
		}

		return prismleaf_customize_get_bool( $control->manager, 'prismleaf_site_metadata_tagline_visible', true );
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_site_metadata_title_dark_enabled' ) ) {
	/**
	 * Enable dark title color only when light title color is set.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_site_metadata_title_dark_enabled( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return false;
		}

		$manager = $control->manager;
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return false;
		}

		$setting = $manager->get_setting( 'prismleaf_site_metadata_title_color_light' );
		$value   = $setting ? $setting->post_value( null ) : null;

		if ( null === $value ) {
			$value = get_theme_mod( 'prismleaf_site_metadata_title_color_light', null );
		}

		$value = is_string( $value ) ? trim( $value ) : '';

		return '' !== $value;
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_site_metadata_tagline_dark_enabled' ) ) {
	/**
	 * Enable dark tagline color only when light tagline color is set.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_site_metadata_tagline_dark_enabled( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return false;
		}

		$manager = $control->manager;
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return false;
		}

		$setting = $manager->get_setting( 'prismleaf_site_metadata_tagline_color_light' );
		$value   = $setting ? $setting->post_value( null ) : null;

		if ( null === $value ) {
			$value = get_theme_mod( 'prismleaf_site_metadata_tagline_color_light', null );
		}

		$value = is_string( $value ) ? trim( $value ) : '';

		return '' !== $value;
	}
}

if ( ! function_exists( 'prismleaf_customize_register_site_metadata' ) ) {
	/**
	 * Register Branding Customizer settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_site_metadata( $wp_customize ) {
		if ( ! ( $wp_customize instanceof WP_Customize_Manager ) ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$wp_customize->add_section(
			'prismleaf_site_metadata',
			array(
				'title'       => __( 'Branding', 'prismleaf' ),
				'description' => __( 'Control the display of the site icon, title, and tagline.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 40,
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_icon_visible',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'prismleaf_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_site_metadata_icon_visible',
			array(
				'type'        => 'checkbox',
				'section'     => 'prismleaf_site_metadata',
				'label'       => __( 'Show icon', 'prismleaf' ),
				'description' => __( 'Controls whether the site icon appears in branding.', 'prismleaf' ),
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_title_color_light',
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
				'prismleaf_site_metadata_title_color_light',
				array(
					'section'     => 'prismleaf_site_metadata',
					'label'       => __( 'Title color (Light palette)', 'prismleaf' ),
					'description' => __( 'Optional. Leave blank to use the theme default title link colors.', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_title_color_dark',
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
				'prismleaf_site_metadata_title_color_dark',
				array(
					'section'         => 'prismleaf_site_metadata',
					'label'           => __( 'Title color (Dark palette)', 'prismleaf' ),
					'description'     => __( 'Optional. Only available after a light title color is set.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_site_metadata_title_dark_enabled',
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_tagline_visible',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'prismleaf_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_site_metadata_tagline_visible',
			array(
				'type'        => 'checkbox',
				'section'     => 'prismleaf_site_metadata',
				'label'       => __( 'Show tagline', 'prismleaf' ),
				'description' => __( 'Controls whether the site tagline is displayed.', 'prismleaf' ),
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_tagline_position',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_sanitize_site_metadata_tagline_position',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_site_metadata_tagline_position',
			array(
				'type'            => 'select',
				'section'         => 'prismleaf_site_metadata',
				'label'           => __( 'Tagline position', 'prismleaf' ),
				'description'     => __( 'Choose where the tagline appears relative to the title.', 'prismleaf' ),
				'choices'         => array(
					''       => __( 'Default (use theme)', 'prismleaf' ),
					'inline' => __( 'Inline', 'prismleaf' ),
					'below'  => __( 'Below', 'prismleaf' ),
				),
				'active_callback' => 'prismleaf_customize_callback_site_metadata_tagline_visible',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_tagline_color_light',
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
				'prismleaf_site_metadata_tagline_color_light',
				array(
					'section'         => 'prismleaf_site_metadata',
					'label'           => __( 'Tagline color (Light palette)', 'prismleaf' ),
					'description'     => __( 'Optional. Leave blank to use the theme default tagline color.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_site_metadata_tagline_visible',
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_tagline_color_dark',
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
				'prismleaf_site_metadata_tagline_color_dark',
				array(
					'section'         => 'prismleaf_site_metadata',
					'label'           => __( 'Tagline color (Dark palette)', 'prismleaf' ),
					'description'     => __( 'Optional. Only available after a light tagline color is set.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_site_metadata_tagline_dark_enabled',
				)
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_site_metadata' );

if ( ! function_exists( 'prismleaf_customize_save_site_metadata_palettes' ) ) {
	/**
	 * Enforce site metadata save rules (no dark override without light).
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_save_site_metadata_palettes( $manager ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return;
		}

		$pairs = array(
			array(
				'light' => 'prismleaf_site_metadata_title_color_light',
				'dark'  => 'prismleaf_site_metadata_title_color_dark',
			),
			array(
				'light' => 'prismleaf_site_metadata_tagline_color_light',
				'dark'  => 'prismleaf_site_metadata_tagline_color_dark',
			),
		);

		foreach ( $pairs as $pair ) {
			$light_value = prismleaf_sanitize_optional_hex_color( get_theme_mod( $pair['light'], null ) );
			$dark_value  = prismleaf_sanitize_optional_hex_color( get_theme_mod( $pair['dark'], null ) );

			if ( null === $light_value && null !== $dark_value ) {
				remove_theme_mod( $pair['dark'] );
			}
		}
	}
}
add_action( 'customize_save_after', 'prismleaf_customize_save_site_metadata_palettes' );
