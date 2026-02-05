<?php
/**
 * Data helpers.
 *
 * Shared helpers for structured data handling.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_decode_json_with_keys' ) ) {
	/**
	 * Decode JSON and validate required keys.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed    $value         JSON value to decode.
	 * @param string[] $expected_keys Required keys.
	 * @return array<string,mixed>|null
	 */
	function prismleaf_decode_json_with_keys( $value, $expected_keys ) {
		if ( ! is_array( $expected_keys ) || empty( $expected_keys ) ) {
			return null;
		}

		$decoded = json_decode( (string) $value, true );
		if ( ! is_array( $decoded ) || count( $decoded ) !== count( $expected_keys ) ) {
			return null;
		}

		foreach ( $expected_keys as $key ) {
			if ( ! array_key_exists( $key, $decoded ) ) {
				return null;
			}
		}

		if ( array_diff( array_keys( $decoded ), $expected_keys ) ) {
			return null;
		}

		return $decoded;
	}
}
