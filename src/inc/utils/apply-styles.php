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
		$css .= prismleaf_get_framed_layout_css_vars();

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
		$value = strtolower( prismleaf_sanitize_text( $value ) );

		if ( '' === $name || '' === $value ) {
			return '';
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
			"--prismleaf-color-{$slug}-on"           => 'on',
			"--prismleaf-color-{$slug}-1"            => '1',
			"--prismleaf-color-{$slug}-2"            => '2',
			"--prismleaf-color-{$slug}-3"            => '3',
			"--prismleaf-color-{$slug}-4"            => '4',
			"--prismleaf-color-{$slug}-5"            => '5',
			"--prismleaf-color-{$slug}-container-on" => 'container_on',
			"--prismleaf-color-{$slug}-container-1"  => 'container_1',
			"--prismleaf-color-{$slug}-container-2"  => 'container_2',
			"--prismleaf-color-{$slug}-container-3"  => 'container_3',
			"--prismleaf-color-{$slug}-container-4"  => 'container_4',
			"--prismleaf-color-{$slug}-container-5"  => 'container_5',
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

		$expected_keys = array(
			'1',
			'2',
			'3',
			'4',
			'5',
			'on',
			'container_1',
			'container_2',
			'container_3',
			'container_4',
			'container_5',
			'container_on',
		);

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

if ( ! function_exists( 'prismleaf_get_framed_layout_css_vars' ) ) {
	/**
	 * Build CSS variables for the framed layout setting.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_framed_layout_css_vars() {
		$is_framed                   = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
		$header_floating             = prismleaf_get_theme_mod_bool( 'prismleaf_header_floating', true );
		$header_contained            = prismleaf_get_theme_mod_bool( 'prismleaf_header_contained', true );
		$footer_floating             = prismleaf_get_theme_mod_bool( 'prismleaf_footer_floating', true );
		$footer_contained            = prismleaf_get_theme_mod_bool( 'prismleaf_footer_contained', true );
		$sidebar_primary_floating    = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_floating', true );
		$sidebar_primary_contained   = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_contained', true );
		$sidebar_secondary_floating  = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_floating', true );
		$sidebar_secondary_contained = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_contained', true );

		$frame_values = array(
			'view_height'     => $is_framed ? 'var(--prismleaf-max-view-height)' : 'auto',
			'border_radius'   => $is_framed ? '0' : 'var(--prismleaf-radius-medium)',
			'border_style'    => $is_framed ? 'none' : 'var(--prismleaf-border-style)',
			'elevation'       => $is_framed ? 'none' : 'var(--prismleaf-shadow-elevation-1)',
			'gap'             => $is_framed ? '0' : 'var(--prismleaf-space-2)',
			'overflow'        => $is_framed ? 'hidden' : 'auto',
			'stretch'         => $is_framed ? 'stretch' : 'start',
			'surface'         => $is_framed ? 'transparent' : 'var(--prismleaf-color-surface-1)',
			'region_overflow' => $is_framed ? 'auto' : 'hidden',
		);

		$region_presets = array(
			'framed' => array(
				'border_radius' => '0',
				'border_style'  => 'none',
				'elevation'     => 'none',
				'margin'        => '0',
				'surface'       => 'var(--prismleaf-color-surface-1)',
			),
			'default' => array(
				'border_radius' => 'var(--prismleaf-radius-medium)',
				'border_style'  => 'var(--prismleaf-border-style)',
				'elevation'     => 'var(--prismleaf-shadow-elevation-2)',
				'margin'        => 'var(--prismleaf-space-2)',
				'surface'       => 'var(--prismleaf-color-surface-2)',
			),
		);

		$region_conditions = array(
			'header' => array(
				'contained'  => $header_contained,
				'floating'   => $header_floating,
				'use_framed' => $is_framed || ( ! $header_contained && ! $header_floating ),
			),
			'footer' => array(
				'contained'  => $footer_contained,
				'floating'   => $footer_floating,
				'use_framed' => $is_framed || ( ! $footer_contained && ! $footer_floating ),
			),
			'sidebar_left' => array(
				'contained'  => $sidebar_primary_contained,
				'floating'   => $sidebar_primary_floating,
				'use_framed' => $is_framed || ( ! $sidebar_primary_contained && ! $sidebar_primary_floating ),
			),
			'sidebar_right' => array(
				'contained'  => $sidebar_secondary_contained,
				'floating'   => $sidebar_secondary_floating,
				'use_framed' => $is_framed || ( ! $sidebar_secondary_contained && ! $sidebar_secondary_floating ),
			),
		);

		$regions = array();
		foreach ( $region_conditions as $region_id => $conditions ) {
			$preset                = $conditions['use_framed'] ? 'framed' : 'default';
			$regions[ $region_id ] = $region_presets[ $preset ];

			if ( ! $is_framed && ! $conditions['use_framed'] && $conditions['contained'] ) {
				$regions[ $region_id ]['margin'] = $conditions['floating'] ? 'var(--prismleaf-space-2)' : '0';
			}
		}

		$css = '';
		$css .= prismleaf_build_css_var( '--prismleaf-view-height', $frame_values['view_height'] );
		$css .= prismleaf_build_css_var( '--prismleaf-region-overflow', $frame_values['region_overflow'] );

		$css .= prismleaf_build_css_var( '--prismleaf-frame-background-color', $frame_values['surface'] );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-border-radius', $frame_values['border_radius'] );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-border-style', $frame_values['border_style'] );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-elevation', $frame_values['elevation'] );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-gap', $frame_values['gap'] );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-overflow', $frame_values['overflow'] );
		$css .= prismleaf_build_css_var( '--prismleaf-frame-stretch', $frame_values['stretch'] );

		$css .= prismleaf_build_css_var( '--prismleaf-footer-background-color', $regions['footer']['surface'] );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-radius', $regions['footer']['border_radius'] );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-border-style', $regions['footer']['border_style'] );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-elevation', $regions['footer']['elevation'] );
		$css .= prismleaf_build_css_var( '--prismleaf-footer-margin', $regions['footer']['margin'] );

		$css .= prismleaf_build_css_var( '--prismleaf-header-background-color', $regions['header']['surface'] );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-radius', $regions['header']['border_radius'] );
		$css .= prismleaf_build_css_var( '--prismleaf-header-border-style', $regions['header']['border_style'] );
		$css .= prismleaf_build_css_var( '--prismleaf-header-elevation', $regions['header']['elevation'] );
		$css .= prismleaf_build_css_var( '--prismleaf-header-margin', $regions['header']['margin'] );

		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-left-background-color', $regions['sidebar_left']['surface'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-left-border-radius', $regions['sidebar_left']['border_radius'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-left-border-style', $regions['sidebar_left']['border_style'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-left-elevation', $regions['sidebar_left']['elevation'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-left-margin', $regions['sidebar_left']['margin'] );

		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-right-background-color', $regions['sidebar_right']['surface'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-right-border-radius', $regions['sidebar_right']['border_radius'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-right-border-style', $regions['sidebar_right']['border_style'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-right-elevation', $regions['sidebar_right']['elevation'] );
		$css .= prismleaf_build_css_var( '--prismleaf-sidebar-right-margin', $regions['sidebar_right']['margin'] );

		if ($is_framed) {
			$css .= prismleaf_build_css_var( '--prismleaf-content-background-color', 'var(--prismleaf-color-surface-1)' );
			$css .= prismleaf_build_css_var( '--prismleaf-content-elevation', 'var(--prismleaf-shadow-elevation-1)' );
		}

		return $css;
	}
}
