<?php
/**
 * Prismleaf Search component renderer and helpers.
 *
 * Provides a single renderer used by both the template part and widget.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base defaults for the search component.
 *
 * @since 1.0.0
 *
 * @return array
 */
function prismleaf_get_search_base_defaults() {
	return array(
		'placeholder'   => __( 'Search', 'prismleaf' ),
		'flyout'        => false,
		'header_has_bg' => false,
	);
}

/**
 * Merge defaults with provided options and sanitize.
 *
 * @since 1.0.0
 *
 * @param array $options          Raw options.
 * @param array $context_defaults Contextual overrides layered on top of base defaults.
 * @return array
 */
function prismleaf_prepare_search_options( $options, $context_defaults = array() ) {
	$base_defaults    = prismleaf_get_search_base_defaults();
	$context_defaults = is_array( $context_defaults ) ? $context_defaults : array();
	$options          = is_array( $options ) ? $options : array();

	$defaults = array_merge( $base_defaults, $context_defaults );

	$placeholder = isset( $options['placeholder'] ) ? (string) $options['placeholder'] : $defaults['placeholder'];
	$placeholder = '' === $placeholder ? $defaults['placeholder'] : $placeholder;

	$flyout        = isset( $options['flyout'] ) ? (bool) wp_validate_boolean( $options['flyout'] ) : (bool) $defaults['flyout'];
	$header_has_bg = isset( $options['header_has_bg'] )
		? (bool) wp_validate_boolean( $options['header_has_bg'] )
		: (bool) $defaults['header_has_bg'];

	return array(
		'placeholder'   => $placeholder,
		'flyout'        => $flyout,
		'header_has_bg' => $header_has_bg,
	);
}

/**
 * Render the Prismleaf Search component.
 *
 * @since 1.0.0
 *
 * @param array $options Prepared options array.
 * @return void
 */
function prismleaf_render_search_component( $options = array() ) {
	$options = prismleaf_prepare_search_options( $options );

	static $instance = 0;
	++$instance;

	$input_id      = 'prismleaf-search-input-' . $instance;
	$is_flyout     = $options['flyout'];
	$header_has_bg = (bool) $options['header_has_bg'];

	$form_classes = array(
		'prismleaf-search',
		$is_flyout ? 'prismleaf-search-flyout' : 'prismleaf-search-inline',
		$header_has_bg ? 'prismleaf-search-header-background' : '',
	);

	$form_classes = array_values(
		array_filter(
			$form_classes,
			static function ( $value ) {
				return '' !== (string) $value;
			}
		)
	);

	?>
	<form class="<?php echo esc_attr( implode( ' ', $form_classes ) ); ?>" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-flyout="<?php echo esc_attr( $is_flyout ? '1' : '0' ); ?>">
		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>">
			<?php esc_html_e( 'Search', 'prismleaf' ); ?>
		</label>
		<div class="prismleaf-search-controls">
			<input
				type="search"
				id="<?php echo esc_attr( $input_id ); ?>"
				class="prismleaf-search-input"
				name="s"
				placeholder="<?php echo esc_attr( $options['placeholder'] ); ?>"
				autocomplete="off"
			/>
			<button type="button" class="prismleaf-search-button" aria-label="<?php esc_attr_e( 'Search', 'prismleaf' ); ?>" aria-expanded="<?php echo esc_attr( $is_flyout ? 'false' : 'true' ); ?>" aria-controls="<?php echo esc_attr( $input_id ); ?>">
				<span class="screen-reader-text"><?php esc_html_e( 'Search', 'prismleaf' ); ?></span>
			</button>
		</div>
	</form>
	<?php
}
