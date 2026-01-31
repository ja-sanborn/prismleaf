<?php
/**
 * Author template for Prismleaf.
 *
 * Displays the author biography and their posts.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author       = get_queried_object();
$author_id    = $author->ID;
$author_name  = get_the_author_meta( 'display_name', $author_id );
$author_bio   = get_the_author_meta( 'description', $author_id );
$author_image = get_avatar( $author_id, 64 );
$title_id     = 'content-title-' . wp_unique_id();
$description  = $author_bio;

if ( '' !== $author_image ) {
	ob_start();
	get_template_part(
		'template-parts/author-bio',
		null,
		array(
			'author_id'    => $author_id,
			'author_image' => $author_image,
			'author_bio'   => $author_bio,
			'author_name'  => '',
			'author_link'  => '',
		)
	);
	$author_bio_output = ob_get_clean();

	if ( '' !== trim( $author_bio_output ) ) {
		$description = $author_bio_output;
	}
}

get_header( '', array( 'title_id' => $title_id ) );
	get_template_part(
		'template-parts/content-title',
		null,
		array(
			'title_id'      => $title_id,
			'title_tag'     => 'h1',
			'content_title' => $author_name,
			'description'   => $description,
		)
	);
?>

<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
	<?php get_template_part( 'template-parts/archive-results', null, array( 'show_poem' => false, 'layout' => 'grid' ) ); ?>
</section>

<?php
get_footer();
