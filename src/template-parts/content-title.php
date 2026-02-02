<?php
/**
 * Template part for the content title.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content_title = isset( $args['content_title'] ) ? (string) $args['content_title'] : '';
$title_id      = isset( $args['title_id'] ) ? (string) $args['title_id'] : '';
$title_tag     = isset( $args['title_tag'] ) ? strtolower( trim( (string) $args['title_tag'] ) ) : '';
$description   = isset( $args['description'] ) ? (string) $args['description'] : '';
$edit_link     = isset( $args['edit_link'] ) ? (string) $args['edit_link'] : '';
$allowed_tags  = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
	$title_tag = 'h1';
}
?>

<header class="prismleaf-content-header">
	<div class="prismleaf-content-title-row">
		<div class="prismleaf-content-title-start">
			<<?php echo esc_html( $title_tag ); ?><?php echo '' !== $title_id ? ' id="' . esc_attr( $title_id ) . '"' : ''; ?> class="prismleaf-content-title entry-title">
				<?php echo wp_kses_post( $content_title ); ?>
			</<?php echo esc_html( $title_tag ); ?>>
		</div>
		<div class="prismleaf-content-title-end">
			<?php if ( '' !== $edit_link ) : ?>
				<a
					class="prismleaf-content-title-edit"
					href="<?php echo esc_url( $edit_link ); ?>"
					aria-label="<?php echo esc_attr_x( 'Edit this entry', 'link label', 'prismleaf' ); ?>"
					rel="noopener noreferrer"
				>
					<span class="screen-reader-text"><?php esc_html_e( 'Edit entry', 'prismleaf' ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( '' !== $description ) : ?>
		<p class="prismleaf-content-description"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>
</header>
