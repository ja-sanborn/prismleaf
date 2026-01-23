<?php
/**
 * Customizer style overrides.
 *
 * Outputs Customizer-driven CSS variable overrides.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_output_customizer_styles' ) ) {
	/**
	 * Output Customizer-driven CSS variable overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_output_customizer_styles( $handle = 'prismleaf-style' ) {
		$css = '';
		$css .= prismleaf_get_palette_css_vars();
		$css .= prismleaf_get_framed_css_vars();
		$css .= prismleaf_get_header_css_vars();
		$css .= prismleaf_get_header_icon_css_vars();
		$css .= prismleaf_get_footer_css_vars();
		$css .= prismleaf_get_sidebar_css_vars();
		$css .= prismleaf_get_content_css_vars();
		$css .= prismleaf_get_widget_css_vars();

		if ( '' === $css ) {
			return;
		}

		$css = '@media(min-width: 768px){:root{' . $css . '}}';
		wp_add_inline_style( $handle, $css );
	}
}

if ( ! function_exists( 'prismleaf_build_css_var' ) ) {
	/**
	 * Build a CSS variable declaration from a name and value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name  CSS variable name.
	 * @param string $value CSS variable value.
	 * @return string
	 */
	function prismleaf_build_css_var( $name, $value ) {
		$name  = strtolower( prismleaf_sanitize_text( $name ) );
		$value = prismleaf_sanitize_css_value( $value );

		if ( '' === $name || '' === $value ) {
			return '';
		}

		if ( 0 === strpos( $value, '--' ) ) {
			$value = 'var(' . $value . ')';
		}

		return $name . ':' . $value . ';';
	}
}

