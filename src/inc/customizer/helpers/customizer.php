<?php
/**
 * Customizer utilities.
 *
 * Helpers for reading Customizer values (including live-preview values) and
 * sanitization callbacks used by Customizer settings.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Get a boolean Customizer value, honoring live-preview when available.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $manager Customizer manager instance.
 * @param string              $setting_id Setting ID.
 * @param bool                $default Default value when unset.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_get_bool' ) ) {
	function prismleaf_customize_get_bool( $manager, $setting_id, $default = false ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return (bool) $default;
		}

		$setting = $manager->get_setting( $setting_id );

		if ( ! $setting ) {
			return (bool) $default;
		}

		$value = $setting->post_value( null );

		if ( null === $value ) {
			return (bool) get_theme_mod( $setting_id, $default );
		}

		return (bool) wp_validate_boolean( $value );
	}
}

/**
 * Get a string Customizer value, honoring live-preview when available.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $manager Customizer manager instance.
 * @param string              $setting_id Setting ID.
 * @param string              $default Default value when unset.
 * @return string
 */
if ( ! function_exists( 'prismleaf_customize_get_string' ) ) {
	function prismleaf_customize_get_string( $manager, $setting_id, $default = '' ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return (string) $default;
		}

		$setting = $manager->get_setting( $setting_id );

		if ( ! $setting ) {
			return (string) $default;
		}

		$value = $setting->post_value( null );

		if ( null === $value ) {
			return (string) get_theme_mod( $setting_id, $default );
		}

		return (string) $value;
	}
}

/**
 * Get a setting value directly (raw), honoring live-preview when available.
 *
 * This is useful for settings where the value type is not strictly boolean/string.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $manager Customizer manager instance.
 * @param string              $setting_id Setting ID.
 * @param mixed               $default Default value when unset.
 * @return mixed
 */
if ( ! function_exists( 'prismleaf_customize_get_value' ) ) {
	function prismleaf_customize_get_value( $manager, $setting_id, $default = null ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return $default;
		}

		$setting = $manager->get_setting( $setting_id );

		if ( ! $setting ) {
			return $default;
		}

		$value = $setting->post_value( null );

		if ( null === $value ) {
			return get_theme_mod( $setting_id, $default );
		}

		return $setting->post_value( null );
	}
}

/**
 * Whether all layout regions are hidden (effective).
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $manager Customizer manager instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_all_regions_hidden' ) ) {
	function prismleaf_customize_all_regions_hidden( $manager ) {
		$regions = array(
			'prismleaf_layout_header_visible',
			'prismleaf_layout_footer_visible',
			'prismleaf_layout_sidebar_left_visible',
			'prismleaf_layout_sidebar_right_visible',
		);

		foreach ( $regions as $setting_id ) {
			if ( prismleaf_customize_get_bool( $manager, $setting_id, true ) ) {
				return false;
			}
		}

		return true;
	}
}

/**
 * Sanitize a boolean (checkbox) setting.
 *
 * @since 1.0.0
 *
 * @param mixed $value Value to sanitize.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_sanitize_checkbox' ) ) {
	function prismleaf_sanitize_checkbox( $value ) {
		return (bool) wp_validate_boolean( $value );
	}
}

/**
 * Sanitize icon corner style for Site Metadata.
 *
 * @since 1.0.0
 *
 * @param mixed $value Raw value.
 * @return string
 */
if ( ! function_exists( 'prismleaf_sanitize_site_metadata_icon_corners' ) ) {
	function prismleaf_sanitize_site_metadata_icon_corners( $value ) {
		if ( null === $value ) {
			return '';
		}

		$value   = (string) $value;
		$allowed = array( '', 'square', 'circle', 'round' );

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return '';
	}
}

/**
 * Sanitize tagline position for Site Metadata.
 *
 * @since 1.0.0
 *
 * @param mixed $value Raw value.
 * @return string
 */
if ( ! function_exists( 'prismleaf_sanitize_site_metadata_tagline_position' ) ) {
	function prismleaf_sanitize_site_metadata_tagline_position( $value ) {
		if ( null === $value ) {
			return '';
		}

		$value   = (string) $value;
		$allowed = array( '', 'inline', 'below' );

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return '';
	}
}

/**
 * Sanitize icon size for Site Metadata.
 *
 * @since 1.0.0
 *
 * @param mixed $value Raw value.
 * @return string
 */
if ( ! function_exists( 'prismleaf_sanitize_site_metadata_icon_size' ) ) {
	function prismleaf_sanitize_site_metadata_icon_size( $value ) {
		if ( null === $value ) {
			return '';
		}

		$value   = (string) $value;
		$allowed = array( '', 'small', 'medium', 'large' );

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		return '';
	}
}

