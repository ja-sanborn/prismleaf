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
					'title'       => __( 'Content Styling', 'prismleaf' ),
					'description' => __( 'Configure the content layout and styling.', 'prismleaf' ),
					'panel'       => 'prismleaf_theme_options',
					'priority'    => 60,
				)
			);
		}

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_content_heading_style',
				'label'      => __( 'Style', 'prismleaf' ),
				'section'    => 'prismleaf_content_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_content_background_color_source',
				'base_setting_id'          => 'prismleaf_content_background_color_base',
				'palette_setting_id'       => 'prismleaf_content_background_color_palette',
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1010,
				'source_default_key'       => 'content_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'content_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'content_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_content_title_color_source',
				'base_setting_id'          => 'prismleaf_content_title_color_base',
				'palette_setting_id'       => 'prismleaf_content_title_color_palette',
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Title color', 'prismleaf' ),
				'description'              => __( 'Optional. Override the title color; leave blank to use the stylesheet default.', 'prismleaf' ),
				'priority'                 => 1020,
				'source_default_key'       => 'content_title_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'content_title_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'content_title_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_content_border_corners',
				'section'           => 'prismleaf_content_options',
				'label'             => __( 'Border corners', 'prismleaf' ),
				'description'       => __( 'Controls the roundness of the content corners.', 'prismleaf' ),
				'priority'          => 1040,
				'default_key'       => 'content_border_corners',
				'default_fallback'  => 'Round',
				'sanitize_callback' => 'prismleaf_sanitize_frame_border_corners',
				'choices'           => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_content_border_style',
				'section'           => 'prismleaf_content_options',
				'label'             => __( 'Border style', 'prismleaf' ),
				'description'       => __( 'Sets the content border line style.', 'prismleaf' ),
				'priority'          => 1050,
				'default_key'       => 'content_border_style',
				'default_fallback'  => 'solid',
				'sanitize_callback' => 'prismleaf_sanitize_frame_border_style',
				'choices'           => array(
					'none'   => __( 'None', 'prismleaf' ),
					'solid'  => __( 'Solid', 'prismleaf' ),
					'dotted' => __( 'Dotted', 'prismleaf' ),
					'dashed' => __( 'Dashed', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_content_border_color_source',
				'base_setting_id'          => 'prismleaf_content_border_color_base',
				'palette_setting_id'       => 'prismleaf_content_border_color_palette',
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Optional. Leave blank to use the theme default.', 'prismleaf' ),
				'priority'                 => 1060,
				'source_default_key'       => 'content_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'content_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'content_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_content_elevation',
				'section'           => 'prismleaf_content_options',
				'label'             => __( 'Elevation', 'prismleaf' ),
				'description'       => __( 'Sets the elevation level for the content area.', 'prismleaf' ),
				'priority'          => 1070,
				'default_key'       => 'content_elevation',
				'default_fallback'  => 'elevation-2',
				'sanitize_callback' => 'prismleaf_sanitize_frame_elevation',
				'choices'           => array(
					'none'        => __( 'None', 'prismleaf' ),
					'elevation-1' => __( 'Elevation 1', 'prismleaf' ),
					'elevation-2' => __( 'Elevation 2', 'prismleaf' ),
					'elevation-3' => __( 'Elevation 3', 'prismleaf' ),
					'elevation-4' => __( 'Elevation 4', 'prismleaf' ),
					'elevation-5' => __( 'Elevation 5', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_content_heading_featured_image',
				'label'      => __( 'Featured Image', 'prismleaf' ),
				'section'    => 'prismleaf_content_options',
				'priority'   => 2000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_content_show_featured_image',
				'section'          => 'prismleaf_content_options',
				'label'            => __( 'Show featured image', 'prismleaf' ),
				'description'      => __( 'Toggle whether the featured image appears before the entry content.', 'prismleaf' ),
				'priority'         => 2010,
				'default_key'      => 'content_show_featured_image',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_content_featured_image_corners',
				'section'           => 'prismleaf_content_options',
				'label'             => __( 'Featured image corners', 'prismleaf' ),
				'description'       => __( 'Control how the featured image corners are rounded.', 'prismleaf' ),
				'priority'          => 2020,
				'default_key'       => 'content_featured_image_corners',
				'default_fallback'  => 'Round',
				'sanitize_callback' => 'prismleaf_sanitize_frame_border_corners',
				'choices'           => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_content_heading_metadata_style',
				'label'      => __( 'Metadata', 'prismleaf' ),
				'section'    => 'prismleaf_content_options',
				'priority'   => 3000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_content_show_metadata',
				'section'          => 'prismleaf_content_options',
				'label'            => __( 'Show metadata', 'prismleaf' ),
				'description'      => __( 'Toggle the visibility of the metadata row (author, categories, tags).', 'prismleaf' ),
				'priority'         => 3010,
				'default_key'      => 'content_show_metadata',
				'default_fallback' => true,
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_content_metadata_text_color_source',
				'base_setting_id'          => 'prismleaf_content_metadata_text_color_base',
				'palette_setting_id'       => 'prismleaf_content_metadata_text_color_palette',
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Text color', 'prismleaf' ),
				'description'              => __( 'Optional. Override the metadata text color; leave blank to keep the stylesheet default.', 'prismleaf' ),
				'priority'                 => 3020,
				'source_default_key'       => 'content_metadata_text_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'content_metadata_text_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'content_metadata_text_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_content_metadata_link_color_source',
				'base_setting_id'          => 'prismleaf_content_metadata_link_color_base',
				'palette_setting_id'       => 'prismleaf_content_metadata_link_color_palette',
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Link color', 'prismleaf' ),
				'description'              => __( 'Optional. Override the metadata link color.', 'prismleaf' ),
				'priority'                 => 3030,
				'source_default_key'       => 'content_metadata_link_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'content_metadata_link_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'content_metadata_link_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_content_heading_author_style',
				'label'      => __( 'Author', 'prismleaf' ),
				'section'    => 'prismleaf_content_options',
				'priority'   => 4000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_content_show_author',
				'section'          => 'prismleaf_content_options',
				'label'            => __( 'Show author', 'prismleaf' ),
				'description'      => __( 'Toggle the author bio block beneath single entries.', 'prismleaf' ),
				'priority'         => 4010,
				'default_key'      => 'content_show_author',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_author_avatar_shape',
				'section'           => 'prismleaf_content_options',
				'label'             => __( 'Avatar shape', 'prismleaf' ),
				'description'       => __( 'Control how author avatars are rounded.', 'prismleaf' ),
				'priority'          => 4020,
				'default_key'       => 'author_avatar_shape',
				'default_fallback'  => 'Circle',
				'sanitize_callback' => 'prismleaf_sanitize_author_avatar_shape',
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
				'source_setting_id'        => 'prismleaf_author_name_color_source',
				'base_setting_id'          => 'prismleaf_author_name_color_base',
				'palette_setting_id'       => 'prismleaf_author_name_color_palette',
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Name color', 'prismleaf' ),
				'description'              => __( 'Optional. Override the author name color used in author bios.', 'prismleaf' ),
				'priority'                 => 4030,
				'source_default_key'       => 'author_name_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'author_name_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'author_name_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_author_text_color_source',
				'base_setting_id'          => 'prismleaf_author_text_color_base',
				'palette_setting_id'       => 'prismleaf_author_text_color_palette',
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Text color', 'prismleaf' ),
				'description'              => __( 'Optional. Override the author bio text color.', 'prismleaf' ),
				'priority'                 => 4040,
				'source_default_key'       => 'author_text_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'author_text_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'author_text_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_content_heading_navigation',
				'label'      => __( 'Navigation', 'prismleaf' ),
				'section'    => 'prismleaf_content_options',
				'priority'   => 5000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_entry_navigation_is_buttons',
				'section'          => 'prismleaf_content_options',
				'label'            => __( 'Render buttons', 'prismleaf' ),
				'description'      => __( 'When enabled, the entry navigation buttons are spaced apart instead of sharing the same surface.', 'prismleaf' ),
				'priority'         => 5010,
				'default_key'      => 'entry_navigation_is_buttons',
				'default_fallback' => false,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_entry_navigation_show_page_numbers',
				'section'          => 'prismleaf_content_options',
				'label'            => __( 'Show page count', 'prismleaf' ),
				'description'      => __( 'Display "Page x of y" between the Previous/Next links when navigating multi-page content.', 'prismleaf' ),
				'priority'         => 5020,
				'default_key'      => 'entry_navigation_show_page_numbers',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_entry_navigation_show_post_titles',
				'section'          => 'prismleaf_content_options',
				'label'            => __( 'Show post titles', 'prismleaf' ),
				'description'      => __( 'When disabled, previous/next post links only show arrows with screen-reader text.', 'prismleaf' ),
				'priority'         => 5030,
				'default_key'      => 'entry_navigation_show_post_titles',
				'default_fallback' => true,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'        => 'prismleaf_entry_navigation_shape',
				'section'           => 'prismleaf_content_options',
				'label'             => __( 'Button shape', 'prismleaf' ),
				'description'       => __( 'Sets the border radius applied when borders or buttons are used in entry navigation.', 'prismleaf' ),
				'priority'          => 5040,
				'default_key'       => 'entry_navigation_shape',
				'default_fallback'  => 'Round',
				'sanitize_callback' => 'prismleaf_sanitize_pagination_shape',
				'choices'           => array(
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
				'section'                  => 'prismleaf_content_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Override the background color used by entry navigation controls.', 'prismleaf' ),
				'priority'                 => 5050,
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
add_action( 'customize_register', 'prismleaf_register_content_options_section' );
