<?php
/**
 * Tag archive template for Prismleaf.
 *
 * Highlights collections of posts grouped by tag with contextual messaging.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) :
	?>
	<section aria-labelledby="tag-title">
		<header>
			<h1 id="tag-title"><?php echo esc_html( single_tag_title( '', false ) ); ?></h1>
			<p><?php esc_html_e( 'Tag archives gather stories that share a finer-grained keyword.', 'prismleaf' ); ?></p>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>

		<div class="prismleaf-post-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
					<header class="entry-header">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<p class="entry-meta">
							<?php
							printf(
								/* translators: %s: post date. */
								esc_html__( 'Published on %s', 'prismleaf' ),
								esc_html( get_the_date() )
							);
							?>
						</p>
					</header>
					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>
					<a class="entry-link" href="<?php the_permalink(); ?>">
						<?php esc_html_e( 'Continue reading', 'prismleaf' ); ?>
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<?php
		the_posts_pagination(
			array(
				'prev_text' => esc_html__( 'Previous', 'prismleaf' ),
				'next_text' => esc_html__( 'Next', 'prismleaf' ),
			)
		);
		?>
	</section>
	<?php
else :
	get_template_part(
		'template-parts/not-found',
		null,
		array(
			'context' => 'entries',
		)
	);
endif;

get_footer();
