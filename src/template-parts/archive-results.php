<?php
/**
 * Archive results list for Prismleaf.
 *
 * Displays the latest posts in an archive.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_poem = isset( $args['show_poem'] ) ? (bool) $args['show_poem'] : true;

if ( have_posts() ) :
	?>
	<div class="prismleaf-post-list">
		<?php
		while ( have_posts() ) :
			the_post();
			$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
			$author_name = get_the_author();
			$author_link = sprintf(
				'<a class="prismleaf-post-author-link" href="%1$s">%2$s</a>',
				esc_url( $author_url ),
				esc_html( $author_name )
			);
			$published_date = get_the_date( 'F j, Y' );
			$published_time = get_the_time( 'g:i a' );
			$published_label = sprintf(
				/* translators: 1: date, 2: time. */
				esc_html__( 'Published on %1$s at %2$s', 'prismleaf' ),
				esc_html( $published_date ),
				esc_html( $published_time )
			);
			$categories_list = get_the_category_list( ', ' );
			$tags_list = get_the_tag_list( '', ', ' );
			$comments_count = get_comments_number();
			$comment_text = sprintf(
				/* translators: %s: comment count. */
				_n( '%s comment', '%s comments', $comments_count, 'prismleaf' ),
				number_format_i18n( $comments_count )
			);
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-thumbnail prismleaf-post-thumbnail">
						<a class="prismleaf-post-thumbnail-link" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php the_post_thumbnail( 'post-thumbnail', array( 'loading' => 'lazy' ) ); ?>
						</a>
					</figure>
				<?php endif; ?>
				<header class="entry-header prismleaf-post-header">
					<?php the_title( sprintf( '<h2 class="entry-title prismleaf-post-title"><a class="prismleaf-post-title-link" href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header>
				<div class="entry-meta prismleaf-post-author-meta">
					<span class="entry-meta-author prismleaf-post-author-name">
						<?php
						printf(
							/* translators: %s: author. */
							esc_html__( 'By %s', 'prismleaf' ),
							wp_kses_post( $author_link )
						);
						?>
					</span>
				</div>
				<div class="entry-meta prismleaf-post-published">
					<span class="entry-meta-date prismleaf-post-published-date">
						<?php echo esc_html( $published_label ); ?>
					</span>
				</div>
				<div class="entry-summary prismleaf-post-summary">
					<?php the_excerpt(); ?>
				</div>
				<div class="entry-meta prismleaf-post-author-archive">
					<span class="entry-meta-author prismleaf-post-author-archive-text">
						<?php
						printf(
							/* translators: %s: author archive. */
							esc_html__( 'Author: %s', 'prismleaf' ),
							wp_kses_post( $author_link )
						);
						?>
					</span>
				</div>
				<?php if ( $categories_list ) : ?>
					<div class="entry-meta prismleaf-post-categories">
						<span class="entry-meta-categories-list prismleaf-post-categories-list">
							<?php
							printf(
								/* translators: %s: category list. */
								esc_html__( 'Categories: %s', 'prismleaf' ),
								wp_kses_post( $categories_list )
							);
							?>
						</span>
					</div>
				<?php endif; ?>
				<?php if ( $tags_list ) : ?>
					<div class="entry-meta prismleaf-post-tags">
						<span class="entry-meta-tags-list prismleaf-post-tags-list">
							<?php
							printf(
								/* translators: %s: tag list. */
								esc_html__( 'Tags: %s', 'prismleaf' ),
								wp_kses_post( $tags_list )
							);
							?>
						</span>
					</div>
				<?php endif; ?>
				<div class="entry-meta prismleaf-post-comments">
					<a class="entry-meta-comments-link prismleaf-post-comments-link" href="<?php echo esc_url( get_comments_link() ); ?>">
						<?php echo esc_html( $comment_text ); ?>
					</a>
				</div>
				<div class="entry-footer prismleaf-post-footer">
					<a class="entry-link prismleaf-post-link" href="<?php echo esc_url( get_permalink() ); ?>">
						<?php esc_html_e( 'Continue reading', 'prismleaf' ); ?>
					</a>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
	<?php
	get_template_part( 'template-parts/pagination', null, array( 'type' => 'archive' ) );
else :
	get_template_part( 'template-parts/not-found', null, array( 'show_poem' => $show_poem ) );
endif;
