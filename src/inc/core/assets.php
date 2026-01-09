<?php
/**
 * Theme assets.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_enqueue_style' ) ) {
	/**
	 * Enqueue a stylesheet from the theme root.
	 *
	 * @since 1.0.0
	 *
	 * @param string       $handle        Style handle.
	 * @param string       $relative_path Relative path from the theme root.
	 * @param string[]     $deps          Optional dependencies.
	 * @param string|false $ver           Optional version.
	 * @return void
	 */
	function prismleaf_enqueue_style( $handle, $relative_path, $deps = array(), $ver = false ) {
		$relative_path = ltrim( (string) $relative_path, '/' );

		if ( 'prismleaf-child-style' === $handle ) {
			if ( ! is_child_theme() ) {
				return;
			}

			$uri  = trailingslashit( get_stylesheet_directory_uri() ) . $relative_path;
			wp_enqueue_style( $handle, $uri, $deps, $ver );
			return;
		}

		$uri  = PRISMLEAF_URI . $relative_path;
		wp_enqueue_style( $handle, $uri, $deps, $ver );
	}
}

if ( ! function_exists( 'prismleaf_enqueue_styles' ) ) {
	/**
	 * Enqueue theme styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_enqueue_styles() {
		prismleaf_enqueue_style(
			'prismleaf-style',
			'style.css',
			array(),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-style', 'rtl', 'replace' );

		$last_handle = 'prismleaf-style';

		prismleaf_enqueue_style(
			'prismleaf-constants',
			'assets/styles/core/constants.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-constants', 'rtl', 'replace' );
		$last_handle = 'prismleaf-constants';

		prismleaf_enqueue_style(
			'prismleaf-typography',
			'assets/styles/core/typography.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-typography', 'rtl', 'replace' );
		$last_handle = 'prismleaf-typography';

		prismleaf_enqueue_style(
			'prismleaf-colors',
			'assets/styles/core/colors.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-colors', 'rtl', 'replace' );
		$last_handle = 'prismleaf-colors';

		prismleaf_enqueue_style(
			'prismleaf-layout',
			'assets/styles/core/layout.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-layout', 'rtl', 'replace' );
		$last_handle = 'prismleaf-layout';

		prismleaf_enqueue_style(
			'prismleaf-regions',
			'assets/styles/core/regions.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-regions', 'rtl', 'replace' );
		$last_handle = 'prismleaf-regions';

		prismleaf_enqueue_style(
			'prismleaf-accessibility',
			'assets/styles/core/accessibility.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-accessibility', 'rtl', 'replace' );
		$last_handle = 'prismleaf-accessibility';

		if ( is_child_theme() ) {
			prismleaf_enqueue_style(
				'prismleaf-child-style',
				'style.css',
				array( $last_handle ),
				wp_get_theme()->get( 'Version' )
			);
			wp_style_add_data( 'prismleaf-child-style', 'rtl', 'replace' );
		}

		if ( function_exists( 'prismleaf_output_customizer_styles' ) ) {
			prismleaf_output_customizer_styles();
		}
	}
}
add_action( 'wp_enqueue_scripts', 'prismleaf_enqueue_styles' );

if ( ! function_exists( 'prismleaf_enqueue_scripts' ) ) {
	/**
	 * Enqueue front-end scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_enqueue_scripts() {
		// remove this comment after adding scripts here.
	}
}
add_action( 'wp_enqueue_scripts', 'prismleaf_enqueue_scripts' );

if ( ! function_exists( 'prismleaf_enqueue_customizer_components' ) ) {
	/**
	 * Enqueue shared Customizer component scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_enqueue_customizer_components() {
		wp_enqueue_style(
			'prismleaf-customizer-controls',
			PRISMLEAF_URI . 'assets/styles/core/customizer.css',
			array(),
			PRISMLEAF_VERSION
		);
	}
}
add_action( 'customize_controls_enqueue_scripts', 'prismleaf_enqueue_customizer_components' );

if ( ! function_exists( 'prismleaf_enqueue_customizer_preview' ) ) {
	/**
	 * Enqueue Customizer preview scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_enqueue_customizer_preview() {
		wp_enqueue_script(
			'prismleaf-customizer-preview',
			PRISMLEAF_URI . 'assets/scripts/customizer-preview.js',
			array( 'customize-preview' ),
			PRISMLEAF_VERSION,
			true
		);
	}
}
add_action( 'customize_preview_init', 'prismleaf_enqueue_customizer_preview' );
