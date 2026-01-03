<?php
/**
 * CSS variable output.
 *
 * Builds effective CSS custom property overrides for Prismleaf.
 *
 * Output is intended to be injected via wp_add_inline_style() and consumed by
 * assets/styles/core/base.css, assets/styles/core/frame.css, and region styles in assets/styles/core/header.css, footer.css, sidebar-left.css, and sidebar-right.css.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_log_invalid_numeric_setting' ) ) {
	/**
	 * Log invalid numeric setting values once per context.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed  $value   Raw value.
	 * @param string $context Context label.
	 * @return void
	 */
	function prismleaf_log_invalid_numeric_setting( $value, $context ) {
		if ( null === $value || '' === $value ) {
			return;
		}

		if ( is_numeric( $value ) ) {
			return;
		}

		do_action( 'prismleaf_invalid_numeric_setting', $context, $value );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_css_variable_string' ) ) {
	/**
	 * Sanitize a CSS value intended for a custom property override.
	 *
	 * Allows:
	 * - `var(--token-name)` references
	 * - Common CSS color keywords (e.g., transparent, currentColor)
	 * - Lengths/percentages (e.g., 12px, 1.25rem, 0, 50%)
	 *
	 * Returns null for empty/invalid input to indicate "no override".
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Raw value.
	 * @return string|null
	 */
	function prismleaf_sanitize_css_variable_string( $value ) {
		if ( null === $value || '' === $value ) {
			return null;
		}

		$value = wp_strip_all_tags( (string) $value );
		$value = trim( $value );

		if ( '' === $value ) {
			return null;
		}

		// Allow var(--token-name) where token-name uses safe characters.
		if ( preg_match( '/^var\(\s*--[A-Za-z0-9_-]+\s*\)$/', $value ) ) {
			return $value;
		}

		// Allow a short list of safe CSS keywords.
		$keywords = array(
			'transparent',
			'currentColor',
			'inherit',
			'initial',
			'unset',
		);

		if ( in_array( $value, $keywords, true ) ) {
			return $value;
		}

		// Allow basic CSS lengths and percentages.
		// Examples: 0, 12px, 1.25rem, 2em, 50%, 0.5vh.
		if ( preg_match( '/^-?(?:\d+|\d*\.\d+)(?:px|rem|em|%|vh|vw|vmin|vmax|ch|ex)?$/', $value ) ) {
			return $value;
		}

		return null;
	}
}

