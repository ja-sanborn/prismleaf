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
		add_image_size( 'prismleaf-archive-card', 300, 0, false );
		add_image_size( 'prismleaf-featured-image', 800, 0, false );

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

if ( ! function_exists( 'prismleaf_widgets_init' ) ) {
	/**
	 * Register widget areas.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_widgets_init() {
		$widget_areas = array(
			array(
				/* translators: Footer widget area name. */
				'name'        => __( 'Footer 1', 'prismleaf' ),
				'id'          => 'footer-1',
				'description' => __( 'First footer widget area.', 'prismleaf' ),
			),
			array(
				/* translators: Footer widget area name. */
				'name'        => __( 'Footer 2', 'prismleaf' ),
				'id'          => 'footer-2',
				'description' => __( 'Second footer widget area.', 'prismleaf' ),
			),
			array(
				/* translators: Footer widget area name. */
				'name'        => __( 'Footer 3', 'prismleaf' ),
				'id'          => 'footer-3',
				'description' => __( 'Third footer widget area.', 'prismleaf' ),
			),
			array(
				/* translators: Footer widget area name. */
				'name'        => __( 'Footer 4', 'prismleaf' ),
				'id'          => 'footer-4',
				'description' => __( 'Fourth footer widget area.', 'prismleaf' ),
			),
			array(
				/* translators: Homepage widget area name. */
				'name'        => __( 'Homepage Widgets', 'prismleaf' ),
				'id'          => 'homepage-widgets',
				'description' => __( 'Widget area that appears on the homepage before the latest posts.', 'prismleaf' ),
			),
			array(
				/* translators: Sidebar area name. */
				'name'        => __( 'Primary Sidebar', 'prismleaf' ),
				'id'          => 'sidebar-primary',
				'description' => __( 'Widgets in the primary sidebar.', 'prismleaf' ),
			),
			array(
				/* translators: Sidebar area name. */
				'name'        => __( 'Secondary Sidebar', 'prismleaf' ),
				'id'          => 'sidebar-secondary',
				'description' => __( 'Widgets in the secondary sidebar.', 'prismleaf' ),
			),
		);

		foreach ( $widget_areas as $widget_area ) {
			register_sidebar(
				array(
					'name'          => $widget_area['name'],
					'id'            => $widget_area['id'],
					'description'   => $widget_area['description'],
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				)
			);
		}
	}
}
add_action( 'widgets_init', 'prismleaf_widgets_init' );

if ( ! function_exists( 'prismleaf_register_theme_options_panel' ) ) {
	/**
	 * Register the Theme Options panel in the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_register_theme_options_panel( $wp_customize ) {
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
	}
}
add_action( 'customize_register', 'prismleaf_register_theme_options_panel' );