/**
 * Sanitize a hex color value (with leading #), allowing empty.
 *
 * @since 1.0.0
 *
 * @param string $value Color value.
 * @return string|null
 */
if ( ! function_exists( 'prismleaf_sanitize_hex_color_or_null' ) ) {
	function prismleaf_sanitize_hex_color_or_null( $value ) {
		$value = (string) $value;

		if ( '' === $value ) {
			return null;
		}

		$sanitized = sanitize_hex_color( $value );

		if ( ! $sanitized ) {
			return null;
		}

		return $sanitized;
	}
}

/**
 * Customizer-safe optional hex color sanitizer.
 *
 * Returns an empty string when cleared so the Customizer UI does not flag it
 * as invalid, while still allowing null/invalid to be treated as invalid.
 *
 * @since 1.0.0
 *
 * @param string|null $value Color string.
 * @return string|null
 */
if ( ! function_exists( 'prismleaf_customize_sanitize_optional_hex_color_empty_ok' ) ) {
	function prismleaf_customize_sanitize_optional_hex_color_empty_ok( $value ) {
		if ( null === $value || '' === (string) $value ) {
			return '';
		}

		return prismleaf_sanitize_optional_hex_color( $value );
	}
}

/**
 * Sanitize an elevation value in the range 0–3 (inclusive).
 *
 * Returns null for empty input to indicate "no override".
 *
 * @since 1.0.0
 *
 * @param mixed $value Elevation value.
 * @return int|null
 */
if ( ! function_exists( 'prismleaf_sanitize_elevation_0_3' ) ) {
	function prismleaf_sanitize_elevation_0_3( $value ) {
		if ( null === $value || '' === $value ) {
			return null;
		}

		$value = absint( $value );

		if ( $value < 0 || $value > 3 ) {
			return null;
		}

		return $value;
	}
}

/**
 * Determine whether controls should be active when not all layout regions are hidden.
 *
 * Used as a Customizer `active_callback`.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control The Customizer control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_not_all_hidden' ) ) {
	function prismleaf_customize_callback_not_all_hidden( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return true;
		}

		return ! prismleaf_customize_all_regions_hidden( $control->manager );
	}
}

/**
 * Determine whether the footer visibility controls should be active.
 *
 * Used as a Customizer `active_callback`.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control The Customizer control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_footer_visible' ) ) {
	function prismleaf_customize_callback_footer_visible( $control ) {
		return prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_footer_visible', true );
	}
}

/**
 * Determine whether the header visibility controls should be active.
 *
 * Used as a Customizer `active_callback`.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control The Customizer control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_header_visible' ) ) {
	function prismleaf_customize_callback_header_visible( $control ) {
		return prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_header_visible', true );
	}
}

/**
 * Determine whether the header containment control should be active.
 *
 * Header containment only applies when:
 * - Not all regions are hidden,
 * - The header is visible, and
 * - The layout is not in framed mode.
 *
 * Used as a Customizer `active_callback`.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control The Customizer control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_header_contained_active' ) ) {
	function prismleaf_customize_callback_header_contained_active( $control ) {
		if ( ! prismleaf_customize_callback_not_all_hidden( $control ) ) {
			return false;
		}

		if ( ! prismleaf_customize_callback_header_visible( $control ) ) {
			return false;
		}

		return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_framed', false );
	}
}

/**
 * Determine whether the footer containment control should be active.
 *
 * Footer containment only applies when:
 * - Not all regions are hidden,
 * - The footer is visible, and
 * - The layout is not in framed mode.
 *
 * Used as a Customizer `active_callback`.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control The Customizer control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_footer_contained_active' ) ) {
	function prismleaf_customize_callback_footer_contained_active( $control ) {
		if ( ! prismleaf_customize_callback_not_all_hidden( $control ) ) {
			return false;
		}

		if ( ! prismleaf_customize_callback_footer_visible( $control ) ) {
			return false;
		}

		return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_framed', false );
	}
}

/**
 * Determine whether the sidebar visibility controls should be active.
 *
 * Used as a Customizer `active_callback`.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control The Customizer control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_sidebar_visible' ) ) {
	function prismleaf_customize_callback_sidebar_visible( $control ) {
		$id = (string) $control->id;

		if ( false !== strpos( $id, 'sidebar_left' ) ) {
			return prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_sidebar_left_visible', true );
		}

		if ( false !== strpos( $id, 'sidebar_right' ) ) {
			return prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_sidebar_right_visible', true );
		}

		// If we can't determine which sidebar, fail open (show control).
		return true;
	}
}

/**
 * Determine whether the sidebar containment control should be active.
 *
 * Sidebar containment only applies when:
 * - Not all regions are hidden,
 * - The relevant sidebar is visible, and
 * - The layout is not in framed mode.
 *
 * Used as a Customizer `active_callback`.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Control $control The Customizer control instance.
 * @return bool
 */
