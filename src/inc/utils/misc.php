<?php
/**
 * Miscellaneous utilities.
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

if ( ! function_exists( 'prismleaf_get_theme_mod_bool' ) ) {
	/**
	 * Get a boolean theme mod value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param bool   $default_value Default value.
	 * @return bool
	 */
	function prismleaf_get_theme_mod_bool( $setting_id, $default_value = false ) {
		return (bool) wp_validate_boolean( get_theme_mod( $setting_id, $default_value ) );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_string' ) ) {
	/**
	 * Get a string theme mod value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_value Default value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_string( $setting_id, $default_value = '' ) {
		$value = get_theme_mod( $setting_id, $default_value );

		return (string) $value;
	}
}

if ( ! function_exists( 'prismleaf_header_has_background_image' ) ) {
	/**
	 * Determine whether the header has a background image set.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_header_has_background_image() {
		$value = prismleaf_get_theme_mod_string( 'prismleaf_header_background_image', '' );

		return '' !== trim( $value );
	}
}
