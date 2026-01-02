<?php
/**
 * Mobile Menu Template Part
 *
 * Outputs the mobile navigation toggle and dialog.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$menu_location = '';
if ( has_nav_menu( 'mobile' ) ) {
	$menu_location = 'mobile';
} elseif ( has_nav_menu( 'primary' ) ) {
	$menu_location = 'primary';
}

if ( '' === $menu_location ) {
	return;
}

$rounded = prismleaf_get_theme_mod_bool( 'prismleaf_menu_mobile_rounded_corners', true );

static $instance = 0;
++$instance;

$dialog_id = 'prismleaf-mobile-menu-dialog-' . $instance;
$toggle_id = 'prismleaf-mobile-menu-toggle-' . $instance;

$dialog_classes = array(
	'prismleaf-mobile-menu-dialog',
	$rounded ? 'prismleaf-mobile-menu-dialog-rounded' : 'prismleaf-mobile-menu-dialog-square',
);

$dialog_classes = array_values(
	array_filter(
		$dialog_classes,
		static function ( $value ) {
			return '' !== (string) $value;
		}
	)
);

$menu_classes = array(
	'prismleaf-mobile-menu-menu',
	'prismleaf-menu',
);

$menu_classes = array_values(
	array_filter(
		$menu_classes,
		static function ( $value ) {
			return '' !== (string) $value;
		}
	)
);
?>

<div class="prismleaf-mobile-menu">
	<button
		type="button"
		id="<?php echo esc_attr( $toggle_id ); ?>"
		class="prismleaf-mobile-menu-toggle"
		data-prismleaf-mobile-menu-toggle
		data-label-open="<?php esc_attr_e( 'Open menu', 'prismleaf' ); ?>"
		data-label-close="<?php esc_attr_e( 'Close menu', 'prismleaf' ); ?>"
		aria-controls="<?php echo esc_attr( $dialog_id ); ?>"
		aria-expanded="false"
		aria-haspopup="dialog"
		aria-label="<?php esc_attr_e( 'Open menu', 'prismleaf' ); ?>"
	>
		<span class="prismleaf-mobile-menu-icon" aria-hidden="true">
			<span class="prismleaf-mobile-menu-line prismleaf-mobile-menu-line-top"></span>
			<span class="prismleaf-mobile-menu-line prismleaf-mobile-menu-line-middle"></span>
			<span class="prismleaf-mobile-menu-line prismleaf-mobile-menu-line-bottom"></span>
		</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'prismleaf' ); ?></span>
	</button>

	<div class="prismleaf-mobile-menu-overlay" data-prismleaf-mobile-menu-overlay hidden aria-hidden="true">
		<div
			id="<?php echo esc_attr( $dialog_id ); ?>"
			class="<?php echo esc_attr( implode( ' ', $dialog_classes ) ); ?>"
			role="dialog"
			aria-modal="true"
			aria-label="<?php esc_attr_e( 'Mobile menu', 'prismleaf' ); ?>"
			tabindex="-1"
			data-prismleaf-mobile-menu-dialog
		>
			<nav class="<?php echo esc_attr( implode( ' ', $menu_classes ) ); ?>" aria-label="<?php esc_attr_e( 'Mobile menu', 'prismleaf' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => $menu_location,
						'container'      => false,
						'menu_class'     => 'prismleaf-menu-list',
						'fallback_cb'    => false,
						'depth'          => 2,
					)
				);
				?>
			</nav>
		</div>
	</div>
</div>
