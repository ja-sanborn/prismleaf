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
	function prismleaf_output_customizer_styles() {
		$css = prismleaf_get_customizer_css_vars();

		if ( '' === $css ) {
			return;
		}

		wp_add_inline_style(
			'prismleaf-regions',
			':root{' . $css . '}'
		);
	}
}

if ( ! function_exists( 'prismleaf_get_customizer_css_vars' ) ) {
	/**
	 * Build a CSS variable list from Customizer settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_customizer_css_vars() {
		$css = '';
		$css .= prismleaf_get_palette_override_css_vars();
		$css .= prismleaf_get_neutral_palette_override_css_vars();
		$css .= prismleaf_get_framed_css_vars();
		$css .= prismleaf_get_header_css_vars();
		$css .= prismleaf_get_footer_css_vars();
		$css .= prismleaf_get_sidebar_primary_css_vars();
		$css .= prismleaf_get_sidebar_secondary_css_vars();
		$css .= prismleaf_get_content_css_vars();

	return $css;
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
	$value = prismleaf_sanitize_text( $value );

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
			"--prismleaf-color-{$slug}-on"                   => 'on',
			"--prismleaf-color-{$slug}-1"                    => '1',
			"--prismleaf-color-{$slug}-2"                    => '2',
			"--prismleaf-color-{$slug}-3"                    => '3',
			"--prismleaf-color-{$slug}-4"                    => '4',
			"--prismleaf-color-{$slug}-5"                    => '5',
			"--prismleaf-color-{$slug}-outline"              => 'outline',
			"--prismleaf-color-{$slug}-outline-variant"      => 'outline_variant',
			"--prismleaf-color-{$slug}-on-surface-muted"     => 'on_surface_muted',
			"--prismleaf-color-{$slug}-disabled-foreground"  => 'disabled_foreground',
			"--prismleaf-color-{$slug}-disabled-surface"     => 'disabled_surface',
			"--prismleaf-color-{$slug}-container-on"         => 'container_on',
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

if ( ! function_exists( 'prismleaf_get_palette_override_css_vars' ) ) {
	/**
	 * Build CSS variables for all palette overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_palette_override_css_vars() {
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

if ( ! function_exists( 'prismleaf_build_neutral_palette_css_vars' ) ) {
	/**
	 * Build CSS variables for a light/dark neutral palette override.
	 *
	 * @since 1.0.0
	 *
	 * @param string               $scheme        Palette scheme ('light' or 'dark').
	 * @param array<string,string> $palette_values Palette value map.
	 * @return string
	 */
	function prismleaf_build_neutral_palette_css_vars( $scheme, $palette_values ) {
		if ( ! is_string( $scheme ) || ! is_array( $palette_values ) ) {
			return '';
		}

		$scheme = strtolower( trim( $scheme ) );
		$scheme = ( 'dark' === $scheme ) ? 'dark' : 'light';

		$expected_keys = prismleaf_get_palette_keys();
		if ( empty( $expected_keys ) ) {
			return '';
		}

		foreach ( $expected_keys as $key ) {
			if ( ! array_key_exists( $key, $palette_values ) || ! is_string( $palette_values[ $key ] ) || '' === $palette_values[ $key ] ) {
				return '';
			}
		}

		$key_map = array(
			"--prismleaf-color-{$scheme}-surface-on"           => 'on',
			"--prismleaf-color-{$scheme}-surface-1"            => '1',
			"--prismleaf-color-{$scheme}-surface-2"            => '2',
			"--prismleaf-color-{$scheme}-surface-3"            => '3',
			"--prismleaf-color-{$scheme}-surface-4"            => '4',
			"--prismleaf-color-{$scheme}-surface-5"            => '5',
			"--prismleaf-color-{$scheme}-outline"              => 'outline',
			"--prismleaf-color-{$scheme}-outline-variant"      => 'outline_variant',
			"--prismleaf-color-{$scheme}-on-surface-muted"     => 'on_surface_muted',
			"--prismleaf-color-{$scheme}-disabled-foreground"  => 'disabled_foreground',
			"--prismleaf-color-{$scheme}-disabled-surface"     => 'disabled_surface',
			"--prismleaf-color-{$scheme}-surface-container-on" => 'container_on',
			"--prismleaf-color-{$scheme}-surface-container-1"  => 'container_1',
			"--prismleaf-color-{$scheme}-surface-container-2"  => 'container_2',
			"--prismleaf-color-{$scheme}-surface-container-3"  => 'container_3',
			"--prismleaf-color-{$scheme}-surface-container-4"  => 'container_4',
			"--prismleaf-color-{$scheme}-surface-container-5"  => 'container_5',
		);

		return prismleaf_build_css_vars_from_key_map( $palette_values, $key_map );
	}
}

