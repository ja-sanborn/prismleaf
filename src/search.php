<?php
/**
 * Search results template for Prismleaf.
 *
 * Outputs search hits for visitors and suggests alternative queries when there are no matches.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<section aria-labelledby="search-title">
		<header>
			<h1 id="search-title"><?php esc_html_e( 'Search Results', 'prismleaf' ); ?></h1>
			<p><?php printf( esc_html__( 'You searched for "%s".', 'prismleaf' ), esc_html( get_search_query() ) ); ?></p>
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
						</header>
						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div>
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
			<p><?php esc_html_e( 'No results matched your search query.', 'prismleaf' ); ?></p>
			<?php get_search_form(); ?>
			<?php
		endif;
		?>
	</section>

<?php
get_footer();
