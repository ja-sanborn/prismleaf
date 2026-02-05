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

$icon_position = prismleaf_get_theme_mod_header_icon_position();
$show_icon     = 'none' !== $icon_position && has_site_icon();
$row_classes   = array( 'prismleaf-header-row' );
$row_classes[] = $show_icon ? 'prismleaf-header-row-with-icon' : 'prismleaf-header-row-no-icon';
$row_classes[] = 'prismleaf-header-row-icon-' . ( $show_icon ? $icon_position : 'none' );

$primary_menu_exists   = has_nav_menu( 'primary' );
$secondary_menu_exists = has_nav_menu( 'secondary' );
$mobile_menu_exists    = has_nav_menu( 'mobile' );

$primary_strip     = prismleaf_get_theme_mod_bool( 'prismleaf_menu_primary_strip', true );
$primary_divider   = prismleaf_get_theme_mod_bool( 'prismleaf_menu_primary_divider', true );
$primary_corners   = prismleaf_get_theme_mod_menu_button_corners( 'prismleaf_menu_primary_button_corners', 'primary_menu_button_corners' );
$secondary_strip   = prismleaf_get_theme_mod_bool( 'prismleaf_menu_secondary_strip', true );
$secondary_divider = prismleaf_get_theme_mod_bool( 'prismleaf_menu_secondary_divider', true );
$secondary_corners = prismleaf_get_theme_mod_menu_button_corners( 'prismleaf_menu_secondary_button_corners', 'secondary_menu_button_corners' );
$mobile_panel      = prismleaf_get_theme_mod_bool( 'prismleaf_menu_mobile_panel', true );
$mobile_divider    = prismleaf_get_theme_mod_bool( 'prismleaf_menu_mobile_divider', true );
$mobile_corners    = prismleaf_get_theme_mod_menu_button_corners( 'prismleaf_menu_mobile_button_corners', 'mobile_menu_button_corners' );

$primary_strip_attr   = $primary_strip ? 'true' : 'false';
$primary_div_attr     = $primary_divider ? 'true' : 'false';
$secondary_strip_attr = $secondary_strip ? 'true' : 'false';
$secondary_div_attr   = $secondary_divider ? 'true' : 'false';
$mobile_panel_attr    = $mobile_panel ? 'true' : 'false';
$mobile_div_attr      = $mobile_divider ? 'true' : 'false';

$primary_menu_id        = 'prismleaf-primary-menu';
$secondary_menu_id      = 'prismleaf-secondary-menu';
$mobile_menu_id         = 'prismleaf-mobile-menu';
$mobile_menu_overlay_id = 'prismleaf-mobile-menu-overlay';
?>

<?php if ( $primary_menu_exists ) : ?>
	<div class="prismleaf-header-menu-row prismleaf-header-menu-row-primary">
		<nav class="prismleaf-menu prismleaf-menu-primary" role="navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'prismleaf' ); ?>" data-prismleaf-menu="primary" data-prismleaf-menu-strip="<?php echo esc_attr( $primary_strip_attr ); ?>" data-prismleaf-menu-divider="<?php echo esc_attr( $primary_div_attr ); ?>" data-prismleaf-menu-button-corners="<?php echo esc_attr( $primary_corners ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_id'        => $primary_menu_id,
					'items_wrap'     => '<ul class="prismleaf-menu-items %2$s">%3$s</ul>',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
<?php endif; ?>

<div class="<?php echo esc_attr( implode( ' ', $row_classes ) ); ?>" data-prismleaf-icon-position="<?php echo esc_attr( $icon_position ); ?>">
	<div class="prismleaf-header-column prismleaf-header-column-title">
		<?php get_template_part( 'template-parts/site-title' ); ?>
	</div>

	<div class="prismleaf-header-column prismleaf-header-column-icon">
		<?php if ( $show_icon ) : ?>
			<?php get_template_part( 'template-parts/site-icon' ); ?>
		<?php endif; ?>
	</div>

	<div class="prismleaf-header-column prismleaf-header-column-meta">
		<div class="prismleaf-header-meta">
			<?php do_action( 'prismleaf_header_center_content' ); ?>
			<?php get_template_part( 'template-parts/theme-switch' ); ?>
			<?php if ( $mobile_menu_exists ) : ?>
				<button
					type="button"
					class="prismleaf-mobile-menu-toggle"
					id="prismleaf-mobile-menu-toggle"
					aria-controls="<?php echo esc_attr( $mobile_menu_overlay_id ); ?>"
					aria-expanded="false"
					data-prismleaf-mobile-menu-toggle
				>
					<span class="screen-reader-text"><?php esc_html_e( 'Toggle mobile menu', 'prismleaf' ); ?></span>
					<span aria-hidden="true" class="prismleaf-mobile-menu-icon"></span>
				</button>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if ( $secondary_menu_exists ) : ?>
	<div class="prismleaf-header-menu-row prismleaf-header-menu-row-secondary">
		<nav class="prismleaf-menu prismleaf-menu-secondary" role="navigation" aria-label="<?php esc_attr_e( 'Secondary menu', 'prismleaf' ); ?>" data-prismleaf-menu="secondary" data-prismleaf-menu-strip="<?php echo esc_attr( $secondary_strip_attr ); ?>" data-prismleaf-menu-divider="<?php echo esc_attr( $secondary_div_attr ); ?>" data-prismleaf-menu-button-corners="<?php echo esc_attr( $secondary_corners ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'secondary',
					'container'      => false,
					'menu_id'        => $secondary_menu_id,
					'items_wrap'     => '<ul class="prismleaf-menu-items %2$s">%3$s</ul>',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
<?php endif; ?>

<?php if ( $mobile_menu_exists ) : ?>
	<div
		id="<?php echo esc_attr( $mobile_menu_overlay_id ); ?>"
		class="prismleaf-mobile-menu-overlay"
		role="presentation"
		tabindex="-1"
		aria-hidden="true"
		data-prismleaf-mobile-menu
		data-prismleaf-mobile-menu-panel="<?php echo esc_attr( $mobile_panel_attr ); ?>"
	>
		<nav
			class="prismleaf-menu prismleaf-menu-mobile"
			role="navigation"
			aria-label="<?php esc_attr_e( 'Mobile menu', 'prismleaf' ); ?>"
			data-prismleaf-menu="mobile"
			data-prismleaf-mobile-menu-divider="<?php echo esc_attr( $mobile_div_attr ); ?>"
			data-prismleaf-menu-button-corners="<?php echo esc_attr( $mobile_corners ); ?>"
		>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'mobile',
					'container'      => false,
					'menu_id'        => $mobile_menu_id,
					'items_wrap'     => '<ul class="prismleaf-menu-items prismleaf-mobile-menu-items %2$s">%3$s</ul>',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
<?php endif; ?>
