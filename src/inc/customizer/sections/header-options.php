<?php
/**
 * Prismleaf Customizer: Header
 *
 * Registers Customizer settings for the header layout controls.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_customize_register_header' ) ) {
	/**
	 * Register Header settings and controls.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 * @return void
	 */
	function prismleaf_customize_register_header( $wp_customize ) {
		if ( ! ( $wp_customize instanceof WP_Customize_Manager ) ) {
			return;
		}

		if ( ! $wp_customize->get_panel( 'prismleaf_theme_options' ) ) {
			return;
		}

		$wp_customize->add_section(
			'prismleaf_header',
			array(
				'title'       => __( 'Header', 'prismleaf' ),
				'description' => __( 'Configure the header layout components.', 'prismleaf' ),
				'panel'       => 'prismleaf_theme_options',
				'priority'    => 90,
			)
		);

		$settings = array(
			'prismleaf_header_show_theme_switch'   => array(
				'default'     => true,
				'label'       => __( 'Show theme switch', 'prismleaf' ),
				'description' => __( 'Controls whether the theme switch appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_show_search'         => array(
				'default'     => true,
				'label'       => __( 'Show search', 'prismleaf' ),
				'description' => __( 'Controls whether the search control appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_show_primary_menu'   => array(
				'default'     => true,
				'label'       => __( 'Show primary menu', 'prismleaf' ),
				'description' => __( 'Controls whether the primary menu appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_show_secondary_menu' => array(
				'default'     => true,
				'label'       => __( 'Show secondary menu', 'prismleaf' ),
				'description' => __( 'Controls whether the secondary menu appears in the header.', 'prismleaf' ),
			),
			'prismleaf_header_swap_menus'          => array(
				'default'     => false,
				'label'       => __( 'Swap primary and secondary menu', 'prismleaf' ),
				'description' => __( 'When enabled, the secondary menu appears above the header content area.', 'prismleaf' ),
			),
		);

		foreach ( $settings as $setting_id => $data ) {
			$wp_customize->add_setting(
				$setting_id,
				array(
					'type'              => 'theme_mod',
					'capability'        => 'edit_theme_options',
					'default'           => $data['default'],
					'sanitize_callback' => 'prismleaf_sanitize_checkbox',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				$setting_id,
				array(
					'type'        => 'checkbox',
					'section'     => 'prismleaf_header',
					'label'       => $data['label'],
					'description' => $data['description'],
				)
			);
		}
	}
}
add_action( 'customize_register', 'prismleaf_customize_register_header' );
