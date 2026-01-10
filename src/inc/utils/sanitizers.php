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
		return prismleaf_sanitize_json( $value, $expected_keys );
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
