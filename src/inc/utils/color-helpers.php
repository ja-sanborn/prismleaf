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

if ( ! function_exists( 'prismleaf_min_contrast_ratio' ) ) {
	/**
	 * Calculate the minimum contrast ratio between a foreground and backgrounds.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $foreground_hex Foreground hex color.
	 * @param string[] $background_hexes Background hex colors.
	 * @return float|null Minimum contrast ratio or null on failure.
	 */
	function prismleaf_min_contrast_ratio( $foreground_hex, $background_hexes ) {
		$foreground_hex = sanitize_hex_color( $foreground_hex );
		if ( ! $foreground_hex || ! is_array( $background_hexes ) || empty( $background_hexes ) ) {
			return null;
		}

		$min_ratio = null;
		foreach ( $background_hexes as $background_hex ) {
			$background_hex = sanitize_hex_color( $background_hex );
			if ( ! $background_hex ) {
				continue;
			}

			$ratio = prismleaf_contrast_ratio( $foreground_hex, $background_hex );
			if ( null === $ratio ) {
				continue;
			}

			if ( null === $min_ratio || $ratio < $min_ratio ) {
				$min_ratio = $ratio;
			}
		}

		return $min_ratio;
	}
}

if ( ! function_exists( 'prismleaf_shift_to_lightness' ) ) {
	/**
	 * Shift a color to a specific lightness while preserving hue/saturation.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hex      Hex color.
	 * @param float  $target_l Target lightness (0..1).
	 * @param float  $min_l    Minimum lightness.
	 * @param float  $max_l    Maximum lightness.
	 * @return string|null Adjusted hex color or null on failure.
	 */
	function prismleaf_shift_to_lightness( $hex, $target_l, $min_l = 0.01, $max_l = 0.99 ) {
		$hex = sanitize_hex_color( $hex );

		if ( ! $hex ) {
			return null;
		}

		$rgb = prismleaf_hex_to_rgb( $hex );
		$hsl = $rgb ? prismleaf_rgb_to_hsl( $rgb ) : null;

		if ( ! $hsl || 3 !== count( $hsl ) ) {
			return null;
		}

		$target_l = prismleaf_clamp_float( (float) $target_l, 0.0, 1.0 );
		$min_l = prismleaf_clamp_float( (float) $min_l, 0.0, 1.0 );
		$max_l = prismleaf_clamp_float( (float) $max_l, 0.0, 1.0 );

		$delta = $target_l - (float) $hsl[2];
		return prismleaf_adjust_lightness_safe( $hex, $delta, $min_l, $max_l );
	}
}

