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

$author      = get_queried_object();
$title_id    = 'content-title-' . wp_unique_id();
$title       = get_the_archive_title();
$description = get_the_archive_description();

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
	<div class="author-summary">
		<?php echo get_avatar( $author->ID, 120 ); ?>
		<?php if ( $bio = get_the_author_meta( 'description', $author->ID ) ) : ?>
			<p><?php echo esc_html( $bio ); ?></p>
		<?php endif; ?>
	</div>
	<?php get_template_part( 'template-parts/archive-results', null, array ( 'show_poem' => false ) ); ?>
</section>

<?php
get_footer();
