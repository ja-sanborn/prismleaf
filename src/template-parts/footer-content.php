<?php
/**
 * Layout Footer Template Part
 *
 * Structural placeholder for the footer region.
 * This file contains only minimal markup and dummy content.
 *
 * Presentation and layout behavior are handled elsewhere.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$widget_areas = array(
	'footer-1',
	'footer-2',
	'footer-3',
	'footer-4',
);

	$has_footer_widgets = false;
	foreach ( $widget_areas as $area ) {
		if ( is_active_sidebar( $area ) ) {
			$has_footer_widgets = true;
			break;
		}
	}

	$hide_footer_widgets_front = prismleaf_get_theme_mod_bool( 'prismleaf_footer_hide_widgets_on_front', false );
	$hide_footer_widgets_other = prismleaf_get_theme_mod_bool( 'prismleaf_footer_hide_widgets_on_other', false );
	$is_front_page          = is_front_page();
	$show_footer_widgets    = $has_footer_widgets
		&& ( ! ( $hide_footer_widgets_front && $is_front_page ) )
		&& ( ! ( $hide_footer_widgets_other && ! $is_front_page ) );

	$copyright_default = sprintf(
		/* translators: 1: Year, 2: Site name. */
		__( 'Copyright &copy; %1$d %2$s. All rights reserved.', 'prismleaf' ),
		(int) current_time( 'Y' ),
		get_bloginfo( 'name' )
	);

$copyright_text = prismleaf_get_theme_mod_string( 'prismleaf_footer_copyright_text', $copyright_default );
?>

<?php if ( $show_footer_widgets ) : ?>
	<div class="prismleaf-footer-row prismleaf-footer-row-widgets">
		<div class="prismleaf-footer-widgets">
			<?php foreach ( $widget_areas as $index => $area ) : ?>
				<div class="prismleaf-footer-widget prismleaf-footer-widget-<?php echo esc_attr( $index + 1 ); ?>">
					<?php
					if ( is_active_sidebar( $area ) ) {
						dynamic_sidebar( $area );
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<div class="prismleaf-footer-row prismleaf-footer-row-copyright">
	<div class="prismleaf-footer-copyright-group">
		<span class="prismleaf-footer-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></span>
		<span class="prismleaf-footer-powered-by">
			<?php
			printf(
				/* translators: 1: Theme name link. */
				wp_kses_post( __( 'Powered by %1$s WordPress Theme', 'prismleaf' ) ),
				'<a href="' . esc_url( 'https://github.com/ja-sanborn/prismleaf' ) . '" rel="noopener" target="_blank">Prismleaf</a>'
			);
			?>
		</span>
	</div>
</div>
