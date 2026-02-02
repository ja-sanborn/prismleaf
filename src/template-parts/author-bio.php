<?php
/**
 * Template part for the author bio block.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author_name  = isset( $args['author_name'] ) ? trim( (string) $args['author_name'] ) : '';
$author_bio   = isset( $args['author_bio'] ) ? trim( (string) $args['author_bio'] ) : '';
$author_image = isset( $args['author_image'] ) ? (string) $args['author_image'] : '';
$author_link  = isset( $args['author_link'] ) ? (string) $args['author_link'] : '';
$author_id    = isset( $args['author_id'] ) ? absint( $args['author_id'] ) : 0;
if ( ! $author_id ) {
	$author_id = get_the_author_meta( 'ID' );
}

$has_name  = '' !== $author_name;
$has_bio   = '' !== $author_bio;
$has_image = '' !== $author_image;

if ( ! $has_name && ! $has_bio && ! $has_image ) {
	return;
}

if ( '' === $author_link && $author_id ) {
	$author_link = get_author_posts_url( $author_id );
}

?>
<aside class="prismleaf-author-bio">
	<?php if ( $has_image ) : ?>
		<figure class="prismleaf-author-bio-figure">
			<?php echo wp_kses_post( $author_image ); ?>
		</figure>
	<?php endif; ?>
	<div class="prismleaf-author-bio-content">
		<?php if ( $has_name ) : ?>
			<p class="prismleaf-author-name">
				<?php
				if ( '' !== $author_link ) {
					printf(
						'<a class="prismleaf-author-name-link" href="%s">%s</a>',
						esc_url( $author_link ),
						esc_html( $author_name )
					);
				} else {
					echo esc_html( $author_name );
				}
				?>
			</p>
		<?php endif; ?>
		<?php if ( $has_bio ) : ?>
			<p class="prismleaf-author-bio-text">
				<?php echo wp_kses_post( $author_bio ); ?>
			</p>
		<?php endif; ?>
	</div>
</aside>