if ( ! function_exists( 'prismleaf_build_css_vars_from_map' ) ) {
	/**
	 * Build CSS variable declarations from a map.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,string> $vars Map of CSS variable => value.
	 * @return string
	 */
	function prismleaf_build_css_vars_from_map( $vars ) {
		if ( ! is_array( $vars ) ) {
			return '';
		}

		$css = '';
		foreach ( $vars as $name => $value ) {
			$css .= prismleaf_build_css_var( $name, $value );
		}

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_build_css_vars_from_key_map' ) ) {
	/**
	 * Build CSS variables from a value map and a CSS-var-to-key map.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,string> $values Value map.
	 * @param array<string,string> $key_map CSS variable => value key map.
	 * @return string
	 */
	function prismleaf_build_css_vars_from_key_map( $values, $key_map ) {
		if ( ! is_array( $values ) || ! is_array( $key_map ) ) {
			return '';
		}

		$vars = array();
		foreach ( $key_map as $css_var => $key ) {
			if ( ! array_key_exists( $key, $values ) ) {
				return '';
			}
			$vars[ $css_var ] = $values[ $key ];
		}

		return prismleaf_build_css_vars_from_map( $vars );
	}
}

if ( ! function_exists( 'prismleaf_get_customizer_css_mappings' ) ) {
	/**
	 * Get CSS mapping definitions for customizer overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return array<int,array<string,mixed>>
	 */
	function prismleaf_get_customizer_css_mappings() {
		return array(
			array(
				'getter'   => 'prismleaf_get_theme_mod_palette_json',
				'setting'  => null,
				'keys'     => prismleaf_get_palette_keys(),
				'key_map'  => array(),
				'build'    => 'prismleaf_build_palette_css_vars',
				'type'     => 'palette',
			),
		);
	}
}

if ( ! function_exists( 'prismleaf_build_palette_css_key_map' ) ) {
	/**
	 * Build a palette CSS key map for a palette slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $palette_slug Palette slug.
	 * @return array<string,string>
	 */
	function prismleaf_build_palette_css_key_map( $palette_slug ) {
		$slug = prismleaf_sanitize_text( $palette_slug );
		if ( '' === $slug ) {
			return array();
		}

		return array(
			"--prismleaf-color-{$slug}-surface-on"           => 'surface_on',
			"--prismleaf-color-{$slug}-surface-on-muted"     => 'surface_on_muted',
			"--prismleaf-color-{$slug}-surface-on-faded"     => 'surface_on_faded',
			"--prismleaf-color-{$slug}-surface-1"            => 'surface_1',
			"--prismleaf-color-{$slug}-surface-2"            => 'surface_2',
			"--prismleaf-color-{$slug}-surface-3"            => 'surface_3',
			"--prismleaf-color-{$slug}-surface-4"            => 'surface_4',
			"--prismleaf-color-{$slug}-surface-5"            => 'surface_5',
			"--prismleaf-color-{$slug}-outline"              => 'outline',
			"--prismleaf-color-{$slug}-outline-variant"      => 'outline_variant',
			"--prismleaf-color-{$slug}-muted"                => 'muted',
			"--prismleaf-color-{$slug}-disabled-foreground"  => 'disabled_foreground',
			"--prismleaf-color-{$slug}-disabled-surface"     => 'disabled_surface',
			"--prismleaf-color-{$slug}-container-on"         => 'container_on',
			"--prismleaf-color-{$slug}-container-on-muted"   => 'container_on_muted',
			"--prismleaf-color-{$slug}-container-on-faded"   => 'container_on_faded',
			"--prismleaf-color-{$slug}-container-1"          => 'container_1',
			"--prismleaf-color-{$slug}-container-2"          => 'container_2',
			"--prismleaf-color-{$slug}-container-3"          => 'container_3',
			"--prismleaf-color-{$slug}-container-4"          => 'container_4',
			"--prismleaf-color-{$slug}-container-5"          => 'container_5',
		);
	}
}

if ( ! function_exists( 'prismleaf_build_palette_css_vars' ) ) {
	/**
	 * Build CSS variables for a specific palette override.
	 *
	 * @since 1.0.0
	 *
	 * @param string               $palette_slug   Palette slug (primary, secondary, etc).
	 * @param array<string,string> $palette_values Palette value map.
	 * @return string
	 */
	function prismleaf_build_palette_css_vars( $palette_slug, $palette_values ) {
		if ( ! is_string( $palette_slug ) || '' === $palette_slug || ! is_array( $palette_values ) ) {
			return '';
		}

		$expected_keys = prismleaf_get_palette_keys();
		if ( empty( $expected_keys ) ) {
			return '';
		}

		foreach ( $expected_keys as $key ) {
			if ( ! array_key_exists( $key, $palette_values ) || ! is_string( $palette_values[ $key ] ) || '' === $palette_values[ $key ] ) {
				return '';
			}
		}

		$slug = prismleaf_sanitize_text( $palette_slug );
		if ( '' === $slug ) {
			return '';
		}

		$key_map = prismleaf_build_palette_css_key_map( $slug );
		if ( empty( $key_map ) ) {
			return '';
		}

		return prismleaf_build_css_vars_from_key_map( $palette_values, $key_map );
	}
}

if ( ! function_exists( 'prismleaf_get_palette_css_vars' ) ) {
	/**
	 * Build CSS variables for all palette overrides, including neutral schemes.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_palette_css_vars() {
		$palette_map = array(
			'primary' => array(
				'base_setting'   => 'prismleaf_palette_primary_base',
				'values_setting' => 'prismleaf_palette_primary_values',
			),
			'secondary' => array(
				'base_setting'   => 'prismleaf_palette_secondary_base',
				'values_setting' => 'prismleaf_palette_secondary_values',
			),
			'tertiary' => array(
				'base_setting'   => 'prismleaf_palette_tertiary_base',
				'values_setting' => 'prismleaf_palette_tertiary_values',
			),
			'error' => array(
				'base_setting'   => 'prismleaf_palette_error_base',
				'values_setting' => 'prismleaf_palette_error_values',
			),
			'warning' => array(
				'base_setting'   => 'prismleaf_palette_warning_base',
				'values_setting' => 'prismleaf_palette_warning_values',
			),
			'info' => array(
				'base_setting'   => 'prismleaf_palette_info_base',
				'values_setting' => 'prismleaf_palette_info_values',
			),
			'light' => array(
				'base_setting'   => 'prismleaf_palette_neutral_light_base',
				'values_setting' => 'prismleaf_palette_neutral_light_values',
			),
			'dark' => array(
				'base_setting'   => 'prismleaf_palette_neutral_dark_base',
				'values_setting' => 'prismleaf_palette_neutral_dark_values',
			),
		);

		$css = '';
		$mappings = prismleaf_get_customizer_css_mappings();

		foreach ( $palette_map as $slug => $settings ) {
			$base_value = prismleaf_get_theme_mod_string( $settings['base_setting'], '' );
			if ( '' === $base_value ) {
				continue;
			}

			foreach ( $mappings as $mapping ) {
				if ( 'palette' !== $mapping['type'] ) {
					continue;
				}

				$getter = $mapping['getter'];
				$keys   = $mapping['keys'];

				if ( ! is_string( $getter ) || ! function_exists( $getter ) || empty( $keys ) ) {
					continue;
				}

				$palette_values = prismleaf_decode_json_with_keys(
					call_user_func( $getter, $settings['values_setting'], '' ),
					$keys
				);

				if ( null === $palette_values ) {
					continue;
				}

				$css .= prismleaf_build_palette_css_vars( $slug, $palette_values );
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_framed_css_vars' ) ) {
	/**
	 * Build CSS variables for the framed layout setting.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_framed_css_vars() {
		$is_framed       = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$max_width       = prismleaf_get_theme_mod_size_value( 'prismleaf_frame_max_width', 'frame_max_width', '1480' );
		$show_background = prismleaf_get_theme_mod_bool( 'prismleaf_frame_show_background', true );
		$surface         = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_frame_background_color_palette', 'surface_1', '--prismleaf-color-surface-1' );
		$border_color    = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_frame_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius   = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_frame_border_corners', 'frame_border_corners', 'Round' );
		$border_style    = prismleaf_get_theme_mod_border_style_value( 'prismleaf_frame_border_style', 'frame_border_style', 'solid' );
		$elevation       = prismleaf_get_theme_mod_elevation_value( 'prismleaf_frame_elevation', 'frame_elevation', 'elevation-1' );

		$view_height     = $is_framed ? '--prismleaf-max-view-height' : 'auto';
		$gap             = $is_framed ? '0' : '--prismleaf-space-2';
		$overflow        = $is_framed ? 'hidden' : 'auto';
		$stretch         = $is_framed ? 'stretch' : 'start';
		$region_overflow = $is_framed ? 'auto' : 'hidden';

		if ( $is_framed || ! $show_background ) {
			$surface = 'transparent';
			$border_radius = '0';
			$border_style = 'none';
			$elevation = 'none';
			$border_color = 'transparent';
		}

		$css = '';
		$css .= prismleaf_build_css_var( '--prismleaf-content-max', $max_width );
		$css .= prismleaf_build_css_var( '--prismleaf-view-height', $view_height );
		$css .= prismleaf_build_css_var( '--prismleaf-region-overflow', $region_overflow );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-gap', $gap );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-overflow', $overflow );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-stretch', $stretch );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-background-color', $surface );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-border-color', $border_color );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-border-radius', $border_radius );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-border-style', $border_style );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-elevation', $elevation );

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_header_css_vars' ) ) {
	/**
	 * Build CSS variables for header layout settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_header_css_vars() {
		$is_framed   = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$is_floating = prismleaf_get_theme_mod_bool( 'prismleaf_header_floating', true );
		$height      = prismleaf_get_theme_mod_header_height( 'prismleaf_header_height', 'header_height' );
		$max_height  = ( $is_framed || ! $is_floating ) ? '150px' : 'none';
		$margin      = '--prismleaf-space-2';

		$surface          = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_header_background_color_palette', 'surface_1', '--prismleaf-color-surface-2' );
		$border_color     = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_header_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius    = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_header_border_corners', 'header_border_corners', 'Round' );
		$border_style     = prismleaf_get_theme_mod_border_style_value( 'prismleaf_header_border_style', 'header_border_style', 'solid' );
		$border_style_alt = $border_style;
		$elevation        = prismleaf_get_theme_mod_elevation_value( 'prismleaf_header_elevation', 'none', '--prismleaf-elevation-2' );

		$background_size       = prismleaf_get_theme_mod_background_size( 'prismleaf_header_background_size', 'header_background_image_size' );
		$background_attachment = prismleaf_get_theme_mod_background_attachment( 'prismleaf_header_background_attachment', 'header_background_image_attachment' );
		$background_image      = prismleaf_get_theme_mod_background_image_url( 'prismleaf_header_background_image', 'header_background_image' );
		$background_repeat     = prismleaf_get_theme_mod_background_repeat( 'prismleaf_header_background_repeat', 'header_background_image_repeat' );
		$background_position   = prismleaf_get_theme_mod_background_position( 'prismleaf_header_background_position_x', 'header_background_image_position_x', 'prismleaf_header_background_position_y', 'header_background_image_position_y' );

		if ( $is_framed ) {
			$surface       = '--prismleaf-color-surface-1';
			$border_color  = 'none';
			$border_style  = 'none';
			$elevation     = 'none';
		}

		if ( $is_framed || ! $is_floating ) {
			$margin           = '0';
			$border_radius    = '0';
			$border_style_alt = 'none';
		}

		$css  = '';
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-color', $surface );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-image', $background_image );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-repeat', $background_repeat );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-position', $background_position );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-size', $background_size );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-attachment', $background_attachment );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-color', $border_color );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-radius', $border_radius );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-style-bottom', $border_style );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-style-left', $border_style_alt );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-style-right', $border_style_alt );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-style-top', $border_style_alt );
		$css .= prismleaf_build_css_var( '--prismleaf-header-elevation', $elevation );
		$css .= prismleaf_build_css_var( '--prismleaf-header-height', $height );
		$css .= prismleaf_build_css_var( '--prismleaf-header-margin', $margin );
		$css .= prismleaf_build_css_var( '--prismleaf-header-max-height', $max_height );

	return $css;
}
}

if ( ! function_exists( 'prismleaf_get_header_icon_css_vars' ) ) {
	/**
	 * Build CSS variables for the header icon settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_header_icon_css_vars() {
		$size  = prismleaf_get_theme_mod_header_icon_size();
		$shape = prismleaf_get_theme_mod_header_icon_shape();

		$size_map = array(
			'small'  => 'var(--prismleaf-space-5)',
			'medium' => 'var(--prismleaf-space-6)',
			'large'  => 'var(--prismleaf-space-8)',
		);

		if ( isset( $size_map[ $size ] ) ) {
			$size_value = $size_map[ $size ];
		} else {
			$size_value = 'var(--prismleaf-space-6)';
		}

		$radius_map = array(
			'small'  => 'var(--prismleaf-radius-small)',
			'medium' => 'var(--prismleaf-radius-medium)',
			'large'  => 'var(--prismleaf-radius-large)',
		);

		if ( isset( $radius_map[ $size ] ) ) {
			$radius_value = $radius_map[ $size ];
		} else {
			$radius_value = 'var(--prismleaf-radius-medium)';
		}

		switch ( $shape ) {
			case 'square':
				$radius_value = '0';
				break;
			case 'circle':
				$radius_value = '50%';
				break;
		}

		$css  = '';
		$css .= prismleaf_build_css_var( '--prismleaf-site-icon-size', $size_value );
		$css .= prismleaf_build_css_var( '--prismleaf-site-icon-border-radius', $radius_value );

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_footer_css_vars' ) ) {
	/**
	 * Build CSS variables for footer layout settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_footer_css_vars() {
		$is_framed   = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$is_floating = prismleaf_get_theme_mod_bool( 'prismleaf_footer_floating', true );
		$height      = prismleaf_get_theme_mod_footer_height( 'prismleaf_footer_height', 'footer_height' );
		$max_height  = ( $is_framed || ! $is_floating ) ? '150px' : 'none';
		$margin      = '--prismleaf-space-2';

		$surface          = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_footer_background_color_palette', 'surface_1', '--prismleaf-color-surface-2' );
		$border_color     = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_footer_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$widget_color     = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_footer_widget_color_palette', 'surface_on_muted', '--prismleaf-color-surface-on-muted' );
		$copyright_color  = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_footer_copyright_color_palette', 'surface_on_muted', '--prismleaf-color-surface-on-muted' );
		$border_radius    = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_footer_border_corners', 'footer_border_corners', 'Round' );
		$border_style     = prismleaf_get_theme_mod_border_style_value( 'prismleaf_footer_border_style', 'footer_border_style', 'solid' );
		$border_style_alt = $border_style;
		$elevation        = prismleaf_get_theme_mod_elevation_value( 'prismleaf_footer_elevation', 'none', '--prismleaf-elevation-2' );
		$alignment        = prismleaf_get_theme_mod_alignment( 'prismleaf_footer_widget_alignment', 'center' );

		$background_size       = prismleaf_get_theme_mod_background_size( 'prismleaf_footer_background_size', 'footer_background_image_size' );
		$background_attachment = prismleaf_get_theme_mod_background_attachment( 'prismleaf_footer_background_attachment', 'footer_background_image_attachment' );
		$background_image      = prismleaf_get_theme_mod_background_image_url( 'prismleaf_footer_background_image', 'footer_background_image' );
		$background_repeat     = prismleaf_get_theme_mod_background_repeat( 'prismleaf_footer_background_repeat', 'footer_background_image_repeat' );
		$background_position   = prismleaf_get_theme_mod_background_position( 'prismleaf_footer_background_position_x', 'footer_background_image_position_x', 'prismleaf_footer_background_position_y', 'footer_background_image_position_y' );
		$overlay_value         = 'none';

		if ( 'none' !== $background_image ) {
			$overlay_color = 'var(--prismleaf-color-scrim)';
			$overlay_value = 'linear-gradient(' . $overlay_color . ', ' . $overlay_color . ')';
		}

		if ( $is_framed ) {
			$surface       = '--prismleaf-color-surface-1';
			$border_color  = 'none';
			$border_style  = 'none';
			$elevation     = 'none';
		}

		if ( $is_framed || ! $is_floating ) {
			$margin           = '0';
			$border_radius    = '0';
			$border_style_alt = 'none';
		}

		$justify_map = array(
			'left'    => 'flex-start',
			'center'  => 'center',
			'right'   => 'flex-end',
			'stretch' => 'space-between',
		);

		$justify = isset( $justify_map[ $alignment ] ) ? $justify_map[ $alignment ] : 'center';
		$container_width = '100%';

		$css  = '';
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-color', $surface );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-image', $background_image );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-repeat', $background_repeat );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-position', $background_position );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-size', $background_size );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-attachment', $background_attachment );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-overlay', $overlay_value );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-color', $border_color );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-radius', $border_radius );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-style-bottom', $border_style_alt );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-style-left', $border_style_alt );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-style-right', $border_style_alt );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-style-top', $border_style );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-copyright-color', $copyright_color );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-widget-color', $widget_color );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-elevation', $elevation );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-height', $height );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-margin', $margin );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-max-height', $max_height );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-widget-row-justify', $justify );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-widget-row-width', $container_width );

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_sidebar_css_vars' ) ) {
	/**
	 * Build CSS variables for both sidebar slots, taking swap into account.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_sidebar_css_vars() {
		$swap_sidebars  = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_swap', false );
		$sidebar_styles = array(
			'primary'   => prismleaf_get_current_sidebar_css_vars( 'primary' ),
			'secondary' => prismleaf_get_current_sidebar_css_vars( 'secondary' ),
		);

		$slot_map = array(
			'left'  => $swap_sidebars ? 'secondary' : 'primary',
			'right' => $swap_sidebars ? 'primary' : 'secondary',
		);

		$css = '';
		foreach ( $slot_map as $slot => $group ) {
			$border_side = 'left' === $slot ? 'right' : 'left';

			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-background-color", $sidebar_styles[ $group ]['surface'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-border-color", $sidebar_styles[ $group ]['border_color'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-border-radius", $sidebar_styles[ $group ]['border_radius'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-border-style-{$border_side}", $sidebar_styles[ $group ]['border_style'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-border-style-{$slot}", $sidebar_styles[ $group ]['border_style_alt'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-border-style-bottom", $sidebar_styles[ $group ]['border_style_alt'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-border-style-top", $sidebar_styles[ $group ]['border_style_alt'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-elevation", $sidebar_styles[ $group ]['elevation'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-margin", $sidebar_styles[ $group ]['margin'] );
			$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$slot}-width", $sidebar_styles[ $group ]['width'] );
		}

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_current_sidebar_css_vars' ) ) {
	/**
	 * Build CSS variables for current sidebar slot.
	 *
	 * @since 1.0.0
	 *
	 * @param string $sidebar Sidebar slot ('primary' or 'secondary').
	 * @return array<string,mixed>
	 */
	function prismleaf_get_current_sidebar_css_vars( $sidebar ) {
		$is_framed        = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false);
		$is_floating      = prismleaf_get_theme_mod_bool( "prismleaf_sidebar_{$sidebar}_floating", true);
		$margin           = '--prismleaf-space-2';
		$default_width    = ( 'primary' === $sidebar ) ? '260' : '200';
		$width            = prismleaf_get_theme_mod_sidebar_width_value( "prismleaf_sidebar_{$sidebar}_width", "sidebar_{$sidebar}_width", $default_width );
		$surface          = prismleaf_get_theme_mod_palette_source_value( "prismleaf_sidebar_{$sidebar}_background_color_palette", 'surface_1', '--prismleaf-color-surface-2' );
		$border_color     = prismleaf_get_theme_mod_palette_source_value( "prismleaf_sidebar_{$sidebar}_border_color_palette", 'outline', '--prismleaf-color-outline' );
		$border_radius    = prismleaf_get_theme_mod_border_radius_value( "prismleaf_sidebar_{$sidebar}_border_corners", "sidebar_{$sidebar}_border_corners", 'Round' );
		$border_style     = prismleaf_get_theme_mod_border_style_value( "prismleaf_sidebar_{$sidebar}_border_style", "sidebar_{$sidebar}_border_style", 'solid' );
		$border_style_alt = $border_style;
		$elevation        = prismleaf_get_theme_mod_elevation_value( "prismleaf_sidebar_{$sidebar}_elevation", 'none', '--prismleaf-elevation-2');

		if ($is_framed) {
			$surface       = '--prismleaf-color-surface-1';
			$border_color  = 'none';
			$border_style  = 'none';
			$elevation     = 'none';
		}

		if ($is_framed || ! $is_floating) {
			$margin           = '0';
			$border_radius    = 'Square';
			$border_style_alt = 'none';
		}

		return array(
			'margin'            => $margin,
			'default_width'     => $default_width,
			'surface'           => $surface,
			'border_color'      => $border_color,
			'border_radius'     => $border_radius,
			'border_style'      => $border_style,
			'border_style_alt'  => $border_style_alt,
			'elevation'         => $elevation,
			'width'             => $width,
		);
	}
}

