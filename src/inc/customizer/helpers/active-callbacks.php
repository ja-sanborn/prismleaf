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

if ( ! function_exists( 'prismleaf_is_header_icon_visible' ) ) {
	/**
	 * Check whether the header icon should be rendered.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_icon_visible() {
		return 'none' !== prismleaf_get_theme_mod_header_icon_position();
	}
}

if ( ! function_exists( 'prismleaf_is_header_icon_control_active' ) ) {
	/**
	 * Check whether header icon controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_icon_control_active() {
		return prismleaf_is_header_control_active() && prismleaf_is_header_icon_visible();
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

if ( ! function_exists( 'prismleaf_is_header_tagline_active' ) ) {
	/**
	 * Determine whether the tagline is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_tagline_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_header_show_tagline', true );
	}
}

if ( ! function_exists( 'prismleaf_is_header_tagline_control_active' ) ) {
	/**
	 * Check whether tagline controls should be visible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_header_tagline_control_active() {
		return prismleaf_is_header_control_active() && prismleaf_is_header_tagline_active();
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

if ( ! function_exists( 'prismleaf_is_primary_menu_button_corners_active' ) ) {
	/**
	 * Show primary menu button corners control when the strip is disabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_primary_menu_button_corners_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_menu_primary_strip', true );
	}
}

if ( ! function_exists( 'prismleaf_is_primary_menu_divider_active' ) ) {
	/**
	 * Show primary menu divider control when the strip is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_primary_menu_divider_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_menu_primary_strip', true );
	}
}

if ( ! function_exists( 'prismleaf_is_secondary_menu_button_corners_active' ) ) {
	/**
	 * Show secondary menu button corners control when the strip is disabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_secondary_menu_button_corners_active() {
		return ! prismleaf_get_theme_mod_bool( 'prismleaf_menu_secondary_strip', true );
	}
}

if ( ! function_exists( 'prismleaf_is_secondary_menu_divider_active' ) ) {
	/**
	 * Show secondary menu divider control when the strip is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_secondary_menu_divider_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_menu_secondary_strip', true );
	}
}

if ( ! function_exists( 'prismleaf_is_mobile_menu_divider_active' ) ) {
	/**
	 * Show mobile divider control when the panel is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function prismleaf_is_mobile_menu_divider_active() {
		return prismleaf_get_theme_mod_bool( 'prismleaf_menu_mobile_panel', true );
	}
}
