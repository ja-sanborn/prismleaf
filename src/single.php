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
?>

	<section aria-labelledby="single-title">
		<header>
			<h2 id="single-title"><?php esc_html_e( 'Single Post', 'prismleaf' ); ?></h2>
			<p><?php esc_html_e( 'Single.php renders the full story, metadata, and navigation for an individual post.', 'prismleaf' ); ?></p>
		</header>

		<?php
		if ( have_posts() ) :
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
						wp_link_pages(
							array(
								'before' => '<nav class="page-links">' . esc_html__( 'Continue reading:', 'prismleaf' ),
								'after'  => '</nav>',
							)
						);
						?>
					</div>
					<footer class="entry-footer">
						<?php
						the_post_navigation(
							array(
								'prev_text' => esc_html__( 'Previous post', 'prismleaf' ),
								'next_text' => esc_html__( 'Next post', 'prismleaf' ),
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
		else :
			?>
			<p><?php esc_html_e( 'No content is available.', 'prismleaf' ); ?></p>
			<?php
		endif;
		?>
	</section>

<?php
get_footer();
