<?php
/**
 * Theme setup.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_setup' ) ) {
	/**
	 * Register theme supports.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_setup() {
		load_theme_textdomain( 'prismleaf', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 96,
				'width'       => 240,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'ffffff',
			)
		);

		add_theme_support( 'customize-selective-refresh-widgets' );

		remove_theme_support( 'widgets-block-editor' );

		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );

		register_nav_menus(
			array(
				'primary'   => __( 'Primary Menu', 'prismleaf' ),
				'secondary' => __( 'Secondary Menu', 'prismleaf' ),
				'mobile'    => __( 'Mobile Menu', 'prismleaf' ),
			)
		);
	}
}
add_action( 'after_setup_theme', 'prismleaf_setup' );

if ( ! function_exists( 'prismleaf_setup_customizer' ) ) {
	/**
	 * Register Theme Options in Customizer settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_setup_customizer( $wp_customize ) {
		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			$wp_customize->add_panel(
				'prismleaf_theme_options',
				array(
					'title'       => __( 'Theme Options', 'prismleaf' ),
					'description' => __( 'Global theme settings that affect the overall site layout and behavior.', 'prismleaf' ),
					'priority'    => 10,
				)
			);
		}

		// Ensure shared Customizer scripts are available globally.
		add_action( 'customize_controls_enqueue_scripts', 'prismleaf_enqueue_customizer_components' );
	}
}
add_action( 'customize_register', 'prismleaf_setup_customizer' );

if ( ! function_exists( 'prismleaf_language_attributes_force_color_scheme' ) ) {
	/**
	 * Force a color scheme attribute on the root element when configured.
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Existing language attributes string.
	 * @return string
	 */
	function prismleaf_language_attributes_force_color_scheme( $output ) {
		$force_light = prismleaf_get_theme_mod_bool( 'prismleaf_brand_force_light', false );

		if ( $force_light ) {
			$output  = trim( $output );
			$output .= ' data-prismleaf-color-scheme="light"';
		}

		return trim( $output );
	}
}
add_filter( 'language_attributes', 'prismleaf_language_attributes_force_color_scheme' );
