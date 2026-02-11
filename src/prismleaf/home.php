<?php
/**
 * Blog index/homepage template for Prismleaf.
 *
 * Displays the custom header and latest posts grid.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$home_title       = prismleaf_get_home_title();
$home_description = prismleaf_get_home_description();
$show_latest      = prismleaf_get_home_show_latest_posts();
$show_page_title  = prismleaf_get_home_show_page_title();
$title_id         = $show_page_title ? 'content-title-' . wp_unique_id() : 'site-title';

if ( $show_page_title ) {
	get_template_part(
		'template-parts/content-title',
		null,
		array(
			'title_id'      => $title_id,
			'title_tag'     => 'h2',
			'content_title' => $home_title,
			'description'   => $home_description,
		)
	);
}
?>

<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
	<article class="prismleaf-home">
		<?php if ( $show_latest ) : ?>
			<div class="prismleaf-content-latest-posts">
				<?php get_template_part( 'template-parts/archive-results', null, array( 'type' => 'home', ) ); ?>
			</div>
		<?php endif; ?>
	</article>
</section>

<?php
get_footer();
