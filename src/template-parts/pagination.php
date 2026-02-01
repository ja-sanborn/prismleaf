<?php
/**
 * Pagination template part.
 *
 * Accepts a `type` argument (archive|pagebreak|post) and renders the
 * corresponding pagination control using the Customizer settings.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed_types   = array( 'archive', 'pagebreak', 'post' );
$pagination_type = 'archive';

if ( isset( $args['type'] ) ) {
	$type_candidate = sanitize_key( $args['type'] );
	if ( in_array( $type_candidate, $allowed_types, true ) ) {
		$pagination_type = $type_candidate;
	}
}

$settings = array(
	'is_buttons'        => false,
	'shape'             => 'Round',
	'show_page_numbers' => true,
	'show_post_titles'  => true,
);

switch ( $pagination_type ) {
	case 'pagebreak':
	case 'post':
		$settings['is_buttons']        = prismleaf_get_theme_mod_bool( 'prismleaf_entry_navigation_is_buttons', false );
		$settings['shape']             = prismleaf_get_theme_mod_pagination_shape( 'prismleaf_entry_navigation_shape', 'entry_navigation_shape', 'Round' );
		$settings['show_page_numbers'] = prismleaf_get_theme_mod_bool( 'prismleaf_entry_navigation_show_page_numbers', true );
		$settings['show_post_titles']  = prismleaf_get_theme_mod_bool( 'prismleaf_entry_navigation_show_post_titles', true );
		break;
	default:
		$settings['is_buttons'] = prismleaf_get_theme_mod_bool( 'prismleaf_result_navigation_is_buttons', false );
		$settings['shape']      = prismleaf_get_theme_mod_pagination_shape( 'prismleaf_result_navigation_shape', 'result_navigation_shape', 'Round' );
		break;
}


$classes = array(
	'prismleaf-pagination',
	'prismleaf-pagination-type-' . $pagination_type,
);

$aria_label = __( 'Archive pagination', 'prismleaf' );
if ( 'pagebreak' === $pagination_type ) {
	$aria_label = __( 'Page navigation', 'prismleaf' );
} elseif ( 'post' === $pagination_type ) {
	$aria_label = __( 'Post navigation', 'prismleaf' );
}

$classes[] = $settings['is_buttons'] ? 'prismleaf-pagination-buttons' : 'prismleaf-pagination-bar';
$classes[] = 'prismleaf-pagination-shape-' . sanitize_title_with_dashes( strtolower( $settings['shape'] ) );

$previous_post = '';
$next_post     = '';
if ( 'post' === $pagination_type ) {
		$previous_post = get_previous_post( false );
		$next_post     = get_next_post( false );
	if ( $previous_post && $next_post ) {
		$classes[] = 'prismleaf-pagination-post-has-both';
	}
	if ( $settings['show_post_titles'] ) {
		$classes[] = 'prismleaf-pagination-post-has-titles';
	}
}

$nav_args = array(
	'prev_text' => sprintf(
		/* translators: arrow indicator for previous page. */
		'<span class="screen-reader-text">%1$s</span><span aria-hidden="true">❮</span>',
		esc_html__( 'Previous page', 'prismleaf' )
	),
	'next_text' => sprintf(
		/* translators: arrow indicator for next page. */
		'<span aria-hidden="true">❯</span><span class="screen-reader-text">%1$s</span>',
		esc_html__( 'Next page', 'prismleaf' )
	),
);

ob_start();

switch ( $pagination_type ) {
	case 'pagebreak':
		global $page, $numpages, $multipage;

		if ( empty( $multipage ) || $numpages < 2 ) {
			break;
		}

		$prev = '';
		if ( $page > 1 ) {
			$prev  = _wp_link_page( $page - 1 );
			$prev .= sprintf(
				'<span aria-hidden="true">❮</span><span class="screen-reader-text">%s</span>',
				esc_html__( 'Previous page', 'prismleaf' )
			);
			$prev .= '</a>';
		}

		$next = '';
		if ( $page < $numpages ) {
			$next  = _wp_link_page( $page + 1 );
			$next .= sprintf(
				'<span class="screen-reader-text">%s</span><span aria-hidden="true">❯</span>',
				esc_html__( 'Next page', 'prismleaf' )
			);
			$next .= '</a>';
		}

		echo wp_kses_post( $prev );

		if ( $settings['show_page_numbers'] ) {
			printf(
				'<span class="prismleaf-pagination-page-count">%s</span>',
				esc_html(
					sprintf(
						/* translators: %1$d: current page number, %2$d: total pages. */
						__( 'Page %1$d of %2$d', 'prismleaf' ),
						$page,
						$numpages
					)
				)
			);
		}

		echo wp_kses_post( $next );
		break;

	case 'post':
		$prev_text = $settings['show_post_titles']
			? sprintf(
				'<span class="screen-reader-text">%1$s</span><span aria-hidden="true">❮</span><span class="prismleaf-pagination-post-link-title">%2$s</span>',
				esc_html__( 'Previous post', 'prismleaf' ),
				'%title'
			)
			: '<span class="screen-reader-text">' . esc_html__( 'Previous post', 'prismleaf' ) . '</span><span aria-hidden="true">❮</span>';

		$next_text = $settings['show_post_titles']
			? sprintf(
				'<span class="prismleaf-pagination-post-link-title">%2$s</span><span class="screen-reader-text">%1$s</span><span aria-hidden="true">❯</span>',
				esc_html__( 'Next post', 'prismleaf' ),
				'%title'
			)
			: '<span class="screen-reader-text">' . esc_html__( 'Next post', 'prismleaf' ) . '</span><span aria-hidden="true">❯</span>';

		$prev_link = get_previous_post_link(
			'%link',
			$prev_text,
			true,
			'',
			'category'
		);

		$next_link = get_next_post_link(
			'%link',
			$next_text,
			true,
			'',
			'category'
		);

		if ( '' !== $prev_link ) {
			echo wp_kses_post( $prev_link );
		}

		if ( '' !== $next_link ) {
			echo wp_kses_post( $next_link );
		}

		break;

	default:
		global $wp_query;

		if ( empty( $wp_query ) || $wp_query->max_num_pages < 2 ) {
			break;
		}

		$mid_size = 1;
		$end_size = 1;
		$big      = 999999999;

		$links = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, get_query_var( 'paged', 1 ) ),
				'total'     => $wp_query->max_num_pages,
				'mid_size'  => $mid_size,
				'end_size'  => $end_size,
				'type'      => 'array',
				'prev_text' => $nav_args['prev_text'],
				'next_text' => $nav_args['next_text'],
			)
		);

		if ( empty( $links ) ) {
			break;
		}

		echo wp_kses_post( implode( '', $links ) );
		break;
}

$content = trim( ob_get_clean() );

if ( '' === $content ) {
	return;
}
?>

<nav
	class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	role="navigation"
	aria-label="<?php echo esc_attr( $aria_label ); ?>"
>
	<div class="prismleaf-pagination-links">
		<?php echo wp_kses_post( $content ); ?>
	</div>
</nav>
