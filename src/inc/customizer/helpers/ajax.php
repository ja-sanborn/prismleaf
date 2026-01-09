<?php
/**
 * Customizer AJAX handlers.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'prismleaf_ajax_neutral_preview' ) ) {
	/**
	 * Ajax handler for neutral preview calculations.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function prismleaf_ajax_neutral_preview() {
		check_ajax_referer( 'prismleaf-neutral-preview' );

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error();
		}

		$light = isset( $_POST['light'] ) ? sanitize_hex_color( wp_unslash( $_POST['light'] ) ) : '';
		$dark  = isset( $_POST['dark'] ) ? sanitize_hex_color( wp_unslash( $_POST['dark'] ) ) : '';
		$source = isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '';

		$payload = prismleaf_build_neutral_preview_payload( $light, $dark, $source );

		wp_send_json_success( $payload );
	}
}
add_action( 'wp_ajax_prismleaf_neutral_preview', 'prismleaf_ajax_neutral_preview' );
