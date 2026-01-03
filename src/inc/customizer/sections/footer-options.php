<?php
/**
 * Prismleaf Customizer: Footer
 *
 * Registers Customizer settings for footer layout and content.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_sanitize_footer_widget_alignment' ) ) {
	/**
	 * Sanitize footer widget alignment.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Alignment value.
	 * @return string
	 */
	function prismleaf_sanitize_footer_widget_alignment( $value ) {
		$value   = (string) $value;
		$allowed = array( 'left', 'center', 'right', 'stretch' );

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return 'center';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_footer_copyright_alignment' ) ) {
	/**
	 * Sanitize footer copyright alignment.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Alignment value.
	 * @return string
	 */
	function prismleaf_sanitize_footer_copyright_alignment( $value ) {
		$value   = (string) $value;
		$allowed = array( 'left', 'center', 'right' );

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return 'center';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_footer_copyright_text' ) ) {
	/**
	 * Sanitize footer copyright text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	function prismleaf_sanitize_footer_copyright_text( $value ) {
		$value = is_string( $value ) ? $value : '';

		return wp_kses_post( $value );
	}
}

if ( ! function_exists( 'prismleaf_customize_register_footer' ) ) {
	/**
	 * Register Footer settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_footer( $wp_customize ) {
		if ( ! ( $wp_customize instanceof WP_Customize_Manager ) ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$wp_customize->add_section(
			'prismleaf_footer',
			array(
				'title'       => __( 'Footer', 'prismleaf' ),
				'description' => __( 'Configure footer widget layout and copyright text.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 95,
			)
		);

		$wp_customize->add_setting(
			'prismleaf_footer_widget_alignment',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => 'center',
				'sanitize_callback' => 'prismleaf_sanitize_footer_widget_alignment',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_footer_widget_alignment',
			array(
				'type'        => 'select',
				'section'     => 'prismleaf_footer',
				'label'       => __( 'Widget alignment', 'prismleaf' ),
				'description' => __( 'Controls alignment of the footer widget row. Stretch fills the available width.', 'prismleaf' ),
				'choices'     => array(
					'left'    => __( 'Left', 'prismleaf' ),
					'center'  => __( 'Center', 'prismleaf' ),
					'right'   => __( 'Right', 'prismleaf' ),
					'stretch' => __( 'Stretch', 'prismleaf' ),
				),
			)
		);

		$wp_customize->add_setting(
			'prismleaf_footer_copyright_alignment',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => 'center',
				'sanitize_callback' => 'prismleaf_sanitize_footer_copyright_alignment',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_footer_copyright_alignment',
			array(
				'type'    => 'select',
				'section' => 'prismleaf_footer',
				'label'   => __( 'Copyright alignment', 'prismleaf' ),
				'choices' => array(
					'left'   => __( 'Left', 'prismleaf' ),
					'center' => __( 'Center', 'prismleaf' ),
					'right'  => __( 'Right', 'prismleaf' ),
				),
			)
		);

		$wp_customize->add_setting(
			'prismleaf_footer_copyright_text',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'prismleaf_sanitize_footer_copyright_text',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_footer_copyright_text',
			array(
				'type'        => 'text',
				'section'     => 'prismleaf_footer',
				'label'       => __( 'Copyright text', 'prismleaf' ),
				'description' => __( 'Optional. Leave blank to use the site name and current year.', 'prismleaf' ),
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_footer' );
