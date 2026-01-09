<?php
/**
 * Base customizer preview control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Prismleaf_Customize_Preview_Control_Base' ) ) {
	/**
	 * Base class for preview-style controls.
	 *
	 * @since 1.0.0
	 */
	abstract class Prismleaf_Customize_Preview_Control_Base extends WP_Customize_Control {
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
			if ( $setting instanceof WP_Customize_Setting ) {
				$setting->set_value( $value );
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
			$id = trim( (string) $id );
			$class = trim( (string) $class );
			$label = (string) $label;
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
	}
}
