<?php
/**
 * Search results template for Prismleaf.
 *
 * Outputs search hits for visitors and suggests alternative queries when there are no matches.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title_id    = 'content-title-' . wp_unique_id();
$title       = __( 'Search Results', 'prismleaf' );
$description = sprintf( esc_html__( 'You searched for "%s".', 'prismleaf' ), esc_html( get_search_query() ) );

get_header( '', array( 'title_id' => $title_id ) );
get_template_part(
	'template-parts/content-title',
	null,
	array(
		'title_id'      => $title_id,
		'title_tag'     => 'h1',
		'content_title' => $title,
		'description'   => $description,
	)
);
?>

<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
	<?php get_template_part( 'template-parts/archive-results' ); ?>
</section>

<?php
get_footer();
