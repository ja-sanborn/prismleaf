<?php
/**
 * Primary Menu Template Part
 *
 * Outputs the primary navigation menu.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$buttons      = prismleaf_get_theme_mod_bool( 'prismleaf_menu_primary_buttons', false );
$has_dividers = $buttons ? false : prismleaf_get_theme_mod_bool( 'prismleaf_menu_primary_divider', false );
$stretch      = $buttons ? false : prismleaf_get_theme_mod_bool( 'prismleaf_menu_primary_stretch', true );
$classes      = array(
	'prismleaf-primary-menu',
	'prismleaf-menu',
	$has_dividers ? 'prismleaf-menu-with-dividers' : '',
	$stretch ? 'prismleaf-menu-stretch' : '',
	$buttons ? 'prismleaf-menu-buttons' : '',
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

<nav class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" aria-label="<?php esc_attr_e( 'Primary menu', 'prismleaf' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'prismleaf-menu-list',
			'fallback_cb'    => false,
			'depth'          => 2,
		)
	);
	?>
</nav>
