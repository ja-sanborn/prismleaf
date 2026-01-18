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
		<h1>Prismleaf Front Template</h1>
		<p>
			This front-page template takes precedence for the landing view.
			Removing it makes WordPress fall back to home.php (or the selected
			static page template), so keep this file to own the front layout.
		</p>

		<p>
			This is placeholder content for the main content area. It exists to
			demonstrate layout behavior and scrolling while the Prismleaf layout
			framework is being built.
		</p>

		<p>
			Resize the viewport or toggle layout options in the Customizer to
			observe framed, non-framed, and stacked behaviors.
		</p>
	</section>

<?php
get_footer();