if ( ! function_exists( 'prismleaf_pick_on_color_from_tones' ) ) {
	/**
	 * Pick an on-color for a tone ramp, preferring a min contrast near 7.0.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $reference_hex Base tone used to preserve hue/saturation.
	 * @param string[] $background_hexes Background tones to satisfy contrast.
	 * @return string Hex color or empty string on failure.
	 */
	function prismleaf_pick_on_color_from_tones( $reference_hex, $background_hexes ) {
		$reference_hex = sanitize_hex_color( $reference_hex );

		if ( ! $reference_hex || ! is_array( $background_hexes ) || empty( $background_hexes ) ) {
			return '';
		}

		$backgrounds = array();
		foreach ( $background_hexes as $background_hex ) {
			$background_hex = sanitize_hex_color( $background_hex );
			if ( $background_hex ) {
				$backgrounds[] = $background_hex;
			}
		}

		if ( empty( $backgrounds ) ) {
			return '';
		}

		$targets = array(
			prismleaf_shift_to_lightness( $reference_hex, 0.90, 0.05, 0.95 ),
			prismleaf_shift_to_lightness( $reference_hex, 0.10, 0.05, 0.95 ),
		);

		$best_candidate = '';
		$best_ratio = 0.0;
		$best_distance = null;

		foreach ( $targets as $candidate ) {
			if ( ! $candidate ) {
				continue;
			}

			$min_ratio = prismleaf_min_contrast_ratio( $candidate, $backgrounds );
			if ( null === $min_ratio ) {
				continue;
			}

			$meets_min = ( $min_ratio >= 4.5 );
			$distance = abs( 7.0 - $min_ratio );

			if ( $meets_min ) {
				if ( null === $best_distance || $distance < $best_distance ) {
					$best_candidate = $candidate;
					$best_ratio = $min_ratio;
					$best_distance = $distance;
				}
			} elseif ( $best_ratio < 4.5 && $min_ratio > $best_ratio ) {
				$best_candidate = $candidate;
				$best_ratio = $min_ratio;
				$best_distance = $distance;
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

if ( ! function_exists( 'prismleaf_generate_palette_from_base' ) ) {
	/**
	 * Generate a palette from a base color.
	 *
	 * Palette values are generated sequentially from the base color, moving
	 * lighter or darker based on the base luminance.
	 *
	 * @since 1.0.0
	 *
	 * @param string $base_hex Base hex color.
	 * @return array<string,string> Palette values.
	 */
	function prismleaf_generate_palette_from_base( $base_hex ) {
		$base_hex = sanitize_hex_color( $base_hex );

		if ( ! $base_hex ) {
			return array();
		}

		$rgb = prismleaf_hex_to_rgb( $base_hex );
		$hsl = $rgb ? prismleaf_rgb_to_hsl( $rgb ) : null;
		if ( ! $hsl || 3 !== count( $hsl ) ) {
			return array();
		}

		$base_l = prismleaf_clamp_float( (float) $hsl[2], 0.05, 0.95 );
		if ( $base_l !== (float) $hsl[2] ) {
			$shifted = prismleaf_shift_to_lightness( $base_hex, $base_l, 0.05, 0.95 );
			if ( $shifted ) {
				$base_hex = $shifted;
			}
		}
		$light_target = 0.95;
		$dark_target  = 0.05;
		$dist_light   = abs( $light_target - $base_l );
		$dist_dark    = abs( $base_l - $dark_target );

		$target_l  = ( $dist_light >= $dist_dark ) ? $light_target : $dark_target;
		$direction = ( $target_l >= $base_l ) ? 1 : -1;

		$step = abs( $target_l - $base_l ) / 9;
		$step = prismleaf_clamp_float( $step, 0.0, 0.06 );

		$deltas = array_fill( 0, 9, $step * $direction );
		$ramp = prismleaf_build_lightness_ramp( $base_hex, $deltas, 0.05, 0.95 );
		if ( count( $ramp ) !== 10 ) {
			return array();
		}

		$tones = array_slice( $ramp, 0, 5 );
		$container_tones = array_slice( $ramp, 5, 5 );
		$on_color = prismleaf_pick_on_color_from_tones( $ramp[0], $tones );
		$container_on = prismleaf_pick_on_color_from_tones( $ramp[5], $container_tones );

		$palette = array(
			'1'            => $ramp[0],
			'2'            => $ramp[1],
			'3'            => $ramp[2],
			'4'            => $ramp[3],
			'5'            => $ramp[4],
			'on'           => $on_color,
			'container_1'  => $ramp[9],
			'container_2'  => $ramp[8],
			'container_3'  => $ramp[7],
			'container_4'  => $ramp[6],
			'container_5'  => $ramp[5],
			'container_on' => $container_on,
		);

		foreach ( $palette as $key => $value ) {
			$palette[ $key ] = is_string( $value ) ? $value : '';
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
	 * @param float   $min_l  Minimum lightness.
	 * @param float   $max_l  Maximum lightness.
	 * @return string[] Hex colors including the start color.
	 */
	function prismleaf_build_lightness_ramp( $start_hex, $deltas, $min_l = 0.01, $max_l = 0.99 ) {
		$start_hex = sanitize_hex_color( $start_hex );

		if ( ! $start_hex || ! is_array( $deltas ) ) {
			return array();
		}

		$min_l = prismleaf_clamp_float( (float) $min_l, 0.0, 1.0 );
		$max_l = prismleaf_clamp_float( (float) $max_l, 0.0, 1.0 );

		$ramp = array( $start_hex );
		$current = $start_hex;

		foreach ( $deltas as $delta ) {
			$next = prismleaf_adjust_lightness_safe( $current, (float) $delta, $min_l, $max_l );
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

		$palette = prismleaf_generate_palette_from_base( $base_hex );
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

