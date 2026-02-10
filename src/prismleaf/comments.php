<?php
/**
 * Template for displaying comments and the comment form.
 *
 * @package prismleaf
 */

if ( post_password_required() ) {
	return;
}

$is_page = is_singular( 'page' );
$is_post = is_singular( 'post' );
if ( $is_page && ! prismleaf_get_theme_mod_show_comments_on_pages() ) {
	return;
}
if ( $is_post && ! prismleaf_get_theme_mod_show_comments_on_posts() ) {
	return;
}

$comments_closed = ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' );

?>
<section id="comments" class="comments-area" aria-label="<?php esc_attr_e( 'Comments', 'prismleaf' ); ?>">
	<?php
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$comment_count = number_format_i18n( get_comments_number() );

			printf(
				esc_html(
					/* translators: %1$s: number of comments, %2$s: post title. */
					_n( '%1$s thought on “%2$s”', '%1$s thoughts on “%2$s”', get_comments_number(), 'prismleaf' )
				),
				esc_html( $comment_count ),
				wp_kses_post( sprintf( '<span>%s</span>', esc_html( get_the_title() ) ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'short_ping'  => true,
					'avatar_size' => 32,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( $comments_closed ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'prismleaf' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'cookies'            => true,
			'title_reply'        => esc_html__( 'Share your thoughts', 'prismleaf' ),
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h3>',
			'label_submit'       => esc_html__( 'Share', 'prismleaf' ),
		)
	);
	?>
</section>
