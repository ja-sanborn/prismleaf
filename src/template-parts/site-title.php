<?php
/**
 * Site title component.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_title       = get_bloginfo( 'name' );
$tagline          = get_bloginfo( 'description' );
$show_tagline     = prismleaf_get_theme_mod_bool( 'prismleaf_header_show_tagline', true ) && '' !== $tagline;
$title_position   = prismleaf_get_theme_mod_header_title_position();
$tagline_position = prismleaf_get_theme_mod_header_tagline_position();
$use_heading_tag  = is_front_page() || is_home();
$title_tag        = $use_heading_tag ? 'h1' : 'span';

$title_classes = array(
	'prismleaf-site-title',
	'prismleaf-site-title-position-' . $title_position,
);

$tagline_classes = array(
	'prismleaf-site-tagline',
	'prismleaf-site-tagline-' . $tagline_position,
);
?>
<div class="<?php echo esc_attr( implode( ' ', $title_classes ) ); ?>">
	<<?php echo esc_html( $title_tag ); ?> class="prismleaf-site-title-text">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php echo esc_html( $site_title ); ?>
		</a>
	</<?php echo esc_html( $title_tag ); ?>>
	<?php if ( $show_tagline ) : ?>
		<span class="<?php echo esc_attr( implode( ' ', $tagline_classes ) ); ?>">
			<?php echo esc_html( $tagline ); ?>
		</span>
	<?php endif; ?>
</div>
