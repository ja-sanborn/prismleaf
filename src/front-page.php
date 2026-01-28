<?php
/**
 * Front-page template for Prismleaf.
 *
 * Outputs the selected landing content with its hero messaging.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) :
	?>
	<section aria-labelledby="front-page-intro">
		<header>
			<h2 id="front-page-intro"><?php esc_html_e( 'Curated Landing Story', 'prismleaf' ); ?></h2>
			<p><?php esc_html_e( 'This template owns the hero narrative, featured blocks, and introductory statements that welcome new visitors.', 'prismleaf' ); ?></p>
		</header>

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<div class="entry-footer">
					<?php
					get_template_part(
						'template-parts/pagination',
						null,
						array(
							'type' => 'pagebreak',
						)
					);
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
				</div>
			</article>
			<?php
		endwhile;
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
