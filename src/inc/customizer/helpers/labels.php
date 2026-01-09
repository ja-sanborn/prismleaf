<?php
/**
 * Customizer label helpers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_build_preview_labels' ) ) {
	/**
	 * Build label text for preview keys.
	 *
	 * @since 1.0.0
	 *
	 * @param string[]             $keys       Keys to label.
	 * @param array<string,string> $label_map  Key-to-label map.
	 * @param array<string,string> $prefix_map Optional prefix-to-label map.
	 * @return array<string,string>
	 */
	function prismleaf_build_preview_labels( $keys, $label_map, $prefix_map = array() ) {
		if ( ! is_array( $keys ) || empty( $keys ) || ! is_array( $label_map ) ) {
			return array();
		}

		$labels = array();

		if ( ! empty( $prefix_map ) ) {
			foreach ( $prefix_map as $prefix => $prefix_label ) {
				foreach ( $keys as $key ) {
					if ( isset( $label_map[ $key ] ) ) {
						$labels[ $prefix . '_' . $key ] = $prefix_label . ' ' . $label_map[ $key ];
					}
				}
			}

			return $labels;
		}

		foreach ( $keys as $key ) {
			if ( isset( $label_map[ $key ] ) ) {
				$labels[ $key ] = $label_map[ $key ];
			}
		}

		return $labels;
	}
}

if ( ! function_exists( 'prismleaf_get_palette_label_map' ) ) {
	/**
	 * Provide label map for palette preview keys.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,string>
	 */
	function prismleaf_get_palette_label_map() {
		return array(
			'base'               => __( 'Base', 'prismleaf' ),
			'on_base'            => __( 'On Base', 'prismleaf' ),
			'base_darker'        => __( 'Base Darker', 'prismleaf' ),
			'base_darkest'       => __( 'Base Darkest', 'prismleaf' ),
			'base_lighter'       => __( 'Base Lighter', 'prismleaf' ),
			'base_lightest'      => __( 'Base Lightest', 'prismleaf' ),
			'container'          => __( 'Container', 'prismleaf' ),
			'on_container'       => __( 'On Container', 'prismleaf' ),
			'container_darker'   => __( 'Container Darker', 'prismleaf' ),
			'container_darkest'  => __( 'Container Darkest', 'prismleaf' ),
			'container_lighter'  => __( 'Container Lighter', 'prismleaf' ),
			'container_lightest' => __( 'Container Lightest', 'prismleaf' ),
		);
	}
}

if ( ! function_exists( 'prismleaf_get_palette_preview_labels' ) ) {
	/**
	 * Provide label text for palette preview keys.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $keys Optional keys to label.
	 * @return array<string,string>
	 */
	function prismleaf_get_palette_preview_labels( $keys = array() ) {
		$keys = is_array( $keys ) && ! empty( $keys ) ? $keys : prismleaf_get_palette_keys();
		return prismleaf_build_preview_labels( $keys, prismleaf_get_palette_label_map() );
	}
}

if ( ! function_exists( 'prismleaf_get_neutral_label_map' ) ) {
	/**
	 * Provide label map for neutral preview keys.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,string>
	 */
	function prismleaf_get_neutral_label_map() {
		return array(
			'background'               => __( 'Background', 'prismleaf' ),
			'on_background'            => __( 'On Background', 'prismleaf' ),
			'background_darker'        => __( 'Background Darker', 'prismleaf' ),
			'background_darkest'       => __( 'Background Darkest', 'prismleaf' ),
			'background_lighter'       => __( 'Background Lighter', 'prismleaf' ),
			'background_lightest'      => __( 'Background Lightest', 'prismleaf' ),
			'surface'                  => __( 'Surface', 'prismleaf' ),
			'on_surface'               => __( 'On Surface', 'prismleaf' ),
			'surface_darker'           => __( 'Surface Darker', 'prismleaf' ),
			'surface_darkest'          => __( 'Surface Darkest', 'prismleaf' ),
			'surface_lighter'          => __( 'Surface Lighter', 'prismleaf' ),
			'surface_lightest'         => __( 'Surface Lightest', 'prismleaf' ),
			'surface_variant'          => __( 'Surface Variant', 'prismleaf' ),
			'on_surface_variant'       => __( 'On Surface Variant', 'prismleaf' ),
			'surface_variant_darker'   => __( 'Surface Variant Darker', 'prismleaf' ),
			'surface_variant_darkest'  => __( 'Surface Variant Darkest', 'prismleaf' ),
			'surface_variant_lighter'  => __( 'Surface Variant Lighter', 'prismleaf' ),
			'surface_variant_lightest' => __( 'Surface Variant Lightest', 'prismleaf' ),
			'foreground'               => __( 'Foreground', 'prismleaf' ),
			'on_foreground'            => __( 'On Foreground', 'prismleaf' ),
			'foreground_darker'        => __( 'Foreground Darker', 'prismleaf' ),
			'foreground_darkest'       => __( 'Foreground Darkest', 'prismleaf' ),
			'foreground_lighter'       => __( 'Foreground Lighter', 'prismleaf' ),
			'foreground_lightest'      => __( 'Foreground Lightest', 'prismleaf' ),
		);
	}
}

if ( ! function_exists( 'prismleaf_get_neutral_preview_labels' ) ) {
	/**
	 * Provide label text for neutral preview keys.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $keys Optional keys to label.
	 * @return array<string,string>
	 */
	function prismleaf_get_neutral_preview_labels( $keys = array() ) {
		$prefix_map = array(
			'light' => __( 'Light', 'prismleaf' ),
			'dark'  => __( 'Dark', 'prismleaf' ),
		);

		$keys = is_array( $keys ) && ! empty( $keys ) ? $keys : prismleaf_get_neutral_palette_keys();
		return prismleaf_build_preview_labels( $keys, prismleaf_get_neutral_label_map(), $prefix_map );
	}
}
