<?php
/**
 * Prismleaf Customizer: Frame Options
 *
 * Registers the Frame Options section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_frame_options_section' ) ) {
	/**
	 * Register the Frame Options section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_frame_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_frame_options',
				'title'       => __( 'Frame', 'prismleaf' ),
				'description' => __( 'Frame layout and styling.', 'prismleaf' ),
				'priority'    => 20,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_frame_heading_layout',
				'label'      => __( 'Layout', 'prismleaf' ),
				'section'    => 'prismleaf_frame_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_global_framed_layout',
				'section'          => 'prismleaf_frame_options',
				'label'            => __( 'Use framed layout', 'prismleaf' ),
				'description'      => __( 'Desktop only. When enabled, the site uses a fixed frame with internal scrolling panels. Mobile always stacks and scrolls normally.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'global_framed_layout',
				'default_fallback' => false,
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_frame_options_section' );
