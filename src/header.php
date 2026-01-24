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

$is_framed        = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
$header_show      = prismleaf_get_theme_mod_bool( 'prismleaf_header_show', true );
$header_contained = prismleaf_get_theme_mod_bool( 'prismleaf_header_contained', true );

$outer_header = $header_show && ( $is_framed || ! $header_contained );
$inner_header = $header_show && ! $outer_header;

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#prismleaf-main">
	<?php esc_html_e( 'Skip to content', 'prismleaf' ); ?>
</a>

<div class="prismleaf-frame">
	<?php if ( $outer_header ) : ?>
		<header class="prismleaf-region-header prismleaf-region">
			<?php get_template_part( 'template-parts/regions/header-content' ); ?>
		</header>
	<?php endif; ?>

	<div class="prismleaf-frame-main">
		<div class="prismleaf-frame-inner">
			<?php if ( $inner_header ) : ?>
				<header class="prismleaf-region-header prismleaf-region">
					<?php get_template_part( 'template-parts/regions/header-content' ); ?>
				</header>
			<?php endif; ?>
			<main id="prismleaf-main" class="prismleaf-region-content prismleaf-region" tabindex="-1">
