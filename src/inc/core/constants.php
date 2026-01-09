<?php
/**
 * Theme constants.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme version.
 *
 * @since 1.0.0
 */
if ( ! defined( 'PRISMLEAF_VERSION' ) ) {
	$theme_version = wp_get_theme()->get( 'Version' );
	define( 'PRISMLEAF_VERSION', $theme_version ? $theme_version : '1.0.0' );
}

/**
 * Theme directory path.
 *
 * @since 1.0.0
 */
if ( ! defined( 'PRISMLEAF_DIR' ) ) {
	define( 'PRISMLEAF_DIR', trailingslashit( get_template_directory() ) );
}

/**
 * Theme directory URI.
 *
 * @since 1.0.0
 */
if ( ! defined( 'PRISMLEAF_URI' ) ) {
	define( 'PRISMLEAF_URI', trailingslashit( get_template_directory_uri() ) );
}
