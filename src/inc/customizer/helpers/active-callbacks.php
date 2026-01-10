<?php
/**
 * Customizer active callback helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_is_footer_layout_control_active' ) ) {
	/**
	 * Check if footer layout controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_footer_layout_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_footer_show', true );
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
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_header_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_sidebar_primary_layout_control_active' ) ) {
	/**
	 * Check if primary sidebar layout controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_sidebar_primary_layout_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_sidebar_secondary_layout_control_active' ) ) {
	/**
	 * Check if secondary sidebar layout controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_sidebar_secondary_layout_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_show', true );
	}
}
