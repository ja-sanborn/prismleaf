<?php
/**
 * Customizer background image control.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Prismleaf_Customize_Background_Image_Control' ) ) {
	/**
	 * Renders a enhanced background image control that mirrors the core UI.
	 *
	 * @since 1.0.0
	 */
	class Prismleaf_Customize_Background_Image_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'prismleaf_background_image';

		/**
		 * Enqueue assets for the control.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_media();
			wp_enqueue_script(
				'prismleaf-customizer-background-image-control',
				PRISMLEAF_URI . 'assets/scripts/customizer-background-image-control.js',
				array( 'customize-controls', 'jquery', 'wp-util' ),
				PRISMLEAF_VERSION,
				true
			);
			static $localized = false;
			if ( ! $localized ) {
				wp_localize_script(
					'prismleaf-customizer-background-image-control',
					'prismleafBackgroundImageControl',
					array(
						'mediaTitle'  => __( 'Select Background Image', 'prismleaf' ),
						'mediaButton' => __( 'Use image', 'prismleaf' ),
					)
				);
				$localized = true;
			}
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

			$image_id   = isset( $this->settings['image'] ) ? (int) $this->settings['image']->value() : 0;
			$preset     = isset( $this->settings['preset'] ) ? (string) $this->settings['preset']->value() : '';
			$position_x = isset( $this->settings['position_x'] ) ? (string) $this->settings['position_x']->value() : 'center';
			$position_y = isset( $this->settings['position_y'] ) ? (string) $this->settings['position_y']->value() : 'center';
			$size       = isset( $this->settings['size'] ) ? (string) $this->settings['size']->value() : 'auto';
			$repeat     = isset( $this->settings['repeat'] ) ? (string) $this->settings['repeat']->value() : 'repeat';
			$attachment = isset( $this->settings['attachment'] ) ? (string) $this->settings['attachment']->value() : 'scroll';

			$has_image    = $image_id > 0;
			$preview_html = '';
			$preview_class = 'prismleaf-background-image-preview' . ( $has_image ? ' has-image' : ' is-empty' );
			if ( $has_image ) {
				$preview_url = wp_get_attachment_image_url( $image_id, 'medium' );
				if ( $preview_url ) {
					$preview_html = '<img src="' . esc_url( $preview_url ) . '" alt="" />';
				}
			}

			if ( '' === $label && '' === $description ) {
				return;
			}
			?>

			<div class="prismleaf-background-image-control" data-control-id="<?php echo esc_attr( $this->id ); ?>">
				<?php if ( '' !== $label ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== $description ) : ?>
					<p class="description customize-control-description"><?php echo wp_kses_post( $description ); ?></p>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $preview_class ); ?>">
					<?php
					if ( $preview_html ) {
						echo $preview_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
					<span class="prismleaf-background-image-placeholder"><?php esc_html_e( 'Select image', 'prismleaf' ); ?></span>
				</div>

				<?php $button_text = $has_image ? __( 'Change image', 'prismleaf' ) : __( 'Select image', 'prismleaf' ); ?>
				<div class="prismleaf-background-image-actions">
					<button type="button" class="link-button prismleaf-background-image-remove" <?php echo $has_image ? '' : 'hidden'; ?>>
						<?php esc_html_e( 'Remove', 'prismleaf' ); ?>
					</button>
					<button type="button" class="button prismleaf-background-image-select">
						<?php echo esc_html( $button_text ); ?>
					</button>
				</div>

				<?php if ( isset( $this->settings['image'] ) ) : ?>
					<input class="prismleaf-background-image-input" type="hidden" value="<?php echo esc_attr( $image_id ); ?>" <?php $this->link( 'image' ); ?> />
				<?php endif; ?>

				<div class="prismleaf-background-image-details" <?php echo $has_image ? '' : 'hidden'; ?>>
					<?php if ( isset( $this->settings['preset'] ) ) : ?>
						<div class="prismleaf-background-image-field">
							<label><?php esc_html_e( 'Preset', 'prismleaf' ); ?></label>
							<select class="prismleaf-background-image-preset" <?php $this->link( 'preset' ); ?>>
								<?php
								foreach ( $this->get_preset_choices() as $value => $text ) :
									?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $preset, $value ); ?>>
										<?php echo esc_html( $text ); ?>
									</option>
									<?php
								endforeach;
								?>
							</select>
						</div>
					<?php endif; ?>

					<?php if ( isset( $this->settings['position_x'] ) && isset( $this->settings['position_y'] ) ) : ?>
						<div class="prismleaf-background-image-field prismleaf-background-image-position">
							<label><?php esc_html_e( 'Image Position', 'prismleaf' ); ?></label>
							<div class="prismleaf-background-position-grid">
								<?php foreach ( $this->get_position_buttons() as $button ) : ?>
									<button type="button" class="prismleaf-background-position-button" data-position-x="<?php echo esc_attr( $button['x'] ); ?>" data-position-y="<?php echo esc_attr( $button['y'] ); ?>" <?php echo ( $button['x'] === $position_x && $button['y'] === $position_y ) ? 'aria-pressed="true"' : 'aria-pressed="false"'; ?>>
										<?php echo esc_html( $button['label'] ); ?>
									</button>
								<?php endforeach; ?>
							</div>
							<input class="prismleaf-background-position-x" type="hidden" value="<?php echo esc_attr( $position_x ); ?>" <?php $this->link( 'position_x' ); ?> />
							<input class="prismleaf-background-position-y" type="hidden" value="<?php echo esc_attr( $position_y ); ?>" <?php $this->link( 'position_y' ); ?> />
						</div>
					<?php endif; ?>

					<?php if ( isset( $this->settings['size'] ) ) : ?>
						<div class="prismleaf-background-image-field">
							<label><?php esc_html_e( 'Image Size', 'prismleaf' ); ?></label>
							<select class="prismleaf-background-image-size" <?php $this->link( 'size' ); ?>>
								<?php
								foreach ( $this->get_size_choices() as $value => $text ) :
									?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $size, $value ); ?>>
										<?php echo esc_html( $text ); ?>
									</option>
									<?php
								endforeach;
								?>
							</select>
						</div>
					<?php endif; ?>

					<?php if ( isset( $this->settings['repeat'] ) ) : ?>
						<div class="prismleaf-background-image-field prismleaf-background-image-toggle">
							<input class="prismleaf-background-image-repeat" type="hidden" value="<?php echo esc_attr( $repeat ); ?>" <?php $this->link( 'repeat' ); ?> />
							<label>
								<input class="prismleaf-background-toggle-input" type="checkbox" data-target="repeat" data-true="repeat" data-false="no-repeat" <?php checked( 'repeat', $repeat ); ?> />
								<?php esc_html_e( 'Repeat Background Image', 'prismleaf' ); ?>
							</label>
						</div>
					<?php endif; ?>

					<?php if ( isset( $this->settings['attachment'] ) ) : ?>
						<div class="prismleaf-background-image-field prismleaf-background-image-toggle">
							<input class="prismleaf-background-image-attachment" type="hidden" value="<?php echo esc_attr( $attachment ); ?>" <?php $this->link( 'attachment' ); ?> />
							<label>
								<input class="prismleaf-background-toggle-input" type="checkbox" data-target="attachment" data-true="scroll" data-false="fixed" <?php checked( 'scroll', $attachment ); ?> />
								<?php esc_html_e( 'Scroll with Page', 'prismleaf' ); ?>
							</label>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Preset choices for the control.
		 *
		 * @since 1.0.0
		 *
		 * @return array<string,string>
		 */
		protected function get_preset_choices() {
			return array(
				'default' => __( 'Default', 'prismleaf' ),
				'fill'    => __( 'Fill', 'prismleaf' ),
				'fit'     => __( 'Fit', 'prismleaf' ),
				'stretch' => __( 'Stretch', 'prismleaf' ),
				'center'  => __( 'Center', 'prismleaf' ),
			);
		}

		/**
		 * Size choices for the control.
		 *
		 * @since 1.0.0
		 *
		 * @return array<string,string>
		 */
		protected function get_size_choices() {
			return array(
				'auto'    => __( 'Original', 'prismleaf' ),
				'cover'   => __( 'Cover', 'prismleaf' ),
				'contain' => __( 'Contain', 'prismleaf' ),
				'stretch' => __( 'Stretch', 'prismleaf' ),
			);
		}

		/**
		 * Position buttons for the 3x3 grid.
		 *
		 * @since 1.0.0
		 *
		 * @return array<int,array<string,string>>
		 */
		protected function get_position_buttons() {
			$labels = array(
				'top-left'     => '↖',
				'top-center'   => '↑',
				'top-right'    => '↗',
				'middle-left'  => '←',
				'middle-center'=> '•',
				'middle-right' => '→',
				'bottom-left'  => '↙',
				'bottom-center'=> '↓',
				'bottom-right' => '↘',
			);

			$mapping = array(
				'top-left'      => array( 'x' => 'left',   'y' => 'top' ),
				'top-center'    => array( 'x' => 'center', 'y' => 'top' ),
				'top-right'     => array( 'x' => 'right',  'y' => 'top' ),
				'middle-left'   => array( 'x' => 'left',   'y' => 'center' ),
				'middle-center' => array( 'x' => 'center', 'y' => 'center' ),
				'middle-right'  => array( 'x' => 'right',  'y' => 'center' ),
				'bottom-left'   => array( 'x' => 'left',   'y' => 'bottom' ),
				'bottom-center' => array( 'x' => 'center', 'y' => 'bottom' ),
				'bottom-right'  => array( 'x' => 'right',  'y' => 'bottom' ),
			);

			$buttons = array();
			foreach ( $mapping as $key => $coords ) {
				$buttons[] = array(
					'label' => $labels[ $key ],
					'x'     => $coords['x'],
					'y'     => $coords['y'],
				);
			}

			return $buttons;
		}
	}
}
