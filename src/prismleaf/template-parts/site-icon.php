<?php
/**
 * Site icon component.
 *
 * Renders the site icon inside the header with a hover overlay.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! has_site_icon() ) {
	return;
}

$icon_size    = prismleaf_get_header_icon_pixel_size();
$site_icon_id = get_option( 'site_icon' );
$site_icon_url = '';

if ( $site_icon_id ) {
	$attachment = wp_get_attachment_image_src( $site_icon_id, array( $icon_size, $icon_size ) );
	if ( is_array( $attachment ) && ! empty( $attachment[0] ) ) {
		$site_icon_url = $attachment[0];
	}
}

if ( ! $site_icon_url ) {
	$site_icon_url = get_site_icon_url( $icon_size );
}

if ( ! $site_icon_url ) {
	return;
}

$site_title = get_bloginfo( 'name' );
$home_url   = home_url( '/' );
?>
<a
	href="<?php echo esc_url( $home_url ); ?>"
	class="prismleaf-site-icon"
	aria-label="<?php echo esc_attr( $site_title ); ?>"
	rel="home"
>
	<img
		src="<?php echo esc_url( $site_icon_url ); ?>"
		alt="<?php echo esc_attr( $site_title ); ?>"
		width="<?php echo esc_attr( $icon_size ); ?>"
		height="<?php echo esc_attr( $icon_size ); ?>"
		decoding="async"
	/>
</a>
