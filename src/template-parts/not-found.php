<?php
/**
 * Template part for not-found / no-results sections.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$context      = isset( $args['context'] ) ? sanitize_key( $args['context'] ) : '404';
$search_query = isset( $args['search_query'] ) ? (string) $args['search_query'] : '';
$show_poem    = isset( $args['show_poem'] ) ? (bool) $args['show_poem'] : true;
$title_tag    = isset( $args['title_tag'] ) ? strtolower( trim( (string) $args['title_tag'] ) ) : '';
$title_id     = 'content-title-' . wp_unique_id();
$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
	$title_tag = 'h1';
}

$poems = array(
	'lost'       => array(
		'line' => array(
			"I lost a World — the other day!",
			"Has Anybody found?",
			"You'll know it by the Row of Stars",
			"Around its forehead bound."
		),
		'body' => array(
			array(
				"A Rich man — might not notice it —",
				"Yet — to my frugal Eye,",
				"Of more Esteem than Ducats —",
				"Oh find it — Sir — for me!",
			),
		),
		'cite' => '— Emily Dickinson, “I lost a World — the other day!”',
	),
	'soft_rains' => array(
		'line' => array(
			'There will come soft rains and the smell of the ground,',
			'And swallows circling with their shimmering sound;'
		),
		'body' => array(
			array(
				'And frogs in the pools singing at night,',
				'And wild plum trees in tremulous white;'
			),
			array(
				'Robins will wear their feathery fire,',
				'Whistling their whims on a low fence-wire;'
			),
			array(
				'And not one will know of the war, not one',
				'Will care at last when it is done.'
			),
			array(
				'Not one would mind, neither fish nor frog,',
				'If mankind perished utterly;'
			),
			array(
				'And Spring herself, when she woke at dawn,',
				'Would scarcely know that we were gone.'
			),
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
		'title'    => __( 'No Matches Found', 'prismleaf' ),
		'summary'  => __( 'We could not find any search results. Try broader keywords or browse the navigation.', 'prismleaf' ),
		'poem_key' => 'soft_rains',
	),
	'entries' => array(
		'title'    => __( 'Content Unavailable', 'prismleaf' ),
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
$read_more = __( 'Read more of the poem', 'prismleaf' );
?>

<section class="prismleaf-content-area" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
	<header class="prismleaf-content-title">
		<<?php echo $title_tag; ?> id="<?php echo esc_attr( $title_id ); ?>" class="prismleaf-content-title-text">
			<?php echo esc_html( $entry['title'] ); ?>
		</<?php echo $title_tag; ?>>
	</header>

	<div class="prismleaf-content-body">
		<?php if ( $show_poem ) : ?>
			<blockquote lang="en">
				<details class="prismleaf-quote-expand" aria-describedby="<?php echo esc_attr( $title_id ); ?>">
					<summary class="prismleaf-quote-line">
						<span class="prismleaf-quote-start"><?php echo wp_kses_post( implode( '<br>', $poem['line'] ) ); ?></span>
						<span class="screen-reader-text"><?php echo esc_html( $read_more ); ?></span>
					</summary>

					<div class="prismleaf-quote-body">
						<?php foreach ( (array) $poem['body'] as $stanza ) : ?>
							<p class="prismleaf-quote-line"><?php echo wp_kses_post( implode( '<br>', $stanza ) ); ?></p>
						<?php endforeach; ?>
					</div>
				</details>

				<cite lang="en"><?php echo esc_html( $poem['cite'] ); ?></cite>
			</blockquote>
		<?php endif; ?>

		<p>
			<?php
			echo esc_html( $entry['summary'] );

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

		<div class="prismleaf-not-found-search">
			<?php get_search_form(); ?>
		</div>
	</div>
</section>
