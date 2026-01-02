<?php
/**
 * Prismleaf Customizer: Site Icon
 *
 * Defines Theme Options - Icon controls.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

		$wp_customize->add_section(
			'prismleaf_site_icon',
			array(
				'title'       => __( 'Icon', 'prismleaf' ),
				'description' => __( 'Control the visibility and styling of the site icon.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 35,
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
				'section'     => 'prismleaf_site_icon',
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
				'section'     => 'prismleaf_site_icon',
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
				'section'     => 'prismleaf_site_icon',
				'label'       => __( 'Show icon border', 'prismleaf' ),
				'description' => __( 'Adds a thin border using the standard border color.', 'prismleaf' ),
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_site_icon' );