if ( ! function_exists( 'prismleaf_get_content_css_vars' ) ) {
	/**
	 * Build CSS variables for the content layout settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_content_css_vars() {
		$is_framed     = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$surface       = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_content_background_color_palette', 'surface_1', '--prismleaf-color-surface-2' );
		$border_color  = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_content_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_content_border_corners', 'content_border_corners', 'Round' );
		$border_style  = prismleaf_get_theme_mod_border_style_value( 'prismleaf_content_border_style', 'content_border_style', 'solid' );
		$elevation     = prismleaf_get_theme_mod_elevation_value( 'prismleaf_content_elevation', '', $is_framed ? 'elevation-1' : 'elevation-2' );

		$css  = '';
		$css .= prismleaf_build_css_var( '--prismleaf-content-background-color', $surface );
		$css .= prismleaf_build_css_var( '--prismleaf-content-border-color', $border_color );
		$css .= prismleaf_build_css_var( '--prismleaf-content-border-radius', $border_radius );
		$css .= prismleaf_build_css_var( '--prismleaf-content-border-style', $border_style );
		$css .= prismleaf_build_css_var( '--prismleaf-content-elevation', $elevation );

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_widget_css_vars' ) ) {
	/**
	 * Build CSS variables for widget styling settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_widget_css_vars() {
		$surface         = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_widget_background_color_palette', 'surface_1', '--prismleaf-color-surface-3' );
		$border_color    = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_widget_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius   = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_widget_border_corners', 'widget_border_corners', 'Round' );
		$border_style    = prismleaf_get_theme_mod_border_style_value( 'prismleaf_widget_border_style', 'widget_border_style', 'solid' );
		$elevation       = prismleaf_get_theme_mod_elevation_value( 'prismleaf_widget_elevation', 'widget_elevation', 'elevation-3' );
		$title_color     = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_widget_title_color_palette', 'surface_1', '--prismleaf-color-primary-surface-3' );
		$title_alignment = prismleaf_get_theme_mod_widget_title_alignment( 'prismleaf_widget_title_alignment', 'left' );

		$css  = '';
		$css .= prismleaf_build_css_var( '--prismleaf-widget-background-color', $surface );
		$css .= prismleaf_build_css_var( '--prismleaf-widget-border-color', $border_color );
		$css .= prismleaf_build_css_var( '--prismleaf-widget-border-radius', $border_radius );
		$css .= prismleaf_build_css_var( '--prismleaf-widget-border-style', $border_style );
		$css .= prismleaf_build_css_var( '--prismleaf-widget-elevation', $elevation );
		$css .= prismleaf_build_css_var( '--prismleaf-widget-title-color', $title_color );
		$css .= prismleaf_build_css_var( '--prismleaf-widget-title-alignment', $title_alignment );

		return $css;
	}
}
