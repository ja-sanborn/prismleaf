<?php
/**
 * Prismleaf Customizer: Menus
 *
 * Registers Customizer settings for menu styling.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

		$wp_customize->add_section(
			'prismleaf_menus',
			array(
				'title'       => __( 'Menus', 'prismleaf' ),
				'description' => __( 'Configure menu colors and dividers.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 80,
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
						'section' => 'prismleaf_menus',
						'label'   => $label,
					)
				)
			);

			// Menu color controls.
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
						'section'     => 'prismleaf_menus',
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
						'section'         => 'prismleaf_menus',
						/* translators: %s is menu label. */
						'label'           => sprintf( __( '%s color (Dark palette)', 'prismleaf' ), $label ),
						'description'     => __( 'Optional. Only available after a light color is set.', 'prismleaf' ),
						'active_callback' => 'prismleaf_customize_callback_menu_dark_enabled',
					)
				)
			);

			// Menu divider controls.
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
						'section'     => 'prismleaf_menus',
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
						'section'     => 'prismleaf_menus',
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
						'section'         => 'prismleaf_menus',
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
						'section'         => 'prismleaf_menus',
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
						'section'     => 'prismleaf_menus',
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
						'section'         => 'prismleaf_menus',
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
						'section'         => 'prismleaf_menus',
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
