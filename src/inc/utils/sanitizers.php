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
