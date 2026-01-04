<?php
/**
 * Prismleaf Customizer: Header Options
 *
 * Registers Customizer settings for header layout, styling, and components.
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
if ( ! function_exists( 'prismleaf_customize_register_header_layout' ) ) {
	/**
	 * Register header layout controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_header_layout( $wp_customize ) {
		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_header_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		// Header.
		$wp_customize->add_setting(
			'prismleaf_layout_heading_header',
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
				'prismleaf_layout_heading_header',
				array(
					'section' => $section_id,
					'label'   => __( 'Layout', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_header_visible',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_header_visible',
			array(
				'type'            => 'checkbox',
				'section'         => $section_id,
				'label'           => __( 'Show header', 'prismleaf' ),
				'description'     => __( 'Controls whether the header is displayed.', 'prismleaf' ),
				'active_callback' => 'prismleaf_customize_callback_not_all_hidden',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_header_contained',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_header_contained',
			array(
				'type'            => 'checkbox',
				'section'         => $section_id,
				'label'           => __( 'Contain header', 'prismleaf' ),
				'description'     => __(
					'When enabled (non-framed desktop), the header is rendered inside the main content area. This option is disabled when using the framed layout.',
					'prismleaf'
				),
				'active_callback' => 'prismleaf_customize_callback_header_contained_active',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_header_floating',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_header_floating',
			array(
				'type'            => 'checkbox',
				'section'         => $section_id,
				'label'           => __( 'Floating header', 'prismleaf' ),
				'description'     => __( 'When disabled, the header stretches to the viewport edge on desktop layouts.', 'prismleaf' ),
				'active_callback' => 'prismleaf_customize_callback_header_floating_active',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_header_height',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'prismleaf_sanitize_header_height',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_header_height',
			array(
				'type'            => 'number',
				'section'         => $section_id,
				'label'           => __( 'Header height', 'prismleaf' ),
				'description'     => __( 'Optional. Leave blank to use the theme default. Values 0-15 are treated as auto height; 16-240 are fixed heights.', 'prismleaf' ),
				'input_attrs'     => array(
					'min'  => 0,
					'max'  => 240,
					'step' => 1,
				),
				'active_callback' => 'prismleaf_customize_callback_header_visible',
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_header_layout' );
if ( ! function_exists( 'prismleaf_customize_register_header_style' ) ) {
	/**
	 * Register Customizer controls for header styling.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_header_style( $wp_customize ) {
		if ( ! $wp_customize instanceof WP_Customize_Manager ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_header_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$prefix = 'prismleaf_global_header_';

		$wp_customize->add_setting(
			'prismleaf_global_heading_header',
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
				'prismleaf_global_heading_header',
				array(
					'section'     => $section_id,
					'label'       => __( 'Header', 'prismleaf' ),
					'description' => __( 'Controls the header region surface.', 'prismleaf' ),
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
					'section'     => $section_id,
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
				'section'         => $section_id,
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
add_action( 'customize_register', 'prismleaf_customize_register_header_style' );
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

		$section_id = 'prismleaf_header_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$wp_customize->add_setting(
			'prismleaf_header_heading_branding',
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
				'prismleaf_header_heading_branding',
				array(
					'section' => $section_id,
					'label'   => __( 'Branding', 'prismleaf' ),
				)
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
				'section'     => $section_id,
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
					'section'     => $section_id,
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
					'section'         => $section_id,
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
				'section'     => $section_id,
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
				'section'         => $section_id,
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
					'section'         => $section_id,
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
					'section'         => $section_id,
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
if ( ! function_exists( 'prismleaf_customize_register_site_icon' ) ) {
	/**
	 * Register Icon Customizer settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_site_icon( $wp_customize ) {
		if ( ! ( $wp_customize instanceof WP_Customize_Manager ) ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_header_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$wp_customize->add_setting(
			'prismleaf_header_heading_icon',
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
				'prismleaf_header_heading_icon',
				array(
					'section' => $section_id,
					'label'   => __( 'Icon', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_icon_size',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_sanitize_site_metadata_icon_size',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_site_metadata_icon_size',
			array(
				'type'        => 'select',
				'section'     => $section_id,
				'label'       => __( 'Icon size', 'prismleaf' ),
				'description' => __( 'Select the icon size. Default uses the theme styles.', 'prismleaf' ),
				'choices'     => array(
					''       => __( 'Default (use theme)', 'prismleaf' ),
					'small'  => __( 'Small', 'prismleaf' ),
					'medium' => __( 'Medium', 'prismleaf' ),
					'large'  => __( 'Large', 'prismleaf' ),
				),
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_icon_corners',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => null,
				'sanitize_callback' => 'prismleaf_sanitize_site_metadata_icon_corners',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_site_metadata_icon_corners',
			array(
				'type'        => 'select',
				'section'     => $section_id,
				'label'       => __( 'Icon corners', 'prismleaf' ),
				'description' => __( 'Choose the icon shape.', 'prismleaf' ),
				'choices'     => array(
					''       => __( 'Default (use theme)', 'prismleaf' ),
					'square' => __( 'Square', 'prismleaf' ),
					'circle' => __( 'Circle', 'prismleaf' ),
					'round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		$wp_customize->add_setting(
			'prismleaf_site_metadata_icon_border',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => false,
				'sanitize_callback' => 'prismleaf_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_site_metadata_icon_border',
			array(
				'type'        => 'checkbox',
				'section'     => $section_id,
				'label'       => __( 'Show icon border', 'prismleaf' ),
				'description' => __( 'Adds a thin border using the standard border color.', 'prismleaf' ),
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_site_icon' );
if ( ! function_exists( 'prismleaf_customize_register_header' ) ) {
	/**
	 * Register Header settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_header( $wp_customize ) {
		if ( ! ( $wp_customize instanceof WP_Customize_Manager ) ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_header_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$wp_customize->add_setting(
			'prismleaf_header_heading_components',
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
				'prismleaf_header_heading_components',
				array(
					'section' => $section_id,
					'label'   => __( 'Header Components', 'prismleaf' ),
				)
			)
		);

		$settings = array(
			'prismleaf_header_show_theme_switch'   => array(
				'default'     => true,
				'label'       => __( 'Show theme switch', 'prismleaf' ),
				'description' => __( 'Controls whether the theme switch appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_show_search'         => array(
				'default'     => true,
				'label'       => __( 'Show search', 'prismleaf' ),
				'description' => __( 'Controls whether the search control appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_show_primary_menu'   => array(
				'default'     => true,
				'label'       => __( 'Show primary menu', 'prismleaf' ),
				'description' => __( 'Controls whether the primary menu appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_show_secondary_menu' => array(
				'default'     => true,
				'label'       => __( 'Show secondary menu', 'prismleaf' ),
				'description' => __( 'Controls whether the secondary menu appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_swap_menus'          => array(
				'default'     => false,
				'label'       => __( 'Swap primary and secondary menu', 'prismleaf' ),
				'description' => __( 'When enabled, the secondary menu appears above the header content area.', 'prismleaf' ),
			),
		);

		foreach ( $settings as $setting_id => $data ) {
			$wp_customize->add_setting(
				$setting_id,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => $data['default'],
					'sanitize_callback' => 'prismleaf_sanitize_checkbox',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$setting_id,
				array(
					'type'        => 'checkbox',
					'section'     => $section_id,
					'label'       => $data['label'],
					'description' => $data['description'],
				)
			);
		}
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_header' );
/**
 * Register Prismleaf Search settings and controls.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function prismleaf_customize_register_prismleaf_search( $wp_customize ) {
	if ( ! $wp_customize instanceof WP_Customize_Manager ) {
		return;
	}

	if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
		return;
	}

	$section_id = 'prismleaf_header_options';
	prismleaf_register_theme_option_section( $wp_customize, $section_id );

	$wp_customize->add_setting(
		'prismleaf_header_heading_search',
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
			'prismleaf_header_heading_search',
			array(
				'section' => $section_id,
				'label'   => __( 'Search', 'prismleaf' ),
			)
		)
	);

	$wp_customize->add_setting(
		'prismleaf_search_placeholder',
		array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => __( 'Search', 'prismleaf' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'prismleaf_search_placeholder',
		array(
			'type'        => 'text',
			'section'     => $section_id,
			'label'       => __( 'Placeholder text', 'prismleaf' ),
			'description' => __( 'Text shown inside the search input.', 'prismleaf' ),
		)
	);

	$wp_customize->add_setting(
		'prismleaf_search_flyout',
		array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'default'           => true,
			'sanitize_callback' => 'prismleaf_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'prismleaf_search_flyout',
		array(
			'type'    => 'checkbox',
			'section' => $section_id,
			'label'   => __( 'Enable flyout behavior', 'prismleaf' ),
		)
	);
}
add_action( 'customize_register', 'prismleaf_customize_register_prismleaf_search' );
if ( ! function_exists( 'prismleaf_customize_callback_menu_dark_enabled' ) ) {
	/**
	 * Enable dark menu color only when light color is set.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_menu_dark_enabled( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return false;
		}

		$force_light = function_exists( 'prismleaf_customize_get_bool' )
			? prismleaf_customize_get_bool( $control->manager, 'prismleaf_brand_force_light', false )
			: prismleaf_get_theme_mod_bool( 'prismleaf_brand_force_light', false );

		if ( $force_light ) {
			return false;
		}

		$id = (string) $control->id;
		if ( ! preg_match( '/^prismleaf_menu_(mobile|primary|secondary)_color_dark$/', $id, $matches ) ) {
			return false;
		}

		$light_setting = 'prismleaf_menu_' . $matches[1] . '_color_light';
		$manager       = $control->manager;
		$setting       = ( $manager instanceof WP_Customize_Manager ) ? $manager->get_setting( $light_setting ) : null;

		$value = null;
		if ( $setting ) {
			$value = $setting->post_value( null );
			if ( null === $value ) {
				$value = get_theme_mod( $light_setting, null );
			}
		} else {
			$value = get_theme_mod( $light_setting, null );
		}

		$value = is_string( $value ) ? trim( $value ) : '';

		return '' !== $value;
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_menu_primary_buttons_disabled' ) ) {
	/**
	 * Show primary menu options only when button mode is disabled.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_menu_primary_buttons_disabled( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return true;
		}

		return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_menu_primary_buttons', false );
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_menu_secondary_buttons_disabled' ) ) {
	/**
	 * Show secondary menu options only when button mode is disabled.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_menu_secondary_buttons_disabled( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return true;
		}

		return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_menu_secondary_buttons', false );
	}
}

if ( ! function_exists( 'prismleaf_customize_register_menus' ) ) {
	/**
	 * Register Menu settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_menus( $wp_customize ) {
		if ( ! ( $wp_customize instanceof WP_Customize_Manager ) ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$section_id = 'prismleaf_header_options';
		prismleaf_register_theme_option_section( $wp_customize, $section_id );

		$wp_customize->add_setting(
			'prismleaf_header_heading_menus',
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
				'prismleaf_header_heading_menus',
				array(
					'section' => $section_id,
					'label'   => __( 'Menus', 'prismleaf' ),
				)
			)
		);

		$menu_sections = array(
			'mobile'    => __( 'Mobile Menu', 'prismleaf' ),
			'primary'   => __( 'Primary Menu', 'prismleaf' ),
			'secondary' => __( 'Secondary Menu', 'prismleaf' ),
		);

		foreach ( $menu_sections as $slug => $label ) {
			$wp_customize->add_setting(
				"prismleaf_menus_heading_{$slug}",
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
					"prismleaf_menus_heading_{$slug}",
					array(
						'section' => $section_id,
						'label'   => $label,
					)
				)
			);

			$wp_customize->add_setting(
				"prismleaf_menu_{$slug}_color_light",
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
					"prismleaf_menu_{$slug}_color_light",
					array(
						'section'     => $section_id,
						/* translators: %s is menu label. */
						'label'       => sprintf( __( '%s color (Light palette)', 'prismleaf' ), $label ),
						'description' => __( 'Optional. Leave blank to use the theme defaults.', 'prismleaf' ),
					)
				)
			);

			$wp_customize->add_setting(
				"prismleaf_menu_{$slug}_color_dark",
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
					"prismleaf_menu_{$slug}_color_dark",
					array(
						'section'         => $section_id,
						/* translators: %s is menu label. */
						'label'           => sprintf( __( '%s color (Dark palette)', 'prismleaf' ), $label ),
						'description'     => __( 'Optional. Only available after a light color is set.', 'prismleaf' ),
						'active_callback' => 'prismleaf_customize_callback_menu_dark_enabled',
					)
				)
			);

			if ( 'mobile' === $slug ) {
				$wp_customize->add_setting(
					'prismleaf_menu_mobile_rounded_corners',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => true,
						'sanitize_callback' => 'prismleaf_sanitize_checkbox',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_menu_mobile_rounded_corners',
					array(
						'type'        => 'checkbox',
						'section'     => $section_id,
						'label'       => __( 'Rounded mobile corners', 'prismleaf' ),
						'description' => __( 'Applies rounded corners to the mobile menu panel.', 'prismleaf' ),
					)
				);

			} elseif ( 'primary' === $slug ) {
				$wp_customize->add_setting(
					'prismleaf_menu_primary_buttons',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => false,
						'sanitize_callback' => 'prismleaf_sanitize_checkbox',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_menu_primary_buttons',
					array(
						'type'        => 'checkbox',
						'section'     => $section_id,
						'label'       => __( 'Primary buttons', 'prismleaf' ),
						'description' => __( 'Shows primary menu items as buttons with mobile-style spacing.', 'prismleaf' ),
					)
				);

				$wp_customize->add_setting(
					'prismleaf_menu_primary_stretch',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => true,
						'sanitize_callback' => 'prismleaf_sanitize_checkbox',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_menu_primary_stretch',
					array(
						'type'            => 'checkbox',
						'section'         => $section_id,
						'label'           => __( 'Primary stretch', 'prismleaf' ),
						'description'     => __( 'Controls whether the primary menu bar uses the menu background color.', 'prismleaf' ),
						'active_callback' => 'prismleaf_customize_callback_menu_primary_buttons_disabled',
					)
				);

				$wp_customize->add_setting(
					'prismleaf_menu_primary_divider',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => false,
						'sanitize_callback' => 'prismleaf_sanitize_checkbox',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_menu_primary_divider',
					array(
						'type'            => 'checkbox',
						'section'         => $section_id,
						'label'           => __( 'Primary divider', 'prismleaf' ),
						'description'     => __( 'Adds divider lines around and between primary menu items.', 'prismleaf' ),
						'active_callback' => 'prismleaf_customize_callback_menu_primary_buttons_disabled',
					)
				);

			} else {
				$wp_customize->add_setting(
					'prismleaf_menu_secondary_buttons',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => false,
						'sanitize_callback' => 'prismleaf_sanitize_checkbox',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_menu_secondary_buttons',
					array(
						'type'        => 'checkbox',
						'section'     => $section_id,
						'label'       => __( 'Secondary buttons', 'prismleaf' ),
						'description' => __( 'Shows secondary menu items as buttons with mobile-style spacing.', 'prismleaf' ),
					)
				);

				$wp_customize->add_setting(
					'prismleaf_menu_secondary_stretch',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => true,
						'sanitize_callback' => 'prismleaf_sanitize_checkbox',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_menu_secondary_stretch',
					array(
						'type'            => 'checkbox',
						'section'         => $section_id,
						'label'           => __( 'Secondary stretch', 'prismleaf' ),
						'description'     => __( 'Controls whether the secondary menu bar uses the menu background color.', 'prismleaf' ),
						'active_callback' => 'prismleaf_customize_callback_menu_secondary_buttons_disabled',
					)
				);

				$wp_customize->add_setting(
					'prismleaf_menu_secondary_divider',
					array(
						'type'              => 'theme_mod',
						'capability'        => 'edit_theme_options',
						'default'           => false,
						'sanitize_callback' => 'prismleaf_sanitize_checkbox',
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					'prismleaf_menu_secondary_divider',
					array(
						'type'            => 'checkbox',
						'section'         => $section_id,
						'label'           => __( 'Secondary divider', 'prismleaf' ),
						'description'     => __( 'Adds divider lines around and between secondary menu items.', 'prismleaf' ),
						'active_callback' => 'prismleaf_customize_callback_menu_secondary_buttons_disabled',
					)
				);
			}
		}
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_menus' );
if ( ! function_exists( 'prismleaf_customize_save_menu_palettes' ) ) {
	/**
	 * Enforce menu save rules (no dark override without light).
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_save_menu_palettes( $manager ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return;
		}

		$force_light = prismleaf_get_theme_mod_bool( 'prismleaf_brand_force_light', false );

		$menus = array( 'mobile', 'primary', 'secondary' );
		foreach ( $menus as $slug ) {
			$light_id = "prismleaf_menu_{$slug}_color_light";
			$dark_id  = "prismleaf_menu_{$slug}_color_dark";

			$light_value = prismleaf_sanitize_optional_hex_color( get_theme_mod( $light_id, null ) );
			$dark_value  = prismleaf_sanitize_optional_hex_color( get_theme_mod( $dark_id, null ) );

			if ( null === $light_value && null !== $dark_value ) {
				remove_theme_mod( $dark_id );
			}

			if ( $force_light && null !== $dark_value ) {
				remove_theme_mod( $dark_id );
			}
		}
	}
}
add_action( 'customize_save_after', 'prismleaf_customize_save_menu_palettes' );
