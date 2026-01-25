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

	<section aria-labelledby="error-404-title">
		<header>
			<h1 id="error-404-title"><?php esc_html_e( 'Page Not Found', 'prismleaf' ); ?></h1>
			<p><?php esc_html_e( 'The page you requested can no longer be found, but the navigation and search tools are still available.', 'prismleaf' ); ?></p>
		</header>

		<p><?php esc_html_e( 'Prismleaf keeps the header, footer, and landmarks consistent so you can return to a known context even when content moves.', 'prismleaf' ); ?></p>

		<p><?php esc_html_e( 'Try a different keyword with the search box below or use the primary navigation to continue exploring.', 'prismleaf' ); ?></p>

		<?php get_search_form(); ?>
	</section>

<?php
get_footer();
