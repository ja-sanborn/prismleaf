<?php
/**
 * Customizer helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_options_section' ) ) {
	/**
	 * Register a section under the Theme Options panel.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Section arguments.
	 * @return void
	 */
	function prismleaf_register_options_section( $wp_customize, $args ) {
		$defaults = array(
			'id'          => '',
			'title'       => '',
			'description' => '',
			'priority'    => 0,
			'panel'       => 'prismleaf_theme_options',
		);

		$args = wp_parse_args( $args, $defaults );
		$args['id']          = prismleaf_sanitize_customizer_id( $args['id'] );
		$args['title']       = prismleaf_sanitize_text( $args['title'] );
		$args['description'] = prismleaf_sanitize_text( $args['description'] );
		$args['panel']       = prismleaf_sanitize_customizer_id( $args['panel'] );
		$args['priority']    = prismleaf_sanitize_int( $args['priority'] );

		if ( '' === $args['id'] || '' === $args['title'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		if ( $wp_customize->get_section( $args['id'] ) ) {
			return;
		}

		$wp_customize->add_section(
			$args['id'],
			array(
				'title'       => $args['title'],
				'description' => $args['description'],
				'panel'       => $args['panel'],
				'priority'    => $args['priority'],
			)
		);
	}
}

if ( ! function_exists( 'prismleaf_add_section_header_control' ) ) {
	/**
	 * Add a section header control.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_section_header_control( $wp_customize, $args ) {
		$defaults = array(
			'setting_id' => '',
			'label'      => '',
			'section'    => '',
			'priority'   => 0,
			'active_callback' => null,
		);

		$args = wp_parse_args( $args, $defaults );
		$args['setting_id'] = prismleaf_sanitize_customizer_id( $args['setting_id'] );
		$args['label']      = prismleaf_sanitize_text( $args['label'] );
		$args['section']    = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['priority']   = prismleaf_sanitize_int( $args['priority'] );

		if ( '' === $args['setting_id'] || '' === $args['label'] || '' === $args['section'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		$wp_customize->add_setting(
			$args['setting_id'],
			array(
				'default'           => '',
				'sanitize_callback' => 'prismleaf_sanitize_text',
			)
		);

		$wp_customize->add_control(
			new Prismleaf_Customize_Section_Header_Control(
				$wp_customize,
				$args['setting_id'],
				array(
					'label'    => $args['label'],
					'section'  => $args['section'],
					'priority' => $args['priority'],
					'active_callback' => $args['active_callback'],
				)
			)
		);
	}
}

if ( ! function_exists( 'prismleaf_add_checkbox_control' ) ) {
	/**
	 * Add a checkbox control with defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_checkbox_control( $wp_customize, $args ) {
		$defaults = array(
			'setting_id'      => '',
			'section'         => '',
			'label'           => '',
			'description'     => '',
			'priority'        => 0,
			'default_key'     => '',
			'default_fallback'=> false,
			'transport'       => 'refresh',
			'active_callback' => null,
		);

		$args = wp_parse_args( $args, $defaults );
		$args['setting_id']       = prismleaf_sanitize_customizer_id( $args['setting_id'] );
		$args['section']          = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['label']            = prismleaf_sanitize_text( $args['label'] );
		$args['description']      = prismleaf_sanitize_text( $args['description'] );
		$args['priority']         = prismleaf_sanitize_int( $args['priority'] );
		$args['default_key']      = prismleaf_sanitize_text( $args['default_key'] );
		$args['default_fallback'] = prismleaf_sanitize_boolean( $args['default_fallback'] );
		$args['transport']        = prismleaf_sanitize_transport( $args['transport'] );

		if ( '' === $args['setting_id'] || '' === $args['section'] || '' === $args['label'] || '' === $args['default_key'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		$wp_customize->add_setting(
			$args['setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['default_key'], $args['default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_boolean',
				'transport'         => $args['transport'],
			)
		);

		$control_args = array(
			'type'        => 'checkbox',
			'section'     => $args['section'],
			'label'       => $args['label'],
			'description' => $args['description'],
			'priority'    => $args['priority'],
		);

		if ( $args['active_callback'] ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		$wp_customize->add_control( $args['setting_id'], $control_args );
	}
}

if ( ! function_exists( 'prismleaf_add_select_control' ) ) {
	/**
	 * Add a select control with defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_select_control( $wp_customize, $args ) {
		$defaults = array(
			'setting_id'       => '',
			'section'          => '',
			'label'            => '',
			'description'      => '',
			'priority'         => 0,
			'default_key'      => '',
			'default_fallback' => '',
			'choices'          => array(),
			'sanitize_callback'=> '',
			'transport'        => 'refresh',
			'active_callback'  => null,
		);

		$args = wp_parse_args( $args, $defaults );
		$args['setting_id']  = prismleaf_sanitize_customizer_id( $args['setting_id'] );
		$args['section']     = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['label']       = prismleaf_sanitize_text( $args['label'] );
		$args['description'] = prismleaf_sanitize_text( $args['description'] );
		$args['priority']    = prismleaf_sanitize_int( $args['priority'] );
		$args['default_key'] = prismleaf_sanitize_text( $args['default_key'] );
		$args['transport']   = prismleaf_sanitize_transport( $args['transport'] );

		if ( '' === $args['setting_id'] || '' === $args['section'] || '' === $args['label'] || '' === $args['default_key'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		if ( ! is_array( $args['choices'] ) || empty( $args['choices'] ) ) {
			return;
		}

		if ( empty( $args['sanitize_callback'] ) || ! is_string( $args['sanitize_callback'] ) ) {
			return;
		}

		$wp_customize->add_setting(
			$args['setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['default_key'], $args['default_fallback'] ),
				'sanitize_callback' => $args['sanitize_callback'],
				'transport'         => $args['transport'],
			)
		);

		$control_args = array(
			'type'        => 'select',
			'section'     => $args['section'],
			'label'       => $args['label'],
			'description' => $args['description'],
			'priority'    => $args['priority'],
			'choices'     => $args['choices'],
		);

		if ( $args['active_callback'] ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		$wp_customize->add_control( $args['setting_id'], $control_args );
	}
}

if ( ! function_exists( 'prismleaf_add_palette_preview_control' ) ) {
	/**
	 * Add a palette preview control with computed palette JSON storage.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_palette_preview_control( $wp_customize, $args ) {
		$defaults = array(
			'setting_id'       => '',
			'palette_setting_id' => '',
			'section'          => '',
			'label'            => '',
			'description'      => '',
			'priority'         => 0,
			'default_key'      => '',
			'default_fallback' => '',
			'palette_default'  => '',
			'transport'        => 'refresh',
			'active_callback'  => null,
		);

		$args = wp_parse_args( $args, $defaults );
		$args['setting_id']         = prismleaf_sanitize_customizer_id( $args['setting_id'] );
		$args['palette_setting_id'] = prismleaf_sanitize_customizer_id( $args['palette_setting_id'] );
		$args['section']            = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['label']              = prismleaf_sanitize_text( $args['label'] );
		$args['description']        = prismleaf_sanitize_text( $args['description'] );
		$args['priority']           = prismleaf_sanitize_int( $args['priority'] );
		$args['default_key']        = prismleaf_sanitize_text( $args['default_key'] );
		$args['transport']          = prismleaf_sanitize_transport( $args['transport'] );

		if ( '' === $args['setting_id'] || '' === $args['palette_setting_id'] || '' === $args['section'] || '' === $args['label'] || '' === $args['default_key'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		$wp_customize->add_setting(
			$args['setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['default_key'], $args['default_fallback'] ),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$args['palette_setting_id'],
			array(
				'default'           => $args['palette_default'],
				'sanitize_callback' => 'prismleaf_sanitize_palette_json',
				'transport'         => $args['transport'],
			)
		);

			$control_args = array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'settings'    => array(
					'default' => $args['setting_id'],
					'palette' => $args['palette_setting_id'],
				),
			'palette_labels' => prismleaf_get_palette_preview_labels( prismleaf_get_palette_keys() ),
		);

		if ( $args['active_callback'] ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		$wp_customize->add_control(
			new Prismleaf_Customize_Palette_Preview_Control(
				$wp_customize,
				$args['setting_id'],
				$control_args
			)
		);
	}
}

if ( ! function_exists( 'prismleaf_add_palette_preview_control_with_defaults' ) ) {
	/**
	 * Add a palette preview control with shared defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_palette_preview_control_with_defaults( $wp_customize, $args ) {
		$defaults = array(
			'description'     => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
			'default_fallback'=> '',
			'palette_default' => '',
		);

		$args = wp_parse_args( $args, $defaults );
		prismleaf_add_palette_preview_control( $wp_customize, $args );
	}
}

if ( ! function_exists( 'prismleaf_add_neutral_preview_control' ) ) {
	/**
	 * Add a neutral preview control with computed neutral JSON storage.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_neutral_preview_control( $wp_customize, $args ) {
		$defaults = array(
			'light_setting_id'   => '',
			'dark_setting_id'    => '',
			'neutral_setting_id' => '',
			'section'            => '',
			'label'              => '',
			'description'        => '',
			'priority'           => 0,
			'default_light_key'  => '',
			'default_dark_key'   => '',
			'default_fallback'   => '',
			'neutral_default'    => '',
			'transport'          => 'refresh',
			'active_callback'    => null,
		);

		$args = wp_parse_args( $args, $defaults );
		$args['light_setting_id']   = prismleaf_sanitize_customizer_id( $args['light_setting_id'] );
		$args['dark_setting_id']    = prismleaf_sanitize_customizer_id( $args['dark_setting_id'] );
		$args['neutral_setting_id'] = prismleaf_sanitize_customizer_id( $args['neutral_setting_id'] );
		$args['section']            = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['label']              = prismleaf_sanitize_text( $args['label'] );
		$args['description']        = prismleaf_sanitize_text( $args['description'] );
		$args['priority']           = prismleaf_sanitize_int( $args['priority'] );
		$args['default_light_key']  = prismleaf_sanitize_text( $args['default_light_key'] );
		$args['default_dark_key']   = prismleaf_sanitize_text( $args['default_dark_key'] );
		$args['transport']          = prismleaf_sanitize_transport( $args['transport'] );

		if ( '' === $args['light_setting_id'] || '' === $args['dark_setting_id'] || '' === $args['neutral_setting_id'] || '' === $args['section'] || '' === $args['label'] || '' === $args['default_light_key'] || '' === $args['default_dark_key'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		$wp_customize->add_setting(
			$args['light_setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['default_light_key'], $args['default_fallback'] ),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$args['dark_setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['default_dark_key'], $args['default_fallback'] ),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$args['neutral_setting_id'],
			array(
				'default'           => $args['neutral_default'],
				'sanitize_callback' => 'prismleaf_sanitize_neutral_json',
				'transport'         => $args['transport'],
			)
		);

			$control_args = array(
				'label'       => $args['label'],
				'description' => $args['description'],
				'section'     => $args['section'],
				'priority'    => $args['priority'],
				'settings'    => array(
					'light'   => $args['light_setting_id'],
					'dark'    => $args['dark_setting_id'],
					'neutral' => $args['neutral_setting_id'],
				),
			'neutral_labels' => prismleaf_get_neutral_preview_labels( prismleaf_get_neutral_palette_keys() ),
		);

		if ( $args['active_callback'] ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		$wp_customize->add_control(
			new Prismleaf_Customize_Neutral_Preview_Control(
				$wp_customize,
				$args['neutral_setting_id'],
				$control_args
			)
		);
	}
}

if ( ! function_exists( 'prismleaf_add_neutral_preview_control_with_defaults' ) ) {
	/**
	 * Add a neutral preview control with shared defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_neutral_preview_control_with_defaults( $wp_customize, $args ) {
		$defaults = array(
			'description'     => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
			'default_fallback'=> '',
			'neutral_default' => '',
		);

		$args = wp_parse_args( $args, $defaults );
		prismleaf_add_neutral_preview_control( $wp_customize, $args );
	}
}

if ( ! function_exists( 'prismleaf_filter_language_attributes' ) ) {
	/**
	 * Append theme mode overrides to the HTML language attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Language attributes string.
	 * @return string
	 */
	function prismleaf_filter_language_attributes( $output ) {
		$mode = prismleaf_get_theme_mod_theme_mode(
			'prismleaf_palette_theme_mode',
			prismleaf_get_default_option( 'palette_theme_mode', 'system' )
		);

		if ( 'system' === $mode ) {
			return $output;
		}

		return $output . ' data-prismleaf-color-scheme="' . esc_attr( $mode ) . '"';
	}
}
add_filter( 'language_attributes', 'prismleaf_filter_language_attributes' );
