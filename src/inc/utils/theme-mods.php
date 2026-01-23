<?php
/**
 * Theme mod helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_get_theme_mod' ) ) {
	/**
	 * Wrap get_theme_mod so blank strings fall back to the default.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod identifier.
	 * @param mixed  $default_value Default value when the stored value is empty.
	 * @return mixed
	 */
	function prismleaf_get_theme_mod( $setting_id, $default_value = '' ) {
		$value = get_theme_mod( $setting_id, $default_value );

		if ( is_string( $value ) ) {
			$trimmed = trim( $value );
			if ( '' === $trimmed ) {
				return $default_value;
			}

			return $trimmed;
		}

		return $value;
	}
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
		return prismleaf_sanitize_boolean( prismleaf_get_theme_mod( $setting_id, $default_value ) );
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
		return prismleaf_sanitize_text( prismleaf_get_theme_mod( $setting_id, $default_value ) );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_alignment' ) ) {
	/**
	 * Get a validated alignment string from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_value Default value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_alignment( $setting_id, $default_value = 'center' ) {
		$setting_id = prismleaf_sanitize_text( $setting_id );

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_footer_widget_alignment( $default_value );
		}

		$value = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_footer_widget_alignment( $value );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_widget_title_alignment' ) ) {
	/**
	 * Get a validated widget title alignment string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_value Default value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_widget_title_alignment( $setting_id, $default_value = 'left' ) {
		return prismleaf_sanitize_widget_title_alignment( prismleaf_get_theme_mod( $setting_id, $default_value ) );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_theme_mode' ) ) {
	/**
	 * Get the theme mode override value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_value Default value when the option is missing.
	 * @return string
	 */
	function prismleaf_get_theme_mod_theme_mode( $setting_id, $default_key = '', $default_value = 'system' ) {
		$setting_id = prismleaf_sanitize_text( $setting_id );
		$default_key = prismleaf_sanitize_text( $default_key );
		$default_value = prismleaf_sanitize_theme_mode( $default_value );

		if ( '' !== $default_key ) {
			$default_value = prismleaf_get_default_option( $default_key, $default_value );
		}

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_theme_mode( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_theme_mode( $raw );
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
		$value = prismleaf_sanitize_palette_json( prismleaf_get_theme_mod( $setting_id, $default_value ) );
		return is_string( $value ) ? $value : '';
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_palette_source_value' ) ) {
	/**
	 * Get a palette source value (hex/rgba/CSS var) from a palette source control.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $key           Palette key to read.
	 * @param string $default_value Default value when missing.
	 * @return string
	 */
	function prismleaf_get_theme_mod_palette_source_value( $setting_id, $key, $default_value = '' ) {
		$setting_id = prismleaf_sanitize_text( $setting_id );
		$key = prismleaf_sanitize_text( $key );

		if ( '' === $setting_id || '' === $key ) {
			return prismleaf_sanitize_palette_value( $default_value );
		}

		$expected_keys = prismleaf_get_palette_keys();
		if ( empty( $expected_keys ) || ! in_array( $key, $expected_keys, true ) ) {
			return prismleaf_sanitize_palette_value( $default_value );
		}

		$palette = prismleaf_sanitize_palette_source_json( prismleaf_get_theme_mod( $setting_id, '' ) );
		$values = prismleaf_decode_json_with_keys( $palette, $expected_keys );
		$value = ( is_array( $values ) && isset( $values[ $key ] ) ) ? $values[ $key ] : '';

		return prismleaf_sanitize_palette_value( $value, $default_value );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_elevation_value' ) ) {
	/**
	 * Get the frame elevation CSS value from theme mods and defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_elevation_value( $setting_id, $default_key, $default_fallback = '' ) {
		$setting_id = prismleaf_sanitize_text( $setting_id );
		$default_key = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key )
			? prismleaf_get_default_option( $default_key, $default_fallback )
			: $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_elevation_value( '', $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		$token = prismleaf_sanitize_frame_elevation( $raw );

		return prismleaf_sanitize_elevation_value( $token, $default_value );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_border_radius_value' ) ) {
	/**
	 * Get the frame border radius CSS value from theme mods and defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_border_radius_value( $setting_id, $default_key, $default_fallback = '' ) {
		$setting_id = prismleaf_sanitize_text( $setting_id );
		$default_key = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key )
			? prismleaf_get_default_option( $default_key, $default_fallback )
			: $default_fallback;

		if ( '' === $setting_id ) {
			if ( '' === $default_value ) {
				return '0';
			}
			return ( 'Square' === prismleaf_sanitize_frame_border_corners( $default_value ) )
				? '0'
				: '--prismleaf-radius-medium';
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		if ( '' === $raw && '' === $default_value ) {
			return '0';
		}

		$value = prismleaf_sanitize_frame_border_corners( $raw );

		return ( 'Square' === $value ) ? '0' : '--prismleaf-radius-medium';
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_border_style_value' ) ) {
	/**
	 * Get the frame border style CSS value from theme mods and defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_border_style_value( $setting_id, $default_key, $default_fallback = '' ) {
		$setting_id = prismleaf_sanitize_text( $setting_id );
		$default_key = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key )
			? prismleaf_get_default_option( $default_key, $default_fallback )
			: $default_fallback;

		if ( '' === $setting_id ) {
			return '' !== $default_value ? prismleaf_sanitize_frame_border_style( $default_value ) : 'none';
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		if ( '' === $raw && '' === $default_value ) {
			return 'none';
		}

		return prismleaf_sanitize_frame_border_style( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_size_value' ) ) {
	/**
	 * Get a size value from theme mods and defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_size_value( $setting_id, $default_key, $default_fallback = '' ) {
		$setting_id = prismleaf_sanitize_text( $setting_id );
		$default_key = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key )
			? prismleaf_get_default_option( $default_key, $default_fallback )
			: $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_size_value( '', $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );

		return prismleaf_sanitize_size_value( $raw, $default_value );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_dimension_value' ) ) {
	/**
	 * Get a dimension (height/width) CSS value from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param mixed  $default_fallback Default fallback value.
	 * @param int    $min Minimum allowed.
	 * @param int    $max Maximum allowed.
	 * @param bool   $allow_auto Whether 'auto' is permitted.
	 * @return string
	 */
	function prismleaf_get_theme_mod_dimension_value( $setting_id, $default_key, $default_fallback, $min, $max, $allow_auto = false ) {
		$setting_id    = prismleaf_sanitize_text( $setting_id );
		$default_key   = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key )
			? prismleaf_get_default_option( $default_key, $default_fallback )
			: $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_dimension_value( '', $min, $max, $default_value, $allow_auto );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_dimension_value( $raw, $min, $max, $default_value, $allow_auto );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_image_id' ) ) {
	/**
	 * Get a sanitized background image ID from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback ID.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_image_id( $setting_id, $default_key, $default_fallback = '' ) {
		$setting_id    = prismleaf_sanitize_text( $setting_id );
		$default_key   = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_background_image( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_background_image( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_image_url' ) ) {
	/**
	 * Get the background image CSS url from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_image_url( $setting_id, $default_key, $default_fallback = '' ) {
		$id = prismleaf_get_theme_mod_background_image_id( $setting_id, $default_key, '' );

		if ( '' !== $id) {
			$url = wp_get_attachment_image_url( $id, 'full' );
			if ( '' === $url ) {
				$url = wp_get_attachment_url( $id );
			}
		}

		if ( '' === $url ) {
			$url = $default_fallback;
		}

		$url = prismleaf_sanitize_text( $url );
		return '' === $url ? 'none' : "url('" . esc_url_raw( $url ) . "')";
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_repeat' ) ) {
	/**
	 * Get the background-repeat value from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_repeat( $setting_id, $default_key, $default_fallback = 'repeat' ) {
		$setting_id   = prismleaf_sanitize_text( $setting_id );
		$default_key  = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_background_repeat( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_background_repeat( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_position_x' ) ) {
	/**
	 * Get horizontal background position from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_position_x( $setting_id, $default_key, $default_fallback = 'center' ) {
		$setting_id   = prismleaf_sanitize_text( $setting_id );
		$default_key  = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_background_position_x( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_background_position_x( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_position_y' ) ) {
	/**
	 * Get vertical background position from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_position_y( $setting_id, $default_key, $default_fallback = 'center' ) {
		$setting_id   = prismleaf_sanitize_text( $setting_id );
		$default_key  = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_background_position_y( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_background_position_y( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_position' ) ) {
	/**
	 * Combine horizontal and vertical background positions.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id_x Theme mod ID for X axis.
	 * @param string $default_key_x Default key for X axis.
	 * @param string $setting_id_y Theme mod ID for Y axis.
	 * @param string $default_key_y Default key for Y axis.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_position( $setting_id_x, $default_key_x, $setting_id_y, $default_key_y ) {
		$x = prismleaf_get_theme_mod_background_position_x( $setting_id_x, $default_key_x );
		$y = prismleaf_get_theme_mod_background_position_y( $setting_id_y, $default_key_y );

		return $x . ' ' . $y;
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_preset' ) ) {
	/**
	 * Get the background preset selection from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_preset( $setting_id, $default_key, $default_fallback = 'default' ) {
		$setting_id   = prismleaf_sanitize_text( $setting_id );
		$default_key  = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_background_preset( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_background_preset( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_size' ) ) {
	/**
	 * Get the background size keyword from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_size( $setting_id, $default_key, $default_fallback = 'auto' ) {
		$setting_id   = prismleaf_sanitize_text( $setting_id );
		$default_key  = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_background_size( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_background_size( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_background_attachment' ) ) {
	/**
	 * Get the background attachment keyword from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_background_attachment( $setting_id, $default_key, $default_fallback = 'scroll' ) {
		$setting_id   = prismleaf_sanitize_text( $setting_id );
		$default_key  = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_background_attachment( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_background_attachment( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_header_height' ) ) {
	/**
	 * Get the header height CSS value from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
function prismleaf_get_theme_mod_header_height( $setting_id, $default_key, $default_fallback = '' ) {
		$default_value = '' !== $default_fallback ? $default_fallback : 'auto';
		return prismleaf_get_theme_mod_dimension_value( $setting_id, $default_key, $default_value, 32, 300, true );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_header_icon_position' ) ) {
	/**
	 * Get the header icon position setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_value Default fallback.
	 * @return string
	 */
	function prismleaf_get_theme_mod_header_icon_position( $setting_id = 'prismleaf_header_icon_position', $default_key = 'header_icon_position', $default_value = 'left' ) {
		$default_value = prismleaf_get_default_option( $default_key, $default_value );

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_header_icon_position( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_header_icon_position( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_header_icon_size' ) ) {
	/**
	 * Get the header icon size setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_value Default fallback.
	 * @return string
	 */
	function prismleaf_get_theme_mod_header_icon_size( $setting_id = 'prismleaf_header_icon_size', $default_key = 'header_icon_size', $default_value = 'medium' ) {
		$default_value = prismleaf_get_default_option( $default_key, $default_value );

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_header_icon_size( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_header_icon_size( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_header_icon_shape' ) ) {
	/**
	 * Get the header icon shape setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id    Theme mod ID.
	 * @param string $default_key   Default option key.
	 * @param string $default_value Default fallback.
	 * @return string
	 */
	function prismleaf_get_theme_mod_header_icon_shape( $setting_id = 'prismleaf_header_icon_shape', $default_key = 'header_icon_shape', $default_value = 'circle' ) {
		$default_value = prismleaf_get_default_option( $default_key, $default_value );

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_header_icon_shape( $default_value );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_header_icon_shape( $raw );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_footer_height' ) ) {
	/**
	 * Get the footer height CSS value from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_footer_height( $setting_id, $default_key, $default_fallback = '' ) {
		$default_value = '' !== $default_fallback ? $default_fallback : 'auto';
		return prismleaf_get_theme_mod_dimension_value( $setting_id, $default_key, $default_value, 32, 600, true );
	}
}

if ( ! function_exists( 'prismleaf_get_theme_mod_sidebar_width_value' ) ) {
	/**
	 * Get a sidebar width CSS value from theme mods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_id Theme mod ID.
	 * @param string $default_key Default option key.
	 * @param string $default_fallback Default fallback value.
	 * @param int    $min Minimum value.
	 * @param int    $max Maximum value.
	 * @return string
	 */
	function prismleaf_get_theme_mod_sidebar_width_value( $setting_id, $default_key, $default_fallback = '', $min = 150, $max = 300 ) {
		$setting_id    = prismleaf_sanitize_text( $setting_id );
		$default_key   = prismleaf_sanitize_text( $default_key );
		$default_value = ( '' !== $default_key ) ? prismleaf_get_default_option( $default_key, $default_fallback ) : $default_fallback;

		if ( '' === $setting_id ) {
			return prismleaf_sanitize_dimension_value( '', $min, $max, $default_value, false );
		}

		$raw = prismleaf_get_theme_mod( $setting_id, $default_value );
		return prismleaf_sanitize_dimension_value( $raw, $min, $max, $default_value, false );
	}
}
