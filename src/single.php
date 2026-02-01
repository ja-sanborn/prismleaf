<?php
/**
 * Single post template for Prismleaf.
 *
 * Displays the entry content, metadata, and navigation for a single post.
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
get_template_part( 'template-parts/entry-content', null, array( 'context' => 'single' ) );
get_footer();
