<?php
/**
 * Template for displaying comments and the comment form.
 *
 * @package prismleaf
 */

if ( post_password_required() ) {
	return;
}

?>
<section id="comments" class="comments-area prismleaf-region" aria-label="<?php esc_attr_e( 'Comments', 'prismleaf' ); ?>">
	<?php
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$comment_count = number_format_i18n( get_comments_number() );

			printf(
				esc_html(
					/* translators: %1$s: number of comments, %2$s: post title. */
					_n( '%1$s comment on “%2$s”', '%1$s comments on “%2$s”', get_comments_number(), 'prismleaf' )
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
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'prismleaf' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_form'         => 'comment-form',
			'title_reply'        => esc_html__( 'Share your thoughts', 'prismleaf' ),
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h3>',
			'label_submit'       => esc_html__( 'Post comment', 'prismleaf' ),
		)
	);
	?>
</section>
