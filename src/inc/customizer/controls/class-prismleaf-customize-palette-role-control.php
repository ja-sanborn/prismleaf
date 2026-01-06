<?php
/**
 * Prismleaf Customizer: Palette Role Control.
 *
 * Composite control for selecting a palette role or custom light/dark colors.
 *
 * @package prismleaf
 */

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Prismleaf_Customize_Palette_Role_Control' ) ) {
	/**
	 * Palette role + custom color control.
	 */
	class Prismleaf_Customize_Palette_Role_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'prismleaf_palette_role';

		/**
		 * Role choices for the dropdown.
		 *
		 * @var array<string,string>
		 */
		public $choices = array();

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
		 * Render the control content.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function render_content() {
			$role_setting  = isset( $this->settings['role'] ) ? $this->settings['role'] : null;
			$light_setting = isset( $this->settings['light'] ) ? $this->settings['light'] : null;
			$dark_setting  = isset( $this->settings['dark'] ) ? $this->settings['dark'] : null;

			$role_value  = $role_setting ? $role_setting->value() : '';
			$light_value = $light_setting ? $light_setting->value() : '';
			$dark_value  = $dark_setting ? $dark_setting->value() : '';

			if ( '' !== $this->label ) {
				echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			}

			if ( '' !== $this->description ) {
				echo '<span class="description customize-control-description">' . wp_kses_post( $this->description ) . '</span>';
			}

			$control_id = esc_attr( $this->id );
			?>
			<div class="prismleaf-palette-role-control" data-control-id="<?php echo $control_id; ?>">
				<select class="prismleaf-palette-role-select" <?php $this->link( 'role' ); ?>>
					<?php foreach ( (array) $this->choices as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( (string) $role_value, (string) $value ); ?>>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>

				<div class="prismleaf-palette-role-custom">
					<div class="prismleaf-palette-role-custom-row">
						<label for="<?php echo esc_attr( $control_id . '-light' ); ?>">
							<?php esc_html_e( 'Custom light color', 'prismleaf' ); ?>
						</label>
						<input
							id="<?php echo esc_attr( $control_id . '-light' ); ?>"
							class="prismleaf-palette-role-custom-input color-picker"
							type="text"
							value="<?php echo esc_attr( $light_value ); ?>"
							<?php $this->link( 'light' ); ?>
						/>
					</div>

					<div class="prismleaf-palette-role-custom-row">
						<label for="<?php echo esc_attr( $control_id . '-dark' ); ?>">
							<?php esc_html_e( 'Custom dark color', 'prismleaf' ); ?>
						</label>
						<input
							id="<?php echo esc_attr( $control_id . '-dark' ); ?>"
							class="prismleaf-palette-role-custom-input color-picker"
							type="text"
							value="<?php echo esc_attr( $dark_value ); ?>"
							<?php $this->link( 'dark' ); ?>
						/>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
