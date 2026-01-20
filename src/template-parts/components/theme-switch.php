<?php
/**
 * Theme switch component.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_switch = prismleaf_get_theme_mod_bool( 'prismleaf_palette_show_theme_switch', true );

if ( ! $show_switch ) {
	return;
}

$theme_mode      = prismleaf_get_theme_mod_theme_mode( 'prismleaf_palette_theme_mode', 'palette_theme_mode' );
$button_state    = 'system' === $theme_mode ? 'auto' : $theme_mode;
$button_disabled = 'system' !== $theme_mode;

$state_labels = array(
	'auto'  => __( 'Automatic (system preference)', 'prismleaf' ),
	'light' => __( 'Light appearance override', 'prismleaf' ),
	'dark'  => __( 'Dark appearance override', 'prismleaf' ),
);

$state_label = isset( $state_labels[ $button_state ] ) ? $state_labels[ $button_state ] : $state_labels['auto'];

$aria_label = $button_disabled
	? sprintf(
		/* translators: %s is the current enforced theme mode label. */
		__( 'Theme mode locked to %s.', 'prismleaf' ),
		$state_label
	)
	: __( 'Toggle the appearance between automatic, dark, and light.', 'prismleaf' );
?>
<button
	type="button"
	class="prismleaf-theme-switch"
	data-prismleaf-theme-switch
	data-prismleaf-theme-switch-state="<?php echo esc_attr( $button_state ); ?>"
	title="<?php echo esc_attr( $aria_label ); ?>"
	aria-label="<?php echo esc_attr( $aria_label ); ?>"
	<?php disabled( $button_disabled ); ?>
>
	<span class="screen-reader-text">
		<?php esc_html_e( 'Cycle the site appearance through automatic, dark, and light modes.', 'prismleaf' ); ?>
	</span>
</button>
