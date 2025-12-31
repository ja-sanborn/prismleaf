<?php
/**
 * Miscellaneous utilities.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
if ( ! function_exists( 'prismleaf_clamp_int' ) ) {
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
