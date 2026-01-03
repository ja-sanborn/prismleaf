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

$prismleaf_framed = prismleaf_get_theme_mod_bool( 'prismleaf_layout_framed', false );
$prismleaf_mobile = wp_is_mobile();

$prismleaf_footer_visible   = prismleaf_get_theme_mod_bool( 'prismleaf_layout_footer_visible', true );
$prismleaf_footer_contained = prismleaf_get_theme_mod_bool( 'prismleaf_layout_footer_contained', true );

$prismleaf_sidebar_left_visible   = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_left_visible', true );
$prismleaf_sidebar_left_contained = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_left_contained', true );

$prismleaf_sidebar_right_visible   = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_right_visible', true );
$prismleaf_sidebar_right_contained = prismleaf_get_theme_mod_bool( 'prismleaf_layout_sidebar_right_contained', true );

$prismleaf_sidebar_left_active  = is_active_sidebar( 'sidebar-left' );
$prismleaf_sidebar_right_active = is_active_sidebar( 'sidebar-right' );

// Mobile layout is never framed or contained.
if ( $prismleaf_mobile ) {
	$prismleaf_framed                  = false;
	$prismleaf_footer_contained        = false;
	$prismleaf_sidebar_left_contained  = false;
	$prismleaf_sidebar_right_contained = false;
}

if ( ! $prismleaf_sidebar_left_active ) {
	$prismleaf_sidebar_left_visible = false;
}

if ( ! $prismleaf_sidebar_right_active ) {
	$prismleaf_sidebar_right_visible = false;
}
?>
			</main>
			<?php if ( $prismleaf_sidebar_left_visible && ( ! $prismleaf_framed && $prismleaf_sidebar_left_contained ) ) : ?>
				<aside class="prismleaf-region prismleaf-region-sidebar-left">
					<?php get_template_part( 'template-parts/core/layout-sidebar', null, array( 'position' => 'left' ) ); ?>
				</aside>
			<?php endif; ?>

			<?php if ( $prismleaf_sidebar_right_visible && ( ! $prismleaf_framed && $prismleaf_sidebar_right_contained ) ) : ?>
				<aside class="prismleaf-region prismleaf-region-sidebar-right">
					<?php get_template_part( 'template-parts/core/layout-sidebar', null, array( 'position' => 'right' ) ); ?>
				</aside>
			<?php endif; ?>

			<?php if ( $prismleaf_footer_visible && ( ! $prismleaf_framed && $prismleaf_footer_contained ) ) : ?>
				<footer class="prismleaf-region prismleaf-region-footer">
					<?php get_template_part( 'template-parts/core/layout-footer' ); ?>
				</footer>
			<?php endif; ?>
		</div><!-- .prismleaf-region-content -->
	</div><!-- .prismleaf-region-middle -->

	<?php if ( $prismleaf_sidebar_left_visible && ( $prismleaf_framed || ! $prismleaf_sidebar_left_contained ) ) : ?>
		<aside class="prismleaf-region prismleaf-region-sidebar-left">
			<?php get_template_part( 'template-parts/core/layout-sidebar', null, array( 'position' => 'left' ) ); ?>
		</aside>
	<?php endif; ?>

	<?php if ( $prismleaf_sidebar_right_visible && ( $prismleaf_framed || ! $prismleaf_sidebar_right_contained ) ) : ?>
		<aside class="prismleaf-region prismleaf-region-sidebar-right">
			<?php get_template_part( 'template-parts/core/layout-sidebar', null, array( 'position' => 'right' ) ); ?>
		</aside>
	<?php endif; ?>

	<?php if ( $prismleaf_footer_visible && ( $prismleaf_framed || ! $prismleaf_footer_contained ) ) : ?>
		<footer class="prismleaf-region prismleaf-region-footer">
			<?php get_template_part( 'template-parts/core/layout-footer' ); ?>
		</footer>
	<?php endif; ?>
</div><!-- .prismleaf-frame -->

<?php wp_footer(); ?>
</body>
</html>
