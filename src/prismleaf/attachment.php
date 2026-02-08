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

if ( ! have_posts() ) {
	get_template_part( '404' );
	return;
}

get_header();
while ( have_posts() ) :
	the_post();

	$title_id        = 'content-title-' . wp_unique_id();
	$entry_title     = get_the_title();
	$parent          = get_post()->post_parent;
	$attachment_meta = wp_get_attachment_metadata();
	$edit_link       = get_edit_post_link( get_the_ID(), 'raw', false );

	get_template_part(
		'template-parts/content-title',
		null,
		array(
			'title_id'      => $title_id,
			'title_tag'     => 'h1',
			'content_title' => $entry_title,
			'edit_link'     => $edit_link,
		)
	);
	?>

	<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-entry' ); ?>>
			<figure class="prismleaf-entry-attachment attachment-media">
				<?php
				if ( wp_attachment_is_image() ) :
					echo wp_get_attachment_image( get_the_ID(), 'large' );
				else :
					echo wp_get_attachment_link( get_the_ID(), 'large', false );
				endif;
				?>
			</figure>

			<div class="prismleaf-entry-content entry-content">
				<?php
				the_content();
				get_template_part(
					'template-parts/pagination',
					null,
					array(
						'type' => 'pagebreak',
					)
				);
				?>
			</div>

			<?php
			if ( $parent ) :
				?>
				<div class="prismleaf-entry-parent-link">
					<?php
					printf(
						/* translators: %s: parent post title. */
						esc_html__( 'Return to %s', 'prismleaf' ),
						'<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( get_the_title( $parent ) ) . '</a>'
					);
					?>
				</div>
				<?php
			endif;

			if ( $attachment_meta ) :
				?>
				<div class="prismleaf-entry-dimensions">
					<?php
					printf(
						/* translators: %s: dimensions. */
						esc_html__( 'Dimensions: %s', 'prismleaf' ),
						esc_html( sprintf( '%dx%d', $attachment_meta['width'], $attachment_meta['height'] ) )
					);
					?>
				</div>
				<?php
			endif;
			?>
		</article>
	</section>
	<?php
endwhile;
get_footer();
