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

$show_theme_switch = prismleaf_get_theme_mod_bool( 'prismleaf_header_show_theme_switch', true );
$show_search       = prismleaf_get_theme_mod_bool( 'prismleaf_header_show_search', true );
$show_primary      = prismleaf_get_theme_mod_bool( 'prismleaf_header_show_primary_menu', true );
$show_secondary    = prismleaf_get_theme_mod_bool( 'prismleaf_header_show_secondary_menu', true );
$swap_menus        = prismleaf_get_theme_mod_bool( 'prismleaf_header_swap_menus', false );

$has_primary_menu   = $show_primary && has_nav_menu( 'primary' );
$has_secondary_menu = $show_secondary && has_nav_menu( 'secondary' );
$has_mobile_menu    = has_nav_menu( 'mobile' ) || has_nav_menu( 'primary' );

$top_menu_template    = $swap_menus ? 'secondary-menu' : 'primary-menu';
$bottom_menu_template = $swap_menus ? 'primary-menu' : 'secondary-menu';

$top_menu_visible    = $swap_menus ? $has_secondary_menu : $has_primary_menu;
$bottom_menu_visible = $swap_menus ? $has_primary_menu : $has_secondary_menu;

$show_tools = $show_search || $show_theme_switch;

$header_classes = array(
	'prismleaf-header',
	$show_tools ? '' : 'prismleaf-header-no-tools',
);

$header_classes = array_values(
	array_filter(
		$header_classes,
		static function ( $value ) {
			return '' !== (string) $value;
		}
	)
);
?>

<div class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>">
	<?php if ( $top_menu_visible ) : ?>
		<div class="prismleaf-header-row prismleaf-header-row-primary">
			<?php get_template_part( 'template-parts/components/' . $top_menu_template ); ?>
		</div>
	<?php endif; ?>

	<div class="prismleaf-header-row prismleaf-header-row-content">
		<div class="prismleaf-header-content">
			<?php if ( $has_mobile_menu ) : ?>
				<div class="prismleaf-header-mobile-toggle">
					<?php get_template_part( 'template-parts/components/mobile-menu' ); ?>
				</div>
			<?php endif; ?>

			<div class="prismleaf-header-branding">
				<?php get_template_part( 'template-parts/components/branding' ); ?>
			</div>

			<?php if ( $show_tools ) : ?>
				<div class="prismleaf-header-tools">
					<?php if ( $show_search ) : ?>
						<?php get_template_part( 'template-parts/components/search' ); ?>
					<?php endif; ?>
					<?php if ( $show_theme_switch ) : ?>
						<?php get_template_part( 'template-parts/components/theme-switch' ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $bottom_menu_visible ) : ?>
		<div class="prismleaf-header-row prismleaf-header-row-secondary">
			<?php get_template_part( 'template-parts/components/' . $bottom_menu_template ); ?>
		</div>
	<?php endif; ?>
</div>
