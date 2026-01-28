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

get_header();

if ( have_posts() ) :
	?>
	<section aria-labelledby="single-title">
		<header>
			<h2 id="single-title"><?php esc_html_e( 'Single Post', 'prismleaf' ); ?></h2>
			<p><?php esc_html_e( 'Single.php renders the full story, metadata, and navigation for an individual post.', 'prismleaf' ); ?></p>
		</header>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
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
				<div class="entry-content">
					<?php the_content(); ?>
					<?php
					get_template_part(
						'template-parts/pagination',
						null,
						array(
							'type' => 'pagebreak',
						)
					);
					?>
				</div>
				<footer class="entry-footer">
					<?php
					get_template_part(
						'template-parts/pagination',
						null,
						array(
							'type' => 'post',
						)
					);
					?>
					<?php
					edit_post_link(
						sprintf(
							/* translators: %s: post title. */
							esc_html__( 'Edit %s', 'prismleaf' ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						),
						'<span class="edit-link">',
						'</span>'
					);
					?>
				</footer>
			</article>
			<?php
			comments_template();
		endwhile;
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
