<?php
/**
 * Theme Switcher Template Part
 *
 * Renders the client-side color scheme toggle (auto/light/dark).
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$force_light    = prismleaf_get_theme_mod_bool( 'prismleaf_brand_force_light', false );
$initial_state  = $force_light ? 'light' : 'auto';
$force_light_ui = $force_light ? 'true' : 'false';
$header_has_bg  = prismleaf_header_has_background_image();
$classes        = array(
	'prismleaf-theme-switch',
	$header_has_bg ? 'prismleaf-theme-switch-header-background' : '',
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

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-initial-state="<?php echo esc_attr( $initial_state ); ?>" data-force-light="<?php echo esc_attr( $force_light_ui ); ?>">
	<button type="button" class="prismleaf-theme-switch-button" aria-label="<?php esc_attr_e( 'Toggle color scheme', 'prismleaf' ); ?>" <?php disabled( $force_light ); ?>>
		<span class="prismleaf-theme-switch-icon" aria-hidden="true"></span>
		<span class="screen-reader-text prismleaf-theme-switch-live" aria-live="polite" aria-atomic="true"><?php esc_html_e( 'Toggle color scheme', 'prismleaf' ); ?></span>
	</button>
</div>
