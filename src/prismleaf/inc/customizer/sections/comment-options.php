<?php
/**
 * Prismleaf Customizer: Comment Styling
 *
 * Registers the Comment Styling section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_comment_options_section' ) ) {
	/**
	 * Register the Comment Styling section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_comment_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_comment_options',
				'title'       => __( 'Comment Styling', 'prismleaf' ),
				'description' => __( 'Configure comment visibility and styling.', 'prismleaf' ),
				'priority'    => 100,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_comment_heading_style',
				'label'      => __( 'Styling', 'prismleaf' ),
				'section'    => 'prismleaf_comment_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_content_show_comments_on_pages',
				'section'          => 'prismleaf_comment_options',
				'label'            => __( 'Show comments on pages', 'prismleaf' ),
				'description'      => __( 'Display the comments area on static pages.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'content_show_comments_on_pages',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_content_show_comments_on_posts',
				'section'          => 'prismleaf_comment_options',
				'label'            => __( 'Show comments on posts', 'prismleaf' ),
				'description'      => __( 'Display the comments area on single posts.', 'prismleaf' ),
				'priority'         => 1020,
				'default_key'      => 'content_show_comments_on_posts',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_comment_avatar_shape',
				'section'           => 'prismleaf_comment_options',
				'label'             => __( 'Avatar shape', 'prismleaf' ),
				'description'       => __( 'Control how comment avatars are rounded.', 'prismleaf' ),
				'priority'          => 1030,
				'default_key'       => 'comment_avatar_shape',
				'default_fallback'  => 'Circle',
				'sanitize_callback' => 'prismleaf_sanitize_comment_avatar_shape',
				'choices'           => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
					'Circle' => __( 'Circle', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_comment_title_color_source',
				'base_setting_id'          => 'prismleaf_comment_title_color_base',
				'palette_setting_id'       => 'prismleaf_comment_title_color_palette',
				'section'                  => 'prismleaf_comment_options',
				'label'                    => __( 'Title color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1040,
				'source_default_key'       => 'comment_title_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'comment_title_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'comment_title_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_comment_author_color_source',
				'base_setting_id'          => 'prismleaf_comment_author_color_base',
				'palette_setting_id'       => 'prismleaf_comment_author_color_palette',
				'section'                  => 'prismleaf_comment_options',
				'label'                    => __( 'Author color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1050,
				'source_default_key'       => 'comment_author_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'comment_author_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'comment_author_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_comment_meta_color_source',
				'base_setting_id'          => 'prismleaf_comment_meta_color_base',
				'palette_setting_id'       => 'prismleaf_comment_meta_color_palette',
				'section'                  => 'prismleaf_comment_options',
				'label'                    => __( 'Meta color', 'prismleaf' ),
				'description'              => __( 'Optional. Applies normal/hover states to comment meta text.', 'prismleaf' ),
				'priority'                 => 1060,
				'source_default_key'       => 'comment_meta_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'comment_meta_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'comment_meta_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_comment_link_color_source',
				'base_setting_id'          => 'prismleaf_comment_link_color_base',
				'palette_setting_id'       => 'prismleaf_comment_link_color_palette',
				'section'                  => 'prismleaf_comment_options',
				'label'                    => __( 'Link color', 'prismleaf' ),
				'description'              => __( 'Optional. Applies normal/hover states to comment links.', 'prismleaf' ),
				'priority'                 => 1070,
				'source_default_key'       => 'comment_link_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'comment_link_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'comment_link_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_comment_button_color_source',
				'base_setting_id'          => 'prismleaf_comment_button_color_base',
				'palette_setting_id'       => 'prismleaf_comment_button_color_palette',
				'section'                  => 'prismleaf_comment_options',
				'label'                    => __( 'Button color', 'prismleaf' ),
				'description'              => __( 'Optional. Applies normal/hover states to the submit button.', 'prismleaf' ),
				'priority'                 => 1080,
				'source_default_key'       => 'comment_button_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'comment_button_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'comment_button_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_comment_button_shape',
				'section'           => 'prismleaf_comment_options',
				'label'             => __( 'Button shape', 'prismleaf' ),
				'description'       => __( 'Control the corner style used for comment action buttons.', 'prismleaf' ),
				'priority'          => 1090,
				'default_key'       => 'comment_button_shape',
				'default_fallback'  => 'Round',
				'sanitize_callback' => 'prismleaf_sanitize_comment_button_shape',
				'choices'           => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
					'Pill'   => __( 'Pill', 'prismleaf' ),
				),
			)
		);
	}
}
add_action( 'customize_register', 'prismleaf_register_comment_options_section' );
