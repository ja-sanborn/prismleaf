<?php
/**
 * Color utilities.
 *
 * Low-level color helpers used across the theme.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Convert a hex color to RGB.
 *
 * @since 1.0.0
 *
 * @param string $hex Hex color.
 * @return array<int,int>|null Array of [r, g, b] or null on failure.
 */
if ( ! function_exists( 'prismleaf_hex_to_rgb' ) ) {
	function prismleaf_hex_to_rgb( $hex ) {
		$hex = ltrim( strtolower( trim( (string) $hex ) ), '#' );

		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
			return null;
		}

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		return array( $r, $g, $b );
	}
}

/**
 * Convert RGB to a hex color.
 *
 * @since 1.0.0
 *
 * @param array<int,int> $rgb Array of [r, g, b].
 * @return string|null Hex color string or null on failure.
 */
if ( ! function_exists( 'prismleaf_rgb_to_hex' ) ) {
	function prismleaf_rgb_to_hex( $rgb ) {
		if ( ! is_array( $rgb ) || 3 !== count( $rgb ) ) {
			return null;
		}

		$r = prismleaf_clamp_int( $rgb[0], 0, 255 );
		$g = prismleaf_clamp_int( $rgb[1], 0, 255 );
		$b = prismleaf_clamp_int( $rgb[2], 0, 255 );

		return sprintf( '#%02x%02x%02x', $r, $g, $b );
	}
}

/**
 * Convert RGB to HSL.
 *
 * @since 1.0.0
 *
 * @param array<int,int> $rgb Array of [r, g, b].
 * @return array<int,float>|null Array of [h, s, l] (0..1) or null on failure.
 */
if ( ! function_exists( 'prismleaf_rgb_to_hsl' ) ) {
	function prismleaf_rgb_to_hsl( $rgb ) {
		if ( ! is_array( $rgb ) || 3 !== count( $rgb ) ) {
			return null;
		}

		$r = prismleaf_clamp_int( $rgb[0], 0, 255 ) / 255;
		$g = prismleaf_clamp_int( $rgb[1], 0, 255 ) / 255;
		$b = prismleaf_clamp_int( $rgb[2], 0, 255 ) / 255;

		$max = max( $r, $g, $b );
		$min = min( $r, $g, $b );
		$h   = 0.0;
		$s   = 0.0;
		$l   = ( $max + $min ) / 2;

		if ( $max !== $min ) {
			$d = $max - $min;

			$s = ( $l > 0.5 ) ? ( $d / ( 2 - $max - $min ) ) : ( $d / ( $max + $min ) );

			if ( $max === $r ) {
				$h = ( $g - $b ) / $d + ( $g < $b ? 6 : 0 );
			} elseif ( $max === $g ) {
				$h = ( $b - $r ) / $d + 2;
			} else {
				$h = ( $r - $g ) / $d + 4;
			}

			$h /= 6;
		}

		return array( $h, $s, $l );
	}
}

/**
 * Convert HSL to RGB.
 *
 * @since 1.0.0
 *
 * @param array<int,float> $hsl Array of [h, s, l] (0..1).
 * @return array<int,int>|null Array of [r, g, b] or null on failure.
 */
