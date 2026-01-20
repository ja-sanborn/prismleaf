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

if ( ! function_exists( 'prismleaf_add_number_control' ) ) {
	/**
	 * Add a number control with defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_number_control( $wp_customize, $args ) {
		$defaults = array(
			'setting_id'       => '',
			'section'          => '',
			'label'            => '',
			'description'      => '',
			'priority'         => 0,
			'default_key'      => '',
			'default_fallback' => '',
			'sanitize_callback'=> '',
			'transport'        => 'refresh',
			'active_callback'  => null,
			'input_attrs'      => array(),
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
			'type'        => 'number',
			'section'     => $args['section'],
			'label'       => $args['label'],
			'description' => $args['description'],
			'priority'    => $args['priority'],
			'input_attrs' => is_array( $args['input_attrs'] ) ? $args['input_attrs'] : array(),
		);

		if ( $args['active_callback'] ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		$wp_customize->add_control( $args['setting_id'], $control_args );
	}
}

if ( ! function_exists( 'prismleaf_add_text_control' ) ) {
	/**
	 * Add a text or textarea control with defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_text_control( $wp_customize, $args ) {
		$defaults = array(
			'setting_id'       => '',
			'section'          => '',
			'label'            => '',
			'description'      => '',
			'priority'         => 0,
			'default_key'      => '',
			'default_fallback' => '',
			'sanitize_callback'=> 'prismleaf_sanitize_text',
			'control_type'     => 'text',
			'transport'        => 'refresh',
			'active_callback'  => null,
			'input_attrs'      => array(),
		);

		$args = wp_parse_args( $args, $defaults );
		$args['setting_id']       = prismleaf_sanitize_customizer_id( $args['setting_id'] );
		$args['section']          = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['label']            = prismleaf_sanitize_text( $args['label'] );
		$args['description']      = prismleaf_sanitize_text( $args['description'] );
		$args['priority']         = prismleaf_sanitize_int( $args['priority'] );
		$args['default_key']      = prismleaf_sanitize_text( $args['default_key'] );
		$args['transport']        = prismleaf_sanitize_transport( $args['transport'] );
		$args['control_type']     = prismleaf_sanitize_text( $args['control_type'] );

		if ( '' === $args['setting_id'] || '' === $args['section'] || '' === $args['label'] || '' === $args['default_key'] || ! array_key_exists( 'priority', $args ) ) {
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
			'section'     => $args['section'],
			'label'       => $args['label'],
			'description' => $args['description'],
			'priority'    => $args['priority'],
			'type'        => $args['control_type'],
		);

		if ( is_array( $args['input_attrs'] ) && ! empty( $args['input_attrs'] ) ) {
			$control_args['input_attrs'] = $args['input_attrs'];
		}

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
			'setting_id'         => '',
			'palette_setting_id' => '',
			'section'            => '',
			'label'              => '',
			'description'        => '',
			'priority'           => 0,
			'default_key'        => '',
			'default_fallback'   => '',
			'palette_default'    => '',
			'transport'          => 'refresh',
			'active_callback'    => null,
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
				'sanitize_callback' => 'prismleaf_sanitize_palette_json_from_base',
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

if ( ! function_exists( 'prismleaf_add_background_image_control' ) ) {
	/**
	 * Add a background image control with supporting settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_background_image_control( $wp_customize, $args ) {
		$defaults = array(
			'section'                    => '',
			'label'                      => '',
			'description'                => '',
			'priority'                   => 0,
			'setting_base'               => '',
			'active_callback'            => null,
			'transport'                  => 'refresh',
			'image_default_key'          => '',
			'image_default_fallback'     => '',
			'repeat_default_key'         => '',
			'repeat_default_fallback'    => 'repeat',
			'position_x_default_key'     => '',
			'position_x_default_fallback'=> 'center',
			'position_y_default_key'     => '',
			'position_y_default_fallback'=> 'center',
			'size_default_key'           => '',
			'size_default_fallback'      => 'auto',
			'attachment_default_key'     => '',
			'attachment_default_fallback'=> 'scroll',
			'preset_default_key'         => '',
			'preset_default_fallback'    => 'default',
		);

		$args = wp_parse_args( $args, $defaults );
		$args['section']               = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['label']                 = prismleaf_sanitize_text( $args['label'] );
		$args['description']           = prismleaf_sanitize_text( $args['description'] );
		$args['priority']              = prismleaf_sanitize_int( $args['priority'] );
		$args['setting_base']          = prismleaf_sanitize_customizer_id( $args['setting_base'] );
		$args['transport']             = prismleaf_sanitize_transport( $args['transport'] );
		$args['image_default_key']     = prismleaf_sanitize_text( $args['image_default_key'] );
		$args['repeat_default_key']    = prismleaf_sanitize_text( $args['repeat_default_key'] );
		$args['position_x_default_key']= prismleaf_sanitize_text( $args['position_x_default_key'] );
		$args['position_y_default_key']= prismleaf_sanitize_text( $args['position_y_default_key'] );
		$args['size_default_key']      = prismleaf_sanitize_text( $args['size_default_key'] );
		$args['attachment_default_key']= prismleaf_sanitize_text( $args['attachment_default_key'] );
		$args['preset_default_key']    = prismleaf_sanitize_text( $args['preset_default_key'] );

		if ( '' === $args['setting_base'] || '' === $args['section'] || '' === $args['label'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		$ids = array(
			'image'      => $args['setting_base'] . '_image',
			'repeat'     => $args['setting_base'] . '_repeat',
			'position_x' => $args['setting_base'] . '_position_x',
			'position_y' => $args['setting_base'] . '_position_y',
			'size'       => $args['setting_base'] . '_size',
			'attachment' => $args['setting_base'] . '_attachment',
			'preset'     => $args['setting_base'] . '_preset',
		);

		$wp_customize->add_setting(
			$ids['image'],
			array(
				'default'           => prismleaf_get_default_option( $args['image_default_key'], $args['image_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_background_image',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$ids['repeat'],
			array(
				'default'           => prismleaf_get_default_option( $args['repeat_default_key'], $args['repeat_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_background_repeat',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$ids['position_x'],
			array(
				'default'           => prismleaf_get_default_option( $args['position_x_default_key'], $args['position_x_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_background_position_x',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$ids['position_y'],
			array(
				'default'           => prismleaf_get_default_option( $args['position_y_default_key'], $args['position_y_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_background_position_y',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$ids['size'],
			array(
				'default'           => prismleaf_get_default_option( $args['size_default_key'], $args['size_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_background_size',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$ids['attachment'],
			array(
				'default'           => prismleaf_get_default_option( $args['attachment_default_key'], $args['attachment_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_background_attachment',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$ids['preset'],
			array(
				'default'           => prismleaf_get_default_option( $args['preset_default_key'], $args['preset_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_background_preset',
				'transport'         => $args['transport'],
			)
		);

		$control_args = array(
			'label'    => $args['label'],
			'description' => $args['description'],
			'section'  => $args['section'],
			'priority' => $args['priority'],
			'settings' => $ids,
		);

		if ( $args['active_callback'] ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		$wp_customize->add_control(
			new Prismleaf_Customize_Background_Image_Control(
				$wp_customize,
				$ids['image'],
				$control_args
			)
		);
	}
}

if ( ! function_exists( 'prismleaf_get_palette_source_choices' ) ) {
	/**
	 * Get palette source choices for a dropdown control.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,string>
	 */
	function prismleaf_get_palette_source_choices() {
		return array(
			'default'       => __( 'Default (Use CSS)', 'prismleaf' ),
			'primary'       => __( 'Primary', 'prismleaf' ),
			'secondary'     => __( 'Secondary', 'prismleaf' ),
			'tertiary'      => __( 'Tertiary', 'prismleaf' ),
			'error'         => __( 'Error', 'prismleaf' ),
			'warning'       => __( 'Warning', 'prismleaf' ),
			'info'          => __( 'Info', 'prismleaf' ),
			'neutral_light' => __( 'Neutral Light', 'prismleaf' ),
			'neutral_dark'  => __( 'Neutral Dark', 'prismleaf' ),
			'custom'        => __( 'Custom', 'prismleaf' ),
		);
	}
}

