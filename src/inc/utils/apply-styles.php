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
			'elevation'       => $is_framed ? 'none' : 'var(--prismleaf-glow-ring)',
			'gap'             => $is_framed ? '0' : 'var(--prismleaf-space-2)',
			'overflow'        => $is_framed ? 'hidden' : 'auto',
			'stretch'         => $is_framed ? 'stretch' : 'start',
			'surface'         => $is_framed ? 'transparent' : 'var(--prismleaf-color-surface-darker)',
			'region_overflow' => $is_framed ? 'auto' : 'hidden',
		);

		$region_presets = array(
			'framed' => array(
				'border_radius' => '0',
				'border_style'  => 'none',
				'elevation'     => 'none',
				'margin'        => '0',
				'surface'       => 'var(--prismleaf-color-surface)',
			),
			'default' => array(
				'border_radius' => 'var(--prismleaf-radius-medium)',
				'border_style'  => 'var(--prismleaf-border-style)',
				'elevation'     => 'var(--prismleaf-shadow-elevation-1)',
				'margin'        => 'var(--prismleaf-space-2)',
				'surface'       => 'var(--prismleaf-color-surface-lighter)',
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

		return $css;
	}
}
