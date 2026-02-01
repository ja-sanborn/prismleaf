<?php
/**
 * Single post template for Prismleaf.
 *
 * Displays the entry content, metadata, and navigation for a single post.
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

	$title_id      = 'content-title-' . wp_unique_id();
	$entry_title   = get_the_title();
	$author_id     = get_the_author_meta( 'ID' );
	$author_name   = get_the_author_meta( 'display_name', $author_id );
	$author_bio    = get_the_author_meta( 'description', $author_id );
	$author_link   = get_author_posts_url( $author_id );
	$author_image  = get_avatar( $author_id, 64 );
	$category_list = get_the_category_list( ', ' );
	$tag_list      = get_the_tag_list( '', ', ' );

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
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-entry prismleaf-entry-single' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="prismleaf-entry-featured-image">
					<?php the_post_thumbnail( 'large', array( 'class' => 'prismleaf-entry-featured-image' ) ); ?>
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

			<div class="prismleaf-entry-meta">
				<div class="prismleaf-entry-author">
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 1: date, 2: time. */
							__( 'Posted on %1$s at %2$s', 'prismleaf' ),
							esc_html( get_the_date() ),
							esc_html( get_the_time() )
						)
					);
					?>
				</div>

				<?php if ( $category_list ) : ?>
					<div class="prismleaf-entry-categories entry-categories">
						<?php
						esc_html_e( 'Categories: ', 'prismleaf' );
						echo wp_kses_post( $category_list );
						?>
					</div>
				<?php endif; ?>

				<?php if ( $tag_list ) : ?>
					<div class="prismleaf-entry-tags entry-tags">
						<?php
						esc_html_e( 'Tags: ', 'prismleaf' );
						echo wp_kses_post( $tag_list );
						?>
					</div>
				<?php endif; ?>
			</div>
		</article>

		<?php
		get_template_part(
			'template-parts/pagination',
			null,
			array(
				'type' => 'post',
			)
		);

		get_template_part(
			'template-parts/author-bio',
			null,
			array(
				'author_name'  => $author_name,
				'author_bio'   => $author_bio,
				'author_link'  => $author_link,
				'author_image' => $author_image,
				'author_id'    => $author_id,
			)
		);

		comments_template();
		?>
	</section>
	<?php
endwhile;
get_footer();
