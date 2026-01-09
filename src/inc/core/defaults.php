<?php
/**
 * Theme defaults.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_get_default_options' ) ) {
	/**
	 * Return default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,mixed>
	 */
	function prismleaf_get_default_options() {
		$defaults = array(
			'global_framed_layout'        => false,
			'header_show'                 => true,
			'header_contained'            => true,
			'header_floating'             => true,
			'footer_show'                 => true,
			'footer_contained'            => true,
			'footer_floating'             => true,
			'sidebar_swap'                => false,
			'sidebar_primary_show'        => true,
			'sidebar_primary_contained'   => true,
			'sidebar_primary_floating'    => true,
			'sidebar_secondary_show'      => true,
			'sidebar_secondary_contained' => true,
			'sidebar_secondary_floating'  => true,
		);

		return apply_filters( 'prismleaf_default_options', $defaults );
	}
}

if ( ! function_exists( 'prismleaf_get_default_option' ) ) {
	/**
	 * Get a default option by key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @param mixed  $fallback Fallback value if missing.
	 * @return mixed
	 */
	function prismleaf_get_default_option( $key, $fallback = null ) {
		$defaults = prismleaf_get_default_options();

		if ( array_key_exists( $key, $defaults ) ) {
			return $defaults[ $key ];
		}

		return $fallback;
	}
}
