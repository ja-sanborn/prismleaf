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

$icon_visible     = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_site_metadata_icon_visible', true ) );
$icon_corners     = (string) get_theme_mod( 'prismleaf_site_metadata_icon_corners', null );
$tagline_visible  = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_site_metadata_tagline_visible', true ) );
$tagline_position = (string) get_theme_mod( 'prismleaf_site_metadata_tagline_position', null );

if ( function_exists( 'prismleaf_sanitize_site_metadata_icon_corners' ) ) {
	$icon_corners = prismleaf_sanitize_site_metadata_icon_corners( $icon_corners );
}

if ( function_exists( 'prismleaf_sanitize_site_metadata_tagline_position' ) ) {
	$tagline_position = prismleaf_sanitize_site_metadata_tagline_position( $tagline_position );
}

$tagline = (string) get_bloginfo( 'description' );

$logo_markup = '';
if ( function_exists( 'has_custom_logo' ) && has_custom_logo() && function_exists( 'get_custom_logo' ) ) {
	$logo_markup = (string) get_custom_logo();
}

if ( '' !== $logo_markup ) {
	echo $logo_markup;
	return;
}

$icon_markup = '';
if ( $icon_visible ) {
	$icon_id = (int) get_option( 'site_icon', 0 );
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
}

$has_icon = '' !== $icon_markup;

$classes = array(
	'prismleaf-site-metadata',
	$has_icon ? 'prismleaf-site-metadata--has-icon' : 'prismleaf-site-metadata--no-icon',
	( '' !== $icon_corners ) ? 'prismleaf-site-metadata--icon-' . $icon_corners : '',
	( $tagline_visible && '' !== $tagline && '' !== $tagline_position )
		? 'prismleaf-site-metadata--tagline-' . $tagline_position
		: '',
	( $tagline_visible && '' !== $tagline ) ? '' : 'prismleaf-site-metadata--tagline-hidden',
);

$classes = array_values(
	array_filter(
		$classes,
		static function( $value ) {
			return '' !== (string) $value;
		}
	)
);
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $has_icon ) : ?>
		<div class="prismleaf-site-icon">
			<?php echo $icon_markup; ?>
		</div>
	<?php endif; ?>

	<div class="prismleaf-site-metadata__text">
		<?php
		$use_h1 = is_front_page() || is_home();
		$title_tag = $use_h1 ? 'h1' : 'p';
		?>
		<<?php echo $title_tag; ?> class="prismleaf-site-title site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		</<?php echo $title_tag; ?>>

		<?php if ( $tagline_visible && '' !== $tagline ) : ?>
			<p class="prismleaf-site-description site-description">
				<?php echo esc_html( $tagline ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>
