<?php
/**
 * Prismleaf Search Widget.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Prismleaf_Search_Widget' ) ) {
	/**
	 * Prismleaf Search widget.
	 *
	 * @since 1.0.0
	 */
	class Prismleaf_Search_Widget extends WP_Widget {
		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct(
				'prismleaf_search_widget',
				__( 'Prismleaf Search', 'prismleaf' ),
				array(
					'description' => __( 'A Prismleaf-styled search form with optional flyout behavior.', 'prismleaf' ),
				)
			);
		}

		/**
		 * Output the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values.
		 * @return void
		 */
		public function widget( $args, $instance ) {
			$options = prismleaf_prepare_search_options(
				$instance,
				array(
					'flyout' => false,
				)
			);

			if ( isset( $args['before_widget'] ) ) {
				echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
			}

			prismleaf_render_search_component( $options );

			if ( isset( $args['after_widget'] ) ) {
				echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}

		/**
		 * Update a widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New values.
		 * @param array $old_instance Old values.
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['placeholder'] = isset( $new_instance['placeholder'] )
				? sanitize_text_field( wp_unslash( $new_instance['placeholder'] ) )
				: '';

			$instance['flyout'] = isset( $new_instance['flyout'] )
				? (bool) wp_validate_boolean( $new_instance['flyout'] )
				: false;

			return $instance;
		}

		/**
		 * Output widget form fields.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Saved values.
		 * @return void
		 */
		public function form( $instance ) {
			$defaults = array(
				'placeholder' => __( 'Search', 'prismleaf' ),
				'flyout'      => false,
			);

			$instance    = wp_parse_args( (array) $instance, $defaults );
			$placeholder = sanitize_text_field( $instance['placeholder'] );
			$flyout      = (bool) wp_validate_boolean( $instance['flyout'] );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>">
					<?php esc_html_e( 'Placeholder text', 'prismleaf' ); ?>
				</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>" type="text" value="<?php echo esc_attr( $placeholder ); ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $flyout ); ?> id="<?php echo esc_attr( $this->get_field_id( 'flyout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flyout' ) ); ?>" value="1" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'flyout' ) ); ?>">
					<?php esc_html_e( 'Use flyout behavior', 'prismleaf' ); ?>
				</label>
			</p>
			<?php
		}
	}
}
