<?php
/**
 * Customizer active callback helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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

