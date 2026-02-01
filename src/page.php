<?php
/**
 * Page template for Prismleaf.
 *
 * Displays static page content with consistent spacing and landmarks.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! have_posts() ) {
	get_template_part( '404' );
	return;
}

get_header();
while ( have_posts() ) :
	the_post();

	$title_id    = 'content-title-' . wp_unique_id();
	$entry_title = get_the_title();

	get_template_part(
		'template-parts/content-title',
		null,
		array(
			'title_id'      => $title_id,
			'title_tag'     => 'h1',
			'content_title' => $entry_title,
			'is_entry'      => true,
		)
	);
	?>

	<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-entry prismleaf-entry-page' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="prismleaf-entry-featured-image">
					<?php the_post_thumbnail( 'large', array( 'class' => 'prismleaf-entry-featured-image__img' ) ); ?>
				</figure>
			<?php endif; ?>

			<div class="prismleaf-entry-content entry-content">
				<?php
				the_content();
				get_template_part(
					'template-parts/pagination',
					null,
					array(
						'type' => 'pagebreak',
					)
				);
				?>
			</div>
		</article>
	</section>
	<?php
endwhile;
get_footer();
