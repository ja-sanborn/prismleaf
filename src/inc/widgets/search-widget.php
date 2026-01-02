<?php
/**
 * Prismleaf Search Widget registration.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Prismleaf Search widget.
 *
 * @since 1.0.0
 *
 * @return void
 */
function prismleaf_register_search_widget() {
	register_widget( 'Prismleaf_Search_Widget' );
}
add_action( 'widgets_init', 'prismleaf_register_search_widget' );
