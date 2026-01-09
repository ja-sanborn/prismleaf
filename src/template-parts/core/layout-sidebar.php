<?php
/**
 * Layout Sidebar Template Part
 *
 * Renders left or right sidebar widget areas based on a passed position.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$position = '';
if ( isset( $args['position'] ) && is_string( $args['position'] ) ) {
	$position = strtolower( $args['position'] );
}

if ( ! in_array( $position, array( 'left', 'right' ), true ) ) {
	return;
}

$sidebar_id = 'sidebar-' . $position;

if ( ! is_active_sidebar( $sidebar_id ) ) {
	return;
}

dynamic_sidebar( $sidebar_id );