if ( ! function_exists( 'prismleaf_hsl_to_rgb' ) ) {
	function prismleaf_hsl_to_rgb( $hsl ) {
		if ( ! is_array( $hsl ) || 3 !== count( $hsl ) ) {
			return null;
		}

		$h = (float) $hsl[0];
		$s = (float) $hsl[1];
		$l = (float) $hsl[2];

		$h = max( 0, min( 1, $h ) );
		$s = max( 0, min( 1, $s ) );
		$l = max( 0, min( 1, $l ) );

		if ( 0.0 === $s ) {
			$val = (int) round( $l * 255 );
			return array( $val, $val, $val );
		}

		$q = ( $l < 0.5 ) ? ( $l * ( 1 + $s ) ) : ( $l + $s - ( $l * $s ) );
		$p = 2 * $l - $q;

		$hue_to_rgb = static function( $p, $q, $t ) {
			if ( $t < 0 ) {
				$t += 1;
			}
			if ( $t > 1 ) {
				$t -= 1;
			}
			if ( $t < 1 / 6 ) {
				return $p + ( $q - $p ) * 6 * $t;
			}
			if ( $t < 1 / 2 ) {
				return $q;
			}
			if ( $t < 2 / 3 ) {
				return $p + ( $q - $p ) * ( 2 / 3 - $t ) * 6;
			}
			return $p;
		};

		$r = $hue_to_rgb( $p, $q, $h + 1 / 3 );
		$g = $hue_to_rgb( $p, $q, $h );
		$b = $hue_to_rgb( $p, $q, $h - 1 / 3 );

		return array(
			(int) round( $r * 255 ),
			(int) round( $g * 255 ),
			(int) round( $b * 255 ),
		);
	}
}

/**
 * Calculate relative luminance for a hex color.
 *
 * @since 1.0.0
 *
 * @param string $hex Hex color.
 * @return float|null Luminance or null on failure.
 */
if ( ! function_exists( 'prismleaf_relative_luminance' ) ) {
	function prismleaf_relative_luminance( $hex ) {
		$rgb = prismleaf_hex_to_rgb( $hex );

		if ( ! $rgb ) {
			return null;
		}

		$srgb = array_map(
			static function( $v ) {
				$v = $v / 255;
				return ( $v <= 0.03928 ) ? ( $v / 12.92 ) : pow( ( ( $v + 0.055 ) / 1.055 ), 2.4 );
			},
			$rgb
		);

		return 0.2126 * $srgb[0] + 0.7152 * $srgb[1] + 0.0722 * $srgb[2];
	}
}

/**
 * Calculate contrast ratio between two hex colors.
 *
 * @since 1.0.0
 *
 * @param string $hex1 First hex color.
 * @param string $hex2 Second hex color.
 * @return float|null Contrast ratio or null on failure.
 */
if ( ! function_exists( 'prismleaf_contrast_ratio' ) ) {
	function prismleaf_contrast_ratio( $hex1, $hex2 ) {
		$l1 = prismleaf_relative_luminance( $hex1 );
		$l2 = prismleaf_relative_luminance( $hex2 );

		if ( null === $l1 || null === $l2 ) {
			return null;
		}

		$lighter = max( $l1, $l2 );
		$darker  = min( $l1, $l2 );

		return ( $lighter + 0.05 ) / ( $darker + 0.05 );
	}
}

/**
 * Pick an accessible on-color (black or white) for a background.
 *
 * @since 1.0.0
 *
 * @param string $background_hex Background hex color.
 * @return string Hex color for foreground.
 */
if ( ! function_exists( 'prismleaf_pick_on_color' ) ) {
	function prismleaf_pick_on_color( $background_hex ) {
		$white = '#ffffff';
		$black = '#000000';

		$cw = prismleaf_contrast_ratio( $background_hex, $white );
		$cb = prismleaf_contrast_ratio( $background_hex, $black );

		if ( null === $cw || null === $cb ) {
			return $black;
		}

		return ( $cw >= $cb ) ? $white : $black;
	}
}

/**
 * Adjust a hex color lightness in HSL space.
 *
 * @since 1.0.0
 *
 * @param string $hex   Hex color.
 * @param float  $delta Lightness delta (-1..1).
 * @return string|null Adjusted hex color or null on failure.
 */
if ( ! function_exists( 'prismleaf_adjust_lightness' ) ) {
	function prismleaf_adjust_lightness( $hex, $delta ) {
		$rgb = prismleaf_hex_to_rgb( $hex );

		if ( ! $rgb ) {
			return null;
		}

		$hsl = prismleaf_rgb_to_hsl( $rgb );

		if ( ! $hsl ) {
			return null;
		}

		$hsl[2] = max( 0, min( 1, $hsl[2] + (float) $delta ) );

		$new_rgb = prismleaf_hsl_to_rgb( $hsl );

		return $new_rgb ? prismleaf_rgb_to_hex( $new_rgb ) : null;
	}
}

