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

if ( ! function_exists( 'prismleaf_hex_to_rgb' ) ) {
	/**
	 * Convert a hex color to RGB.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex Hex color.
	 * @return array<int,int>|null Array of [r, g, b] or null on failure.
	 */
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

if ( ! function_exists( 'prismleaf_rgb_to_hex' ) ) {
	/**
	 * Convert RGB to a hex color.
	 *
	 * @since 1.0.0
	 *
	 * @param array<int,int> $rgb Array of [r, g, b].
	 * @return string|null Hex color string or null on failure.
	 */
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

if ( ! function_exists( 'prismleaf_rgb_to_hsl' ) ) {
	/**
	 * Convert RGB to HSL.
	 *
	 * @since 1.0.0
	 *
	 * @param array<int,int> $rgb Array of [r, g, b].
	 * @return array<int,float>|null Array of [h, s, l] (0..1) or null on failure.
	 */
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

if ( ! function_exists( 'prismleaf_hsl_to_rgb' ) ) {
	/**
	 * Convert HSL to RGB.
	 *
	 * @since 1.0.0
	 *
	 * @param array<int,float> $hsl Array of [h, s, l] (0..1).
	 * @return array<int,int>|null Array of [r, g, b] or null on failure.
	 */
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

		$hue_to_rgb = static function ( $p, $q, $t ) {
			if ( $t < 0 ) {
				++$t;
			}
			if ( $t > 1 ) {
				--$t;
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

if ( ! function_exists( 'prismleaf_relative_luminance' ) ) {
	/**
	 * Calculate relative luminance for a hex color.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex Hex color.
	 * @return float|null Luminance or null on failure.
	 */
	function prismleaf_relative_luminance( $hex ) {
		$rgb = prismleaf_hex_to_rgb( $hex );

		if ( ! $rgb ) {
			return null;
		}

		$srgb = array_map(
			static function ( $v ) {
				$v = $v / 255;
				return ( $v <= 0.03928 ) ? ( $v / 12.92 ) : pow( ( ( $v + 0.055 ) / 1.055 ), 2.4 );
			},
			$rgb
		);

		return 0.2126 * $srgb[0] + 0.7152 * $srgb[1] + 0.0722 * $srgb[2];
	}
}

if ( ! function_exists( 'prismleaf_contrast_ratio' ) ) {
	/**
	 * Calculate contrast ratio between two hex colors.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex1 First hex color.
	 * @param string $hex2 Second hex color.
	 * @return float|null Contrast ratio or null on failure.
	 */
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

if ( ! function_exists( 'prismleaf_pick_on_color' ) ) {
	/**
	 * Pick an accessible on-color (black or white) for a background.
	 *
	 * @since 1.0.0
	 *
	 * @param string $background_hex Background hex color.
	 * @return string Hex color for foreground.
	 */
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

if ( ! function_exists( 'prismleaf_pick_on_color_with_contrast' ) ) {
	/**
	 * Pick an on-color by shifting the background lightness until a contrast target is met.
	 *
	 * @since 1.0.0
	 *
	 * @param string $background_hex Background hex color.
	 * @param float  $target_ratio   Minimum contrast ratio.
	 * @return string Hex color for foreground.
	 */
	function prismleaf_pick_on_color_with_contrast( $background_hex, $target_ratio = 4.5 ) {
		$background_hex = sanitize_hex_color( $background_hex );

		if ( ! $background_hex ) {
			return '#010101';
		}

		$rgb = prismleaf_hex_to_rgb( $background_hex );
		$hsl = $rgb ? prismleaf_rgb_to_hsl( $rgb ) : null;
		$luminance = prismleaf_relative_luminance( $background_hex );
		$direction = ( null !== $luminance && $luminance > 0.5 ) ? -1 : 1;
		$step      = 0.02;
		$delta     = 0.0;
		$max_steps = 60;
		$best_candidate = $background_hex;
		$best_ratio     = 0.0;

		for ( $i = 0; $i < $max_steps; $i++ ) {
			$candidate = prismleaf_adjust_lightness_safe( $background_hex, $delta );
			if ( ! $candidate ) {
				break;
			}

			$ratio = prismleaf_contrast_ratio( $candidate, $background_hex );
			if ( $ratio > $best_ratio ) {
				$best_ratio     = $ratio;
				$best_candidate = $candidate;
			}

			if ( $ratio >= $target_ratio ) {
				return $candidate;
			}

			$delta += $step * $direction;
		}

		if ( is_array( $hsl ) && 3 === count( $hsl ) ) {
			$min_l = 0.01;
			$max_l = 0.99;
			$extreme_delta = ( $direction > 0 ) ? ( $max_l - $hsl[2] ) : ( $min_l - $hsl[2] );
			$extreme = prismleaf_adjust_lightness_safe( $background_hex, $extreme_delta, $min_l, $max_l );
			if ( $extreme ) {
				$extreme_ratio = prismleaf_contrast_ratio( $extreme, $background_hex );
				if ( $extreme_ratio > $best_ratio ) {
					$best_candidate = $extreme;
				}
			}
		}

		return $best_candidate;
	}
}

if ( ! function_exists( 'prismleaf_adjust_lightness_safe' ) ) {
	/**
	 * Adjust a hex color lightness in HSL space with guard rails.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex   Hex color.
	 * @param float  $delta Lightness delta (-1..1).
	 * @param float  $min_l Minimum lightness.
	 * @param float  $max_l Maximum lightness.
	 * @return string|null Adjusted hex color or null on failure.
	 */
	function prismleaf_adjust_lightness_safe( $hex, $delta, $min_l = 0.01, $max_l = 0.99 ) {
		$rgb = prismleaf_hex_to_rgb( $hex );

		if ( ! $rgb ) {
			return null;
		}

		$hsl = prismleaf_rgb_to_hsl( $rgb );

		if ( ! $hsl ) {
			return null;
		}

		$min_l = (float) $min_l;
		$max_l = (float) $max_l;
		if ( $min_l < 0 ) {
			$min_l = 0.0;
		}
		if ( $max_l > 1 ) {
			$max_l = 1.0;
		}

		$hsl[2] = max( $min_l, min( $max_l, $hsl[2] + (float) $delta ) );

		$new_rgb = prismleaf_hsl_to_rgb( $hsl );

		return $new_rgb ? prismleaf_rgb_to_hex( $new_rgb ) : null;
	}
}

if ( ! function_exists( 'prismleaf_scale_lightness_deltas' ) ) {
	/**
	 * Scale lightness deltas to keep values within a safe range.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex    Hex color.
	 * @param float[] $deltas Lightness deltas.
	 * @param float  $min_l  Minimum lightness.
	 * @param float  $max_l  Maximum lightness.
	 * @return float[] Scaled deltas.
	 */
	function prismleaf_scale_lightness_deltas( $hex, $deltas, $min_l = 0.01, $max_l = 0.99 ) {
		$rgb = prismleaf_hex_to_rgb( $hex );
		if ( ! $rgb || ! is_array( $deltas ) ) {
			return array();
		}

		$hsl = prismleaf_rgb_to_hsl( $rgb );
		if ( ! $hsl ) {
			return array();
		}

		$current_l = $hsl[2];
		$min_l = (float) $min_l;
		$max_l = (float) $max_l;

		$positives = array_filter( $deltas, static function ( $delta ) {
			return $delta > 0;
		} );
		$negatives = array_filter( $deltas, static function ( $delta ) {
			return $delta < 0;
		} );

		$scale_pos = 1.0;
		if ( ! empty( $positives ) ) {
			$max_up = $max_l - $current_l;
			$max_delta = max( $positives );
			if ( $max_delta > 0 && $max_up < $max_delta ) {
				$scale_pos = $max_up / $max_delta;
			}
		}

		$scale_neg = 1.0;
		if ( ! empty( $negatives ) ) {
			$max_down = $current_l - $min_l;
			$max_delta = min( $negatives );
			if ( $max_delta < 0 && $max_down < abs( $max_delta ) ) {
				$scale_neg = $max_down / abs( $max_delta );
			}
		}

		$scaled = array();
		foreach ( $deltas as $delta ) {
			if ( $delta > 0 ) {
				$scaled[] = $delta * $scale_pos;
			} elseif ( $delta < 0 ) {
				$scaled[] = $delta * $scale_neg;
			} else {
				$scaled[] = $delta;
			}
		}

		return $scaled;
	}
}

if ( ! function_exists( 'prismleaf_adjust_lightness' ) ) {
	/**
	 * Adjust a hex color lightness in HSL space.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex   Hex color.
	 * @param float  $delta Lightness delta (-1..1).
	 * @return string|null Adjusted hex color or null on failure.
	 */
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

if ( ! function_exists( 'prismleaf_generate_palette_from_base' ) ) {
	/**
	 * Generate a palette from a base color.
	 *
	 * @since 1.0.0
	 *
	 * @param string $base_hex Base hex color.
	 * @param string $scheme   Scheme: 'light' or 'dark'.
	 * @return array<string,string> Palette values.
	 */
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
		$container_darker_delta   = ( 'light' === $scheme ) ? -0.12 : -0.38;
		$container_darkest_delta  = ( 'light' === $scheme ) ? -0.24 : -0.46;
		$container_lighter_delta  = ( 'light' === $scheme ) ? 0.44 : 0.22;
		$container_lightest_delta = ( 'light' === $scheme ) ? 0.52 : 0.14;

		$container_hex = prismleaf_adjust_lightness_safe( $base_hex, $container_delta );

		$base_darkest  = prismleaf_adjust_lightness_safe( $base_hex, $base_darkest_delta );
		$base_lightest = prismleaf_adjust_lightness_safe( $base_hex, $base_lightest_delta );

		if ( $container_hex ) {
			$scaled_container_deltas = prismleaf_scale_lightness_deltas(
				$container_hex,
				array(
					$container_darker_delta,
					$container_darkest_delta,
					$container_lighter_delta,
					$container_lightest_delta,
				)
			);
			$container_darker = prismleaf_adjust_lightness_safe( $container_hex, $scaled_container_deltas[0] );
			$container_darkest = prismleaf_adjust_lightness_safe( $container_hex, $scaled_container_deltas[1] );
			$container_lighter = prismleaf_adjust_lightness_safe( $container_hex, $scaled_container_deltas[2] );
			$container_lightest = prismleaf_adjust_lightness_safe( $container_hex, $scaled_container_deltas[3] );
		} else {
			$container_darker = null;
			$container_darkest = null;
			$container_lighter = null;
			$container_lightest = null;
		}

		$palette = array(
			'base'               => $base_hex,
			'on_base'            => prismleaf_pick_on_color_with_contrast( $base_hex ),
			'base_darker'        => prismleaf_adjust_lightness_safe( $base_hex, $base_darker_delta ),
			'base_darkest'       => $base_darkest,
			'base_lighter'       => prismleaf_adjust_lightness_safe( $base_hex, $base_lighter_delta ),
			'base_lightest'      => $base_lightest,
			'container'          => $container_hex,
			'on_container'       => $container_hex ? prismleaf_pick_on_color_with_contrast( $container_hex ) : null,
			'container_darker'   => $container_darker,
			'container_darkest'  => $container_darkest,
			'container_lighter'  => $container_lighter,
			'container_lightest' => $container_lightest,
		);

		foreach ( $palette as $k => $v ) {
			$palette[ $k ] = is_string( $v ) ? $v : '';
		}

		return $palette;
	}
}

if ( ! function_exists( 'prismleaf_build_lightness_ramp' ) ) {
	/**
	 * Build a list of hex colors by applying sequential lightness deltas.
	 *
	 * @since 1.0.0
	 *
	 * @param string $start_hex Starting hex color.
	 * @param float[] $deltas Lightness deltas to apply in order.
	 * @return string[] Hex colors including the start color.
	 */
	function prismleaf_build_lightness_ramp( $start_hex, $deltas ) {
		$start_hex = sanitize_hex_color( $start_hex );

		if ( ! $start_hex || ! is_array( $deltas ) ) {
			return array();
		}

		$ramp = array( $start_hex );
		$current = $start_hex;

		foreach ( $deltas as $delta ) {
			$next = prismleaf_adjust_lightness( $current, (float) $delta );
			if ( ! $next ) {
				return array();
			}

			$ramp[]  = $next;
			$current = $next;
		}

		return $ramp;
	}
}

if ( ! function_exists( 'prismleaf_get_palette_keys' ) ) {
	/**
	 * Get palette keys for a base palette JSON payload.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	function prismleaf_get_palette_keys() {
		return array(
			'base',
			'on_base',
			'base_darker',
			'base_darkest',
			'base_lighter',
			'base_lightest',
			'container',
			'on_container',
			'container_darker',
			'container_darkest',
			'container_lighter',
			'container_lightest',
		);
	}
}

if ( ! function_exists( 'prismleaf_generate_light_neutral_background_palette' ) ) {
	/**
	 * Generate the light neutral background palette from the lightest background.
	 *
	 * @since 1.0.0
	 *
	 * @param string $background_lightest_hex Lightest background hex color.
	 * @return array<string,string> Neutral background palette values.
	 */
	function prismleaf_generate_light_neutral_background_palette( $background_lightest_hex ) {
		$deltas = array(
			-0.007843,
			-0.007843,
			-0.015686,
			-0.017647,
			-0.013725,
			-0.043137,
			-0.009804,
			-0.017647,
			-0.013725,
			-0.019608,
			-0.023529,
			-0.029412,
			-0.027451,
			-0.001961,
		);

		$steps = array(
			'background_lightest',
			'background_lighter',
			'background',
			'background_darker',
			'background_darkest',
			'surface_lightest',
			'surface_lighter',
			'surface',
			'surface_darker',
			'surface_darkest',
			'surface_variant_lightest',
			'surface_variant_lighter',
			'surface_variant',
			'surface_variant_darker',
			'surface_variant_darkest',
		);

		$ramp = prismleaf_build_lightness_ramp( $background_lightest_hex, $deltas );
		if ( count( $ramp ) !== count( $steps ) ) {
			return array();
		}

		return array_combine( $steps, $ramp );
	}
}

if ( ! function_exists( 'prismleaf_generate_dark_neutral_background_palette' ) ) {
	/**
	 * Generate the dark neutral background palette from the darkest background.
	 *
	 * @since 1.0.0
	 *
	 * @param string $background_darkest_hex Darkest background hex color.
	 * @return array<string,string> Neutral background palette values.
	 */
	function prismleaf_generate_dark_neutral_background_palette( $background_darkest_hex ) {
		$deltas = array(
			0.023529,
			0.023529,
			0.023529,
			0.023529,
			0.023529,
			0.023529,
			0.023529,
			0.019608,
			0.023529,
			0.023529,
			0.023529,
			0.023529,
			0.023529,
			0.023529,
		);

		$steps = array(
			'background_darkest',
			'background_darker',
			'background',
			'background_lighter',
			'background_lightest',
			'surface_darkest',
			'surface_darker',
			'surface',
			'surface_lighter',
			'surface_lightest',
			'surface_variant_darkest',
			'surface_variant_darker',
			'surface_variant',
			'surface_variant_lighter',
			'surface_variant_lightest',
		);

		$ramp = prismleaf_build_lightness_ramp( $background_darkest_hex, $deltas );
		if ( count( $ramp ) !== count( $steps ) ) {
			return array();
		}

		return array_combine( $steps, $ramp );
	}
}

if ( ! function_exists( 'prismleaf_generate_light_neutral_foreground_palette' ) ) {
	/**
	 * Generate the light neutral foreground palette from the darkest foreground.
	 *
	 * @since 1.0.0
	 *
	 * @param string $foreground_darkest_hex Darkest foreground hex color.
	 * @return array<string,string> Neutral foreground palette values.
	 */
	function prismleaf_generate_light_neutral_foreground_palette( $foreground_darkest_hex ) {
		$deltas = array(
			0.02549,
			0.041176,
			0.131373,
			0.121569,
		);

		$steps = array(
			'foreground_darkest',
			'foreground_darker',
			'foreground',
			'foreground_lighter',
			'foreground_lightest',
		);

		$ramp = prismleaf_build_lightness_ramp( $foreground_darkest_hex, $deltas );
		if ( count( $ramp ) !== count( $steps ) ) {
			return array();
		}

		return array_combine( $steps, $ramp );
	}
}

if ( ! function_exists( 'prismleaf_generate_dark_neutral_foreground_palette' ) ) {
	/**
	 * Generate the dark neutral foreground palette from the lightest foreground.
	 *
	 * @since 1.0.0
	 *
	 * @param string $foreground_lightest_hex Lightest foreground hex color.
	 * @return array<string,string> Neutral foreground palette values.
	 */
	function prismleaf_generate_dark_neutral_foreground_palette( $foreground_lightest_hex ) {
		$deltas = array(
			-0.039216,
			-0.039216,
			-0.113725,
			-0.109804,
		);

		$steps = array(
			'foreground_lightest',
			'foreground_lighter',
			'foreground',
			'foreground_darker',
			'foreground_darkest',
		);

		$ramp = prismleaf_build_lightness_ramp( $foreground_lightest_hex, $deltas );
		if ( count( $ramp ) !== count( $steps ) ) {
			return array();
		}

		return array_combine( $steps, $ramp );
	}
}

if ( ! function_exists( 'prismleaf_generate_neutral_palette' ) ) {
	/**
	 * Generate a neutral palette from background and foreground seeds.
	 *
	 * @since 1.0.0
	 *
	 * @param string $background_hex Background seed hex color.
	 * @param string $foreground_hex Foreground seed hex color.
	 * @param string $scheme         Scheme: 'light' or 'dark'.
	 * @return array<string,string> Neutral palette values.
	 */
	function prismleaf_generate_neutral_palette( $background_hex, $foreground_hex, $scheme ) {
		$scheme = strtolower( (string) $scheme );
		$scheme = in_array( $scheme, array( 'light', 'dark' ), true ) ? $scheme : 'light';

		if ( 'dark' === $scheme ) {
			$background = prismleaf_generate_dark_neutral_background_palette( $background_hex );
			$foreground = prismleaf_generate_dark_neutral_foreground_palette( $foreground_hex );
		} else {
			$background = prismleaf_generate_light_neutral_background_palette( $background_hex );
			$foreground = prismleaf_generate_light_neutral_foreground_palette( $foreground_hex );
		}

		if ( empty( $background ) || empty( $foreground ) ) {
			return array();
		}

		$palette = array(
			'background'                 => $background['background'],
			'on_background'              => $foreground['foreground'],
			'background_darker'          => $background['background_darker'],
			'background_darkest'         => $background['background_darkest'],
			'background_lighter'         => $background['background_lighter'],
			'background_lightest'        => $background['background_lightest'],
			'surface'                    => $background['surface'],
			'on_surface'                 => $foreground['foreground'],
			'surface_darker'             => $background['surface_darker'],
			'surface_darkest'            => $background['surface_darkest'],
			'surface_lighter'            => $background['surface_lighter'],
			'surface_lightest'           => $background['surface_lightest'],
			'surface_variant'            => $background['surface_variant'],
			'on_surface_variant'         => ( 'dark' === $scheme )
				? $foreground['foreground_darkest']
				: $foreground['foreground_lightest'],
			'surface_variant_darker'     => $background['surface_variant_darker'],
			'surface_variant_darkest'    => $background['surface_variant_darkest'],
			'surface_variant_lighter'    => $background['surface_variant_lighter'],
			'surface_variant_lightest'   => $background['surface_variant_lightest'],
			'foreground'                 => $foreground['foreground'],
			'on_foreground'              => $background['background'],
			'foreground_darker'          => $foreground['foreground_darker'],
			'foreground_darkest'         => $foreground['foreground_darkest'],
			'foreground_lighter'         => $foreground['foreground_lighter'],
			'foreground_lightest'        => $foreground['foreground_lightest'],
		);

		foreach ( $palette as $k => $v ) {
			$palette[ $k ] = is_string( $v ) ? $v : '';
		}

		return $palette;
	}
}

if ( ! function_exists( 'prismleaf_get_neutral_palette_keys' ) ) {
	/**
	 * Get the neutral palette keys without a light/dark prefix.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	function prismleaf_get_neutral_palette_keys() {
		return array(
			'background',
			'on_background',
			'background_darker',
			'background_darkest',
			'background_lighter',
			'background_lightest',
			'surface',
			'on_surface',
			'surface_darker',
			'surface_darkest',
			'surface_lighter',
			'surface_lightest',
			'surface_variant',
			'on_surface_variant',
			'surface_variant_darker',
			'surface_variant_darkest',
			'surface_variant_lighter',
			'surface_variant_lightest',
			'foreground',
			'on_foreground',
			'foreground_darker',
			'foreground_darkest',
			'foreground_lighter',
			'foreground_lightest',
		);
	}
}

if ( ! function_exists( 'prismleaf_get_neutral_json_keys' ) ) {
	/**
	 * Get neutral palette keys prefixed for light and dark sets.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	function prismleaf_get_neutral_json_keys() {
		$base_keys = prismleaf_get_neutral_palette_keys();
		if ( empty( $base_keys ) ) {
			return array();
		}

		$prefixed = array();
		foreach ( array( 'light', 'dark' ) as $prefix ) {
			foreach ( $base_keys as $key ) {
				$prefixed[] = $prefix . '_' . $key;
			}
		}

		return $prefixed;
	}
}

if ( ! function_exists( 'prismleaf_invert_lightness_hex' ) ) {
	/**
	 * Invert the lightness of a hex color while preserving hue and saturation.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex Hex color.
	 * @return string Inverted hex color or empty string on failure.
	 */
	function prismleaf_invert_lightness_hex( $hex ) {
		$hex = sanitize_hex_color( $hex );
		if ( ! $hex ) {
			return '';
		}

		$rgb = prismleaf_hex_to_rgb( $hex );
		$hsl = $rgb ? prismleaf_rgb_to_hsl( $rgb ) : null;
		if ( ! $hsl || 3 !== count( $hsl ) ) {
			return '';
		}

		$hsl[2] = 1 - $hsl[2];
		$new_rgb = prismleaf_hsl_to_rgb( $hsl );
		$new_hex = $new_rgb ? prismleaf_rgb_to_hex( $new_rgb ) : null;

		return $new_hex ? $new_hex : '';
	}
}

if ( ! function_exists( 'prismleaf_shift_color_for_contrast' ) ) {
	/**
	 * Shift a candidate color to meet a target contrast ratio with a fixed color.
	 *
	 * @since 1.0.0
	 *
	 * @param string $fixed_hex     Fixed hex color.
	 * @param string $candidate_hex Candidate hex color to shift.
	 * @param float  $target_ratio  Target contrast ratio.
	 * @return array<string,mixed> Shifted color, ratio, and whether target was met.
	 */
	function prismleaf_shift_color_for_contrast( $fixed_hex, $candidate_hex, $target_ratio = 4.5 ) {
		$fixed_hex     = sanitize_hex_color( $fixed_hex );
		$candidate_hex = sanitize_hex_color( $candidate_hex );

		if ( ! $fixed_hex || ! $candidate_hex ) {
			return array(
				'color' => '',
				'ratio' => 0.0,
				'met'   => false,
			);
		}

		$best_color = $candidate_hex;
		$best_ratio = prismleaf_contrast_ratio( $fixed_hex, $candidate_hex );
		if ( null === $best_ratio ) {
			$best_ratio = 0.0;
		}

		if ( $best_ratio >= $target_ratio ) {
			return array(
				'color' => $candidate_hex,
				'ratio' => $best_ratio,
				'met'   => true,
			);
		}

		$fixed_luminance = prismleaf_relative_luminance( $fixed_hex );
		$candidate_luminance = prismleaf_relative_luminance( $candidate_hex );
		$direction = ( null !== $fixed_luminance && null !== $candidate_luminance && $candidate_luminance >= $fixed_luminance ) ? 1 : -1;
		$step = 0.02;
		$delta = 0.0;
		$max_steps = 60;

		for ( $i = 0; $i < $max_steps; $i++ ) {
			$delta += $step * $direction;
			$shifted = prismleaf_adjust_lightness_safe( $candidate_hex, $delta );
			if ( ! $shifted ) {
				break;
			}

			$ratio = prismleaf_contrast_ratio( $fixed_hex, $shifted );
			if ( null === $ratio ) {
				continue;
			}

			if ( $ratio > $best_ratio ) {
				$best_ratio = $ratio;
				$best_color = $shifted;
			}

			if ( $ratio >= $target_ratio ) {
				return array(
					'color' => $shifted,
					'ratio' => $ratio,
					'met'   => true,
				);
			}
		}

		return array(
			'color' => $best_color,
			'ratio' => $best_ratio,
			'met'   => false,
		);
	}
}

if ( ! function_exists( 'prismleaf_normalize_neutral_bases' ) ) {
	/**
	 * Normalize light/dark neutral base colors with contrast enforcement.
	 *
	 * @since 1.0.0
	 *
	 * @param string $light_hex Light base hex color.
	 * @param string $dark_hex  Dark base hex color.
	 * @param string $source    Source setting that changed ('light' or 'dark').
	 * @return array<string,string> Normalized light/dark hex values.
	 */
	function prismleaf_normalize_neutral_bases( $light_hex, $dark_hex, $source = '' ) {
		$light_hex = sanitize_hex_color( $light_hex ) ? sanitize_hex_color( $light_hex ) : '';
		$dark_hex  = sanitize_hex_color( $dark_hex ) ? sanitize_hex_color( $dark_hex ) : '';

		if ( '' === $light_hex && '' === $dark_hex ) {
			return array(
				'light' => '',
				'dark'  => '',
			);
		}

		if ( '' === $light_hex && '' !== $dark_hex ) {
			$light_hex = prismleaf_invert_lightness_hex( $dark_hex );
		} elseif ( '' === $dark_hex && '' !== $light_hex ) {
			$dark_hex = prismleaf_invert_lightness_hex( $light_hex );
		}

		$light_lum = prismleaf_relative_luminance( $light_hex );
		$dark_lum  = prismleaf_relative_luminance( $dark_hex );
		if ( null !== $light_lum && null !== $dark_lum && $dark_lum > $light_lum ) {
			$tmp = $light_hex;
			$light_hex = $dark_hex;
			$dark_hex = $tmp;
		}

		$target_ratio = 4.5;
		$current_ratio = prismleaf_contrast_ratio( $light_hex, $dark_hex );
		$current_ratio = null === $current_ratio ? 0.0 : $current_ratio;

		if ( $current_ratio < $target_ratio ) {
			if ( 'light' === $source ) {
				$primary = 'dark';
			} elseif ( 'dark' === $source ) {
				$primary = 'light';
			} else {
				$primary = 'dark';
			}

			if ( 'dark' === $primary ) {
				$shift = prismleaf_shift_color_for_contrast( $light_hex, $dark_hex, $target_ratio );
				if ( $shift['color'] ) {
					$dark_hex = $shift['color'];
				}
				$current_ratio = $shift['ratio'];
			} else {
				$shift = prismleaf_shift_color_for_contrast( $dark_hex, $light_hex, $target_ratio );
				if ( $shift['color'] ) {
					$light_hex = $shift['color'];
				}
				$current_ratio = $shift['ratio'];
			}

			if ( $current_ratio < $target_ratio ) {
				if ( 'dark' === $primary ) {
					$shift = prismleaf_shift_color_for_contrast( $dark_hex, $light_hex, $target_ratio );
					if ( $shift['color'] ) {
						$light_hex = $shift['color'];
					}
				} else {
					$shift = prismleaf_shift_color_for_contrast( $light_hex, $dark_hex, $target_ratio );
					if ( $shift['color'] ) {
						$dark_hex = $shift['color'];
					}
				}
			}
		}

		$light_lum = prismleaf_relative_luminance( $light_hex );
		$dark_lum  = prismleaf_relative_luminance( $dark_hex );
		if ( null !== $light_lum && null !== $dark_lum && $dark_lum > $light_lum ) {
			$tmp = $light_hex;
			$light_hex = $dark_hex;
			$dark_hex = $tmp;
		}

		return array(
			'light' => $light_hex,
			'dark'  => $dark_hex,
		);
	}
}

if ( ! function_exists( 'prismleaf_build_neutral_json_from_bases' ) ) {
	/**
	 * Build neutral JSON from light/dark base colors.
	 *
	 * @since 1.0.0
	 *
	 * @param string $light_hex Light base hex color.
	 * @param string $dark_hex  Dark base hex color.
	 * @param string $source    Source setting that changed ('light' or 'dark').
	 * @return array<string,string> Normalized light/dark colors and JSON payload.
	 */
	function prismleaf_build_neutral_json_from_bases( $light_hex, $dark_hex, $source = '' ) {
		$normalized = prismleaf_normalize_neutral_bases( $light_hex, $dark_hex, $source );
		if ( '' === $normalized['light'] || '' === $normalized['dark'] ) {
			return array(
				'light' => $normalized['light'],
				'dark'  => $normalized['dark'],
				'json'  => '',
			);
		}

		$light_foreground = prismleaf_pick_on_color_with_contrast( $normalized['light'] );
		$dark_foreground  = prismleaf_pick_on_color_with_contrast( $normalized['dark'] );

		$light_palette = prismleaf_generate_neutral_palette( $normalized['light'], $light_foreground, 'light' );
		$dark_palette  = prismleaf_generate_neutral_palette( $normalized['dark'], $dark_foreground, 'dark' );

		if ( empty( $light_palette ) || empty( $dark_palette ) ) {
			return array(
				'light' => $normalized['light'],
				'dark'  => $normalized['dark'],
				'json'  => '',
			);
		}

		$palette_keys = prismleaf_get_neutral_palette_keys();
		if ( empty( $palette_keys ) ) {
			return array(
				'light' => $normalized['light'],
				'dark'  => $normalized['dark'],
				'json'  => '',
			);
		}

		$values = array();
		foreach ( $palette_keys as $key ) {
			if ( empty( $light_palette[ $key ] ) || empty( $dark_palette[ $key ] ) ) {
				return array(
					'light' => $normalized['light'],
					'dark'  => $normalized['dark'],
					'json'  => '',
				);
			}

			$light_value = sanitize_hex_color( $light_palette[ $key ] );
			$dark_value  = sanitize_hex_color( $dark_palette[ $key ] );
			if ( ! $light_value || ! $dark_value ) {
				return array(
					'light' => $normalized['light'],
					'dark'  => $normalized['dark'],
					'json'  => '',
				);
			}

			$values[ 'light_' . $key ] = $light_value;
			$values[ 'dark_' . $key ]  = $dark_value;
		}

		return array(
			'light' => $normalized['light'],
			'dark'  => $normalized['dark'],
			'json'  => wp_json_encode( $values ),
		);
	}
}

if ( ! function_exists( 'prismleaf_build_neutral_preview_payload' ) ) {
	/**
	 * Build a normalized payload for the neutral preview control.
	 *
	 * @since 1.0.0
	 *
	 * @param string $light_hex Light base hex color.
	 * @param string $dark_hex  Dark base hex color.
	 * @param string $source    Source setting that changed ('light' or 'dark').
	 * @return array<string,string> Payload containing light, dark, and json.
	 */
	function prismleaf_build_neutral_preview_payload( $light_hex, $dark_hex, $source = '' ) {
		$source = strtolower( (string) $source );
		$source = in_array( $source, array( 'light', 'dark' ), true ) ? $source : '';

		return prismleaf_build_neutral_json_from_bases( $light_hex, $dark_hex, $source );
	}
}

if ( ! function_exists( 'prismleaf_build_palette_json_from_base' ) ) {
	/**
	 * Build palette JSON from a base color.
	 *
	 * @since 1.0.0
	 *
	 * @param string $base_hex Base hex color.
	 * @return string
	 */
	function prismleaf_build_palette_json_from_base( $base_hex ) {
		$base_hex = sanitize_hex_color( $base_hex );

		if ( ! $base_hex || ! function_exists( 'prismleaf_generate_palette_from_base' ) ) {
			return '';
		}

		$palette = prismleaf_generate_palette_from_base( $base_hex, 'light' );
		if ( empty( $palette ) ) {
			return '';
		}

		$expected_keys = prismleaf_get_palette_keys();
		if ( empty( $expected_keys ) ) {
			return '';
		}

		$clean = array();
		foreach ( $expected_keys as $key ) {
			if ( empty( $palette[ $key ] ) ) {
				return '';
			}
			$clean_value = sanitize_hex_color( $palette[ $key ] );
			if ( ! $clean_value ) {
				return '';
			}
			$clean[ $key ] = $clean_value;
		}

		return wp_json_encode( $clean );
	}
}

if ( ! function_exists( 'prismleaf_derive_dark_base_from_light' ) ) {
	/**
	 * Derive a dark base color from a light base color.
	 *
	 * @since 1.0.0
	 *
	 * @param string $light_hex Light base hex color.
	 * @return string Derived dark base hex color, or empty string on failure.
	 */
	function prismleaf_derive_dark_base_from_light( $light_hex ) {
		$light_hex = sanitize_hex_color( $light_hex );

		if ( ! $light_hex ) {
			return '';
		}

		$derived = prismleaf_adjust_lightness( $light_hex, -0.30 );

		return $derived ? $derived : '';
	}
}
