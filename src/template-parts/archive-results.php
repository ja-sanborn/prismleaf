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
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'prismleaf-post' ); ?>>
                <header class="entry-header">
                    <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                    <?php
                    $time_string = sprintf(
                        '<time datetime="%1$s">%2$s</time>',
                        esc_attr( get_the_date( DATE_W3C ) ),
                        esc_html( get_the_date() )
                    );

                    printf(
                        '<p class="entry-meta">%s</p>',
                        wp_kses_post(
                            sprintf(
                                /* translators: %s: post date. */
                                esc_html__( 'Published on %s', 'prismleaf' ),
                                $time_string
                            )
                        )
                    );
                    ?>
                </header>
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div>
                <a class="entry-link" href="<?php echo esc_url( get_permalink() ); ?>">
                    <?php esc_html_e( 'Continue reading', 'prismleaf' ); ?>
                </a>
            </article>
        <?php endwhile; ?>
    </div>
    <?php
    get_template_part( 'template-parts/pagination', null, array( 'type' => 'archive' ) );
else :
    get_template_part( 'template-parts/not-found', null, array ( 'show_poem' => $show_poem ) );
endif;
