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
$show_featured_image = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_featured_image',
	prismleaf_get_default_option( 'result_show_featured_image', true )
);
$show_categories = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_categories',
	prismleaf_get_default_option( 'result_show_categories', true )
);
$show_author = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_author',
	prismleaf_get_default_option( 'result_show_author', true )
);
$show_date = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_date',
	prismleaf_get_default_option( 'result_show_date', true )
);
$show_comments = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_comments',
	prismleaf_get_default_option( 'result_show_comments', true )
);

$layout = isset( $args['layout'] ) ? sanitize_key( $args['layout'] ) : 'list';
$allowed_layouts = array( 'grid', 'list' );
if ( ! in_array( $layout, $allowed_layouts, true ) ) {
	$layout = 'list';
}

$layout_wrapper_classes = array(
	'grid' => 'prismleaf-archive-list-layout-grid',
	'list' => 'prismleaf-archive-list-layout-list',
);

$archive_list_classes = implode(
	' ',
	array_filter(
		array(
			'prismleaf-archive-list',
			$layout_wrapper_classes[ $layout ],
		)
	)
);

if ( have_posts() ) :
	?>
	<div class="<?php echo esc_attr( $archive_list_classes ); ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
			$author_name = get_the_author();
			$author_link = sprintf(
				'<a class="prismleaf-archive-author-link" href="%1$s">%2$s</a>',
				esc_url( $author_url ),
				esc_html( $author_name )
			);
			$published_date = get_the_date( 'F j, Y' );
			$meta_parts = array();

			if ( $show_author ) {
				$meta_parts[] = sprintf(
					/* translators: %s: author. */
					esc_html__( 'by %s', 'prismleaf' ),
					wp_kses_post( $author_link )
				);
			}

			if ( $show_date ) {
				$meta_parts[] = sprintf(
					/* translators: %s: date. */
					esc_html__( 'on %s', 'prismleaf' ),
					esc_html( $published_date )
				);
			}

			$posted_by_label = '';
			if ( ! empty( $meta_parts ) ) {
				$posted_by_label = esc_html__( 'Posted', 'prismleaf' ) . ' ' . implode( ' ', $meta_parts );
			}
			$categories_list = get_the_category_list( ', ' );
			$comments_count = get_comments_number();
			$comment_text = sprintf(
				/* translators: %s: comment count. */
				_n( '%s comment', '%s comments', $comments_count, 'prismleaf' ),
				number_format_i18n( $comments_count )
			);
			?>
			<?php $has_thumbnail = $show_featured_image && has_post_thumbnail(); ?>
			<?php
			$article_classes = array( 'prismleaf-archive-card' );
			if ( 'list' === $layout ) {
				$article_classes[] = 'prismleaf-archive-card-layout-list';
			}
			?>
			<article
				id="post-<?php the_ID(); ?>"
				<?php post_class( $article_classes ); ?>
			>
				<?php if ( 'grid' === $layout ) : ?>
					<?php if ( $has_thumbnail ) : ?>
						<figure class="prismleaf-archive-card-thumbnail">
							<a class="prismleaf-archive-card-thumbnail-link" href="<?php echo esc_url( get_permalink() ); ?>">
								<?php the_post_thumbnail( 'prismleaf-archive-card', array( 'loading' => 'lazy', 'sizes' => '(max-width: 300px) 100vw, 300px' ) ); ?>
							</a>
						</figure>
					<?php endif; ?>
					<header class="prismleaf-archive-card-header">
						<?php the_title( sprintf( '<h2 class="prismleaf-archive-card-title"><a class="prismleaf-archive-card-title-link" href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php if ( '' !== $posted_by_label ) : ?>
							<p class="prismleaf-archive-card-meta">
								<?php echo $posted_by_label; ?>
							</p>
						<?php endif; ?>
						<?php if ( $categories_list && $show_categories ) : ?>
							<p class="prismleaf-archive-card-categories">
								<?php echo wp_kses_post( $categories_list ); ?>
							</p>
						<?php endif; ?>
					</header>
					<div class="prismleaf-archive-card-excerpt">
						<?php the_excerpt(); ?>
					</div>
					<div class="prismleaf-archive-card-footer">
						<?php if ( $show_comments ) : ?>
							<a class="prismleaf-archive-card-comments-link" href="<?php echo esc_url( get_comments_link() ); ?>">
								<?php echo esc_html( $comment_text ); ?>
							</a>
						<?php endif; ?>
						<a class="prismleaf-archive-card-continue-link" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php esc_html_e( 'Continue reading', 'prismleaf' ); ?>
						</a>
					</div>
				<?php else : ?>
					<header class="prismleaf-archive-card-header">
						<?php the_title( sprintf( '<h2 class="prismleaf-archive-card-title"><a class="prismleaf-archive-card-title-link" href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header>
					<?php
					$list_body_classes = array( 'prismleaf-archive-card-list-body' );
					if ( ! $has_thumbnail ) {
						$list_body_classes[] = 'prismleaf-archive-card-list-body-no-media';
					}
					?>
					<div class="<?php echo esc_attr( implode( ' ', $list_body_classes ) ); ?>">
						<?php if ( $has_thumbnail ) : ?>
							<figure class="prismleaf-archive-card-thumbnail prismleaf-archive-card-thumbnail-list">
								<a class="prismleaf-archive-card-thumbnail-link" href="<?php echo esc_url( get_permalink() ); ?>">
									<?php the_post_thumbnail( 'prismleaf-archive-card', array( 'loading' => 'lazy', 'sizes' => '(max-width: 300px) 100vw, 300px' ) ); ?>
								</a>
							</figure>
						<?php endif; ?>
						<div class="prismleaf-archive-card-list-excerpt">
							<div class="prismleaf-archive-card-excerpt">
								<?php the_excerpt(); ?>
							</div>
							<a class="prismleaf-archive-card-continue-link" href="<?php echo esc_url( get_permalink() ); ?>">
								<?php esc_html_e( 'Continue reading', 'prismleaf' ); ?>
							</a>
						</div>
					</div>
					<div class="prismleaf-archive-card-list-meta-row">
						<?php if ( '' !== $posted_by_label ) : ?>
							<p class="prismleaf-archive-card-meta prismleaf-archive-card-meta-list">
								<?php echo $posted_by_label; ?>
							</p>
						<?php endif; ?>
						<?php if ( $show_comments ) : ?>
							<a class="prismleaf-archive-card-comments-link prismleaf-archive-card-comments-link-list" href="<?php echo esc_url( get_comments_link() ); ?>">
								<?php echo esc_html( $comment_text ); ?>
							</a>
						<?php endif; ?>
					</div>
					<?php if ( $categories_list && $show_categories ) : ?>
						<p class="prismleaf-archive-card-categories prismleaf-archive-card-categories-list">
							<?php echo wp_kses_post( $categories_list ); ?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
	</div>
	<?php
	get_template_part( 'template-parts/pagination', null, array( 'type' => 'archive' ) );
else :
	get_template_part( 'template-parts/not-found', null, array( 'show_poem' => $show_poem ) );
endif;
