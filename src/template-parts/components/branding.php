<?php
/**
 * Site Metadata Template Part
 *
 * Outputs site icon, title, and tagline per Customizer settings.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon_corners     = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_icon_corners', '' );
$icon_visible     = prismleaf_get_theme_mod_bool( 'prismleaf_site_metadata_icon_visible', true );
$tagline_visible  = prismleaf_get_theme_mod_bool( 'prismleaf_site_metadata_tagline_visible', true );
$tagline_position = prismleaf_get_theme_mod_string( 'prismleaf_site_metadata_tagline_position', '' );
$header_has_bg    = prismleaf_header_has_background_image();

$tagline = (string) get_bloginfo( 'description' );

$logo_markup = '';
if ( function_exists( 'has_custom_logo' ) && has_custom_logo() && function_exists( 'get_custom_logo' ) ) {
	$logo_markup = (string) get_custom_logo();
}

if ( '' !== $logo_markup ) {
	echo wp_kses_post( $logo_markup );
	return;
}

$classes = array(
	'prismleaf-branding',
	'prismleaf-branding-responsive',
	( '' !== $icon_corners ) ? 'prismleaf-branding-icon-' . $icon_corners : '',
	( $tagline_visible && '' !== $tagline && '' !== $tagline_position )
		? 'prismleaf-branding-tagline-' . $tagline_position
		: '',
	( $tagline_visible && '' !== $tagline ) ? '' : 'prismleaf-branding-tagline-hidden',
	$header_has_bg ? 'prismleaf-branding-header-background' : '',
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
	<?php
	$has_icon = $icon_visible && ( 0 !== (int) get_option( 'site_icon', 0 ) );
	if ( $has_icon ) {
		get_template_part( 'template-parts/components/site-icon' );
	}
	?>

	<div class="prismleaf-branding-text">
		<?php
		$use_h1         = is_front_page() || is_home();
		$title_tag      = $use_h1 ? 'h1' : 'p';
		$safe_title_tag = tag_escape( $title_tag );
		?>
		<<?php echo esc_html( $safe_title_tag ); ?> class="prismleaf-site-title site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		</<?php echo esc_html( $safe_title_tag ); ?>>

		<?php if ( $tagline_visible && '' !== $tagline ) : ?>
			<p class="prismleaf-site-description site-description">
				<?php echo esc_html( $tagline ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>
