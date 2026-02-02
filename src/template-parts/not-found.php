<?php
/**
 * Template part for not-found / no-results sections.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = array(
	'not_found' => array(
		'summary'   => __( 'The content you requested could not be located. It may have been moved, deleted, or never published, but the search box is still available below.', 'prismleaf' ),
		'poem_line' => array(
			'I lost a World — the other day!',
			'Has Anybody found?',
			"You'll know it by the Row of Stars",
			'Around its forehead bound.',
		),
		'poem_body' => array(
			array(
				'A Rich man — might not notice it —',
				'Yet — to my frugal Eye,',
				'Of more Esteem than Ducats —',
				'Oh find it — Sir — for me!',
			),
		),
		'poem_cite' => 'Emily Dickinson, “I lost a World — the other day!”',
	),
	'results'   => array(
		'summary'   => __( 'It appears there are no matching results right now.', 'prismleaf' ),
		'poem_line' => array(
			'There will come soft rains and the smell of the ground,',
			'And swallows circling with their shimmering sound;',
		),
		'poem_body' => array(
			array(
				'And frogs in the pools singing at night,',
				'And wild plum trees in tremulous white;',
			),
			array(
				'Robins will wear their feathery fire,',
				'Whistling their whims on a low fence-wire;',
			),
			array(
				'And not one will know of the war, not one',
				'Will care at last when it is done.',
			),
			array(
				'Not one would mind, neither fish nor frog,',
				'If mankind perished utterly;',
			),
			array(
				'And Spring herself, when she woke at dawn,',
				'Would scarcely know that we were gone.',
			),
		),
		'poem_cite' => 'Sara Teasdale, “There Will Come Soft Rains”',
	),
);

$is_404       = isset( $args['is_404'] ) ? (bool) $args['is_404'] : false;
$show_poem    = isset( $args['show_poem'] ) ? (bool) $args['show_poem'] : true;
$read_more    = __( 'Read more of the poem', 'prismleaf' );
$described_by = isset( $args['described_by'] ) ? (string) $args['described_by'] : '';
$entry        = $is_404 ? $content['not_found'] : $content['results'];

if ( $show_poem ) :
	?>
	<blockquote class="prismleaf-quote" lang="en">
		<details
			class="prismleaf-quote-expand"
			<?php echo '' !== $described_by ? 'aria-describedby="' . esc_attr( $described_by ) . '"' : ''; ?>
		>
			<summary class="prismleaf-quote-summary">
				<span class="prismleaf-quote-line"><?php echo wp_kses_post( implode( '<br>', $entry['poem_line'] ) ); ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $read_more ); ?></span>
			</summary>
			<div class="prismleaf-quote-body">
				<?php foreach ( (array) $entry['poem_body'] as $stanza ) : ?>
					<p class="prismleaf-quote-line"><?php echo wp_kses_post( implode( '<br>', $stanza ) ); ?></p>
				<?php endforeach; ?>
			</div>
		</details>
		<cite class="prismleaf-quote-cite" lang="en"><?php echo esc_html( $entry['poem_cite'] ); ?></cite>
	</blockquote>
	<?php
endif;
?>

<p class="prismleaf-not-found-body">
	<?php echo esc_html( $entry['summary'] ); ?>
</p>
<div class="prismleaf-not-found-search">
	<?php get_search_form(); ?>
</div>
