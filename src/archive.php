<?php
/**
 * Archive template for Prismleaf.
 *
 * Displays the archive title, description, and loop for date/category/tag/year views.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	<?php get_template_part( 'template-parts/archive-results' ); ?>
</section>

<?php
get_footer();
