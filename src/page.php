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
?>

	<section aria-labelledby="page-template-title">
		<header>
			<h1 id="page-template-title"><?php esc_html_e( 'Static Page Layout', 'prismleaf' ); ?></h1>
			<p><?php esc_html_e( 'Page.php renders custom pages when no specialized template is provided.', 'prismleaf' ); ?></p>
		</header>

		<?php
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
					wp_link_pages(
						array(
							'before' => '<nav class="page-links">' . esc_html__( 'Continue reading:', 'prismleaf' ),
							'after'  => '</nav>',
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
get_footer();
