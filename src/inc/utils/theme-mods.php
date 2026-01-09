<?php
/**
 * Theme mod helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
		return prismleaf_sanitize_boolean( get_theme_mod( $setting_id, $default_value ) );
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
		return prismleaf_sanitize_text( get_theme_mod( $setting_id, $default_value ) );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_theme_mode' ) ) {
	/**
	 * Get the theme mode override value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_value Default value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_theme_mode( $setting_id, $default_value = 'system' ) {
		return prismleaf_sanitize_theme_mode( get_theme_mod( $setting_id, $default_value ) );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_palette_json' ) ) {
	/**
	 * Get a palette JSON theme mod value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_value Default value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_palette_json( $setting_id, $default_value = '' ) {
		$value = prismleaf_sanitize_palette_json( get_theme_mod( $setting_id, $default_value ) );
		return is_string( $value ) ? $value : '';
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_neutral_json' ) ) {
	/**
	 * Get a neutral JSON theme mod value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_value Default value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_neutral_json( $setting_id, $default_value = '' ) {
		$value = prismleaf_sanitize_neutral_json( get_theme_mod( $setting_id, $default_value ) );
		return is_string( $value ) ? $value : '';
	}
}
