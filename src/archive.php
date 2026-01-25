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

get_header();
?>

	<section aria-labelledby="archive-title">
		<header>
			<h1 id="archive-title"><?php the_archive_title(); ?></h1>
			<p><?php esc_html_e( 'Archives group similar stories so readers can browse by date, category, tag, or topic.', 'prismleaf' ); ?></p>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		</header>

		<?php
		if ( have_posts() ) :
			?>
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
		else :
			?>
			<p><?php esc_html_e( 'No items match this archive yet.', 'prismleaf' ); ?></p>
			<?php
		endif;
		?>
	</section>

<?php
get_footer();
