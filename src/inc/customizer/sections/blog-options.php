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

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_navigation_is_buttons',
				'section'          => 'prismleaf_blog_options',
				'label'            => __( 'Render buttons', 'prismleaf' ),
				'description'      => __( 'When enabled, pagination items are spaced apart rather than merged into a single bar.', 'prismleaf' ),
				'priority'         => 2010,
				'default_key'      => 'result_navigation_is_buttons',
				'default_fallback' => false,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_navigation_size',
				'section'          => 'prismleaf_blog_options',
				'label'            => __( 'Pagination size', 'prismleaf' ),
				'description'      => __( 'Controls how many numbered links appear in the archive pagination.', 'prismleaf' ),
				'priority'         => 2030,
				'default_key'      => 'result_navigation_size',
				'default_fallback' => 'Medium',
				'sanitize_callback'=> 'prismleaf_sanitize_pagination_size',
				'choices'          => array(
					'Small'  => __( 'Small', 'prismleaf' ),
					'Medium' => __( 'Medium', 'prismleaf' ),
					'Large'  => __( 'Large', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_navigation_shape',
				'section'          => 'prismleaf_blog_options',
				'label'            => __( 'Button shape', 'prismleaf' ),
				'description'      => __( 'Sets the border radius used for the navigation container and buttons.', 'prismleaf' ),
				'priority'         => 2040,
				'default_key'      => 'result_navigation_shape',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_pagination_shape',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
					'Pill'   => __( 'Pill', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_result_navigation_background_color_source',
				'base_setting_id'          => 'prismleaf_result_navigation_background_color_base',
				'palette_setting_id'       => 'prismleaf_result_navigation_background_color_palette',
				'section'                  => 'prismleaf_blog_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Pick a custom background color for the archive pagination. Leave blank to rely on the default token.', 'prismleaf' ),
				'priority'                 => 2050,
				'source_default_key'       => 'result_navigation_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_navigation_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_navigation_background_color_palette',
				'palette_default_fallback' => '',
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

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_entry_navigation_is_buttons',
				'section'          => 'prismleaf_blog_options',
				'label'            => __( 'Render buttons', 'prismleaf' ),
				'description'      => __( 'When enabled, the entry navigation buttons are spaced apart instead of sharing the same surface.', 'prismleaf' ),
				'priority'         => 4010,
				'default_key'      => 'entry_navigation_is_buttons',
				'default_fallback' => false,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_entry_navigation_show_page_numbers',
				'section'          => 'prismleaf_blog_options',
				'label'            => __( 'Show page count', 'prismleaf' ),
				'description'      => __( 'Display "Page x of y" between the Previous/Next links when navigating multi-page content.', 'prismleaf' ),
				'priority'         => 4030,
				'default_key'      => 'entry_navigation_show_page_numbers',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_entry_navigation_show_post_titles',
				'section'          => 'prismleaf_blog_options',
				'label'            => __( 'Show post titles', 'prismleaf' ),
				'description'      => __( 'When disabled, previous/next post links only show arrows with screen-reader text.', 'prismleaf' ),
				'priority'         => 4040,
				'default_key'      => 'entry_navigation_show_post_titles',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_entry_navigation_shape',
				'section'          => 'prismleaf_blog_options',
				'label'            => __( 'Button shape', 'prismleaf' ),
				'description'      => __( 'Sets the border radius applied when borders or buttons are used in entry navigation.', 'prismleaf' ),
				'priority'         => 4050,
				'default_key'      => 'entry_navigation_shape',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_pagination_shape',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
					'Pill'   => __( 'Pill', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_entry_navigation_background_color_source',
				'base_setting_id'          => 'prismleaf_entry_navigation_background_color_base',
				'palette_setting_id'       => 'prismleaf_entry_navigation_background_color_palette',
				'section'                  => 'prismleaf_blog_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Override the background color used by entry navigation controls.', 'prismleaf' ),
				'priority'                 => 4070,
				'source_default_key'       => 'entry_navigation_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'entry_navigation_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'entry_navigation_background_color_palette',
				'palette_default_fallback' => '',
			)
		);
	}
}

add_action( 'customize_register', 'prismleaf_register_blog_options_section' );
