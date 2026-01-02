<?php
/**
 * Site Icon Template Part
 *
 * Outputs the site icon per Customizer settings.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon_corners = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_icon_corners', '' );
$icon_border  = prismleaf_get_theme_mod_bool( 'prismleaf_site_metadata_icon_border', false );

$icon_markup = '';
$icon_id     = (int) get_option( 'site_icon', 0 );
if ( $icon_id ) {
	$icon_image = wp_get_attachment_image(
		$icon_id,
		'full',
		false,
		array(
			'class' => 'prismleaf-site-icon-image',
			'alt'   => esc_attr( get_bloginfo( 'name' ) ),
		)
	);
	if ( $icon_image ) {
		$icon_markup = sprintf(
			'<a href="%s" class="prismleaf-site-icon-link" rel="home">%s</a>',
			esc_url( home_url( '/' ) ),
			$icon_image
		);
	}
}

$has_icon = '' !== $icon_markup;
if ( ! $has_icon ) {
	return;
}

$classes = array(
	'prismleaf-site-icon',
	( '' !== $icon_corners ) ? 'prismleaf-site-icon-icon-' . $icon_corners : '',
	$icon_border ? 'prismleaf-site-icon-border' : '',
);

$classes = array_values(
	array_filter(
		$classes,
		static function ( $value ) {
			return '' !== (string) $value;
		}
	)
);
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php echo wp_kses_post( $icon_markup ); ?>
</div>
