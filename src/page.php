<?php
/**
 * Page template for Prismleaf.
 *
 * Displays static page content with consistent spacing and landmarks.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) :
	$title_id   = 'page-title-' . wp_unique_id();
	$page_title = get_the_title( get_queried_object_id() );
	?>
	<section aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
		<?php
		get_template_part(
			'template-parts/content-title',
			array(
				'title_id'      => $title_id,
				'title_tag'     => 'h1',
				'content_title' => $page_title,
				'description'   => __( 'Page.php renders custom pages when no specialized template is provided.', 'prismleaf' ),
			)
		);

		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<footer class="entry-footer">
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
				</footer>
			</article>
			<?php
		endwhile;
		?>
	</section>
	<?php
else :
	get_template_part( 'template-parts/not-found' );
endif;

get_footer();
