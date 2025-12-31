<?php
/**
 * Prismleaf Customizer: Prismleaf Search
 *
 * Registers Customizer settings for the Prismleaf Search template part.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

	$wp_customize->add_section(
		'prismleaf_search',
		array(
			'title'       => __( 'Search', 'prismleaf' ),
			'description' => __( 'Configure the Prismleaf Search control displayed in the template part.', 'prismleaf' ),
			'panel'       => 'prismleaf_theme_options',
			'priority'    => 50,
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
			'section'     => 'prismleaf_search',
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
			'section' => 'prismleaf_search',
			'label'   => __( 'Enable flyout behavior', 'prismleaf' ),
		)
	);
}
add_action( 'customize_register', 'prismleaf_customize_register_prismleaf_search' );
