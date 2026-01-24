<?php
/**
 * Customizer palette source control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Prismleaf_Customize_Palette_Source_Control' ) ) {
	/**
	 * Renders a palette source select with an embedded palette preview.
	 *
	 * @since 1.0.0
	 */
	class Prismleaf_Customize_Palette_Source_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'prismleaf_palette_source';

		/**
		 * Labels for palette keys.
		 *
		 * @var array<string,string>
		 */
		public $palette_labels = array();

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
		}

		/**
		 * Render the label and description for a control.
		 *
		 * @since 1.0.0
		 *
		 * @param string $label Label text.
		 * @param string $description Description text.
		 * @return void
		 */
		protected function render_label_description( $label, $description ) {
			if ( '' !== $label ) {
				?>
				<span class="customize-control-title"><?php echo esc_html( $label ); ?></span>
				<?php
			}

			if ( '' !== $description ) {
				?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $description ); ?></span>
				<?php
			}
		}

		/**
		 * Render the palette source select field.
		 *
		 * @since 1.0.0
		 *
		 * @param string $setting_key Setting key to link.
		 * @return void
		 */
		protected function render_source_select( $setting_key ) {
			if ( empty( $this->choices ) ) {
				return;
			}

			$current = '';
			if ( isset( $this->settings[ $setting_key ] ) ) {
				$current = (string) $this->settings[ $setting_key ]->value();
			}
			?>
			<select class="prismleaf-palette-source-select" <?php $this->link( $setting_key ); ?>>
				<?php foreach ( $this->choices as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, (string) $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php
		}

		/**
		 * Render the default color picker input for palette-style controls.
		 *
		 * @since 1.0.0
		 *
		 * @param string $setting_key Setting key to link.
		 * @return void
		 */
		protected function render_default_color_input( $setting_key ) {
			?>
			<input class="prismleaf-palette-preview-input color-picker" type="text" <?php $this->link( $setting_key ); ?> />
			<?php
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

			if ( '' === $label && '' === $description ) {
				return;
			}
			?>
			<div class="prismleaf-palette-source-control" data-control-id="<?php echo esc_attr( $this->id ); ?>">
				<?php $this->render_label_description( $label, $description ); ?>

				<?php $this->render_source_select( 'source' ); ?>

				<div class="prismleaf-palette-source-preview">
					<?php $this->render_default_color_input( 'base' ); ?>

					<?php if ( isset( $this->settings['palette'] ) ) : ?>
						<input class="prismleaf-palette-preview-json" type="hidden" <?php $this->link( 'palette' ); ?> />
					<?php endif; ?>

					<div class="prismleaf-preview-grid" hidden></div>
				</div>
			</div>
			<?php
		}
	}
}
