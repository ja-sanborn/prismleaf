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

get_header();

$author = get_queried_object();

if ( have_posts() ) :
	?>
	<section aria-labelledby="author-title">
		<header>
			<h1 id="author-title">
				<?php
				printf(
					/* translators: %s: author name. */
					esc_html__( '%s’s Posts', 'prismleaf' ),
					esc_html( get_the_author_meta( 'display_name', $author->ID ) )
				);
				?>
			</h1>
			<p><?php esc_html_e( 'Highlight the author biography, avatar, and metadata alongside their published work.', 'prismleaf' ); ?></p>
		</header>

		<div class="author-summary">
			<?php echo get_avatar( $author->ID, 120 ); ?>
			<?php if ( $bio = get_the_author_meta( 'description', $author->ID ) ) : ?>
				<p><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
		</div>

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
						<?php esc_html_e( 'Read more', 'prismleaf' ); ?>
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<?php
		get_template_part(
			'template-parts/pagination',
			null,
			array(
				'type' => 'archive',
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
