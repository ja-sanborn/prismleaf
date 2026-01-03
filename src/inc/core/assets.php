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

			$path = trailingslashit( get_stylesheet_directory() ) . $relative_path;
			$uri  = trailingslashit( get_stylesheet_directory_uri() ) . $relative_path;

			wp_enqueue_style( $handle, $uri, $deps, $ver );
			return;
		}

		$path = PRISMLEAF_DIR . $relative_path;
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

		$token_handle = 'prismleaf-style';

		if ( is_child_theme() ) {
			prismleaf_enqueue_style(
				'prismleaf-child-style',
				'style.css',
				array( 'prismleaf-style' ),
				wp_get_theme()->get( 'Version' )
			);

			$token_handle = 'prismleaf-child-style';
		}

		prismleaf_enqueue_style(
			'prismleaf-tokens-hardcoded',
			'assets/styles/tokens/foundational.css',
			array( $token_handle ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-tokens-hardcoded', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-tokens-colors',
			'assets/styles/tokens/colors.css',
			array( 'prismleaf-tokens-hardcoded' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-tokens-colors', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-tokens-active',
			'assets/styles/tokens/active.css',
			array( 'prismleaf-tokens-colors' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-tokens-active', 'rtl', 'replace' );

		if ( function_exists( 'prismleaf_get_css_variable_overrides' ) ) {
			$prismleaf_inline_vars = prismleaf_get_css_variable_overrides();
			if ( '' !== $prismleaf_inline_vars ) {
				wp_add_inline_style( 'prismleaf-tokens-active', $prismleaf_inline_vars );
			}
		}

		prismleaf_enqueue_style(
			'prismleaf-base',
			'assets/styles/core/base.css',
			array( 'prismleaf-tokens-active' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-base', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-layout',
			'assets/styles/core/layout.css',
			array( 'prismleaf-base' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-layout', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-regions',
			'assets/styles/core/regions.css',
			array( 'prismleaf-layout' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-regions', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-branding',
			'assets/styles/components/branding.css',
			array( 'prismleaf-regions' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-branding', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-site-icon',
			'assets/styles/components/site-icon.css',
			array( 'prismleaf-regions' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-site-icon', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-header',
			'assets/styles/components/header.css',
			array( 'prismleaf-regions' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-header', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-footer',
			'assets/styles/components/footer.css',
			array( 'prismleaf-regions' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-footer', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-primary-menu',
			'assets/styles/components/primary-menu.css',
			array( 'prismleaf-header' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-primary-menu', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-secondary-menu',
			'assets/styles/components/secondary-menu.css',
			array( 'prismleaf-header' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-secondary-menu', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-mobile-menu',
			'assets/styles/components/mobile-menu.css',
			array( 'prismleaf-header' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-mobile-menu', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-theme-switch',
			'assets/styles/components/theme-switch.css',
			array( 'prismleaf-regions' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-theme-switch', 'rtl', 'replace' );

		prismleaf_enqueue_style(
			'prismleaf-search',
			'assets/styles/components/search.css',
			array( 'prismleaf-regions' ),
			PRISMLEAF_VERSION
		);
		wp_style_add_data( 'prismleaf-search', 'rtl', 'replace' );

		if ( function_exists( 'prismleaf_get_site_metadata_css_variable_overrides' ) ) {
			$site_metadata_vars = prismleaf_get_site_metadata_css_variable_overrides();
			if ( '' !== $site_metadata_vars ) {
				wp_add_inline_style( 'prismleaf-site-icon', $site_metadata_vars );
			}
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
		wp_enqueue_script(
			'prismleaf-theme-switch',
			PRISMLEAF_URI . 'assets/scripts/theme-switch.js',
			array(),
			PRISMLEAF_VERSION,
			true
		);

		wp_enqueue_script(
			'prismleaf-search',
			PRISMLEAF_URI . 'assets/scripts/search.js',
			array(),
			PRISMLEAF_VERSION,
			true
		);

		wp_enqueue_script(
			'prismleaf-branding-script',
			PRISMLEAF_URI . 'assets/scripts/branding.js',
			array(),
			PRISMLEAF_VERSION,
			true
		);

		wp_enqueue_script(
			'prismleaf-mobile-menu',
			PRISMLEAF_URI . 'assets/scripts/mobile-menu.js',
			array(),
			PRISMLEAF_VERSION,
			true
		);

		$force_light = prismleaf_get_theme_mod_bool( 'prismleaf_brand_force_light', false );

		wp_add_inline_script(
			'prismleaf-theme-switch',
			'window.PrismleafThemeSwitch = ' . wp_json_encode(
				array(
					'forceLight'    => $force_light,
					'initialState'  => $force_light ? 'light' : 'auto',
					'labelAuto'     => __( 'Color scheme: Auto', 'prismleaf' ),
					'labelLight'    => __( 'Color scheme: Light', 'prismleaf' ),
					'labelDark'     => __( 'Color scheme: Dark', 'prismleaf' ),
					'hintNextLight' => __( 'Next: Light', 'prismleaf' ),
					'hintNextDark'  => __( 'Next: Dark', 'prismleaf' ),
					'hintNextAuto'  => __( 'Next: Auto', 'prismleaf' ),
				)
			) . ';',
			'before'
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
		static $enqueued = false;

		if ( $enqueued ) {
			return;
		}

		wp_enqueue_script(
			'prismleaf-customizer-components',
			PRISMLEAF_URI . 'assets/scripts/utils.js',
			array( 'customize-controls', 'wp-color-picker' ),
			PRISMLEAF_VERSION,
			true
		);

		$enqueued = true;
	}
}
