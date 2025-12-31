<?php
/**
 * Header template.
 *
 * Outputs the document head and opens the primary layout regions.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$prismleaf_framed           = (bool) get_theme_mod( 'prismleaf_layout_framed', false );
$prismleaf_header_visible   = (bool) get_theme_mod( 'prismleaf_layout_header_visible', true );
$prismleaf_header_contained = (bool) get_theme_mod( 'prismleaf_layout_header_contained', true );
$prismleaf_mobile           = wp_is_mobile();

// When all regions are hidden, framed is treated as off.
if ( function_exists( 'prismleaf_layout_all_regions_hidden' ) && prismleaf_layout_all_regions_hidden() ) {
	$prismleaf_framed = false;
}

// Mobile layout is never framed or contained.
if ( $prismleaf_mobile ) {
	$prismleaf_framed           = false;
	$prismleaf_header_contained = false;
}

// Framed layout forces not-contained regions.
if ( $prismleaf_framed ) {
	$prismleaf_header_contained = false;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#prismleaf-content">
	<?php esc_html_e( 'Skip to content', 'prismleaf' ); ?>
</a>

<div class="prismleaf-frame">
	<?php if ( $prismleaf_header_visible && ( $prismleaf_framed || ! $prismleaf_header_contained ) ) : ?>
		<header class="prismleaf-region prismleaf-region-header">
			<?php get_template_part( 'template-parts/core/layout-header' ); ?>
		</header>
	<?php endif; ?>

	<div class="prismleaf-region prismleaf-region-middle">
		<div class="prismleaf-region prismleaf-region-content">
			<?php if ( $prismleaf_header_visible && ( ! $prismleaf_framed && $prismleaf_header_contained ) ) : ?>
				<header class="prismleaf-region prismleaf-region-header">
					<?php get_template_part( 'template-parts/core/layout-header' ); ?>
				</header>
			<?php endif; ?>
			<main id="prismleaf-content" class="prismleaf-content" tabindex="-1">
