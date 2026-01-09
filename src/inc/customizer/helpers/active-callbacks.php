<?php
/**
 * Customizer active callback helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_is_unframed_layout_active' ) ) {
	/**
	 * Check if the framed layout is disabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_unframed_layout_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
	}
}

if ( ! function_exists( 'prismleaf_is_header_shown' ) ) {
	/**
	 * Check if the header is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_shown() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_header_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_header_layout_control_active' ) ) {
	/**
	 * Check if header layout controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_layout_control_active() {
		return prismleaf_is_unframed_layout_active() && prismleaf_is_header_shown();
	}
}
