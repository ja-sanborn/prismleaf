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

$site_icon_url = get_site_icon_url( 512 );
if ( ! $site_icon_url ) {
	return;
}

$site_title = get_bloginfo( 'name' );
$home_url  = home_url( '/' );
?>
<a
	href="<?php echo esc_url( $home_url ); ?>"
	class="prismleaf-site-icon"
	aria-label="<?php echo esc_attr( $site_title ); ?>"
	rel="home"
>
	<img src="<?php echo esc_url( $site_icon_url ); ?>" alt="<?php echo esc_attr( $site_title ); ?>" decoding="async" />
</a>