if ( ! function_exists( 'prismleaf_get_neutral_palette_override_css_vars' ) ) {
	/**
	 * Build CSS variables for light/dark neutral palette overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_neutral_palette_override_css_vars() {
		$neutral_map = array(
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
		foreach ( $neutral_map as $scheme => $settings ) {
			$base_value = prismleaf_get_theme_mod_string( $settings['base_setting'], '' );
			if ( '' === $base_value ) {
				continue;
			}

			$palette_values = prismleaf_decode_json_with_keys(
				prismleaf_get_theme_mod_palette_json( $settings['values_setting'], '' ),
				prismleaf_get_palette_keys()
			);

			if ( null === $palette_values ) {
				continue;
			}

			$css .= prismleaf_build_neutral_palette_css_vars( $scheme, $palette_values );
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
		$surface         = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_frame_background_color_palette', '1', '--prismleaf-color-surface-1' );
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
		$is_framed        = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$header_floating  = prismleaf_get_theme_mod_bool( 'prismleaf_header_floating', true );
		$header_contained = prismleaf_get_theme_mod_bool( 'prismleaf_header_contained', true );
		$use_framed       = $is_framed || ( ! $header_contained && ! $header_floating );
		$surface_default  = $use_framed ? '--prismleaf-color-surface-1' : '--prismleaf-color-surface-2';
		$margin           = $use_framed ? '0' : '--prismleaf-space-2';

		$surface       = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_header_background_color_palette', '1', $surface_default );
		$border_color  = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_header_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_header_border_corners', 'header_border_corners', 'Round' );
		$border_style  = prismleaf_get_theme_mod_border_style_value( 'prismleaf_header_border_style', 'header_border_style', 'solid' );
		$elevation     = prismleaf_get_theme_mod_elevation_value( 'prismleaf_header_elevation', '', $is_framed ? 'elevation-1' : 'elevation-2' );
		$height        = prismleaf_get_theme_mod_header_height( 'prismleaf_header_height', 'header_height' );
		$header_background_image      = prismleaf_get_theme_mod_background_image_url( 'prismleaf_header_background_image', 'header_background_image' );
		$header_background_repeat     = prismleaf_get_theme_mod_background_repeat( 'prismleaf_header_background_repeat', 'header_background_image_repeat' );
		$header_background_position   = prismleaf_get_theme_mod_background_position(
			'prismleaf_header_background_position_x',
			'header_background_image_position_x',
			'prismleaf_header_background_position_y',
			'header_background_image_position_y'
		);
		$header_background_size       = prismleaf_get_theme_mod_background_size( 'prismleaf_header_background_size', 'header_background_image_size' );
		$header_background_attachment = prismleaf_get_theme_mod_background_attachment( 'prismleaf_header_background_attachment', 'header_background_image_attachment' );

		if ( ! $use_framed && $header_contained ) {
			$margin = $header_floating ? '--prismleaf-space-2' : '0';
		}

		$css  = '';
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-color', $surface );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-image', $header_background_image );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-repeat', $header_background_repeat );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-position', $header_background_position );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-size', $header_background_size );
		$css .= prismleaf_build_css_var( '--prismleaf-header-background-attachment', $header_background_attachment );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-color', $border_color );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-radius', $border_radius );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-style', $border_style );
		$css .= prismleaf_build_css_var( '--prismleaf-header-elevation', $elevation );
		$css .= prismleaf_build_css_var( '--prismleaf-header-height', $height );
		$css .= prismleaf_build_css_var( '--prismleaf-header-margin', $margin );

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
		$is_framed        = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$footer_floating  = prismleaf_get_theme_mod_bool( 'prismleaf_footer_floating', true );
		$footer_contained = prismleaf_get_theme_mod_bool( 'prismleaf_footer_contained', true );
		$use_framed       = $is_framed || ( ! $footer_contained && ! $footer_floating );
		$surface_default  = $use_framed ? '--prismleaf-color-surface-1' : '--prismleaf-color-surface-2';
		$margin           = $use_framed ? '0' : '--prismleaf-space-2';

		$surface       = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_footer_background_color_palette', '1', $surface_default );
		$border_color  = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_footer_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_footer_border_corners', 'footer_border_corners', 'Round' );
		$border_style  = prismleaf_get_theme_mod_border_style_value( 'prismleaf_footer_border_style', 'footer_border_style', 'solid' );
		$elevation     = prismleaf_get_theme_mod_elevation_value( 'prismleaf_footer_elevation', '', $is_framed ? 'elevation-1' : 'elevation-2' );
		$height        = prismleaf_get_theme_mod_footer_height( 'prismleaf_footer_height', 'footer_height' );
		$footer_background_image      = prismleaf_get_theme_mod_background_image_url( 'prismleaf_footer_background_image', 'footer_background_image' );
		$footer_background_repeat     = prismleaf_get_theme_mod_background_repeat( 'prismleaf_footer_background_repeat', 'footer_background_image_repeat' );
		$footer_background_position   = prismleaf_get_theme_mod_background_position(
			'prismleaf_footer_background_position_x',
			'footer_background_image_position_x',
			'prismleaf_footer_background_position_y',
			'footer_background_image_position_y'
		);
		$footer_background_size       = prismleaf_get_theme_mod_background_size( 'prismleaf_footer_background_size', 'footer_background_image_size' );
		$footer_background_attachment = prismleaf_get_theme_mod_background_attachment( 'prismleaf_footer_background_attachment', 'footer_background_image_attachment' );

		if ( ! $use_framed && $footer_contained ) {
			$margin = $footer_floating ? '--prismleaf-space-2' : '0';
		}

		$css  = '';
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-color', $surface );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-image', $footer_background_image );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-repeat', $footer_background_repeat );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-position', $footer_background_position );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-size', $footer_background_size );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-attachment', $footer_background_attachment );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-color', $border_color );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-radius', $border_radius );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-style', $border_style );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-elevation', $elevation );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-height', $height );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-margin', $margin );

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_sidebar_primary_css_vars' ) ) {
	/**
	 * Build CSS variables for the primary sidebar layout settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_sidebar_primary_css_vars() {
		$is_framed                 = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$swap_sidebars             = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_swap', false );
		$sidebar_primary_floating  = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_floating', true );
		$sidebar_primary_contained = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_contained', true );
		$use_framed                = $is_framed || ( ! $sidebar_primary_contained && ! $sidebar_primary_floating );
		$surface_default           = $use_framed ? '--prismleaf-color-surface-1' : '--prismleaf-color-surface-2';
		$margin                    = $use_framed ? '0' : '--prismleaf-space-2';

		$surface       = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_sidebar_primary_background_color_palette', '1', $surface_default );
		$border_color  = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_sidebar_primary_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_sidebar_primary_border_corners', 'sidebar_primary_border_corners', 'Round' );
		$border_style  = prismleaf_get_theme_mod_border_style_value( 'prismleaf_sidebar_primary_border_style', 'sidebar_primary_border_style', 'solid' );
		$elevation     = prismleaf_get_theme_mod_elevation_value( 'prismleaf_sidebar_primary_elevation', '', $is_framed ? 'elevation-1' : 'elevation-2' );

		if ( ! $use_framed && $sidebar_primary_contained ) {
			$margin = $sidebar_primary_floating ? '--prismleaf-space-2' : '0';
		}

		$target_side = $swap_sidebars ? 'right' : 'left';
		$width_value = prismleaf_get_theme_mod_primary_sidebar_width( 'prismleaf_sidebar_primary_width', 'sidebar_primary_width' );

		$css  = '';
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-background-color", $surface );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-border-color", $border_color );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-border-radius", $border_radius );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-border-style", $border_style );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-elevation", $elevation );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-margin", $margin );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-width", $width_value );

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_sidebar_secondary_css_vars' ) ) {
	/**
	 * Build CSS variables for the secondary sidebar layout settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_sidebar_secondary_css_vars() {
		$is_framed                   = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$swap_sidebars               = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_swap', false );
		$sidebar_secondary_floating  = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_floating', true );
		$sidebar_secondary_contained = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_contained', true );
		$use_framed                  = $is_framed || ( ! $sidebar_secondary_contained && ! $sidebar_secondary_floating );
		$surface_default             = $use_framed ? '--prismleaf-color-surface-1' : '--prismleaf-color-surface-2';
		$margin                      = $use_framed ? '0' : '--prismleaf-space-2';

		$surface       = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_sidebar_secondary_background_color_palette', '1', $surface_default );
		$border_color  = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_sidebar_secondary_border_color_palette', 'outline', '--prismleaf-color-outline' );
		$border_radius = prismleaf_get_theme_mod_border_radius_value( 'prismleaf_sidebar_secondary_border_corners', 'sidebar_secondary_border_corners', 'Round' );
		$border_style  = prismleaf_get_theme_mod_border_style_value( 'prismleaf_sidebar_secondary_border_style', 'sidebar_secondary_border_style', 'solid' );
		$elevation     = prismleaf_get_theme_mod_elevation_value( 'prismleaf_sidebar_secondary_elevation', '', $is_framed ? 'elevation-1' : 'elevation-2' );

		if ( ! $use_framed && $sidebar_secondary_contained ) {
			$margin = $sidebar_secondary_floating ? '--prismleaf-space-2' : '0';
		}

		$target_side = $swap_sidebars ? 'left' : 'right';
		$width_value = prismleaf_get_theme_mod_secondary_sidebar_width( 'prismleaf_sidebar_secondary_width', 'sidebar_secondary_width' );

		$css  = '';
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-background-color", $surface );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-border-color", $border_color );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-border-radius", $border_radius );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-border-style", $border_style );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-elevation", $elevation );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-margin", $margin );
		$css .= prismleaf_build_css_var( "--prismleaf-sidebar-{$target_side}-width", $width_value );

		return $css;
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
		$surface       = prismleaf_get_theme_mod_palette_source_value( 'prismleaf_content_background_color_palette', '1', '--prismleaf-color-surface-2' );
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