if ( ! function_exists( 'prismleaf_add_palette_source_control' ) ) {
	/**
	 * Add a palette source control using existing settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @param array<string,mixed>  $args Control arguments.
	 * @return void
	 */
	function prismleaf_add_palette_source_control( $wp_customize, $args ) {
		$defaults = array(
			'source_setting_id'        => '',
			'base_setting_id'          => '',
			'palette_setting_id'       => '',
			'section'                  => '',
			'label'                    => '',
			'description'              => '',
			'priority'                 => 0,
			'active_callback'          => null,
			'source_default_key'       => '',
			'source_default_fallback'  => '',
			'base_default_key'         => '',
			'base_default_fallback'    => '',
			'palette_default_key'      => '',
			'palette_default_fallback' => '',
			'transport'                => 'refresh',
		);

		$args = wp_parse_args( $args, $defaults );
		$args['source_setting_id']   = prismleaf_sanitize_customizer_id( $args['source_setting_id'] );
		$args['base_setting_id']     = prismleaf_sanitize_customizer_id( $args['base_setting_id'] );
		$args['palette_setting_id']  = prismleaf_sanitize_customizer_id( $args['palette_setting_id'] );
		$args['section']             = prismleaf_sanitize_customizer_id( $args['section'] );
		$args['label']               = prismleaf_sanitize_text( $args['label'] );
		$args['description']         = prismleaf_sanitize_text( $args['description'] );
		$args['priority']            = prismleaf_sanitize_int( $args['priority'] );
		$args['source_default_key']  = prismleaf_sanitize_text( $args['source_default_key'] );
		$args['base_default_key']    = prismleaf_sanitize_text( $args['base_default_key'] );
		$args['palette_default_key'] = prismleaf_sanitize_text( $args['palette_default_key'] );
		$args['transport']           = prismleaf_sanitize_transport( $args['transport'] );

		if ( '' === $args['source_setting_id'] || '' === $args['base_setting_id'] || '' === $args['palette_setting_id'] || '' === $args['section'] || '' === $args['label'] || ! array_key_exists( 'priority', $args ) ) {
			return;
		}

		$wp_customize->add_setting(
			$args['source_setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['source_default_key'], $args['source_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_palette_source',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$args['base_setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['base_default_key'], $args['base_default_fallback'] ),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => $args['transport'],
			)
		);

		$wp_customize->add_setting(
			$args['palette_setting_id'],
			array(
				'default'           => prismleaf_get_default_option( $args['palette_default_key'], $args['palette_default_fallback'] ),
				'sanitize_callback' => 'prismleaf_sanitize_palette_source_json',
				'transport'         => $args['transport'],
			)
		);

		$control_args = array(
			'label'       => $args['label'],
			'description' => $args['description'],
			'section'     => $args['section'],
			'priority'    => $args['priority'],
			'choices'     => prismleaf_get_palette_source_choices(),
			'settings'    => array(
				'source'  => $args['source_setting_id'],
				'base'    => $args['base_setting_id'],
				'palette' => $args['palette_setting_id'],
			),
			'palette_labels' => prismleaf_get_palette_preview_labels( prismleaf_get_palette_keys() ),
		);

		if ( $args['active_callback'] ) {
			$control_args['active_callback'] = $args['active_callback'];
		}

		$wp_customize->add_control(
			new Prismleaf_Customize_Palette_Source_Control(
				$wp_customize,
				$args['source_setting_id'],
				$control_args
			)
		);
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
		$mode = prismleaf_get_theme_mod_theme_mode( 'prismleaf_palette_theme_mode','palette_theme_mode' );

		if ( 'system' === $mode ) {
			return $output;
		}

		return $output . ' data-prismleaf-color-scheme="' . esc_attr( $mode ) . '"';
	}
}
add_filter( 'language_attributes', 'prismleaf_filter_language_attributes' );
