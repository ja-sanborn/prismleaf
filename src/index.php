<?php
/**
 * Index template for Prismleaf.
 *
 * Renders the fallback blog loop when no other template matches the query.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="prismleaf-content-area" aria-labelledby="site-title">
	<?php
	get_template_part(
		'template-parts/archive-results',
		null,
		array(
			'show_poem' => false,
			'layout'    => 'grid',
		)
	);
	?>
</section>

<?php
get_footer();
