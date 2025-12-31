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

$placeholder = (string) get_theme_mod( 'prismleaf_search_placeholder', __( 'Search', 'prismleaf' ) );
$flyout      = get_theme_mod( 'prismleaf_search_flyout', true );

$options = array(
	'placeholder' => $placeholder,
	'flyout'      => $flyout,
);

$options = prismleaf_prepare_search_options(
	$options,
	array(
		'flyout' => true,
	)
);

prismleaf_render_search_component( $options );
