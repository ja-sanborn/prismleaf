<?php
/**
 * Prismleaf Search Template Part
 *
 * Outputs the Prismleaf Search component using Customizer-provided options.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$placeholder   = prismleaf_get_theme_mod_string( 'prismleaf_search_placeholder', __( 'Search', 'prismleaf' ) );
$flyout        = prismleaf_get_theme_mod_bool( 'prismleaf_search_flyout', true );
$header_has_bg = prismleaf_header_has_background_image();

$options = array(
	'placeholder'   => $placeholder,
	'flyout'        => $flyout,
	'header_has_bg' => $header_has_bg,
);

$options = prismleaf_prepare_search_options(
	$options,
	array(
		'flyout' => true,
	)
);

prismleaf_render_search_component( $options );
