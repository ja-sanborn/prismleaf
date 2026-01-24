<?php
/**
 * Layout Header Template Part
 *
 * Structural placeholder for the header region.
 * This file outputs markup only.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon_position       = prismleaf_get_theme_mod_header_icon_position();
$show_icon           = 'none' !== $icon_position && has_site_icon();
$row_classes         = array( 'prismleaf-header-row' );
$row_classes[]       = $show_icon ? 'prismleaf-header-row-with-icon' : 'prismleaf-header-row-no-icon';
$row_classes[]       = 'prismleaf-header-row-icon-' . ( $show_icon ? $icon_position : 'none' );
?>

<div
	class="<?php echo esc_attr( implode( ' ', $row_classes ) ); ?>"
	data-prismleaf-icon-position="<?php echo esc_attr( $icon_position ); ?>"
>
	<div class="prismleaf-header-column prismleaf-header-column-title">
		<?php get_template_part( 'template-parts/components/site-title' ); ?>
	</div>

	<div class="prismleaf-header-column prismleaf-header-column-icon">
		<?php if ( $show_icon ) : ?>
			<?php get_template_part( 'template-parts/components/site-icon' ); ?>
		<?php endif; ?>
	</div>

	<div class="prismleaf-header-column prismleaf-header-column-meta">
		<?php do_action( 'prismleaf_header_center_content' ); ?>
		<?php get_template_part( 'template-parts/components/theme-switch' ); ?>
	</div>
</div>
