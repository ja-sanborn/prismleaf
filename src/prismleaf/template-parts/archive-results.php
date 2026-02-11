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

$show_poem           = isset( $args['show_poem'] ) ? (bool) $args['show_poem'] : false;
$show_featured_image = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_featured_image',
	prismleaf_get_default_option( 'result_show_featured_image', true )
);
$show_categories     = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_categories',
	prismleaf_get_default_option( 'result_show_categories', true )
);
$show_author         = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_author',
	prismleaf_get_default_option( 'result_show_author', true )
);
$show_date           = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_date',
	prismleaf_get_default_option( 'result_show_date', true )
);
$show_comments       = prismleaf_get_theme_mod_bool(
	'prismleaf_result_show_comments',
	prismleaf_get_default_option( 'result_show_comments', true )
);

$allowed_layouts = array( 'grid', 'list', 'title', 'full', 'firstfull' );
$layout          = '';

if ( isset( $args['layout'] ) ) {
	$layout = sanitize_key( $args['layout'] );
}

if ( ! in_array( $layout, $allowed_layouts, true ) ) {
	$type = isset( $args['type'] ) ? sanitize_key( $args['type'] ) : 'default';
	if ( ! in_array( $type, array( 'default', 'home', 'archive', 'search' ), true ) ) {
		$type = 'default';
	}

	$layout = prismleaf_get_archive_results_layout( $type );
}

if ( ! in_array( $layout, $allowed_layouts, true ) ) {
	$layout = 'grid';
}

