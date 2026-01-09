<?php
/**
 * Customizer palette preview control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Prismleaf_Customize_Preview_Control_Base' ) && ! class_exists( 'Prismleaf_Customize_Palette_Preview_Control' ) ) {
	/**
	 * Renders a palette preview control for computed palette values.
	 *
	 * @since 1.0.0
	 */
	class Prismleaf_Customize_Palette_Preview_Control extends Prismleaf_Customize_Preview_Control_Base {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'prismleaf_palette_preview';

		/**
		 * Labels for palette keys.
		 *
		 * @var array<string,string>
		 */
		public $palette_labels = array();

		/**
		 * Enqueue scripts/styles for the control.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
		}

		/**
		 * Export data to JS.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function to_json() {
			parent::to_json();
			$this->json['paletteLabels'] = $this->palette_labels;
			$this->json['paletteSetting'] = isset( $this->settings['palette'] ) ? $this->settings['palette']->id : '';
		}

		/**
		 * Render the control content.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function render_content() {
			$label       = (string) $this->label;
			$description = (string) $this->description;
			$palette_setting = isset( $this->settings['palette'] ) ? $this->settings['palette'] : null;
			$base_value = sanitize_hex_color( $this->value() );
			$palette_json = '';

			if ( $palette_setting && $base_value ) {
				$palette_json = prismleaf_build_palette_json_from_base( $base_value );
				$this->update_setting_value( $palette_setting, $palette_json );
			} elseif ( $palette_setting ) {
				$this->update_setting_value( $palette_setting, '' );
			}

			if ( '' === $label && '' === $description ) {
				return;
			}
			?>
			<div class="prismleaf-palette-preview-control" data-control-id="<?php echo esc_attr( $this->id ); ?>">
				<?php $this->render_label_description( $label, $description ); ?>

				<?php $this->render_default_color_input(); ?>

				<?php if ( $palette_setting ) : ?>
					<input class="prismleaf-palette-preview-json" type="hidden" value="<?php echo esc_attr( $palette_json ); ?>" <?php $this->link( 'palette' ); ?> />
				<?php endif; ?>

				<?php $this->render_preview_grid( 'prismleaf-preview-grid' ); ?>
			</div>
			<?php
		}
	}
}
