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

$title_id    = 'content-title-' . wp_unique_id();
$index_title = __( 'Latest Posts', 'prismleaf' );

get_header();
get_template_part(
	'template-parts/content-title',
	null,
	array(
		'title_id'      => $title_id,
		'title_tag'     => is_front_page() || is_home() ? 'h2' : 'h1',
		'content_title' => $index_title,
	)
);
?>

<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
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
