<?php
/**
 * Prismleaf Customizer: Palette Options
 *
 * Registers the Palette Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_palette_options_section' ) ) {
	/**
	 * Register the Palette Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_palette_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_palette_options',
				'title'       => __( 'Palette', 'prismleaf' ),
				'description' => __( 'Configure the theme color palette.', 'prismleaf' ),
				'priority'    => 10,
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_palette_options_section' );
