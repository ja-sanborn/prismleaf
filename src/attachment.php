<?php
/**
 * Attachment template for Prismleaf.
 *
 * Presents the selected media item with context and metadata.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) :
	?>
	<section aria-labelledby="attachment-title">
		<header>
			<h1 id="attachment-title"><?php the_title(); ?></h1>
			<p><?php esc_html_e( 'This template displays a single attachment with its caption and metadata.', 'prismleaf' ); ?></p>
		</header>

		<?php
		while ( have_posts() ) :
			the_post();
			$parent = get_post()->post_parent;
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
				<div class="attachment-media">
					<?php
					if ( wp_attachment_is_image() ) :
						echo wp_get_attachment_image( get_the_ID(), 'large' );
					else :
						echo wp_get_attachment_link( get_the_ID(), 'large', false );
					endif;
					?>
				</div>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<footer class="entry-footer">
					<?php if ( $parent ) : ?>
						<p>
							<?php
							printf(
								/* translators: %s: parent post title. */
								esc_html__( 'Return to %s', 'prismleaf' ),
								'<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( get_the_title( $parent ) ) . '</a>'
							);
							?>
						</p>
					<?php endif; ?>
					<?php if ( $meta = wp_get_attachment_metadata() ) : ?>
						<p>
							<?php esc_html_e( 'Dimensions', 'prismleaf' ); ?>: <?php echo esc_html( sprintf( '%dx%d', $meta['width'], $meta['height'] ) ); ?>
						</p>
					<?php endif; ?>
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