$layout_wrapper_classes = array(
	'grid'      => 'prismleaf-archive-list-layout-grid',
	'list'      => 'prismleaf-archive-list-layout-list',
	'title'     => 'prismleaf-archive-list-layout-title',
	'full'      => 'prismleaf-archive-list-layout-full',
	'firstfull' => 'prismleaf-archive-list-layout-list',
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
	<?php if ( 'title' === $layout ) : ?>
	<ol class="<?php echo esc_attr( $archive_list_classes ); ?>">
	<?php else : ?>
	<div class="<?php echo esc_attr( $archive_list_classes ); ?>">
	<?php endif; ?>
		<?php
		$post_index = 0;
		while ( have_posts() ) :
			the_post();
			$author_url     = get_author_posts_url( get_the_author_meta( 'ID' ) );
			$author_name    = get_the_author();
			$author_link    = sprintf(
				'<a class="prismleaf-archive-author-link" href="%1$s">%2$s</a>',
				esc_url( $author_url ),
				esc_html( $author_name )
			);
			$published_date = get_the_date( 'F j, Y' );
			$meta_parts     = array();

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
			$comments_count  = get_comments_number();
			$comment_text    = sprintf(
				/* translators: %s: comment count. */
				_n( '%s comment', '%s comments', $comments_count, 'prismleaf' ),
				number_format_i18n( $comments_count )
			);
			$is_firstfull_post  = 'firstfull' === $layout && ! is_paged() && 0 === $post_index;
			$render_full_layout = 'full' === $layout || $is_firstfull_post;
			?>
			<?php $has_thumbnail = $show_featured_image && has_post_thumbnail(); ?>
			<?php if ( 'title' === $layout ) : ?>
				<li id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-archive-title-item' ); ?>>
					<a class="prismleaf-archive-title-link" href="<?php echo esc_url( get_permalink() ); ?>">
						<?php the_title(); ?>
					</a>
					<?php if ( $show_author ) : ?>
						<span class="prismleaf-archive-title-meta-fragment">
							<?php
							printf(
								/* translators: %s: author link. */
								wp_kses_post( __( ' by %s', 'prismleaf' ) ),
								wp_kses_post( $author_link )
							);
							?>
						</span>
					<?php endif; ?>
					<?php if ( $show_date ) : ?>
						<span class="prismleaf-archive-title-meta-fragment">
							<?php
							printf(
								/* translators: %s: published date. */
								esc_html__( ' on %s', 'prismleaf' ),
								esc_html( $published_date )
							);
							?>
						</span>
					<?php endif; ?>
					<?php if ( $show_categories && $categories_list ) : ?>
						<span class="prismleaf-archive-title-meta-fragment">
							<?php
							printf(
								/* translators: %s: category list. */
								wp_kses_post( __( ' filed under %s', 'prismleaf' ) ),
								wp_kses_post( $categories_list )
							);
							?>
						</span>
					<?php endif; ?>
				</li>
				<?php
				$post_index++;
				continue;
				?>
			<?php endif; ?>
			<?php
			$article_classes = array( 'prismleaf-archive-card' );
			if ( $render_full_layout ) {
				$article_classes[] = 'prismleaf-archive-card-layout-full';
			} elseif ( 'list' === $layout || 'firstfull' === $layout ) {
				$article_classes[] = 'prismleaf-archive-card-layout-list';
			}
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $article_classes ); ?>>
				<?php if ( 'grid' === $layout ) : ?>
					<?php if ( $has_thumbnail ) : ?>
						<figure class="prismleaf-archive-card-thumbnail">
							<a class="prismleaf-archive-card-thumbnail-link" href="<?php echo esc_url( get_permalink() ); ?>">
								<?php
								the_post_thumbnail(
									'prismleaf-archive-card',
									array(
										'loading' => 'lazy',
										'sizes'   => '(max-width: 300px) 100vw, 300px',
									)
								);
								?>
							</a>
						</figure>
					<?php endif; ?>
					<header class="prismleaf-archive-card-header entry-header">
						<?php the_title( sprintf( '<h2 class="prismleaf-archive-card-title entry-title"><a class="prismleaf-archive-card-title-link entry-title-link" href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php if ( '' !== $posted_by_label ) : ?>
							<p class="prismleaf-archive-card-meta entry-meta">
								<?php echo wp_kses_post( $posted_by_label ); ?>
							</p>
						<?php endif; ?>
						<?php if ( $categories_list && $show_categories ) : ?>
							<p class="prismleaf-archive-card-categories entry-categories">
								<?php echo wp_kses_post( $categories_list ); ?>
							</p>
						<?php endif; ?>
					</header>
					<div class="prismleaf-archive-card-excerpt entry-summary">
						<?php the_excerpt(); ?>
					</div>
					<div class="prismleaf-archive-card-footer">
						<?php if ( $show_comments ) : ?>
							<a class="prismleaf-archive-card-comments-link" href="<?php echo esc_url( get_comments_link() ); ?>">
								<?php echo esc_html( $comment_text ); ?>
							</a>
						<?php endif; ?>
						<a class="prismleaf-archive-card-continue-link entry-read-more" href="<?php echo esc_url( get_permalink() ); ?>">
							<?php esc_html_e( 'Continue reading', 'prismleaf' ); ?>
						</a>
					</div>
				<?php elseif ( $render_full_layout ) : ?>
					<?php
					$full_top_classes = array( 'prismleaf-archive-card-full-top' );
					if ( ! $has_thumbnail ) {
						$full_top_classes[] = 'prismleaf-archive-card-full-top-no-media';
					}
					?>
					<div class="<?php echo esc_attr( implode( ' ', $full_top_classes ) ); ?>">
						<?php if ( $has_thumbnail ) : ?>
							<figure class="prismleaf-archive-card-thumbnail prismleaf-archive-card-thumbnail-full">
								<a class="prismleaf-archive-card-thumbnail-link" href="<?php echo esc_url( get_permalink() ); ?>">
									<?php
									the_post_thumbnail(
										'prismleaf-archive-card',
										array(
											'loading' => 'lazy',
											'sizes'   => '(max-width: 300px) 100vw, 300px',
										)
									);
									?>
								</a>
							</figure>
						<?php endif; ?>
						<div class="prismleaf-archive-card-full-meta-wrap">
							<header class="prismleaf-archive-card-header prismleaf-archive-card-header-full entry-header">
								<?php the_title( sprintf( '<h2 class="prismleaf-archive-card-title entry-title"><a class="prismleaf-archive-card-title-link entry-title-link" href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							</header>
							<div class="prismleaf-archive-card-full-meta">
								<?php if ( $show_author ) : ?>
									<p class="prismleaf-archive-card-meta prismleaf-archive-card-full-meta-line">
										<?php
										printf(
											/* translators: %s: author link. */
											wp_kses_post( __( 'Posted by: %s', 'prismleaf' ) ),
											wp_kses_post( $author_link )
										);
										?>
									</p>
								<?php endif; ?>
								<?php if ( $show_date ) : ?>
									<p class="prismleaf-archive-card-meta prismleaf-archive-card-full-meta-line">
										<?php
										printf(
											/* translators: %s: published date. */
											esc_html__( 'Date: %s', 'prismleaf' ),
											esc_html( $published_date )
										);
										?>
									</p>
								<?php endif; ?>
								<?php if ( $show_categories && $categories_list ) : ?>
									<p class="prismleaf-archive-card-categories prismleaf-archive-card-full-meta-line">
										<?php
										printf(
											/* translators: %s: category list. */
											wp_kses_post( __( 'Category: %s', 'prismleaf' ) ),
											wp_kses_post( $categories_list )
										);
										?>
									</p>
								<?php endif; ?>
								<?php if ( $show_comments ) : ?>
									<p class="prismleaf-archive-card-meta prismleaf-archive-card-full-meta-line">
										<?php
										printf(
											/* translators: %s: comments link. */
											wp_kses_post( __( 'Comments: %s', 'prismleaf' ) ),
											'<a class="prismleaf-archive-card-comments-link" href="' . esc_url( get_comments_link() ) . '">' . esc_html( $comment_text ) . '</a>'
										);
										?>
									</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="prismleaf-archive-card-full-content entry-content">
						<?php the_content(); ?>
					</div>
				<?php else : ?>
					<header class="prismleaf-archive-card-header entry-header">
						<?php the_title( sprintf( '<h2 class="prismleaf-archive-card-title entry-title"><a class="prismleaf-archive-card-title-link entry-title-link" href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
									<?php
									the_post_thumbnail(
										'prismleaf-archive-card',
										array(
											'loading' => 'lazy',
											'sizes'   => '(max-width: 300px) 100vw, 300px',
										)
									);
									?>
								</a>
							</figure>
						<?php endif; ?>
						<div class="prismleaf-archive-card-list-excerpt">
							<div class="prismleaf-archive-card-excerpt entry-summary">
								<?php the_excerpt(); ?>
							</div>
							<a class="prismleaf-archive-card-continue-link" href="<?php echo esc_url( get_permalink() ); ?>">
								<?php esc_html_e( 'Continue reading', 'prismleaf' ); ?>
							</a>
						</div>
					</div>
					<div class="prismleaf-archive-card-list-meta-row">
						<?php if ( '' !== $posted_by_label ) : ?>
							<p class="prismleaf-archive-card-meta prismleaf-archive-card-meta-list entry-meta">
								<?php echo wp_kses_post( $posted_by_label ); ?>
							</p>
						<?php endif; ?>
						<?php if ( $show_comments ) : ?>
							<a class="prismleaf-archive-card-comments-link prismleaf-archive-card-comments-link-list entry-comments-link" href="<?php echo esc_url( get_comments_link() ); ?>">
								<?php echo esc_html( $comment_text ); ?>
							</a>
						<?php endif; ?>
					</div>
					<?php if ( $categories_list && $show_categories ) : ?>
						<p class="prismleaf-archive-card-categories prismleaf-archive-card-categories-list entry-categories">
							<?php echo wp_kses_post( $categories_list ); ?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</article>
			<?php $post_index++; ?>
		<?php endwhile; ?>
	<?php if ( 'title' === $layout ) : ?>
	</ol>
	<?php else : ?>
	</div>
	<?php endif; ?>
	<?php
	get_template_part( 'template-parts/pagination', null, array( 'type' => 'archive' ) );
else :
	get_template_part( 'template-parts/not-found', null, array( 'show_poem' => $show_poem ) );
endif;
