<?php
/**
 * Prismleaf Customizer: Search Styling
 *
 * Registers the Search Styling section.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_register_search_options_section' ) ) {
	/**
	 * Register the Search Styling section in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_search_options_section( $wp_customize ) {
		prismleaf_register_options_section(
			$wp_customize,
			array(
				'id'          => 'prismleaf_search_options',
				'title'       => __( 'Search Styling', 'prismleaf' ),
				'description' => __( 'Fine-tune how search results appear throughout the site.', 'prismleaf' ),
				'priority'    => 90,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_search_heading_layout',
				'label'      => __( 'Layout', 'prismleaf' ),
				'section'    => 'prismleaf_search_options',
				'priority'   => 1000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_show_author',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Show author', 'prismleaf' ),
				'description'      => __( 'Display the author name in the metadata row.', 'prismleaf' ),
				'priority'         => 1010,
				'default_key'      => 'result_show_author',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_show_date',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Show date', 'prismleaf' ),
				'description'      => __( 'Show the published date alongside the author metadata.', 'prismleaf' ),
				'priority'         => 1020,
				'default_key'      => 'result_show_date',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_show_featured_image',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Show featured image', 'prismleaf' ),
				'description'      => __( 'Toggle the thumbnail that appears at the top of each archive card.', 'prismleaf' ),
				'priority'         => 1030,
				'default_key'      => 'result_show_featured_image',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_show_categories',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Show categories', 'prismleaf' ),
				'description'      => __( 'Display the category list beneath the meta row.', 'prismleaf' ),
				'priority'         => 1040,
				'default_key'      => 'result_show_categories',
				'default_fallback' => true,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_show_comments',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Show comments', 'prismleaf' ),
				'description'      => __( 'Toggle the comments link inside the archive card footer.', 'prismleaf' ),
				'priority'         => 1050,
				'default_key'      => 'result_show_comments',
				'default_fallback' => true,
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_search_heading_card_style',
				'label'      => __( 'Card Style', 'prismleaf' ),
				'section'    => 'prismleaf_search_options',
				'priority'   => 2000,
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_result_background_color_source',
				'base_setting_id'          => 'prismleaf_result_background_color_base',
				'palette_setting_id'       => 'prismleaf_result_background_color_palette',
				'section'                  => 'prismleaf_search_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Override the archive card background color. Leave blank to use the default surface color.', 'prismleaf' ),
				'priority'                 => 2010,
				'source_default_key'       => 'result_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_border_corners',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Border corners', 'prismleaf' ),
				'description'      => __( 'Roundness of the archive cards.', 'prismleaf' ),
				'priority'         => 2020,
				'default_key'      => 'result_border_corners',
				'default_fallback' => 'Round',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_corners',
				'choices'          => array(
					'Square' => __( 'Square', 'prismleaf' ),
					'Round'  => __( 'Round', 'prismleaf' ),
				),
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_border_style',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Border style', 'prismleaf' ),
				'description'      => __( 'Sets the archive card border line style.', 'prismleaf' ),
				'priority'         => 2030,
				'default_key'      => 'result_border_style',
				'default_fallback' => 'solid',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_border_style',
				'choices'          => array(
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
				'source_setting_id'        => 'prismleaf_result_border_color_source',
				'base_setting_id'          => 'prismleaf_result_border_color_base',
				'palette_setting_id'       => 'prismleaf_result_border_color_palette',
				'section'                  => 'prismleaf_search_options',
				'label'                    => __( 'Border color', 'prismleaf' ),
				'description'              => __( 'Customize the border color surrounding each card.', 'prismleaf' ),
				'priority'                 => 2040,
				'source_default_key'       => 'result_border_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_border_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_border_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_elevation',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Elevation', 'prismleaf' ),
				'description'      => __( 'Sets the drop shadow for archive cards.', 'prismleaf' ),
				'priority'         => 2050,
				'default_key'      => 'result_elevation',
				'default_fallback' => 'elevation-2',
				'sanitize_callback'=> 'prismleaf_sanitize_frame_elevation',
				'choices'          => array(
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
				'setting_id' => 'prismleaf_search_heading_text_style',
				'label'      => __( 'Text Style', 'prismleaf' ),
				'section'    => 'prismleaf_search_options',
				'priority'   => 3000,
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_result_foreground_color_source',
				'base_setting_id'          => 'prismleaf_result_foreground_color_base',
				'palette_setting_id'       => 'prismleaf_result_foreground_color_palette',
				'section'                  => 'prismleaf_search_options',
				'label'                    => __( 'Foreground color', 'prismleaf' ),
				'description'              => __( 'Optional custom text color. If left blank and a background color is chosen, the background-on color will be used.', 'prismleaf' ),
				'priority'                 => 3010,
				'source_default_key'       => 'result_foreground_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_foreground_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_foreground_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_result_title_color_source',
				'base_setting_id'          => 'prismleaf_result_title_color_base',
				'palette_setting_id'       => 'prismleaf_result_title_color_palette',
				'section'                  => 'prismleaf_search_options',
				'label'                    => __( 'Title color', 'prismleaf' ),
				'description'              => __( 'Override the post title link color.', 'prismleaf' ),
				'priority'                 => 3020,
				'source_default_key'       => 'result_title_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_title_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_title_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_result_metadata_color_source',
				'base_setting_id'          => 'prismleaf_result_metadata_color_base',
				'palette_setting_id'       => 'prismleaf_result_metadata_color_palette',
				'section'                  => 'prismleaf_search_options',
				'label'                    => __( 'Metadata color', 'prismleaf' ),
				'description'              => __( 'Sets the color for author, date, and category text.', 'prismleaf' ),
				'priority'                 => 3030,
				'source_default_key'       => 'result_metadata_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_metadata_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_metadata_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_palette_source_control(
			$wp_customize,
			array(
				'source_setting_id'        => 'prismleaf_result_metadata_link_color_source',
				'base_setting_id'          => 'prismleaf_result_metadata_link_color_base',
				'palette_setting_id'       => 'prismleaf_result_metadata_link_color_palette',
				'section'                  => 'prismleaf_search_options',
				'label'                    => __( 'Metadata link color', 'prismleaf' ),
				'description'              => __( 'Customizer for links such as comments and continue reading.', 'prismleaf' ),
				'priority'                 => 3040,
				'source_default_key'       => 'result_metadata_link_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_metadata_link_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_metadata_link_color_palette',
				'palette_default_fallback' => '',
			)
		);

		prismleaf_add_section_header_control(
			$wp_customize,
			array(
				'setting_id' => 'prismleaf_search_heading_navigation_style',
				'label'      => __( 'Navigation Style', 'prismleaf' ),
				'section'    => 'prismleaf_search_options',
				'priority'   => 4000,
			)
		);

		prismleaf_add_checkbox_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_navigation_is_buttons',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Render buttons', 'prismleaf' ),
				'description'      => __( 'When enabled, pagination items are spaced apart rather than merged into a single bar.', 'prismleaf' ),
				'priority'         => 4010,
				'default_key'      => 'result_navigation_is_buttons',
				'default_fallback' => false,
			)
		);

		prismleaf_add_select_control(
			$wp_customize,
			array(
				'setting_id'       => 'prismleaf_result_navigation_shape',
				'section'          => 'prismleaf_search_options',
				'label'            => __( 'Button shape', 'prismleaf' ),
				'description'      => __( 'Sets the border radius used for the navigation container and buttons.', 'prismleaf' ),
				'priority'         => 4020,
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
				'section'                  => 'prismleaf_search_options',
				'label'                    => __( 'Background color', 'prismleaf' ),
				'description'              => __( 'Pick a custom background color for the archive pagination. Leave blank to rely on the default token.', 'prismleaf' ),
				'priority'                 => 4030,
				'source_default_key'       => 'result_navigation_background_color_source',
				'source_default_fallback'  => '',
				'base_default_key'         => 'result_navigation_background_color_base',
				'base_default_fallback'    => '',
				'palette_default_key'      => 'result_navigation_background_color_palette',
				'palette_default_fallback' => '',
			)
		);

	}
}

add_action( 'customize_register', 'prismleaf_register_search_options_section' );
