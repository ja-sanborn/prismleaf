<?php
/**
 * Admin interface for Prismleaf Redaction.
 *
 * @package prismleaf-redact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin screen controller.
 *
 * @since 1.0.0
 */
class Prismleaf_Redact_Admin {
	/**
	 * Plugin instance.
	 *
	 * @var Prismleaf_Redact_Plugin
	 */
	private $plugin;

	/**
	 * Dictionary instance.
	 *
	 * @var Prismleaf_Redact_Dictionary
	 */
	private $dictionary;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Prismleaf_Redact_Plugin     $plugin     Plugin instance.
	 * @param Prismleaf_Redact_Dictionary $dictionary Dictionary manager.
	 */
	public function __construct( Prismleaf_Redact_Plugin $plugin, Prismleaf_Redact_Dictionary $dictionary ) {
		$this->plugin     = $plugin;
		$this->dictionary = $dictionary;

		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'handle_actions' ) );
	}

	/**
	 * Register the settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_menu() {
		add_menu_page(
			__( 'Redactions', 'prismleaf-redact' ),
			__( 'Redactions', 'prismleaf-redact' ),
			'manage_options',
			'prismleaf-redactions',
			array( $this, 'render_page' ),
			'dashicons-hidden',
			65
		);
	}

	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'prismleaf_redact_settings_group',
			'prismleaf_redact_settings',
			array( $this->plugin, 'sanitize_settings' )
		);
	}

	/**
	 * Handle admin form submissions.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_actions() {
		if ( ! isset( $_POST['prismleaf_redact_action'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'prismleaf_redact_terms' );

		$action = sanitize_key( wp_unslash( $_POST['prismleaf_redact_action'] ) );
		switch ( $action ) {
			case 'add_term':
				$this->handle_add_term();
				break;
			case 'update_terms':
				$this->handle_update_terms();
				break;
		}
	}

	/**
	 * Render the admin page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = $this->plugin->get_settings();
		$entries  = $this->dictionary->get_all_entries();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Redactions', 'prismleaf-redact' ); ?></h1>
			<?php settings_errors( 'prismleaf_redact_notices' ); ?>

			<h2><?php echo esc_html__( 'Settings', 'prismleaf-redact' ); ?></h2>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'prismleaf_redact_settings_group' );
				?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php echo esc_html__( 'Enabled', 'prismleaf-redact' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="prismleaf_redact_settings[enabled]" value="1" <?php checked( ! empty( $settings['enabled'] ) ); ?> />
								<?php echo esc_html__( 'Enable redaction on the front-end', 'prismleaf-redact' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Redaction character', 'prismleaf-redact' ); ?></th>
						<td>
							<input type="text" name="prismleaf_redact_settings[redaction_char]" value="<?php echo esc_attr( $settings['redaction_char'] ); ?>" maxlength="4" class="regular-text" />
							<p class="description"><?php echo esc_html__( 'Single character used for masking.', 'prismleaf-redact' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Redaction length', 'prismleaf-redact' ); ?></th>
						<td>
							<input type="number" name="prismleaf_redact_settings[redaction_length]" value="<?php echo esc_attr( $settings['redaction_length'] ); ?>" min="1" max="5" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Show first character', 'prismleaf-redact' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="prismleaf_redact_settings[show_first]" value="1" <?php checked( ! empty( $settings['show_first'] ) ); ?> />
								<?php echo esc_html__( 'Reveal the first character of the matched word.', 'prismleaf-redact' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Show last character', 'prismleaf-redact' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="prismleaf_redact_settings[show_last]" value="1" <?php checked( ! empty( $settings['show_last'] ) ); ?> />
								<?php echo esc_html__( 'Reveal the last character of the matched word.', 'prismleaf-redact' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>

			<h2><?php echo esc_html__( 'Dictionary', 'prismleaf-redact' ); ?></h2>
			<form method="post">
				<?php wp_nonce_field( 'prismleaf_redact_terms' ); ?>
				<input type="hidden" name="prismleaf_redact_action" value="add_term" />
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php echo esc_html__( 'New term', 'prismleaf-redact' ); ?></th>
						<td>
							<input type="text" name="prismleaf_redact_term" value="" class="regular-text" required />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Replacement (optional)', 'prismleaf-redact' ); ?></th>
						<td>
							<input type="text" name="prismleaf_redact_replace" value="" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Enabled', 'prismleaf-redact' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="prismleaf_redact_enabled" value="1" checked="checked" />
								<?php echo esc_html__( 'Activate newly added terms.', 'prismleaf-redact' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Add Terms', 'prismleaf-redact' ) ); ?>
			</form>

			<form method="post">
				<?php wp_nonce_field( 'prismleaf_redact_terms' ); ?>
				<input type="hidden" name="prismleaf_redact_action" value="update_terms" />
				<table class="widefat striped" style="margin-top: 20px;">
					<thead>
						<tr>
							<th><?php echo esc_html__( 'Term', 'prismleaf-redact' ); ?></th>
							<th><?php echo esc_html__( 'Replacement', 'prismleaf-redact' ); ?></th>
							<th><?php echo esc_html__( 'Enabled', 'prismleaf-redact' ); ?></th>
							<th><?php echo esc_html__( 'Actions', 'prismleaf-redact' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( empty( $entries ) ) : ?>
							<tr>
								<td colspan="4"><?php echo esc_html__( 'No terms added yet.', 'prismleaf-redact' ); ?></td>
							</tr>
						<?php else : ?>
							<?php foreach ( $entries as $entry ) : ?>
								<tr>
									<td><?php echo esc_html( $entry->term ); ?></td>
									<td>
										<input type="text" name="replace[<?php echo esc_attr( $entry->id ); ?>]" value="<?php echo esc_attr( $entry->replace ); ?>" class="regular-text" />
									</td>
									<td>
										<input type="checkbox" name="enabled[<?php echo esc_attr( $entry->id ); ?>]" value="1" <?php checked( (int) $entry->enabled, 1 ); ?> />
									</td>
									<td>
										<?php
										submit_button(
											__( 'Delete', 'prismleaf-redact' ),
											'delete',
											'delete_id',
											false,
											array( 'value' => $entry->id )
										);
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
				<?php submit_button( __( 'Update Terms', 'prismleaf-redact' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle adding terms.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function handle_add_term() {
		check_admin_referer( 'prismleaf_redact_terms' );

		$raw_term = isset( $_POST['prismleaf_redact_term'] ) ? sanitize_text_field( wp_unslash( $_POST['prismleaf_redact_term'] ) ) : '';
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Stored verbatim (validated in dictionary).
		$replace = isset( $_POST['prismleaf_redact_replace'] ) ? wp_unslash( $_POST['prismleaf_redact_replace'] ) : '';
		$enabled = ! empty( $_POST['prismleaf_redact_enabled'] );

		$terms = $this->dictionary->normalize_terms( $raw_term );
		if ( empty( $terms ) ) {
			add_settings_error(
				'prismleaf_redact_notices',
				'prismleaf_redact_empty',
				__( 'Please enter a valid term.', 'prismleaf-redact' ),
				'error'
			);
			return;
		}

		$this->dictionary->upsert_terms( $terms, $replace, $enabled ? 1 : 0 );

		add_settings_error(
			'prismleaf_redact_notices',
			'prismleaf_redact_added',
			__( 'Terms updated.', 'prismleaf-redact' ),
			'updated'
		);
	}

	/**
	 * Handle updates.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function handle_update_terms() {
		check_admin_referer( 'prismleaf_redact_terms' );

		if ( isset( $_POST['delete_id'] ) ) {
			$entry_id = absint( $_POST['delete_id'] );
			if ( $entry_id > 0 ) {
				$this->dictionary->delete_entry( $entry_id );

				add_settings_error(
					'prismleaf_redact_notices',
					'prismleaf_redact_deleted',
					__( 'Term deleted.', 'prismleaf-redact' ),
					'updated'
				);
			}

			return;
		}

		$entries = $this->dictionary->get_all_entries();
		$updates = array();

		foreach ( $entries as $entry ) {
			$id = (int) $entry->id;
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Stored verbatim (validated in dictionary).
			$replace = isset( $_POST['replace'][ $id ] ) ? wp_unslash( $_POST['replace'][ $id ] ) : '';
			$enabled = isset( $_POST['enabled'][ $id ] ) ? 1 : 0;

			$updates[ $id ] = array(
				'replace' => $replace,
				'enabled' => $enabled,
			);
		}

		$this->dictionary->update_entries( $updates );

		add_settings_error(
			'prismleaf_redact_notices',
			'prismleaf_redact_saved',
			__( 'Dictionary updated.', 'prismleaf-redact' ),
			'updated'
		);
	}
}
