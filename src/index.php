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

get_header();

if ( have_posts() ) :
	?>
	<section aria-labelledby="index-title">
		<header>
			<h1 id="index-title"><?php esc_html_e( 'Blog Fallback View', 'prismleaf' ); ?></h1>
			<p><?php esc_html_e( 'Index.php powers the blog loop whenever a more specific template is not available.', 'prismleaf' ); ?></p>
		</header>

		<div class="prismleaf-post-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
					<header class="entry-header">
						<?php
						the_title(
							sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
							'</a></h2>'
						);
						?>
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
						<?php esc_html_e( 'Read the full story', 'prismleaf' ); ?>
					</a>
				</article>
				<?php
			endwhile;
			?>
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
			'context'    => 'entries',
			'title_tag'  => 'h2',
			'show_poem'  => false,
		)
	);
endif;

get_footer();
