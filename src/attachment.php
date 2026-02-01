<?php
/**
 * Attachment template for Prismleaf.
 *
 * Presents the selected media item with context and metadata.
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
get_template_part( 'template-parts/entry-content', null, array( 'context' => 'attachment' ) );
get_footer();
