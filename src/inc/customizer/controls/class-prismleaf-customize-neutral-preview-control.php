<?php
/**
 * Customizer neutral preview control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Prismleaf_Customize_Preview_Control_Base' ) && ! class_exists( 'Prismleaf_Customize_Neutral_Preview_Control' ) ) {
	/**
	 * Renders a neutral preview control for computed neutral values.
	 *
	 * @since 1.0.0
	 */
	class Prismleaf_Customize_Neutral_Preview_Control extends Prismleaf_Customize_Preview_Control_Base {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'prismleaf_neutral_preview';

		/**
		 * Labels for neutral keys.
		 *
		 * @var array<string,string>
		 */
		public $neutral_labels = array();

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
			$this->json['neutralLabels'] = $this->neutral_labels;
			$this->json['lightSetting']  = isset( $this->settings['light'] ) ? $this->settings['light']->id : '';
			$this->json['darkSetting']   = isset( $this->settings['dark'] ) ? $this->settings['dark']->id : '';
			$this->json['neutralSetting'] = isset( $this->settings['neutral'] ) ? $this->settings['neutral']->id : '';
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
			$light_setting = isset( $this->settings['light'] ) ? $this->settings['light'] : null;
			$dark_setting  = isset( $this->settings['dark'] ) ? $this->settings['dark'] : null;
			$neutral_setting = isset( $this->settings['neutral'] ) ? $this->settings['neutral'] : null;

			$light_value = $light_setting ? sanitize_hex_color( $light_setting->value() ) : '';
			$dark_value  = $dark_setting ? sanitize_hex_color( $dark_setting->value() ) : '';

			$neutral_payload = array(
				'light' => '',
				'dark'  => '',
				'json'  => '',
			);

			if ( $neutral_setting && ( $light_value || $dark_value ) ) {
				$neutral_payload = prismleaf_build_neutral_preview_payload( $light_value, $dark_value, '' );
				$this->update_setting_value( $neutral_setting, $neutral_payload['json'] );

				if ( $light_setting && $neutral_payload['light'] ) {
					$this->update_setting_value( $light_setting, $neutral_payload['light'] );
				}

				if ( $dark_setting && $neutral_payload['dark'] ) {
					$this->update_setting_value( $dark_setting, $neutral_payload['dark'] );
				}
			} elseif ( $neutral_setting ) {
				$this->update_setting_value( $neutral_setting, '' );
			}

			if ( '' === $label && '' === $description ) {
				return;
			}
			?>
			<div class="prismleaf-neutral-preview-control" data-control-id="<?php echo esc_attr( $this->id ); ?>">
				<?php $this->render_label_description( $label, $description ); ?>

				<div class="prismleaf-neutral-preview-inputs">
					<?php
					$this->render_color_input(
						'light',
						$this->id . '-light',
						'prismleaf-neutral-preview-input color-picker',
						__( 'Light', 'prismleaf' ),
						'prismleaf-neutral-preview-label'
					);
					$this->render_color_input(
						'dark',
						$this->id . '-dark',
						'prismleaf-neutral-preview-input color-picker',
						__( 'Dark', 'prismleaf' ),
						'prismleaf-neutral-preview-label'
					);
					?>
				</div>

				<?php if ( $neutral_setting ) : ?>
					<input class="prismleaf-neutral-preview-json" type="hidden" value="<?php echo esc_attr( $neutral_payload['json'] ); ?>" <?php $this->link( 'neutral' ); ?> />
				<?php endif; ?>

				<?php $this->render_preview_grid( 'prismleaf-preview-grid' ); ?>
			</div>
			<?php
		}
	}
}
