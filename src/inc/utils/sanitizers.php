<?php
/**
 * Sanitization helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_clamp_int' ) ) {
	/**
	 * Clamp a value to an integer range.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to clamp.
	 * @param int   $min   Minimum value.
	 * @param int   $max   Maximum value.
	 * @return int
	 */
	function prismleaf_clamp_int( $value, $min, $max ) {
		$value = is_numeric( $value ) ? (int) $value : (int) $min;
		$min   = (int) $min;
		$max   = (int) $max;

		if ( $value < $min ) {
			return $min;
		}

		if ( $value > $max ) {
			return $max;
		}

		return $value;
	}
}

if ( ! function_exists( 'prismleaf_clamp_float' ) ) {
	/**
	 * Clamp a value to a float range.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to clamp.
	 * @param float $min   Minimum value.
	 * @param float $max   Maximum value.
	 * @return float
	 */
	function prismleaf_clamp_float( $value, $min, $max ) {
		$value = is_numeric( $value ) ? (float) $value : (float) $min;
		$min   = (float) $min;
		$max   = (float) $max;

		if ( $value < $min ) {
			return $min;
		}

		if ( $value > $max ) {
			return $max;
		}

		return $value;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_boolean' ) ) {
	/**
	 * Sanitize a boolean-like value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return bool
	 */
	function prismleaf_sanitize_boolean( $value ) {
		return (bool) wp_validate_boolean( $value );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_text' ) ) {
	/**
	 * Sanitize a text field value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_text( $value ) {
		return (string) sanitize_text_field( $value );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_int' ) ) {
	/**
	 * Sanitize a whole number value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return int
	 */
	function prismleaf_sanitize_int( $value ) {
		return is_numeric( $value ) ? (int) $value : 0;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_float' ) ) {
	/**
	 * Sanitize a decimal number value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return float
	 */
	function prismleaf_sanitize_float( $value ) {
		return is_numeric( $value ) ? (float) $value : 0.0;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_transport' ) ) {
	/**
	 * Sanitize a Customizer transport value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_transport( $value ) {
		$value = strtolower( trim( (string) $value ) );
		return ( 'postmessage' === $value ) ? 'postMessage' : 'refresh';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_theme_mode' ) ) {
	/**
	 * Sanitize the theme mode override value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_theme_mode( $value ) {
		$value = strtolower( trim( (string) $value ) );
		$allowed = array( 'system', 'light', 'dark' );

		return in_array( $value, $allowed, true ) ? $value : 'system';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_customizer_id' ) ) {
	/**
	 * Sanitize a Customizer ID (section/control/panel).
	 *
	 * Allows alphanumeric, underscore, dash, and square brackets.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_customizer_id( $value ) {
		$value = (string) $value;
		return preg_replace( '/[^a-zA-Z0-9_\\-\\[\\]]/', '', $value );
	}
}

if ( ! function_exists( 'prismleaf_is_rgba_color' ) ) {
	/**
	 * Check whether a value is a valid rgba() color string.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to check.
	 * @return bool
	 */
	function prismleaf_is_rgba_color( $value ) {
		if ( ! is_string( $value ) ) {
			return false;
		}

		$value = trim( $value );
		if ( '' === $value ) {
			return false;
		}

		if ( ! preg_match( '/^rgba\\(\\s*(\\d{1,3})\\s*,\\s*(\\d{1,3})\\s*,\\s*(\\d{1,3})\\s*,\\s*(0|1|0?\\.\\d+)\\s*\\)$/', $value, $matches ) ) {
			return false;
		}

		$r = (int) $matches[1];
		$g = (int) $matches[2];
		$b = (int) $matches[3];
		$a = (float) $matches[4];

		if ( $r < 0 || $r > 255 || $g < 0 || $g > 255 || $b < 0 || $b > 255 ) {
			return false;
		}

		return ( $a >= 0.0 && $a <= 1.0 );
	}
}

if ( ! function_exists( 'prismleaf_is_palette_opacity_key' ) ) {
	/**
	 * Check if a palette key is an opacity-derived value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $key Key to check.
	 * @return bool
	 */
	function prismleaf_is_palette_opacity_key( $key ) {
		if ( ! is_string( $key ) || '' === $key ) {
			return false;
		}

		return in_array(
			$key,
			array(
				'outline',
				'outline_variant',
				'on_surface_muted',
				'disabled_foreground',
				'disabled_surface',
			),
			true
		);
	}
}

if ( ! function_exists( 'prismleaf_sanitize_palette_json' ) ) {
	/**
	 * Sanitize palette JSON for the palette preview control.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_palette_json( $value ) {
		$expected_keys = prismleaf_get_palette_keys();
		if ( ! is_array( $expected_keys ) || empty( $expected_keys ) ) {
			return '';
		}

		$decoded = prismleaf_decode_json_with_keys( $value, $expected_keys );
		if ( null === $decoded ) {
			return '';
		}

		$clean = array();
		foreach ( $expected_keys as $key ) {
			$item = $decoded[ $key ];
			if ( prismleaf_is_palette_opacity_key( $key ) ) {
				if ( ! prismleaf_is_rgba_color( $item ) ) {
					return '';
				}
				$clean[ $key ] = $item;
				continue;
			}

			$hex = sanitize_hex_color( $item );
			if ( ! $hex ) {
				return '';
			}

			$clean[ $key ] = $hex;
		}

		return wp_json_encode( $clean );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_palette_json_from_base' ) ) {
	/**
	 * Sanitize palette JSON, falling back to a computed palette from the base color.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed                $value   Palette JSON value to sanitize.
	 * @param WP_Customize_Setting $setting Setting context.
	 * @return string
	 */
	function prismleaf_sanitize_palette_json_from_base( $value, $setting ) {
		if ( ! ( $setting instanceof WP_Customize_Setting ) ) {
			return '';
		}

		$setting_id = (string) $setting->id;
		if ( '' === $setting_id || ! function_exists( 'prismleaf_build_palette_json_from_base' ) ) {
			return '';
		}

		$suffix = '_values';
		if ( strlen( $setting_id ) <= strlen( $suffix ) || substr( $setting_id, -strlen( $suffix ) ) !== $suffix ) {
			return '';
		}

		$base_id = substr( $setting_id, 0, -strlen( $suffix ) ) . '_base';
		if ( ! $setting->manager ) {
			return '';
		}

		$base_setting = $setting->manager->get_setting( $base_id );
		if ( ! $base_setting ) {
			return '';
		}

		$base_value = $base_setting->post_value();
		if ( null === $base_value ) {
			$base_value = $base_setting->value();
		}

		$base_hex = sanitize_hex_color( $base_value );
		if ( $base_hex ) {
			$computed = prismleaf_build_palette_json_from_base( $base_hex );
			return is_string( $computed ) ? $computed : '';
		}

		$clean = prismleaf_sanitize_palette_json( $value );
		return $clean;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_palette_source' ) ) {
	/**
	 * Sanitize a palette source selection.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_palette_source( $value ) {
		$value = strtolower( trim( (string) $value ) );
		$allowed = array(
			'default',
			'primary',
			'secondary',
			'tertiary',
			'error',
			'warning',
			'info',
			'neutral_light',
			'neutral_dark',
			'custom',
		);

		return in_array( $value, $allowed, true ) ? $value : 'default';
	}
}

if ( ! function_exists( 'prismleaf_is_css_var_name' ) ) {
	/**
	 * Check if a string looks like a Prismleaf CSS variable name.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to check.
	 * @return bool
	 */
	function prismleaf_is_css_var_name( $value ) {
		if ( ! is_string( $value ) || '' === $value ) {
			return false;
		}

		return 1 === preg_match( '/^--prismleaf-color-[a-z0-9_-]+$/', $value );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_palette_source_json' ) ) {
	/**
	 * Sanitize palette JSON that can contain hex values or CSS var names.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_palette_source_json( $value ) {
		$expected_keys = prismleaf_get_palette_keys();
		if ( ! is_array( $expected_keys ) || empty( $expected_keys ) ) {
			return '';
		}

		$decoded = prismleaf_decode_json_with_keys( $value, $expected_keys );
		if ( null === $decoded ) {
			return '';
		}

		$clean = array();
		foreach ( $expected_keys as $key ) {
			$item = $decoded[ $key ];
			if ( prismleaf_is_css_var_name( $item ) ) {
				$clean[ $key ] = $item;
				continue;
			}

			if ( prismleaf_is_palette_opacity_key( $key ) ) {
				if ( ! prismleaf_is_rgba_color( $item ) ) {
					return '';
				}
				$clean[ $key ] = $item;
				continue;
			}

			$hex = sanitize_hex_color( $item );
			if ( ! $hex ) {
				return '';
			}

			$clean[ $key ] = $hex;
		}

		return wp_json_encode( $clean );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_frame_max_width' ) ) {
	/**
	 * Sanitize frame max width (1000-2000).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_frame_max_width( $value ) {
		if ( ! is_numeric( $value ) ) {
			return '1480';
		}

		$value = (int) $value;
		if ( $value < 1000 ) {
			$value = 1000;
		}
		if ( $value > 2000 ) {
			$value = 2000;
		}

		return (string) $value;
	}
}

if ( ! function_exists( 'prismleaf_sanitize_frame_border_corners' ) ) {
	/**
	 * Sanitize frame border corners.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_frame_border_corners( $value ) {
		$value = trim( (string) $value );
		$allowed = array( 'Square', 'Round' );

		return in_array( $value, $allowed, true ) ? $value : 'Round';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_frame_border_style' ) ) {
	/**
	 * Sanitize frame border style.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_frame_border_style( $value ) {
		$value = strtolower( trim( (string) $value ) );
		$allowed = array( 'none', 'solid', 'dotted', 'dashed' );

		return in_array( $value, $allowed, true ) ? $value : 'solid';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_size_value' ) ) {
	/**
	 * Sanitize a size value with px or numeric fallback.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value   Value to sanitize.
	 * @param mixed $default Default value to use when value fails.
	 * @return string
	 */
	function prismleaf_sanitize_size_value( $value, $default ) {
		$pick_value = static function ( $candidate ) {
			if ( is_string( $candidate ) ) {
				$trimmed = trim( $candidate );
				$lower = strtolower( $trimmed );
				if ( strlen( $lower ) > 2 && 'px' === substr( $lower, -2 ) ) {
					$number = trim( substr( $lower, 0, -2 ) );
					if ( '' !== $number && is_numeric( $number ) ) {
						return $trimmed;
					}
				}
			}

			if ( is_numeric( $candidate ) ) {
				return (string) $candidate;
			}

			return '';
		};

		$selected = $pick_value( $value );
		if ( '' === $selected ) {
			$selected = $pick_value( $default );
		}

		if ( '' === $selected ) {
			return '';
		}

		$lower = strtolower( $selected );
		if ( strlen( $lower ) > 2 && 'px' === substr( $lower, -2 ) ) {
			return $selected;
		}

		return rtrim( $selected, ' ' ) . 'px';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_palette_value' ) ) {
	/**
	 * Sanitize a palette value, supporting CSS var names and color strings.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value   Value to sanitize.
	 * @param mixed $default Default value when value is invalid.
	 * @return string
	 */
	function prismleaf_sanitize_palette_value( $value, $default = '' ) {
		$sanitize_value = static function ( $candidate ) {
			if ( ! is_string( $candidate ) ) {
				return '';
			}

			$trimmed = trim( $candidate );
			if ( '' === $trimmed ) {
				return '';
			}

			if ( 0 === strpos( $trimmed, '--prismleaf-color-' ) ) {
				return $trimmed;
			}

			if ( function_exists( 'prismleaf_is_rgba_color' ) && prismleaf_is_rgba_color( $trimmed ) ) {
				return $trimmed;
			}

			$hex = sanitize_hex_color( $trimmed );
			return $hex ? $hex : '';
		};

		$sanitized = $sanitize_value( $value );
		if ( '' !== $sanitized ) {
			return $sanitized;
		}

		return $sanitize_value( $default );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_elevation_value' ) ) {
	/**
	 * Sanitize an elevation token into a CSS value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @param mixed $default Default value when value is invalid.
	 * @return string
	 */
	function prismleaf_sanitize_elevation_value( $value, $default ) {
		$pick_value = static function ( $candidate ) {
			if ( ! is_string( $candidate ) ) {
				return '';
			}

			$clean = strtolower( trim( $candidate ) );
			if ( '' === $clean ) {
				return '';
			}
			if ( 'none' === $clean ) {
				return 'none';
			}

			if ( preg_match( '/^elevation-([1-5])$/', $clean, $matches ) ) {
				return '--prismleaf-shadow-elevation-' . $matches[1];
			}

			return '';
		};

		$selected = $pick_value( $value );
		if ( '' === $selected ) {
			$selected = $pick_value( $default );
		}

		return '' !== $selected ? $selected : 'none';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_frame_elevation' ) ) {
	/**
	 * Sanitize frame elevation.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return string
	 */
	function prismleaf_sanitize_frame_elevation( $value ) {
		$value = strtolower( trim( (string) $value ) );
		$allowed = array(
			'none',
			'elevation-1',
			'elevation-2',
			'elevation-3',
			'elevation-4',
			'elevation-5',
		);

		return in_array( $value, $allowed, true ) ? $value : 'none';
	}
}

if ( ! function_exists( 'prismleaf_sanitize_json' ) ) {
	/**
	 * Sanitize JSON with an expected key list.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed    $value         JSON value to sanitize.
	 * @param string[] $expected_keys Required keys.
	 * @return string
	 */
	function prismleaf_sanitize_json( $value, $expected_keys ) {
		if ( ! is_array( $expected_keys ) || empty( $expected_keys ) ) {
			return '';
		}

		$decoded = prismleaf_decode_json_with_keys( $value, $expected_keys );
		if ( null === $decoded ) {
			return '';
		}

		$clean = array();
		foreach ( $expected_keys as $key ) {
			$clean_value = sanitize_hex_color( $decoded[ $key ] );
			if ( ! $clean_value ) {
				return '';
			}

			$clean[ $key ] = $clean_value;
		}

		return wp_json_encode( $clean );
	}
}
