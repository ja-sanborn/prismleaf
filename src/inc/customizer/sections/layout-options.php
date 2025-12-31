<?php
/**
 * Prismleaf Customizer: Layout Options
 *
 * Defines Theme Options → Layout controls for the global layout system.
 *
 * Requirements:
 * - All labels and descriptions are localized.
 * - Controls hide when irrelevant:
 *   - Framed mode forces not-contained (contained controls hidden).
 *   - When a region is hidden, its dependent controls are hidden.
 *   - If all regions are hidden, Framed is hidden (and treated as off elsewhere).
 * - Active callbacks must respect live Customizer values (not only saved theme_mods).
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register layout-related Customizer settings and controls.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
if ( ! function_exists( 'prismleaf_customize_register_global_layout' ) ) {
	function prismleaf_customize_register_global_layout( $wp_customize ) {
		$wp_customize->add_section(
			'prismleaf_layout',
			array(
				'title'       => __( 'Layout', 'prismleaf' ),
				'description' => __( 'Control the global layout mode (framed vs scrolling) and region containment.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 20,
			)
		);

		// Frames.
		$wp_customize->add_setting(
			'prismleaf_layout_heading_frames',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_framed',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => false,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_framed',
			array(
				'type'        => 'checkbox',
				'section'     => 'prismleaf_layout',
				'label'       => __( 'Use framed layout (desktop)', 'prismleaf' ),
				'description' => __( 'When enabled, the site uses a fixed frame with internal scrolling panels. On mobile, the layout always stacks and scrolls normally.', 'prismleaf' ),
				'priority'    => 10,
			)
		);

		// Header.
		$wp_customize->add_setting(
			'prismleaf_layout_heading_header',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new Prismleaf_Customize_Section_Header_Control(
				$wp_customize,
				'prismleaf_layout_heading_header',
				array(
					'section' => 'prismleaf_layout',
					'label'   => __( 'Header', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_header_visible',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_header_visible',
			array(
				'type'            => 'checkbox',
				'section'         => 'prismleaf_layout',
				'label'           => __( 'Show header', 'prismleaf' ),
				'description'     => __( 'Controls whether the header is displayed.', 'prismleaf' ),
				'active_callback' => 'prismleaf_customize_callback_not_all_hidden',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_header_contained',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_header_contained',
			array(
				'type'        => 'checkbox',
				'section'     => 'prismleaf_layout',
				'label'       => __( 'Contain header', 'prismleaf' ),
				'description' => __(
					'When enabled (non-framed desktop), the header is rendered inside the main content area. This option is disabled when using the framed layout.',
					'prismleaf'
				),
				'active_callback' => 'prismleaf_customize_callback_header_contained_active',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_header_height',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'prismleaf_sanitize_header_height',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_header_height',
			array(
				'type'        => 'number',
				'section'     => 'prismleaf_layout',
				'label'       => __( 'Header height', 'prismleaf' ),
				'description' => __( 'Optional. Leave blank to use the theme default. Values 0–15 are treated as auto height; 16–240 are fixed heights.', 'prismleaf' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 240,
					'step' => 1,
				),
				'active_callback' => 'prismleaf_customize_callback_header_visible',
			)
		);

		// Footer.
		$wp_customize->add_setting(
			'prismleaf_layout_heading_footer',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new Prismleaf_Customize_Section_Header_Control(
				$wp_customize,
				'prismleaf_layout_heading_footer',
				array(
					'section' => 'prismleaf_layout',
					'label'   => __( 'Footer', 'prismleaf' ),
				)
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_footer_visible',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_footer_visible',
			array(
				'type'            => 'checkbox',
				'section'         => 'prismleaf_layout',
				'label'           => __( 'Show footer', 'prismleaf' ),
				'description'     => __( 'Controls whether the footer is displayed.', 'prismleaf' ),
				'active_callback' => 'prismleaf_customize_callback_not_all_hidden',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_footer_contained',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => true,
				'sanitize_callback' => 'wp_validate_boolean',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_footer_contained',
			array(
				'type'        => 'checkbox',
				'section'     => 'prismleaf_layout',
				'label'       => __( 'Contain footer', 'prismleaf' ),
				'description' => __(
					'When enabled (non-framed desktop), the footer is rendered inside the main content area. This option is disabled when using the framed layout.',
					'prismleaf'
				),
				'active_callback' => 'prismleaf_customize_callback_footer_contained_active',
			)
		);

		$wp_customize->add_setting(
			'prismleaf_layout_footer_height',
			array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'prismleaf_sanitize_footer_height',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'prismleaf_layout_footer_height',
			array(
				'type'        => 'number',
				'section'     => 'prismleaf_layout',
				'label'       => __( 'Footer height', 'prismleaf' ),
				'description' => __( 'Optional. Leave blank to use the theme default. Values 0–15 are treated as auto height; 16–240 are fixed heights.', 'prismleaf' ),
				'input_attrs' => array(
					'min'  => 0,
					'max'  => 240,
					'step' => 1,
				),
				'active_callback' => 'prismleaf_customize_callback_footer_visible',
			)
		);

		// Sidebars.
		$sidebars = array(
			'left'  => __( 'Left sidebar', 'prismleaf' ),
			'right' => __( 'Right sidebar', 'prismleaf' ),
		);

		$sidebar_headers = array(
			'left'  => __( 'Left Sidebar', 'prismleaf' ),
			'right' => __( 'Right Sidebar', 'prismleaf' ),
		);

		foreach ( $sidebars as $side => $label ) {
			$visible_setting = "prismleaf_layout_sidebar_{$side}_visible";
			$width_setting   = "prismleaf_layout_sidebar_{$side}_width";
			$coll_setting    = "prismleaf_layout_sidebar_{$side}_collapsible";
			$cont_setting    = "prismleaf_layout_sidebar_{$side}_contained";

			// Sidebar section heading.
			$wp_customize->add_setting(
				"prismleaf_layout_heading_sidebar_{$side}",
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				new Prismleaf_Customize_Section_Header_Control(
					$wp_customize,
					"prismleaf_layout_heading_sidebar_{$side}",
					array(
						'section' => 'prismleaf_layout',
						'label'   => isset( $sidebar_headers[ $side ] ) ? $sidebar_headers[ $side ] : $label,
					)
				)
			);

			$wp_customize->add_setting(
				$visible_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'wp_validate_boolean',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$visible_setting,
				array(
					'type'        => 'checkbox',
					'section'     => 'prismleaf_layout',
					/* translators: %s is sidebar name. */
					'label'       => sprintf( __( 'Show %s', 'prismleaf' ), $label ),
					'description' => __( 'Controls whether this sidebar is displayed.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_not_all_hidden',
					'priority'        => 28,
				)
			);

			$wp_customize->add_setting(
				$cont_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'wp_validate_boolean',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$cont_setting,
				array(
					'type'        => 'checkbox',
					'section'     => 'prismleaf_layout',
					/* translators: %s is sidebar name. */
					'label'       => sprintf( __( 'Contain %s', 'prismleaf' ), $label ),
					'description' => __(
						'When enabled, the sidebar is visually separated from the site edges. This option is disabled when using the framed layout.',
						'prismleaf'
					),
					'active_callback' => 'prismleaf_customize_callback_sidebar_contained_active',
					'priority'        => 30,
				)
			);

			$wp_customize->add_setting(
				$width_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => 300,
					'sanitize_callback' => 'prismleaf_sanitize_sidebar_width',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$width_setting,
				array(
					'type'        => 'number',
					'section'     => 'prismleaf_layout',
					/* translators: %s is sidebar name. */
					'label'       => sprintf( __( '%s width (desktop)', 'prismleaf' ), $label ),
					'description' => __( 'Width in pixels on desktop layouts.', 'prismleaf' ),
					'input_attrs' => array(
						'min'  => 200,
						'max'  => 400,
						'step' => 10,
					),
					'active_callback' => 'prismleaf_customize_callback_sidebar_visible',
					'priority'        => 31,
				)
			);

			$wp_customize->add_setting(
				$coll_setting,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => 'wp_validate_boolean',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$coll_setting,
				array(
					'type'        => 'checkbox',
					'section'     => 'prismleaf_layout',
					/* translators: %s is sidebar name. */
					'label'       => sprintf( __( '%s collapsible on desktop', 'prismleaf' ), $label ),
					'description' => __( 'When enabled, the sidebar can collapse. Collapsing does not apply to the stacked mobile layout.', 'prismleaf' ),
					'active_callback' => 'prismleaf_customize_callback_sidebar_visible',
					'priority'        => 32,
				)
			);
		}
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_global_layout' );