/**
 * Generate a palette from a base color.
 *
 * @since 1.0.0
 *
 * @param string $base_hex Base hex color.
 * @param string $scheme   Scheme: 'light' or 'dark'.
 * @return array<string,string> Palette values.
 */
if ( ! function_exists( 'prismleaf_generate_palette_from_base' ) ) {
	function prismleaf_generate_palette_from_base( $base_hex, $scheme ) {
		$base_hex = sanitize_hex_color( $base_hex );

		if ( ! $base_hex ) {
			return array();
		}

		$scheme = strtolower( (string) $scheme );
		$scheme = in_array( $scheme, array( 'light', 'dark' ), true ) ? $scheme : 'light';

		$base_darker_delta   = ( 'light' === $scheme ) ? -0.12 : -0.06;
		$base_darkest_delta  = ( 'light' === $scheme ) ? -0.22 : -0.12;
		$base_lighter_delta  = ( 'light' === $scheme ) ? 0.08 : 0.14;
		$base_lightest_delta = ( 'light' === $scheme ) ? 0.16 : 0.22;

		$container_delta          = ( 'light' === $scheme ) ? 0.36 : -0.30;
		$container_darker_delta   = ( 'light' === $scheme ) ? 0.24 : -0.38;
		$container_darkest_delta  = ( 'light' === $scheme ) ? 0.12 : -0.46;
		$container_lighter_delta  = ( 'light' === $scheme ) ? 0.44 : -0.22;
		$container_lightest_delta = ( 'light' === $scheme ) ? 0.52 : -0.14;

		$container_hex = prismleaf_adjust_lightness( $base_hex, $container_delta );

		$palette = array(
			'base'          => $base_hex,
			'on_base'       => prismleaf_pick_on_color( $base_hex ),
			'base_darker'   => prismleaf_adjust_lightness( $base_hex, $base_darker_delta ),
			'base_darkest'  => prismleaf_adjust_lightness( $base_hex, $base_darkest_delta ),
			'base_lighter'  => prismleaf_adjust_lightness( $base_hex, $base_lighter_delta ),
			'base_lightest' => prismleaf_adjust_lightness( $base_hex, $base_lightest_delta ),

			'container'          => $container_hex,
			'on_container'       => $container_hex ? prismleaf_pick_on_color( $container_hex ) : null,
			'container_darker'   => $container_hex ? prismleaf_adjust_lightness( $container_hex, $container_darker_delta ) : null,
			'container_darkest'  => $container_hex ? prismleaf_adjust_lightness( $container_hex, $container_darkest_delta ) : null,
			'container_lighter'  => $container_hex ? prismleaf_adjust_lightness( $container_hex, $container_lighter_delta ) : null,
			'container_lightest' => $container_hex ? prismleaf_adjust_lightness( $container_hex, $container_lightest_delta ) : null,
		);

		foreach ( $palette as $k => $v ) {
			$palette[ $k ] = is_string( $v ) ? $v : '';
		}

		return $palette;
	}
}

/**
 * Derive a dark base color from a light base color.
 *
 * @since 1.0.0
 *
 * @param string $light_hex Light base hex color.
 * @return string Derived dark base hex color, or empty string on failure.
 */
if ( ! function_exists( 'prismleaf_derive_dark_base_from_light' ) ) {
	function prismleaf_derive_dark_base_from_light( $light_hex ) {
		$light_hex = sanitize_hex_color( $light_hex );

		if ( ! $light_hex ) {
			return '';
		}

		$derived = prismleaf_adjust_lightness( $light_hex, -0.30 );

		return $derived ? $derived : '';
	}
}
