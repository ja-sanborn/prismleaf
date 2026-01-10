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
			'1'            => __( 'Shade 1', 'prismleaf' ),
			'2'            => __( 'Shade 2', 'prismleaf' ),
			'3'            => __( 'Shade 3', 'prismleaf' ),
			'4'            => __( 'Shade 4', 'prismleaf' ),
			'5'            => __( 'Shade 5', 'prismleaf' ),
			'on'           => __( 'Shade Text', 'prismleaf' ),
			'container_1'  => __( 'Variant 1', 'prismleaf' ),
			'container_2'  => __( 'Variant 2', 'prismleaf' ),
			'container_3'  => __( 'Variant 3', 'prismleaf' ),
			'container_4'  => __( 'Variant 4', 'prismleaf' ),
			'container_5'  => __( 'Variant 5', 'prismleaf' ),
			'container_on' => __( 'Variant Text', 'prismleaf' ),
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
