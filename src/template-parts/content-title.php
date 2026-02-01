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
$content_title = '' === $content_title ? get_bloginfo( 'name' ) : $content_title;
$title_id      = isset( $args['title_id'] ) ? (string) $args['title_id'] : '';
$title_tag     = isset( $args['title_tag'] ) ? strtolower( trim( (string) $args['title_tag'] ) ) : '';
$allowed_tags  = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$description   = isset( $args['description'] ) ? (string) $args['description'] : '';
$is_entry      = isset( $args['is_entry'] ) ? (bool) $args['is_entry'] : false;

if ( ! in_array( $title_tag, $allowed_tags, true ) || $is_entry ) {
	$title_tag = 'h1';
}

$title_class       = $is_entry ? 'prismleaf-entry-title entry-title entry-title' : 'prismleaf-content-title entry';
$description_class = $is_entry ? 'prismleaf-entry-meta entry-meta entry-meta' : 'prismleaf-content-description';

?>

<header class="prismleaf-content-header">
	<<?php echo esc_html( $title_tag ); ?><?php echo '' !== $title_id ? ' id="' . esc_attr( $title_id ) . '"' : ''; ?> class="<?php echo esc_attr( $title_class ); ?>">
		<?php echo wp_kses_post( $content_title ); ?>
	</<?php echo esc_html( $title_tag ); ?>>
	<?php if ( '' !== $description ) : ?>
		<p class="<?php echo esc_attr( $description_class ); ?>"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>
</header>
