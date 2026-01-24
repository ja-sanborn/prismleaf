<?php
/**
 * Layout Sidebar Template Part
 *
 * Renders left or right sidebar widget areas based on a passed argument.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine which sidebar this slot is rendering.
 *
 * Defaults to the primary menu when no argument is provided.
 */

$sidebar = '';
if ( isset( $args['sidebar'] ) && is_string( $args['sidebar'] ) ) {
	$sidebar = trim( strtolower( $args['sidebar'] ) );
}

if ( ! in_array( $sidebar, array( 'primary', 'secondary' ), true ) ) {
	return;
}

$sidebar_id = 'sidebar-' . $sidebar;

dynamic_sidebar( $sidebar_id );
