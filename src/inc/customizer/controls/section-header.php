<?php
/**
 * Customizer section header control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders a non-interactive header inside a Customizer section.
 *
 * @since 1.0.0
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Prismleaf_Customize_Section_Header_Control' ) ) {
	class Prismleaf_Customize_Section_Header_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @since 1.0.0
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
			echo '<hr class="prismleaf-customize-divider" style="border:0;border-top:1px solid rgba(0,0,0,0.15);margin:10px 0;" />';

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
