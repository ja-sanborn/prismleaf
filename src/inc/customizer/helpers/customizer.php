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

if ( ! function_exists( 'prismleaf_customize_get_bool' ) ) {
	/**
	 * Get a boolean Customizer value, honoring live-preview when available.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager       Customizer manager instance.
	 * @param string               $setting_id    Setting ID.
	 * @param bool                 $default_value Default value when unset.
	 * @return bool
	 */
	function prismleaf_customize_get_bool( $manager, $setting_id, $default_value = false ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return (bool) $default_value;
		}

		$setting = $manager->get_setting( $setting_id );

		if ( ! $setting ) {
			return (bool) $default_value;
		}

		$value = $setting->post_value( null );

		if ( null === $value ) {
			return (bool) get_theme_mod( $setting_id, $default_value );
		}

		return (bool) wp_validate_boolean( $value );
	}
}

if ( ! function_exists( 'prismleaf_customize_get_string' ) ) {
	/**
	 * Get a string Customizer value, honoring live-preview when available.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager       Customizer manager instance.
	 * @param string               $setting_id    Setting ID.
	 * @param string               $default_value Default value when unset.
	 * @return string
	 */
	function prismleaf_customize_get_string( $manager, $setting_id, $default_value = '' ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return (string) $default_value;
		}

		$setting = $manager->get_setting( $setting_id );

		if ( ! $setting ) {
			return (string) $default_value;
		}

		$value = $setting->post_value( null );

		if ( null === $value ) {
			return (string) get_theme_mod( $setting_id, $default_value );
		}

		return (string) $value;
	}
}

if ( ! function_exists( 'prismleaf_customize_get_value' ) ) {
	/**
	 * Get a setting value directly (raw), honoring live-preview when available.
	 *
	 * This is useful for settings where the value type is not strictly boolean/string.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager       Customizer manager instance.
	 * @param string               $setting_id    Setting ID.
	 * @param mixed                $default_value Default value when unset.
	 * @return mixed
	 */
	function prismleaf_customize_get_value( $manager, $setting_id, $default_value = null ) {
		if ( ! ( $manager instanceof WP_Customize_Manager ) ) {
			return $default_value;
		}

		$setting = $manager->get_setting( $setting_id );

		if ( ! $setting ) {
			return $default_value;
		}

		$value = $setting->post_value( null );

		if ( null === $value ) {
			return get_theme_mod( $setting_id, $default_value );
		}

		return $setting->post_value( null );
	}
}

