<?php
/**
 * Theme defaults.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_get_default_options' ) ) {
	/**
	 * Return default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,mixed>
	 */
	function prismleaf_get_default_options() {
		$defaults = array(
			'author_avatar_shape'                        => 'Circle',
			'author_name_color_base'                     => '',
			'author_name_color_palette'                  => '',
			'author_name_color_source'                   => '',
			'author_text_color_base'                     => '',
			'author_text_color_palette'                  => '',
			'author_text_color_source'                   => '',

			'comment_avatar_shape'                       => 'Circle',
			'comment_author_color_base'                  => '',
			'comment_author_color_palette'               => '',
			'comment_author_color_source'                => '',
			'comment_button_color_base'                  => '',
			'comment_button_color_palette'               => '',
			'comment_button_color_source'                => '',
			'comment_button_shape'                       => 'Round',
			'comment_link_color_base'                    => '',
			'comment_link_color_palette'                 => '',
			'comment_link_color_source'                  => '',
			'comment_meta_color_base'                    => '',
			'comment_meta_color_palette'                 => '',
			'comment_meta_color_source'                  => '',
			'comment_title_color_base'                   => '',
			'comment_title_color_palette'                => '',
			'comment_title_color_source'                 => '',

			'content_background_color_base'              => '',
			'content_background_color_palette'           => '',
			'content_background_color_source'            => '',
			'content_border_color_base'                  => '',
			'content_border_color_palette'               => '',
			'content_border_color_source'                => '',
			'content_border_corners'                     => 'Round',
			'content_border_style'                       => 'solid',
			'content_elevation'                          => 'elevation-2',
			'content_featured_image_corners'             => 'Round',
			'content_metadata_link_color_base'           => '',
			'content_metadata_link_color_palette'        => '',
			'content_metadata_link_color_source'         => '',
			'content_metadata_text_color_base'           => '',
			'content_metadata_text_color_palette'        => '',
			'content_metadata_text_color_source'         => '',
			'content_show_author'                        => true,
			'content_show_comments_on_pages'             => true,
			'content_show_comments_on_posts'             => true,
			'content_show_featured_image'                => true,
			'content_show_metadata'                      => true,
			'content_title_color_base'                   => '',
			'content_title_color_palette'                => '',
			'content_title_color_source'                 => '',

			'entry_navigation_background_color_base'     => '',
			'entry_navigation_background_color_palette'  => '',
			'entry_navigation_background_color_source'   => '',
			'entry_navigation_is_buttons'                => false,
			'entry_navigation_shape'                     => 'Round',
			'entry_navigation_show_page_numbers'         => true,
			'entry_navigation_show_post_titles'          => true,

			'footer_background_color_base'               => '',
			'footer_background_color_palette'            => '',
			'footer_background_color_source'             => '',
			'footer_background_image'                    => '',
			'footer_background_image_attachment'         => 'scroll',
			'footer_background_image_position_x'         => 'center',
			'footer_background_image_position_y'         => 'center',
			'footer_background_image_preset'             => 'default',
			'footer_background_image_repeat'             => 'repeat',
			'footer_background_image_size'               => 'auto',
			'footer_border_color_base'                   => '',
			'footer_border_color_palette'                => '',
			'footer_border_color_source'                 => '',
			'footer_border_corners'                      => 'Round',
			'footer_border_style'                        => 'solid',
			'footer_contained'                           => true,
			'footer_copyright_color_base'                => '',
			'footer_copyright_color_palette'             => '',
			'footer_copyright_color_source'              => '',
			'footer_copyright_text'                      => '',
			'footer_elevation'                           => 'elevation-2',
			'footer_floating'                            => true,
			'footer_height'                              => '',
			'footer_show'                                => true,
			'footer_hide_widgets_on_front'               => false,
			'footer_hide_widgets_on_other'               => false,
			'footer_widget_alignment'                    => 'center',
			'footer_widget_color_base'                   => '',
			'footer_widget_color_palette'                => '',
			'footer_widget_color_source'                 => '',

			'frame_background_color_base'                => '',
			'frame_background_color_palette'             => '',
			'frame_background_color_source'              => '',
			'frame_border_color_base'                    => '',
			'frame_border_color_palette'                 => '',
			'frame_border_color_source'                  => '',
			'frame_border_corners'                       => 'Round',
			'frame_border_style'                         => 'solid',
			'frame_elevation'                            => 'elevation-1',
			'frame_max_width'                            => '1480',
			'frame_show_background'                      => true,

			'global_framed_layout'                       => false,

			'header_background_color_base'               => '',
			'header_background_color_palette'            => '',
			'header_background_color_source'             => '',
			'header_background_image'                    => '',
			'header_background_image_attachment'         => 'scroll',
			'header_background_image_position_x'         => 'center',
			'header_background_image_position_y'         => 'center',
			'header_background_image_preset'             => 'default',
			'header_background_image_repeat'             => 'repeat',
			'header_background_image_size'               => 'auto',
			'header_border_color_base'                   => '',
			'header_border_color_palette'                => '',
			'header_border_color_source'                 => '',
			'header_border_corners'                      => 'Round',
			'header_border_style'                        => 'solid',
			'header_contained'                           => true,
			'header_elevation'                           => 'elevation-2',
			'header_floating'                            => true,
			'header_height'                              => '',
			'header_icon_position'                       => 'left',
			'header_icon_shape'                          => 'circle',
			'header_icon_size'                           => 'medium',
			'header_show'                                => true,
			'header_show_tagline'                        => true,
			'header_tagline_color'                       => '',
			'header_tagline_position'                    => 'inline',
			'header_title_color'                         => '',
			'header_title_position'                      => 'left',

			'home_title'                                 => 'Welcome',
			'home_description'                           => '',
			'home_show_page_title'                       => true,
			'home_show_latest_posts'                     => true,
			'home_show_widget_area'                      => false,

			'mobile_menu_background_color_base'          => '',
			'mobile_menu_background_color_palette'       => '',
			'mobile_menu_background_color_source'        => 'default',
			'mobile_menu_button_corners'                 => 'Round',
			'mobile_menu_divider'                        => true,
			'mobile_menu_foreground_color_base'          => '',
			'mobile_menu_foreground_color_palette'       => '',
			'mobile_menu_foreground_color_source'        => 'default',
			'mobile_menu_panel'                          => true,

			'palette_error_base'                         => '',
			'palette_info_base'                          => '',
			'palette_neutral_dark_base'                  => '',
			'palette_neutral_light_base'                 => '',
			'palette_primary_base'                       => '',
			'palette_secondary_base'                     => '',
			'palette_show_theme_switch'                  => true,
			'palette_tertiary_base'                      => '',
			'palette_theme_mode'                         => 'system',
			'palette_warning_base'                       => '',

			'primary_menu_background_color_base'         => '',
			'primary_menu_background_color_palette'      => '',
			'primary_menu_background_color_source'       => 'default',
			'primary_menu_button_corners'                => 'Round',
			'primary_menu_divider'                       => true,
			'primary_menu_foreground_color_base'         => '',
			'primary_menu_foreground_color_palette'      => '',
			'primary_menu_foreground_color_source'       => 'default',
			'primary_menu_strip'                         => true,

			'result_background_color_base'               => '',
			'result_background_color_palette'            => '',
			'result_background_color_source'             => '',
			'result_border_color_base'                   => '',
			'result_border_color_palette'                => '',
			'result_border_color_source'                 => '',
			'result_border_corners'                      => 'Round',
			'result_border_style'                        => 'solid',
			'result_elevation'                           => 'elevation-2',
			'result_foreground_color_base'               => '',
			'result_foreground_color_palette'            => '',
			'result_foreground_color_source'             => '',
			'result_metadata_color_base'                 => '',
			'result_metadata_color_palette'              => '',
			'result_metadata_color_source'               => '',
			'result_metadata_link_color_base'            => '',
			'result_metadata_link_color_palette'         => '',
			'result_metadata_link_color_source'          => '',
			'result_navigation_background_color_base'    => '',
			'result_navigation_background_color_palette' => '',
			'result_navigation_background_color_source'  => '',
			'result_navigation_is_buttons'               => false,
			'result_navigation_shape'                    => 'Round',
			'result_layout_archive'                      => '',
			'result_layout_default'                      => 'grid',
			'result_layout_home'                         => '',
			'result_layout_search'                       => '',
			'result_show_author'                         => true,
			'result_show_categories'                     => true,
			'result_show_comments'                       => true,
			'result_show_date'                           => true,
			'result_show_featured_image'                 => true,
			'result_title_color_base'                    => '',
			'result_title_color_palette'                 => '',
			'result_title_color_source'                  => '',

			'secondary_menu_background_color_base'       => '',
			'secondary_menu_background_color_palette'    => '',
			'secondary_menu_background_color_source'     => 'default',
			'secondary_menu_button_corners'              => 'Round',
			'secondary_menu_divider'                     => true,
			'secondary_menu_foreground_color_base'       => '',
			'secondary_menu_foreground_color_palette'    => '',
			'secondary_menu_foreground_color_source'     => 'default',
			'secondary_menu_strip'                       => true,

			'sidebar_hide_on_front'                      => false,
			'sidebar_hide_on_other'                      => false,
			'sidebar_primary_background_color_base'      => '',
			'sidebar_primary_background_color_palette'   => '',
			'sidebar_primary_background_color_source'    => '',
			'sidebar_primary_border_color_base'          => '',
			'sidebar_primary_border_color_palette'       => '',
			'sidebar_primary_border_color_source'        => '',
			'sidebar_primary_border_corners'             => 'Round',
			'sidebar_primary_border_style'               => 'solid',
			'sidebar_primary_contained'                  => true,
			'sidebar_primary_elevation'                  => 'elevation-2',
			'sidebar_primary_floating'                   => true,
			'sidebar_primary_show'                       => true,
			'sidebar_primary_width'                      => '260',
			'sidebar_secondary_background_color_base'    => '',
			'sidebar_secondary_background_color_palette' => '',
			'sidebar_secondary_background_color_source'  => '',
			'sidebar_secondary_border_color_base'        => '',
			'sidebar_secondary_border_color_palette'     => '',
			'sidebar_secondary_border_color_source'      => '',
			'sidebar_secondary_border_corners'           => 'Round',
			'sidebar_secondary_border_style'             => 'solid',
			'sidebar_secondary_contained'                => true,
			'sidebar_secondary_elevation'                => 'elevation-2',
			'sidebar_secondary_floating'                 => true,
			'sidebar_secondary_show'                     => true,
			'sidebar_secondary_width'                    => '200',
			'sidebar_swap'                               => false,

			'widget_background_color_base'               => '',
			'widget_background_color_palette'            => '',
			'widget_background_color_source'             => '',
			'widget_border_color_base'                   => '',
			'widget_border_color_palette'                => '',
			'widget_border_color_source'                 => '',
			'widget_border_corners'                      => 'Round',
			'widget_border_style'                        => 'solid',
			'widget_elevation'                           => 'elevation-3',
			'widget_title_alignment'                     => 'left',
			'widget_title_color_base'                    => '',
			'widget_title_color_palette'                 => '',
			'widget_title_color_source'                  => '',
		);

		return apply_filters( 'prismleaf_default_options', $defaults );
	}
}

if ( ! function_exists( 'prismleaf_get_default_option' ) ) {
	/**
	 * Get a default option by key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @param mixed  $fallback Fallback value if missing.
	 * @return mixed
	 */
	function prismleaf_get_default_option( $key, $fallback = null ) {
		$defaults = prismleaf_get_default_options();

		if ( array_key_exists( $key, $defaults ) ) {
			return $defaults[ $key ];
		}

		return $fallback;
	}
}
