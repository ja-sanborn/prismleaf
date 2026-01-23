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
$theme_switch_left   = 'right' === $icon_position;
$row_classes         = array( 'prismleaf-header-row' );
$row_classes[]       = $show_icon ? 'prismleaf-header-row-with-icon' : 'prismleaf-header-row-without-icon';
?>

<div class="<?php echo esc_attr( implode( ' ', $row_classes ) ); ?>">
	<div class="prismleaf-header-column prismleaf-header-column-left">
		<?php if ( $theme_switch_left ) : ?>
			<?php get_template_part( 'template-parts/components/theme-switch' ); ?>
		<?php elseif ( 'left' === $icon_position && $show_icon ) : ?>
			<?php get_template_part( 'template-parts/components/site-icon' ); ?>
		<?php endif; ?>
	</div>

	<?php if ( $show_icon ) : ?>
		<div class="prismleaf-header-column prismleaf-header-column-center">
			<?php do_action( 'prismleaf_header_center_content' ); ?>
		</div>
	<?php endif; ?>

	<div class="prismleaf-header-column prismleaf-header-column-right">
		<?php if ( ! $theme_switch_left ) : ?>
			<?php get_template_part( 'template-parts/components/theme-switch' ); ?>
		<?php elseif ( 'right' === $icon_position && $show_icon ) : ?>
			<?php get_template_part( 'template-parts/components/site-icon' ); ?>
		<?php endif; ?>
	</div>
</div>
