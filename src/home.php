<?php
/**
 * Home template for Prismleaf.
 *
 * Renders the blog index when a static front page is configured.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) :
	?>
	<section aria-labelledby="home-title">
		<header>
			<h2 id="home-title"><?php esc_html_e( 'Latest Posts', 'prismleaf' ); ?></h2>
			<p><?php esc_html_e( 'The home template displays the latest posts when a static front page is selected.', 'prismleaf' ); ?></p>
		</header>

		<ul class="prismleaf-post-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<li>
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
				</li>
				<?php
			endwhile;
			?>
		</ul>
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
			'context'    => 'entries',
			'show_title' => false,
			'show_poem'  => false,
		)
	);
endif;

get_footer();
