<?php
/**
 * Template part for entry content that serves posts, pages, and attachments.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$context = isset( $args['context'] ) ? (string) $args['context'] : '';
if ( '' === $context ) {
	if ( is_attachment() ) {
		$context = 'attachment';
	} elseif ( is_page() ) {
		$context = 'page';
	} else {
		$context = 'single';
	}
}

while ( have_posts() ) :
	the_post();

	$title_id = 'content-title-' . wp_unique_id();
	$parent   = get_post()->post_parent;

	get_template_part(
		'template-parts/content-title',
		null,
		array(
			'title_id'      => $title_id,
			'title_tag'     => 'h1',
			'content_title' => get_the_title(),
			'is_entry'      => true,
		)
	);
	?>

	<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
			<?php if ( 'attachment' === $context ) : ?>
				<div class="attachment-media">
					<?php
					if ( wp_attachment_is_image() ) :
						echo wp_get_attachment_image( get_the_ID(), 'large' );
					else :
						echo wp_get_attachment_link( get_the_ID(), 'large', false );
					endif;
					?>
				</div>
			<?php else : ?>
				<?php if ( 'single' === $context ) : ?>
					<header class="entry-header">
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
				<?php endif; ?>
			<?php endif; ?>

			<div class="entry-content">
				<?php the_content(); ?>

				<?php if ( 'attachment' !== $context ) : ?>
					<?php
					get_template_part(
						'template-parts/pagination',
						null,
						array(
							'type' => 'pagebreak',
						)
					);
					?>
				<?php endif; ?>
			</div>

			<footer class="entry-footer">
				<?php if ( 'attachment' === $context ) : ?>
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
							<?php esc_html_e( 'Dimensions', 'prismleaf' ); ?>:
							<?php echo esc_html( sprintf( '%dx%d', $meta['width'], $meta['height'] ) ); ?>
						</p>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( 'single' === $context ) : ?>
						<?php
						get_template_part(
							'template-parts/pagination',
							null,
							array(
								'type' => 'post',
							)
						);
						?>
					<?php endif; ?>

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
				<?php endif; ?>
			</footer>
		</article>

		<?php
		if ( 'single' === $context ) :
			$single_author_id = get_the_author_meta( 'ID' );
			get_template_part(
				'template-parts/author-bio',
				null,
				array(
					'author_name'  => get_the_author_meta( 'display_name', $single_author_id ),
					'author_bio'   => get_the_author_meta( 'description', $single_author_id ),
					'author_link'  => get_author_posts_url( $single_author_id ),
					'author_image' => get_avatar( $single_author_id, 64 ),
					'author_id'    => $single_author_id,
				)
			);

			comments_template();
		endif;
		?>
	</section>
	<?php
endwhile;