if ( ! function_exists( 'prismleaf_customize_callback_sidebar_contained_active' ) ) {
	function prismleaf_customize_callback_sidebar_contained_active( $control ) {
		if ( ! prismleaf_customize_callback_not_all_hidden( $control ) ) {
			return false;
		}

		if ( ! prismleaf_customize_callback_sidebar_visible( $control ) ) {
			return false;
		}

		return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_framed', false );
	}
}

/**
 * Whether all layout regions are hidden (front-end effective).
 *
 * Supports both legacy `prismleaf_layout_hide_*` mods and the current
 * `prismleaf_layout_*_visible` mods. If both exist, `*_visible` wins.
 *
 * @since 1.0.0
 *
 * @return bool
 */
if ( ! function_exists( 'prismleaf_layout_all_regions_hidden' ) ) {
	function prismleaf_layout_all_regions_hidden() {
		$regions = array(
			'header'        => array(
				'visible' => 'prismleaf_layout_header_visible',
				'hide'    => 'prismleaf_layout_hide_header',
				'default' => true,
			),
			'footer'        => array(
				'visible' => 'prismleaf_layout_footer_visible',
				'hide'    => 'prismleaf_layout_hide_footer',
				'default' => true,
			),
			'sidebar_left'  => array(
				'visible' => 'prismleaf_layout_sidebar_left_visible',
				'hide'    => 'prismleaf_layout_hide_sidebar_left',
				'default' => true,
			),
			'sidebar_right' => array(
				'visible' => 'prismleaf_layout_sidebar_right_visible',
				'hide'    => 'prismleaf_layout_hide_sidebar_right',
				'default' => true,
			),
		);

		foreach ( $regions as $region ) {
			// Prefer the current `*_visible` setting if it has been saved.
			$visible_value = get_theme_mod( $region['visible'], null );
			if ( null !== $visible_value ) {
				if ( (bool) wp_validate_boolean( $visible_value ) ) {
					return false;
				}
				continue;
			}

			// Fall back to legacy `hide_*` if present.
			$hide_value = get_theme_mod( $region['hide'], null );
			if ( null !== $hide_value ) {
				if ( ! (bool) wp_validate_boolean( $hide_value ) ) {
					return false;
				}
				continue;
			}

			// If neither mod exists, assume default visibility (not hidden).
			if ( (bool) $region['default'] ) {
				return false;
			}
		}

		return true;
	}
}

/**
 * Sanitize header height (px), allowing 0 for "auto".
 *
 * @since 1.0.0
 *
 * @param mixed $value Height value.
 * @return int
 */
if ( ! function_exists( 'prismleaf_sanitize_header_height' ) ) {
	function prismleaf_sanitize_header_height( $value ) {
		if ( '' !== (string) $value && ! is_numeric( $value ) ) {
		}

		$value = absint( $value );

		// 0 means "auto".
		if ( 0 === $value ) {
			return 0;
		}

		// Defensive clamp.
		return max( 16, min( 240, $value ) );
	}
}

/**
 * Sanitize footer height (px), allowing 0 for "auto".
 *
 * @since 1.0.0
 *
 * @param mixed $value Height value.
 * @return int
 */
if ( ! function_exists( 'prismleaf_sanitize_footer_height' ) ) {
	function prismleaf_sanitize_footer_height( $value ) {
		if ( '' !== (string) $value && ! is_numeric( $value ) ) {
		}

		$value = absint( $value );

		// 0 means "auto".
		if ( 0 === $value ) {
			return 0;
		}

		// Defensive clamp.
		return max( 16, min( 320, $value ) );
	}
}

/**
 * Sanitize sidebar width (px).
 *
 * @since 1.0.0
 *
 * @param mixed $value Width value.
 * @return int
 */
if ( ! function_exists( 'prismleaf_sanitize_sidebar_width' ) ) {
	function prismleaf_sanitize_sidebar_width( $value ) {
		if ( '' !== (string) $value && ! is_numeric( $value ) ) {
		}

		$value = absint( $value );

		// Defensive clamp.
		return max( 200, min( 400, $value ) );
	}
}

/**
 * Sanitize a border style value.
 *
 * @since 1.0.0
 *
 * @param mixed $value Border style.
 * @return string
 */
if ( ! function_exists( 'prismleaf_sanitize_border_style' ) ) {
	function prismleaf_sanitize_border_style( $value ) {
		$value = (string) $value;

		$allowed = array(
			'none',
			'solid',
			'dashed',
			'dotted',
			'double',
		);

		if ( in_array( $value, $allowed, true ) ) {
			return $value;
		}

		// Default to a safe, predictable value.
		return 'solid';
	}
}