if ( ! function_exists( 'prismleaf_get_css_variable_overrides' ) ) {
	/**
	 * Returns a CSS string of all variable overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_css_variable_overrides() {
		$declarations  = '';
		$declarations .= prismleaf_get_brand_css_variable_overrides();
		$declarations .= prismleaf_get_layout_css_variable_overrides();
		$declarations .= prismleaf_get_menu_css_variable_overrides();
		$declarations .= prismleaf_get_footer_css_variable_overrides();
		$declarations .= prismleaf_get_global_style_css_variable_overrides();

		if ( '' === $declarations ) {
			return '';
		}

		return ':root{' . $declarations . '}';
	}
}

if ( ! function_exists( 'prismleaf_get_menu_css_variable_overrides' ) ) {
	/**
	 * Returns menu-related CSS custom property overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_menu_css_variable_overrides() {
		$menus = array( 'mobile', 'primary', 'secondary' );
		$css   = '';

		foreach ( $menus as $slug ) {
			$light_id = "prismleaf_menu_{$slug}_color_light";
			$dark_id  = "prismleaf_menu_{$slug}_color_dark";

			$light_raw  = prismleaf_get_theme_mod_string( $light_id, '' );
			$dark_raw   = prismleaf_get_theme_mod_string( $dark_id, '' );
			$light_base = '' !== trim( $light_raw ) ? $light_raw : null;
			$dark_base  = '' !== trim( $dark_raw ) ? $dark_raw : null;

			if ( null === $light_base ) {
				continue;
			}

			$palette_light = function_exists( 'prismleaf_generate_palette_from_base' )
				? prismleaf_generate_palette_from_base( $light_base, 'light' )
				: array();

			$palette_dark = array();

			if ( null === $dark_base ) {
				$palette_dark = $palette_light;
			} elseif ( $dark_base && function_exists( 'prismleaf_generate_palette_from_base' ) ) {
				$palette_dark = prismleaf_generate_palette_from_base( $dark_base, 'dark' );
			}

			$light_bg        = isset( $palette_light['base'] ) ? $palette_light['base'] : $light_base;
			$light_bg_hover  = isset( $palette_light['base_darker'] ) ? $palette_light['base_darker'] : $light_bg;
			$light_bg_active = isset( $palette_light['base_darkest'] ) ? $palette_light['base_darkest'] : $light_bg;
			$light_link      = isset( $palette_light['on_base'] ) && '' !== $palette_light['on_base']
				? $palette_light['on_base']
				: $light_bg;

			$css .= "--prismleaf-menu-{$slug}-bg-light-override:" . $light_bg . ';';
			$css .= "--prismleaf-menu-{$slug}-bg-hover-light-override:" . $light_bg_hover . ';';
			$css .= "--prismleaf-menu-{$slug}-bg-active-light-override:" . $light_bg_active . ';';
			$css .= "--prismleaf-menu-{$slug}-link-light-override:" . $light_link . ';';
			$css .= "--prismleaf-menu-{$slug}-link-hover-light-override:" . $light_link . ';';
			$css .= "--prismleaf-menu-{$slug}-link-active-light-override:" . $light_link . ';';
			$css .= "--prismleaf-menu-{$slug}-link-visited-light-override:" . $light_link . ';';

			if ( ! empty( $palette_dark ) ) {
				$dark_bg        = isset( $palette_dark['base'] ) ? $palette_dark['base'] : $dark_base;
				$dark_bg_hover  = isset( $palette_dark['base_darker'] ) ? $palette_dark['base_darker'] : $dark_bg;
				$dark_bg_active = isset( $palette_dark['base_darkest'] ) ? $palette_dark['base_darkest'] : $dark_bg;
				$dark_link      = isset( $palette_dark['on_base'] ) && '' !== $palette_dark['on_base']
					? $palette_dark['on_base']
					: $dark_bg;

				$css .= "--prismleaf-menu-{$slug}-bg-dark-override:" . $dark_bg . ';';
				$css .= "--prismleaf-menu-{$slug}-bg-hover-dark-override:" . $dark_bg_hover . ';';
				$css .= "--prismleaf-menu-{$slug}-bg-active-dark-override:" . $dark_bg_active . ';';
				$css .= "--prismleaf-menu-{$slug}-link-dark-override:" . $dark_link . ';';
				$css .= "--prismleaf-menu-{$slug}-link-hover-dark-override:" . $dark_link . ';';
				$css .= "--prismleaf-menu-{$slug}-link-active-dark-override:" . $dark_link . ';';
				$css .= "--prismleaf-menu-{$slug}-link-visited-dark-override:" . $dark_link . ';';
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_footer_css_variable_overrides' ) ) {
	/**
	 * Returns footer-related CSS custom property overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_footer_css_variable_overrides() {
		$widget_alignment_raw = prismleaf_get_theme_mod_string( 'prismleaf_footer_widget_alignment', 'center' );
		$widget_alignment     = in_array( $widget_alignment_raw, array( 'left', 'center', 'right', 'stretch' ), true )
			? $widget_alignment_raw
			: 'center';

	$justify_map = array(
		'left'    => 'flex-start',
		'center'  => 'center',
		'right'   => 'flex-end',
		'stretch' => 'stretch',
		);

		$columns = ( 'stretch' === $widget_alignment )
			? 'repeat(auto-fit,minmax(150px,1fr))'
			: 'repeat(4,150px)';

		$widget_justify = isset( $justify_map[ $widget_alignment ] ) ? $justify_map[ $widget_alignment ] : 'center';

	$css  = '--prismleaf-footer-widget-columns:' . $columns . ';';
	$css .= '--prismleaf-footer-widget-justify:' . $widget_justify . ';';

	return $css;
}
}

if ( ! function_exists( 'prismleaf_get_layout_css_variable_overrides' ) ) {
	/**
	 * Returns layout-level CSS custom property overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_layout_css_variable_overrides() {
		$header_height_raw = get_theme_mod( 'prismleaf_layout_header_height', 0 );
		$footer_height_raw = get_theme_mod( 'prismleaf_layout_footer_height', 0 );

		prismleaf_log_invalid_numeric_setting( $header_height_raw, 'layout.header_height' );
		prismleaf_log_invalid_numeric_setting( $footer_height_raw, 'layout.footer_height' );

		$header_height = (int) $header_height_raw;
		$footer_height = (int) $footer_height_raw;

		$sidebar_left_width_raw  = get_theme_mod( 'prismleaf_layout_sidebar_left_width', 280 );
		$sidebar_right_width_raw = get_theme_mod( 'prismleaf_layout_sidebar_right_width', 280 );

		prismleaf_log_invalid_numeric_setting( $sidebar_left_width_raw, 'layout.sidebar_left_width' );
		prismleaf_log_invalid_numeric_setting( $sidebar_right_width_raw, 'layout.sidebar_right_width' );

		$sidebar_left_width  = (int) $sidebar_left_width_raw;
		$sidebar_right_width = (int) $sidebar_right_width_raw;

		$css  = '';
		$css .= '--prismleaf-header-height:' . ( 0 === $header_height ? 'auto' : ( (int) $header_height . 'px' ) ) . ';';
		$css .= '--prismleaf-footer-height:' . ( 0 === $footer_height ? 'auto' : ( (int) $footer_height . 'px' ) ) . ';';
		$css .= '--prismleaf-sidebar-left-width:' . (int) $sidebar_left_width . 'px;';
		$css .= '--prismleaf-sidebar-right-width:' . (int) $sidebar_right_width . 'px;';

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_global_style_css_variable_overrides' ) ) {
	/**
	 * Returns global style CSS custom property overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_global_style_css_variable_overrides() {
		$framed = prismleaf_get_theme_mod_bool( 'prismleaf_layout_framed', false );

		$frames_elevation_raw = get_theme_mod( 'prismleaf_global_frames_elevation', null );
		$frames_elevation     = ( null === $frames_elevation_raw || '' === $frames_elevation_raw )
			? null
			: (int) $frames_elevation_raw;

		$regions = array(
			'header'        => array(
				'slug'        => 'header',
				'fallback_bg' => 'var(--prismleaf-color-surface)',
				'fallback_fg' => 'var(--prismleaf-color-on-surface)',
			),
			'content'       => array(
				'slug'        => 'content',
				'fallback_bg' => 'var(--prismleaf-color-surface)',
				'fallback_fg' => 'var(--prismleaf-color-on-surface)',
			),
			'footer'        => array(
				'slug'        => 'footer',
				'fallback_bg' => 'var(--prismleaf-color-surface)',
				'fallback_fg' => 'var(--prismleaf-color-on-surface)',
			),
			'sidebar_left'  => array(
				'slug'        => 'sidebar-left',
				'fallback_bg' => 'var(--prismleaf-color-surface)',
				'fallback_fg' => 'var(--prismleaf-color-on-surface)',
			),
			'sidebar_right' => array(
				'slug'        => 'sidebar-right',
				'fallback_bg' => 'var(--prismleaf-color-surface)',
				'fallback_fg' => 'var(--prismleaf-color-on-surface)',
			),
		);

		$css = '';

		foreach ( $regions as $region => $props ) {
			$slug = $props['slug'];

			$default_bg = $props['fallback_bg'];
			$default_fg = $props['fallback_fg'];

			$prefix = 'prismleaf_global_region_' . $region . '_';

			$bg_raw      = get_theme_mod( $prefix . 'background', null );
			$fg_raw      = get_theme_mod( $prefix . 'foreground', null );
			$bg_override = ( null === $bg_raw || '' === (string) $bg_raw ) ? null : (string) $bg_raw;
			$fg_override = ( null === $fg_raw || '' === (string) $fg_raw ) ? null : (string) $fg_raw;

			$border_style_raw   = get_theme_mod( $prefix . 'border_style', null );
			$border_width_raw   = get_theme_mod( $prefix . 'border_width', null );
			$border_style_value = ( null === $border_style_raw || '' === (string) $border_style_raw ) ? null : (string) $border_style_raw;
			$border_width_value = ( null === $border_width_raw || '' === (string) $border_width_raw ) ? null : (string) $border_width_raw;

			$radius_raw      = get_theme_mod( $prefix . 'radius', null );
			$radius_override = ( null === $radius_raw || '' === (string) $radius_raw ) ? null : (string) $radius_raw;

			$border_color_raw   = get_theme_mod( $prefix . 'border_color', null );
			$border_color_value = ( null === $border_color_raw || '' === (string) $border_color_raw ) ? null : (string) $border_color_raw;

			$elevation_raw = get_theme_mod( $prefix . 'elevation', null );
			$elevation     = ( null === $elevation_raw || '' === $elevation_raw ) ? null : (int) $elevation_raw;

			// Defaults are only used for logic decisions, not for emitting overrides.
			$default_bg_value = $framed ? 'var(--prismleaf-color-surface)' : $default_bg;

			// Border style (optional; null means leave defaults alone).
			$effective_border_style = $border_style_value;

			// Border width (optional; null means leave defaults alone).
			$effective_border_width = $border_width_value;

			if ( 'none' === $effective_border_style ) {
				$effective_border_width = '0px';
			}

			// Emit overrides.
			if ( null !== $bg_override ) {
				$css .= '--prismleaf-region-' . $slug . '-bg:' . $bg_override . ';';
			}

			if ( null !== $fg_override ) {
				$css .= '--prismleaf-region-' . $slug . '-fg:' . $fg_override . ';';
			}

			if ( null !== $radius_override ) {
				$css .= '--prismleaf-region-' . $slug . '-radius:' . $radius_override . ';';
			}

			if ( null !== $effective_border_style ) {
				$css .= '--prismleaf-region-' . $slug . '-border-style:' . $effective_border_style . ';';

				// If border-style becomes none, border-width is irrelevant and should be removed.
				if ( 'none' === $effective_border_style ) {
					$effective_border_width = '0px';
				}
			}

			if ( null !== $effective_border_width ) {
				$css .= '--prismleaf-region-' . $slug . '-border-width:' . $effective_border_width . ';';
			}

			if ( null !== $border_color_value ) {
				$css .= '--prismleaf-region-' . $slug . '-border-color:' . $border_color_value . ';';
			}

			if ( null !== $elevation ) {
				$css .= '--prismleaf-region-' . $slug . '-elevation:' . (int) $elevation . ';';
			}

			// Frame elevation applies only when framed.
			if ( $framed && null !== $frames_elevation ) {
				$css .= '--prismleaf-global-frames-elevation:' . (int) $frames_elevation . ';';
			}

			// Ensure regions have usable defaults when framed.
			if ( $framed && null === $bg_override ) {
				$css .= '--prismleaf-region-' . $slug . '-bg:' . $default_bg_value . ';';
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_get_brand_css_variable_overrides' ) ) {
	/**
	 * Build brand palette overrides for light/dark roles.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_brand_css_variable_overrides() {
		$roles = array( 'primary', 'secondary', 'tertiary', 'error', 'warning', 'info' );
		$css   = '';

		foreach ( $roles as $role ) {
			$light_id = "prismleaf_brand_{$role}_light";
			$dark_id  = "prismleaf_brand_{$role}_dark";

			$light_raw  = prismleaf_get_theme_mod_string( $light_id, '' );
			$dark_raw   = prismleaf_get_theme_mod_string( $dark_id, '' );
			$light_base = '' !== trim( $light_raw ) ? $light_raw : null;
			$dark_base  = '' !== trim( $dark_raw ) ? $dark_raw : null;

			// Dark overrides are ignored unless light is set.
			if ( null === $light_base ) {
				continue;
			}

			$light_palette = prismleaf_generate_palette_from_base( $light_base, 'light' );

			$dark_palette = array();

			if ( null === $dark_base ) {
				$dark_palette = $light_palette;
			} elseif ( $dark_base ) {
				$dark_palette = prismleaf_generate_palette_from_base( $dark_base, 'dark' );
			}

			$css .= prismleaf_render_brand_palette_css( $role, 'light', $light_palette );

			if ( ! empty( $dark_palette ) ) {
				$css .= prismleaf_render_brand_palette_css( $role, 'dark', $dark_palette );
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_render_brand_palette_css' ) ) {
	/**
	 * Render palette values into CSS custom property overrides.
	 *
	 * @since 1.0.0
	 *
	 * @param string               $role     Brand role (e.g., primary).
	 * @param string               $scheme   Scheme ('light' or 'dark').
	 * @param array<string,string> $palette  Palette values.
	 * @return string
	 */
	function prismleaf_render_brand_palette_css( $role, $scheme, $palette ) {
		if ( empty( $palette ) ) {
			return '';
		}

		$role   = str_replace( '_', '-', (string) $role );
		$scheme = ( 'dark' === $scheme ) ? 'dark' : 'light';

		$map = array(
			'base'               => "--prismleaf-color-{$scheme}-{$role}",
			'on_base'            => "--prismleaf-color-{$scheme}-on-{$role}",
			'base_darker'        => "--prismleaf-color-{$scheme}-{$role}-darker",
			'base_darkest'       => "--prismleaf-color-{$scheme}-{$role}-darkest",
			'base_lighter'       => "--prismleaf-color-{$scheme}-{$role}-lighter",
			'base_lightest'      => "--prismleaf-color-{$scheme}-{$role}-lightest",
			'container'          => "--prismleaf-color-{$scheme}-{$role}-container",
			'on_container'       => "--prismleaf-color-{$scheme}-on-{$role}-container",
			'container_darker'   => "--prismleaf-color-{$scheme}-{$role}-container-darker",
			'container_darkest'  => "--prismleaf-color-{$scheme}-{$role}-container-darkest",
			'container_lighter'  => "--prismleaf-color-{$scheme}-{$role}-container-lighter",
			'container_lightest' => "--prismleaf-color-{$scheme}-{$role}-container-lightest",
		);

		$css = '';

		foreach ( $map as $key => $var_name ) {
			if ( isset( $palette[ $key ] ) && '' !== (string) $palette[ $key ] ) {
				$css .= $var_name . ':' . $palette[ $key ] . ';';
			}
		}

		return $css;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_optional_hex_color' ) ) {
	/**
	 * Sanitize optional hex color value.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $value Color string.
	 * @return string|null
	 */
	function prismleaf_sanitize_optional_hex_color( $value ) {
		if ( null === $value || '' === $value ) {
			return null;
		}

		$sanitized = sanitize_hex_color( $value );
		if ( ! $sanitized ) {
			return null;
		}

		return $sanitized;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_border_style_or_empty' ) ) {
	/**
	 * Sanitize a border style value (or empty string).
	 *
	 * Empty string means "use default".
	 * Null means "no override".
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $value Border style.
	 * @return string|null
	 */
	function prismleaf_sanitize_border_style_or_empty( $value ) {
		if ( null === $value ) {
			return null;
		}

		$value = (string) $value;

		if ( '' === $value ) {
			return '';
		}

		$allowed = array(
			'none',
			'solid',
			'dashed',
			'dotted',
			'double',
		);

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return null;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_border_width_or_empty' ) ) {
	/**
	 * Sanitize a border width value (or empty string).
	 *
	 * Empty string means "use default".
	 * Null means "no override".
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $value Border width.
	 * @return string|null
	 */
	function prismleaf_sanitize_border_width_or_empty( $value ) {
		if ( null === $value ) {
			return null;
		}

		$value = (string) $value;

		if ( '' === $value ) {
			return '';
		}

		// Only allow a small safe set (CSS length with units).
		if ( preg_match( '/^(?:\d+|\d*\.\d+)(?:px|rem|em|%)$/', $value ) ) {
			return $value;
		}

		return null;
	}
}

if ( ! function_exists( 'prismleaf_get_site_metadata_css_variable_overrides' ) ) {
	/**
	 * Returns site metadata CSS custom property overrides.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function prismleaf_get_site_metadata_css_variable_overrides() {
		$raw_size = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_icon_size', '' );
		$size     = '' !== trim( $raw_size ) ? $raw_size : '';

		$root_css = '';

		$map = array(
			'small'  => 'var(--prismleaf-icon-sm)',
			'medium' => 'var(--prismleaf-icon-md)',
			'large'  => 'var(--prismleaf-icon-lg)',
		);

		if ( '' !== (string) $size && null !== $size ) {
			if ( isset( $map[ $size ] ) ) {
				$root_css .= '--prismleaf-site-icon-size:' . $map[ $size ] . ';';
			}
		}

		$title_light_raw = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_title_color_light', '' );
		$title_light     = '' !== trim( $title_light_raw ) ? $title_light_raw : null;

		$title_light_values = null;
		$title_dark_values  = null;

		if ( null !== $title_light ) {
			$palette_light = function_exists( 'prismleaf_generate_palette_from_base' )
			? prismleaf_generate_palette_from_base( $title_light, 'light' )
			: array();

			$hover_light  = isset( $palette_light['base_darker'] ) ? $palette_light['base_darker'] : $title_light;
			$active_light = isset( $palette_light['base_darkest'] ) ? $palette_light['base_darkest'] : $title_light;

			$title_light_values = array(
				'link'        => $title_light,
				'hover'       => $hover_light,
				'active'      => $active_light,
				'visited'     => $hover_light,
				'description' => $hover_light,
			);

			$title_dark_raw = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_title_color_dark', '' );
			$title_dark     = '' !== trim( $title_dark_raw ) ? $title_dark_raw : null;

			if ( null === $title_dark ) {
				$title_dark_values = $title_light_values;
			} elseif ( '' !== (string) $title_dark ) {
				$palette_dark = function_exists( 'prismleaf_generate_palette_from_base' )
				? prismleaf_generate_palette_from_base( $title_dark, 'dark' )
				: array();

				$hover_dark  = isset( $palette_dark['base_darker'] ) ? $palette_dark['base_darker'] : $title_dark;
				$active_dark = isset( $palette_dark['base_darkest'] ) ? $palette_dark['base_darkest'] : $title_dark;

				$title_dark_values = array(
					'link'        => $title_dark,
					'hover'       => $hover_dark,
					'active'      => $active_dark,
					'visited'     => $hover_dark,
					'description' => $hover_dark,
				);
			}
		}

		if ( is_array( $title_light_values ) ) {
			$root_css .= '--prismleaf-color-light-title-link:' . $title_light_values['link'] . ';';
			$root_css .= '--prismleaf-color-light-title-link-hover:' . $title_light_values['hover'] . ';';
			$root_css .= '--prismleaf-color-light-title-link-active:' . $title_light_values['active'] . ';';
			$root_css .= '--prismleaf-color-light-title-link-visited:' . $title_light_values['visited'] . ';';
		}

		if ( is_array( $title_dark_values ) ) {
			$root_css .= '--prismleaf-color-dark-title-link:' . $title_dark_values['link'] . ';';
			$root_css .= '--prismleaf-color-dark-title-link-hover:' . $title_dark_values['hover'] . ';';
			$root_css .= '--prismleaf-color-dark-title-link-active:' . $title_dark_values['active'] . ';';
			$root_css .= '--prismleaf-color-dark-title-link-visited:' . $title_dark_values['visited'] . ';';
		}

		$tagline_light_raw = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_tagline_color_light', '' );
		$tagline_light     = '' !== trim( $tagline_light_raw ) ? $tagline_light_raw : null;

		$tagline_light_value = null;
		$tagline_dark_value  = null;

		if ( null !== $tagline_light ) {
			$tagline_light_value = $tagline_light;

			$tagline_dark_raw = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_tagline_color_dark', '' );
			$tagline_dark     = '' !== trim( $tagline_dark_raw ) ? $tagline_dark_raw : null;

			if ( null === $tagline_dark ) {
				$tagline_dark_value = $tagline_light_value;
			} elseif ( '' !== (string) $tagline_dark ) {
				$tagline_dark_value = $tagline_dark;
			}
		}

		if ( null !== $tagline_light_value ) {
			$root_css .= '--prismleaf-color-light-site-description:' . $tagline_light_value . ';';
		}

		if ( null !== $tagline_dark_value ) {
			$root_css .= '--prismleaf-color-dark-site-description:' . $tagline_dark_value . ';';
		}

		$css = '';
		if ( '' !== $root_css ) {
			$css .= ':root{' . $root_css . '}';
		}

		return $css;
	}
}
