<?php
/**
 * The main template file for Prismleaf.
 *
 * This file is intentionally content-focused.
 * It includes the header, outputs placeholder content,
 * and defers all global layout structure to header.php and footer.php.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<?php
	/**
	 * Placeholder content.
	 *
	 * This exists only to make the layout visible while the
	 * structural framework is being established.
	 * Real themes/child themes will replace this with the loop
	 * and proper templates.
	 */
	?>
	<section>
		<h1><?php esc_html_e( 'Prismleaf Content Area', 'prismleaf' ); ?></h1>

		<p>
			<?php
			esc_html_e(
				'This is placeholder content for the main content area. It exists to demonstrate layout behavior and scrolling while the Prismleaf layout framework is being built.',
				'prismleaf'
			);
			?>
		</p>

		<p>
			<?php
			esc_html_e(
				'Resize the viewport or toggle layout options in the Customizer to observe framed, non-framed, and stacked behaviors.',
				'prismleaf'
			);
			?>
		</p>
	</section>

<?php
get_footer();
