<?php
/**
 * Customizer section header control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Prismleaf_Customize_Section_Header_Control' ) ) {
	/**
	 * Renders a non-interactive header inside a Customizer section.
	 *
	 * @since 1.0.0
	 */
	class Prismleaf_Customize_Section_Header_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'prismleaf_section_header';

		/**
		 * Render the control content.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function render_content() {
			$label       = (string) $this->label;
			$description = (string) $this->description;

			if ( '' === $label && '' === $description ) {
				return;
			}

			echo '<div class="prismleaf-customize-section-header">';
			echo '<hr class="prismleaf-customize-divider" />';

			if ( '' !== $label ) {
				echo '<span class="customize-control-title" style="font-size:15px;line-height:1.4;display:block;">' . esc_html( $label ) . '</span>';
			}

			if ( '' !== $description ) {
				echo '<p class="description customize-control-description">' . wp_kses_post( $description ) . '</p>';
			}

			echo '</div>';
		}
	}
}
