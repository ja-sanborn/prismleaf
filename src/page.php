<?php
/**
 * Page template for Prismleaf.
 *
 * Displays static page content with consistent spacing and landmarks.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! have_posts() ) {
	get_template_part( '404' );
	return;
}

get_header();
get_template_part( 'template-parts/entry-content', null, array( 'context' => 'page' ) );
get_footer();
