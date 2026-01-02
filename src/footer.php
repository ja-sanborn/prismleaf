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

// Mobile layout is never framed or contained.
if ( $prismleaf_mobile ) {
	$prismleaf_framed                  = false;
	$prismleaf_footer_contained        = false;
	$prismleaf_sidebar_left_contained  = false;
	$prismleaf_sidebar_right_contained = false;
}
?>
			</main>
			<?php if ( $prismleaf_sidebar_left_visible && ( ! $prismleaf_framed && $prismleaf_sidebar_left_contained ) ) : ?>
				<aside class="prismleaf-region prismleaf-region-sidebar-left">
					<?php get_template_part( 'template-parts/core/layout-sidebar-left' ); ?>
				</aside>
			<?php endif; ?>

			<?php if ( $prismleaf_sidebar_right_visible && ( ! $prismleaf_framed && $prismleaf_sidebar_right_contained ) ) : ?>
				<aside class="prismleaf-region prismleaf-region-sidebar-right">
					<?php get_template_part( 'template-parts/core/layout-sidebar-right' ); ?>
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
			<?php get_template_part( 'template-parts/core/layout-sidebar-left' ); ?>
		</aside>
	<?php endif; ?>

	<?php if ( $prismleaf_sidebar_right_visible && ( $prismleaf_framed || ! $prismleaf_sidebar_right_contained ) ) : ?>
		<aside class="prismleaf-region prismleaf-region-sidebar-right">
			<?php get_template_part( 'template-parts/core/layout-sidebar-right' ); ?>
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
