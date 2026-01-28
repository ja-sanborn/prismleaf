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

if ( have_posts() ) :
	?>
	<section aria-labelledby="search-title">
		<header>
			<h1 id="search-title"><?php esc_html_e( 'Search Results', 'prismleaf' ); ?></h1>
			<p><?php printf( esc_html__( 'You searched for "%s".', 'prismleaf' ), esc_html( get_search_query() ) ); ?></p>
		</header>

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
			'context'      => 'search',
			'search_query' => get_search_query(),
		)
	);
endif;

get_footer();
