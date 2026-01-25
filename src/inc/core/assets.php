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
			'assets/styles/constants.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-constants', 'rtl', 'replace' );
		$last_handle = 'prismleaf-constants';

		prismleaf_enqueue_style(
			'prismleaf-typography',
			'assets/styles/typography.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-typography', 'rtl', 'replace' );
		$last_handle = 'prismleaf-typography';

		prismleaf_enqueue_style(
			'prismleaf-colors',
			'assets/styles/colors.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-colors', 'rtl', 'replace' );
		$last_handle = 'prismleaf-colors';

		prismleaf_enqueue_style(
			'prismleaf-layout',
			'assets/styles/layout.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-layout', 'rtl', 'replace' );
		$last_handle = 'prismleaf-layout';

		prismleaf_enqueue_style(
			'prismleaf-frames',
			'assets/styles/frames.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-frames', 'rtl', 'replace' );
		$last_handle = 'prismleaf-frames';

		prismleaf_enqueue_style(
			'prismleaf-header',
			'assets/styles/header.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-header', 'rtl', 'replace' );
		$last_handle = 'prismleaf-header';

		prismleaf_enqueue_style(
			'prismleaf-footer',
			'assets/styles/footer.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-footer', 'rtl', 'replace' );
		$last_handle = 'prismleaf-footer';

		prismleaf_enqueue_style(
			'prismleaf-sidebars',
			'assets/styles/sidebars.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-sidebars', 'rtl', 'replace' );
		$last_handle = 'prismleaf-sidebars';

		prismleaf_enqueue_style(
			'prismleaf-content',
			'assets/styles/content.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-content', 'rtl', 'replace' );
		$last_handle = 'prismleaf-content';

		prismleaf_enqueue_style(
			'prismleaf-widgets',
			'assets/styles/widgets.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-widgets', 'rtl', 'replace' );
		$last_handle = 'prismleaf-widgets';

		prismleaf_enqueue_style(
			'prismleaf-theme-switch',
			'assets/styles/theme-switch.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-theme-switch', 'rtl', 'replace' );
		$last_handle = 'prismleaf-theme-switch';

		prismleaf_enqueue_style(
			'prismleaf-site-icon',
			'assets/styles/site-icon.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-site-icon', 'rtl', 'replace' );
		$last_handle = 'prismleaf-site-icon';

		prismleaf_enqueue_style(
			'prismleaf-site-title',
			'assets/styles/site-title.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-site-title', 'rtl', 'replace' );
		$last_handle = 'prismleaf-site-title';

		prismleaf_enqueue_style(
			'prismleaf-menus',
			'assets/styles/menus.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-menus', 'rtl', 'replace' );
		$last_handle = 'prismleaf-menus';

		prismleaf_enqueue_style(
			'prismleaf-mobile',
			'assets/styles/mobile.css',
			array( $last_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-mobile', 'rtl', 'replace' );
		$last_handle = 'prismleaf-mobile';

		prismleaf_enqueue_style(
			'prismleaf-accessibility',
			'assets/styles/accessibility.css',
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
			$last_handle = 'prismleaf-child-style';
		}

		prismleaf_output_customizer_styles( $last_handle );
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
	wp_enqueue_script(
		'prismleaf-theme-switch',
		PRISMLEAF_URI . 'assets/scripts/theme-switch.js',
		array(),
		PRISMLEAF_VERSION,
		true
	);

	wp_localize_script(
		'prismleaf-theme-switch',
		'prismleafThemeSwitchStrings',
		array(
				'labels' => array(
					'auto'  => __( 'Automatic (system preference)', 'prismleaf' ),
					'dark'  => __( 'Dark appearance override', 'prismleaf' ),
					'light' => __( 'Light appearance override', 'prismleaf' ),
				),
			'actions' => array(
				'auto'  => __( 'Switch to light override', 'prismleaf' ),
				'light' => __( 'Switch to dark override', 'prismleaf' ),
				'dark'  => __( 'Switch to automatic system control', 'prismleaf' ),
			),
			'storageKey' => 'prismleaf_theme_switch_mode',
		)
	);

	wp_enqueue_script(
		'prismleaf-mobile-menu',
		PRISMLEAF_URI . 'assets/scripts/mobile-menu.js',
		array(),
		PRISMLEAF_VERSION,
		true
	);
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
			PRISMLEAF_URI . 'assets/styles/customizer.css',
			array(),
			PRISMLEAF_VERSION
		);
		wp_enqueue_script(
			'prismleaf-customizer-helpers',
			PRISMLEAF_URI . 'assets/scripts/customizer-helpers.js',
			array( 'customize-controls' ),
			PRISMLEAF_VERSION,
			true
		);
		wp_enqueue_script(
			'prismleaf-customizer-palette-control',
			PRISMLEAF_URI . 'assets/scripts/customizer-palette-control.js',
			array( 'customize-controls', 'wp-color-picker', 'prismleaf-customizer-helpers' ),
			PRISMLEAF_VERSION,
			true
		);
		wp_enqueue_script(
			'prismleaf-customizer-palette-source-control',
			PRISMLEAF_URI . 'assets/scripts/customizer-palette-source-control.js',
			array( 'customize-controls', 'wp-color-picker', 'prismleaf-customizer-helpers', 'prismleaf-customizer-palette-control' ),
			PRISMLEAF_VERSION,
			true
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
