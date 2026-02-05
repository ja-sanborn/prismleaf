<?php
/**
 * Customizer palette preview control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Prismleaf_Customize_Palette_Preview_Control' ) ) {
	/**
	 * Renders a palette preview control for computed palette values.
	 *
	 * @since 1.0.0
	 */
	class Prismleaf_Customize_Palette_Preview_Control extends WP_Customize_Control {
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
			$this->json['paletteLabels']  = $this->palette_labels;
			$this->json['paletteSetting'] = isset( $this->settings['palette'] ) ? $this->settings['palette']->id : '';
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
		 * Update a Customizer setting value when available.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Customize_Setting|null $setting Customizer setting.
		 * @param string                    $value   New value.
		 * @return void
		 */
		protected function update_setting_value( $setting, $value ) {
			if ( $setting instanceof WP_Customize_Setting && $this->manager instanceof WP_Customize_Manager ) {
				$this->manager->set_post_value( $setting->id, $value );
			}
		}

		/**
		 * Render a color picker input with an optional label.
		 *
		 * @since 1.0.0
		 *
		 * @param string $setting_key  Setting key to link.
		 * @param string $id           Input id attribute.
		 * @param string $class        Input class attribute.
		 * @param string $label        Optional label text.
		 * @param string $label_class  Optional label class attribute.
		 * @return void
		 */
		protected function render_color_input( $setting_key, $id, $class, $label = '', $label_class = '' ) {
			$id          = trim( (string) $id );
			$class       = trim( (string) $class );
			$label       = (string) $label;
			$label_class = trim( (string) $label_class );

			if ( '' !== $label ) {
				?>
				<label class="<?php echo esc_attr( $label_class ); ?>" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
				<?php
			}
			?>
			<input id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>" type="text" <?php $this->link( $setting_key ); ?> />
			<?php
		}

		/**
		 * Render a preview grid container.
		 *
		 * @since 1.0.0
		 *
		 * @param string $class CSS class name.
		 * @return void
		 */
		protected function render_preview_grid( $class ) {
			$class = trim( (string) $class );
			if ( '' === $class ) {
				return;
			}
			?>
			<div class="<?php echo esc_attr( $class ); ?>" hidden></div>
			<?php
		}

		/**
		 * Render the default color picker input for palette-style controls.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		protected function render_default_color_input() {
			$this->render_color_input( 'default', $this->id . '-base', 'prismleaf-palette-preview-input color-picker' );
		}

		/**
		 * Render the control content.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function render_content() {
			$label           = (string) $this->label;
			$description     = (string) $this->description;
			$palette_setting = isset( $this->settings['palette'] ) ? $this->settings['palette'] : null;
			$base_value      = sanitize_hex_color( $this->value() );
			$palette_json    = '';

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
