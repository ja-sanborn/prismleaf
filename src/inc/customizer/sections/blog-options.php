<?php
/**
 * Prismleaf Customizer: Blog Styling
 *
 * Registers the Blog Styling section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_blog_options_section' ) ) {
	/**
	 * Register the Blog Styling section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_blog_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_blog_options',
				'title'       => __( 'Blog Styling', 'prismleaf' ),
				'description' => __( 'Fine-tune how results and entries appear throughout the blog layouts.', 'prismleaf' ),
				'priority'    => 90,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_blog_heading_result_options',
				'label'      => __( 'Result Options', 'prismleaf' ),
				'section'    => 'prismleaf_blog_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_blog_heading_result_navigation',
				'label'      => __( 'Result Navigation', 'prismleaf' ),
				'section'    => 'prismleaf_blog_options',
				'priority'   => 2000,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_blog_heading_entry_options',
				'label'      => __( 'Entry Options', 'prismleaf' ),
				'section'    => 'prismleaf_blog_options',
				'priority'   => 3000,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_blog_heading_entry_navigation',
				'label'      => __( 'Entry Navigation', 'prismleaf' ),
				'section'    => 'prismleaf_blog_options',
				'priority'   => 4000,
			)
		);
	}
}

add_action( 'customize_register', 'prismleaf_register_blog_options_section' );
