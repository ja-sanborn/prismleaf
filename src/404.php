<?php
/**
 * The main template file for Prismleaf.
 *
 * This file is intentionally content-focused.
 * It includes the header, outputs placeholder content,
 * and defers all global layout structure to header.php and footer.php.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title_id    = 'content-title-' . wp_unique_id();
$title       = __( 'Page Not Found', 'prismleaf' );
$description = __( 'The page you are looking for cannot be found. Try the search box below or explore another section.', 'prismleaf' );

get_header( '', array( 'title_id' => $title_id ) );
get_template_part(
	'template-parts/content-title',
	array(
		'title_id'      => $title_id,
		'title_tag'     => 'h1',
		'content_title' => $title,
		'description'   => $description,
	)
);
?>

<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
    <?php get_template_part( 'template-parts/not-found', null, array( 'is_404' => true ) ); ?>
</section>

<?php
get_footer();
