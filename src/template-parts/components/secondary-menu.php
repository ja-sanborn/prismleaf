<?php
/**
 * Secondary Menu Template Part
 *
 * Outputs the secondary navigation menu.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$buttons      = prismleaf_get_theme_mod_bool( 'prismleaf_menu_secondary_buttons', false );
$has_dividers = $buttons ? false : prismleaf_get_theme_mod_bool( 'prismleaf_menu_secondary_divider', false );
$stretch      = $buttons ? false : prismleaf_get_theme_mod_bool( 'prismleaf_menu_secondary_stretch', true );
$classes      = array(
	'prismleaf-secondary-menu',
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

<nav class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" aria-label="<?php esc_attr_e( 'Secondary menu', 'prismleaf' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'secondary',
			'container'      => false,
			'menu_class'     => 'prismleaf-menu-list',
			'fallback_cb'    => false,
			'depth'          => 2,
		)
	);
	?>
</nav>