/**
 * Add layout-related body classes based on Customizer settings.
 *
 * @since 1.0.0
 *
 * @param string[] $classes Existing body classes.
 * @return string[] Modified body classes.
 */
if ( ! function_exists( 'prismleaf_layout_body_classes' ) ) {
	function prismleaf_layout_body_classes( $classes ) {
		$is_mobile = wp_is_mobile();

		$framed = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_framed', false ) );
		if ( $is_mobile ) {
			$framed = false;
		}

		$header_visible = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_header_visible', true ) );
		$footer_visible = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_footer_visible', true ) );

		$sidebar_left_visible  = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_sidebar_left_visible', true ) );
		$sidebar_right_visible = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_sidebar_right_visible', true ) );

		$all_hidden = prismleaf_layout_all_regions_hidden();
		if ( $all_hidden ) {
			$classes[] = 'prismleaf-layout-all-hidden';
		}

		$classes[] = $framed ? 'prismleaf-layout-framed' : 'prismleaf-layout-not-framed';

		if ( $header_visible ) {
			$contained = $is_mobile ? false : (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_header_contained', true ) );
			$classes[] = $contained ? 'prismleaf-header-contained' : 'prismleaf-header-not-contained';
		}

		if ( $footer_visible ) {
			$contained = $is_mobile ? false : (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_footer_contained', true ) );
			$classes[] = $contained ? 'prismleaf-footer-contained' : 'prismleaf-footer-not-contained';
		}

		if ( $sidebar_left_visible ) {
			$contained = $is_mobile ? false : (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_sidebar_left_contained', true ) );
			$classes[] = $contained ? 'prismleaf-sidebar-left-contained' : 'prismleaf-sidebar-left-not-contained';
		}

		if ( $sidebar_right_visible ) {
			$contained = $is_mobile ? false : (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_layout_sidebar_right_contained', true ) );
			$classes[] = $contained ? 'prismleaf-sidebar-right-contained' : 'prismleaf-sidebar-right-not-contained';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'prismleaf_layout_body_classes' );
