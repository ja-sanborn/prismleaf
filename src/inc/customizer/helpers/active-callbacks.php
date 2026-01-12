<?php
/**
 * Customizer active callback helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_is_footer_control_active' ) ) {
	/**
	 * Check if footer controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_footer_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_footer_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_footer_background_control_active' ) ) {
	/**
	 * Check if footer background controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_footer_background_control_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_footer_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_header_control_active' ) ) {
	/**
	 * Check if header controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_header_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_header_background_control_active' ) ) {
	/**
	 * Check if header background controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_background_control_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_header_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_sidebar_primary_control_active' ) ) {
	/**
	 * Check if primary sidebar controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_sidebar_primary_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_sidebar_primary_background_control_active' ) ) {
	/**
	 * Check if primary sidebar background controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_sidebar_primary_background_control_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_sidebar_secondary_control_active' ) ) {
	/**
	 * Check if secondary sidebar controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_sidebar_secondary_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_sidebar_secondary_background_control_active' ) ) {
	/**
	 * Check if secondary sidebar background controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_sidebar_secondary_background_control_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_show', true );
	}
}

if ( ! function_exists( 'prismleaf_is_frame_layout_control_active' ) ) {
	/**
	 * Check if frame layout controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_frame_layout_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
	}
}

if ( ! function_exists( 'prismleaf_is_frame_background_control_active' ) ) {
	/**
	 * Check if frame background controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_frame_background_control_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false )
			&& prismleaf_get_theme_mod_bool( 'prismleaf_frame_show_background', true );
	}
}
