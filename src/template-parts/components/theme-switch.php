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

$force_light    = (bool) wp_validate_boolean( get_theme_mod( 'prismleaf_brand_force_light', false ) );
$initial_state  = $force_light ? 'light' : 'auto';
$force_light_ui = $force_light ? 'true' : 'false';
?>

<div class="prismleaf-theme-switch" data-initial-state="<?php echo esc_attr( $initial_state ); ?>" data-force-light="<?php echo esc_attr( $force_light_ui ); ?>">
	<button type="button" class="prismleaf-theme-switch__button" aria-label="<?php esc_attr_e( 'Toggle color scheme', 'prismleaf' ); ?>" <?php disabled( $force_light ); ?>>
		<span class="prismleaf-theme-switch__icon" aria-hidden="true"></span>
		<span class="screen-reader-text prismleaf-theme-switch__live" aria-live="polite" aria-atomic="true"><?php esc_html_e( 'Toggle color scheme', 'prismleaf' ); ?></span>
	</button>
</div>
