<?php
/**
 * Prismleaf Customizer: Content Options
 *
 * Registers the Content Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_content_options_section' ) ) {
	/**
	 * Register the Content Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_content_options_section( $wp_customize ) {
		if ( ! $wp_customize->get_section( 'prismleaf_content_options' ) ) {
			$wp_customize->add_section(
				'prismleaf_content_options',
				array(
					'title'       => __( 'Content', 'prismleaf' ),
					'description' => __( 'Configure the content layout and styling.', 'prismleaf' ),
					'panel'       => 'prismleaf_theme_options',
					'priority'    => 60,
				)
			);
		}
	}
}
add_action( 'customize_register', 'prismleaf_register_content_options_section' );
