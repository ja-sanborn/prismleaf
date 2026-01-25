<?php
/**
 * Template part for not-found / no-results sections.
 *
 * Contexts supported:
 * - 404     : Page not found (Dickinson)
 * - search  : Search performed but no results (Teasdale)
 * - entries : No entries available / empty state (Teasdale by default)
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$context      = isset( $args['context'] ) ? sanitize_key( $args['context'] ) : '404';
$search_query = isset( $args['search_query'] ) ? (string) $args['search_query'] : '';
$show_poem    = isset( $args['show_poem'] ) ? (bool) $args['show_poem'] : true;
$show_title   = isset( $args['show_title'] ) ? (bool) $args['show_title'] : true;

$read_more = __( 'Read more of the poem', 'prismleaf' );
$show_less = __( 'Show less', 'prismleaf' );

$poems = array(
	'lost'      => array(
		'line' => "I lost a World — the other day!<br>Has Anybody found?<br>You'll know it by the Row of Stars<br>Around its forehead bound.",
		'body' => array(
			"A Rich man — might not notice it —<br>Yet — to my frugal Eye,<br>Of more Esteem than Ducats —<br>Oh find it — Sir — for me!",
		),
		'cite' => '— Emily Dickinson, “I lost a World — the other day!”',
	),
	'soft_rains' => array(
		'line' => 'There will come soft rains and the smell of the ground,<br>And swallows circling with their shimmering sound;',
		'body' => array(
			'And frogs in the pools singing at night,<br>And wild plum trees in tremulous white;',
			'Robins will wear their feathery fire,<br>Whistling their whims on a low fence-wire;',
			'And not one will know of the war, not one<br>Will care at last when it is done.',
			'Not one would mind, neither bird nor tree,<br>If mankind perished utterly;',
			'And Spring herself, when she woke at dawn,<br>Would scarcely know that we were gone.',
		),
		'cite' => '— Sara Teasdale, “There Will Come Soft Rains”',
	),
);

$contexts = array(
	'404'     => array(
		'title'    => __( 'Page Not Found', 'prismleaf' ),
		'summary'  => __( 'The content you requested could not be located. It may have been moved, deleted, or never published, but the search box is still available below.', 'prismleaf' ),
		'poem_key' => 'lost',
	),
	'search'  => array(
		'title'    => __( 'Nothing matched your search', 'prismleaf' ),
		'summary'  => __( 'We could not find any search results. Try broader keywords or browse the navigation.', 'prismleaf' ),
		'poem_key' => 'soft_rains',
	),
	'entries' => array(
		'title'    => __( 'Content unavailable', 'prismleaf' ),
		'summary'  => __( 'It appears there are no matching entries right now.', 'prismleaf' ),
		'poem_key' => 'soft_rains',
	),
);

if ( ! isset( $contexts[ $context ] ) ) {
	$context = '404';
}

if ( 'search' === $context && '' === trim( $search_query ) ) {
	$context = 'entries';
}

$entry     = $contexts[ $context ];
$poem      = isset( $poems[ $entry['poem_key'] ] ) ? $poems[ $entry['poem_key'] ] : $poems['lost'];
$title     = $entry['title'];
$summary   = $entry['summary'];
$title_tag = $show_title ? 'h1' : 'span';
?>

<section class="prismleaf-not-found" aria-labelledby="not-found-title">
	<header class="prismleaf-not-found-title">
		<?php if ( 'span' === $title_tag ) : ?>
			<span id="not-found-title" class="prismleaf-not-found-title-text" role="heading" aria-level="1">
				<?php echo esc_html( $title ); ?>
			</span>
		<?php else : ?>
			<h1 id="not-found-title" class="prismleaf-not-found-title-text">
				<?php echo esc_html( $title ); ?>
			</h1>
		<?php endif; ?>
	</header>

	<?php if ( $show_poem ) : ?>
		<blockquote lang="en">
			<details class="prismleaf-quote-expand" aria-describedby="not-found-title">
				<summary>
					<span class="prismleaf-quote-line"><?php echo wp_kses_post( $poem['line'] ); ?></span>
					<span class="screen-reader-text"><?php echo esc_html( $read_more ); ?></span>
				</summary>

				<div class="prismleaf-quote-body">
					<?php foreach ( (array) $poem['body'] as $stanza ) : ?>
						<span class="prismleaf-quote-line"><?php echo wp_kses_post( $stanza ); ?></span>
					<?php endforeach; ?>
				</div>
			</details>

			<cite lang="en"><?php echo esc_html( $poem['cite'] ); ?></cite>
		</blockquote>
	<?php endif; ?>

	<p>
		<?php
		echo esc_html( $summary );

		if ( 'search' === $context && '' !== trim( $search_query ) ) {
			printf(
				'<br>%s',
				sprintf(
					/* translators: %s: search query. */
					esc_html__( 'You searched for: “%s”.', 'prismleaf' ),
					esc_html( $search_query )
				)
			);
		}
		?>
	</p>

	<?php get_search_form(); ?>
</section>
