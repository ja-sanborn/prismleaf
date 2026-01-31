<?php
/**
 * Footer template.
 *
 * Closes the primary layout regions and outputs the footer content.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_framed           = prismleaf_get_theme_mod_bool( 'prismleaf_global_framed_layout', false );
$footer_show         = prismleaf_get_theme_mod_bool( 'prismleaf_footer_show', true );
$footer_contained    = prismleaf_get_theme_mod_bool( 'prismleaf_footer_contained', true );
$swap_sidebars       = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_swap', false );
$primary_show        = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_show', true );
$primary_contained   = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_primary_contained', true );
$secondary_show      = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_show', true );
$secondary_contained = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_secondary_contained', true );
$hide_sidebars_front = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_hide_on_front', false );
$hide_sidebars_other = prismleaf_get_theme_mod_bool( 'prismleaf_sidebar_hide_on_other', false );

if ( $hide_sidebars_front && is_front_page() ) {
	$primary_show   = false;
	$secondary_show = false;
}

if ( $hide_sidebars_other && ! is_front_page() ) {
	$primary_show   = false;
	$secondary_show = false;
}

$primary_has_widgets   = is_active_sidebar( 'sidebar-primary' );
$secondary_has_widgets = is_active_sidebar( 'sidebar-secondary' );

$outer_footer = $footer_show && ( $is_framed || ! $footer_contained );
$inner_footer = $footer_show && ! $outer_footer;

$outer_left_sidebar  = $primary_show && $primary_has_widgets && ( $is_framed || ! $primary_contained );
$outer_right_sidebar = $secondary_show && $secondary_has_widgets && ( $is_framed || ! $secondary_contained );
$inner_left_sidebar  = $primary_show && $primary_has_widgets && ! $outer_left_sidebar;
$inner_right_sidebar = $secondary_show && $secondary_has_widgets && ! $outer_right_sidebar;
$left_sidebar        = array( 'sidebar' => 'primary' );
$right_sidebar       = array( 'sidebar' => 'secondary' );

if ( $swap_sidebars ) {
	$outer_left_sidebar  = $secondary_show && $secondary_has_widgets && ( $is_framed || ! $secondary_contained );
	$outer_right_sidebar = $primary_show && $primary_has_widgets && ( $is_framed || ! $primary_contained );
	$inner_left_sidebar  = $secondary_show && $secondary_has_widgets && ! $outer_left_sidebar;
	$inner_right_sidebar = $primary_show && $primary_has_widgets && ! $outer_right_sidebar;
	$left_sidebar		 = array( 'sidebar' => 'secondary' );
	$right_sidebar		 = array( 'sidebar' => 'primary' );
}

if ( ( $outer_left_sidebar || $outer_right_sidebar ) && $inner_footer ) {
	$inner_footer = false;
	$outer_footer = true;
}
?>
			</main><!-- .prismleaf-region-content -->
			<?php if ( $inner_left_sidebar ) : ?>
				<aside class="prismleaf-region-sidebar-left prismleaf-region">
					<?php get_template_part( 'template-parts/sidebar-content', null, $left_sidebar ); ?>
				</aside>
			<?php endif; ?>

			<?php if ( $inner_right_sidebar ) : ?>
				<aside class="prismleaf-region-sidebar-right prismleaf-region">
					<?php get_template_part( 'template-parts/sidebar-content', null, $right_sidebar ); ?>
				</aside>
			<?php endif; ?>

			<?php if ( $inner_footer ) : ?>
				<footer class="prismleaf-region-footer prismleaf-region">
					<?php get_template_part( 'template-parts/footer-content' ); ?>
				</footer>
			<?php endif; ?>
		</div><!-- .prismleaf-frame-inner -->
	</div><!-- .prismleaf-frame-main -->

	<?php if ( $outer_left_sidebar ) : ?>
		<aside class="prismleaf-region-sidebar-left prismleaf-region">
			<?php get_template_part( 'template-parts/sidebar-content', null, $left_sidebar ); ?>
		</aside>
	<?php endif; ?>

	<?php if ( $outer_right_sidebar ) : ?>
		<aside class="prismleaf-region-sidebar-right prismleaf-region">
			<?php get_template_part( 'template-parts/sidebar-content', null, $right_sidebar ); ?>
		</aside>
	<?php endif; ?>

	<?php if ( $outer_footer ) : ?>
		<footer class="prismleaf-region-footer prismleaf-region">
			<?php get_template_part( 'template-parts/footer-content' ); ?>
		</footer>
	<?php endif; ?>
</div><!-- .prismleaf-frame -->

<?php wp_footer(); ?>
</body>
</html>