if ( ! function_exists( 'prismleaf_customize_all_regions_hidden' ) ) {
	/**
	 * Whether all layout regions are hidden (effective).
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager Customizer manager instance.
	 * @return bool
	 */
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

if ( ! function_exists( 'prismleaf_sanitize_checkbox' ) ) {
	/**
	 * Sanitize a boolean (checkbox) setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return bool
	 */
	function prismleaf_sanitize_checkbox( $value ) {
		return (bool) wp_validate_boolean( $value );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_site_metadata_icon_corners' ) ) {
	/**
	 * Sanitize icon corner style for Site Metadata.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
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

if ( ! function_exists( 'prismleaf_sanitize_site_metadata_tagline_position' ) ) {
	/**
	 * Sanitize tagline position for Site Metadata.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
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

if ( ! function_exists( 'prismleaf_sanitize_site_metadata_icon_size' ) ) {
	/**
	 * Sanitize icon size for Site Metadata.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Raw value.
	 * @return string
	 */
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

if ( ! function_exists( 'prismleaf_sanitize_hex_color_or_null' ) ) {
	/**
	 * Sanitize a hex color value (with leading #), allowing empty.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Color value.
	 * @return string|null
	 */
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

if ( ! function_exists( 'prismleaf_customize_sanitize_optional_hex_color_empty_ok' ) ) {
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
	function prismleaf_customize_sanitize_optional_hex_color_empty_ok( $value ) {
		if ( null === $value || '' === (string) $value ) {
			return '';
		}

		return prismleaf_sanitize_optional_hex_color( $value );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_elevation_0_3' ) ) {
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

if ( ! function_exists( 'prismleaf_customize_callback_not_all_hidden' ) ) {
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
	function prismleaf_customize_callback_not_all_hidden( $control ) {
		if ( ! ( $control instanceof WP_Customize_Control ) ) {
			return true;
		}

		return ! prismleaf_customize_all_regions_hidden( $control->manager );
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_footer_visible' ) ) {
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
	function prismleaf_customize_callback_footer_visible( $control ) {
		return prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_footer_visible', true );
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_header_visible' ) ) {
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
	function prismleaf_customize_callback_header_visible( $control ) {
		return prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_header_visible', true );
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_header_contained_active' ) ) {
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

if ( ! function_exists( 'prismleaf_customize_callback_header_floating_active' ) ) {
	/**
	 * Determine whether the header floating control should be active.
	 *
	 * Header floating only applies when:
	 * - The header is visible, and
	 * - The header is not contained.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control The Customizer control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_header_floating_active( $control ) {
		if ( ! prismleaf_customize_callback_header_visible( $control ) ) {
			return false;
		}

		return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_header_contained', true );
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_footer_contained_active' ) ) {
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

if ( ! function_exists( 'prismleaf_customize_callback_footer_floating_active' ) ) {
	/**
	 * Determine whether the footer floating control should be active.
	 *
	 * Footer floating only applies when:
	 * - The footer is visible, and
	 * - The footer is not contained.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control The Customizer control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_footer_floating_active( $control ) {
		if ( ! prismleaf_customize_callback_footer_visible( $control ) ) {
			return false;
		}

		return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_footer_contained', true );
	}
}

if ( ! function_exists( 'prismleaf_customize_callback_sidebar_visible' ) ) {
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

if ( ! function_exists( 'prismleaf_customize_callback_sidebar_contained_active' ) ) {
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

if ( ! function_exists( 'prismleaf_customize_callback_sidebar_floating_active' ) ) {
	/**
	 * Determine whether the sidebar floating control should be active.
	 *
	 * Sidebar floating only applies when:
	 * - The sidebar is visible, and
	 * - The sidebar is not contained.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control The Customizer control instance.
	 * @return bool
	 */
	function prismleaf_customize_callback_sidebar_floating_active( $control ) {
		if ( ! prismleaf_customize_callback_sidebar_visible( $control ) ) {
			return false;
		}

		$id = (string) $control->id;

		if ( false !== strpos( $id, 'sidebar_left' ) ) {
			return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_sidebar_left_contained', true );
		}

		if ( false !== strpos( $id, 'sidebar_right' ) ) {
			return ! prismleaf_customize_get_bool( $control->manager, 'prismleaf_layout_sidebar_right_contained', true );
		}

		return true;
	}
}

if ( ! function_exists( 'prismleaf_layout_all_regions_hidden' ) ) {
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

if ( ! function_exists( 'prismleaf_sanitize_header_height' ) ) {
	/**
	 * Sanitize header height (px), allowing 0 for "auto".
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Height value.
	 * @return int
	 */
	function prismleaf_sanitize_header_height( $value ) {
		if ( '' === (string) $value ) {
			return '';
		}

		if ( ! is_numeric( $value ) ) {
			return '';
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

if ( ! function_exists( 'prismleaf_sanitize_footer_height' ) ) {
	/**
	 * Sanitize footer height (px), allowing 0 for "auto".
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Height value.
	 * @return int
	 */
	function prismleaf_sanitize_footer_height( $value ) {
		if ( '' === (string) $value ) {
			return '';
		}

		if ( ! is_numeric( $value ) ) {
			return '';
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

if ( ! function_exists( 'prismleaf_sanitize_sidebar_width' ) ) {
	/**
	 * Sanitize sidebar width (px).
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Width value.
	 * @return int
	 */
	function prismleaf_sanitize_sidebar_width( $value ) {
		if ( '' === (string) $value ) {
			return 300;
		}

		if ( ! is_numeric( $value ) ) {
			return 300;
		}

		$value = absint( $value );

		// Defensive clamp.
		return max( 200, min( 400, $value ) );
	}
}

if ( ! function_exists( 'prismleaf_sanitize_border_style' ) ) {
	/**
	 * Sanitize a border style value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Border style.
	 * @return string
	 */
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
