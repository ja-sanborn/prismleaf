<?php
/**
 * Dictionary management for Prismleaf Redaction.
 *
 * @package prismleaf-redact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dictionary storage handler.
 */
class Prismleaf_Redact_Dictionary {
	/**
	 * Cached enabled terms per site.
	 *
	 * @var array
	 */
	private static $enabled_cache = array();

	/**
	 * Get the dictionary table name.
	 *
	 * @return string
	 */
	public function get_table_name() {
		global $wpdb;

		return $wpdb->prefix . 'prismleaf_redact_terms';
	}

	/**
	 * Create the dictionary table.
	 *
	 * @return void
	 */
	public function create_table() {
		global $wpdb;

		$table_name      = $this->get_table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL auto_increment,
			term varchar(191) NOT NULL,
			`replace` text NULL,
			enabled tinyint(1) NOT NULL DEFAULT 1,
			PRIMARY KEY  (id),
			UNIQUE KEY term (term)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Normalize a raw term string into tokens.
	 *
	 * @param string $raw_term Raw input.
	 * @return string[]
	 */
	public function normalize_terms( $raw_term ) {
		$raw_term = wp_check_invalid_utf8( $raw_term, true );
		$raw_term = trim( (string) $raw_term );

		if ( '' === $raw_term ) {
			return array();
		}

		$lower = $this->to_lowercase( $raw_term );
		$parts = preg_split( '/[^\p{L}\p{N}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY );

		if ( empty( $parts ) ) {
			return array();
		}

		return array_values( array_unique( $parts ) );
	}

	/**
	 * Get enabled terms keyed by lowercase term.
	 *
	 * @return array
	 */
	public function get_enabled_terms() {
		$blog_id = get_current_blog_id();

		if ( isset( self::$enabled_cache[ $blog_id ] ) ) {
			return self::$enabled_cache[ $blog_id ];
		}

		global $wpdb;

		$table_name = $this->get_table_name();
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Dictionary data is request-scoped and table name is safe.
		$rows  = $wpdb->get_results( $wpdb->prepare( "SELECT term, `replace` FROM {$table_name} WHERE enabled = %d", 1 ) );
		$terms = array();

		foreach ( $rows as $row ) {
			$key           = $this->to_lowercase( $row->term );
			$terms[ $key ] = array(
				'term'    => $row->term,
				'replace' => $row->replace,
			);
		}

		self::$enabled_cache[ $blog_id ] = $terms;

		return $terms;
	}

	/**
	 * Get all dictionary entries.
	 *
	 * @return array
	 */
	public function get_all_entries() {
		global $wpdb;

		$table_name = $this->get_table_name();
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Dictionary data is request-scoped and table name is safe.
		return $wpdb->get_results( "SELECT id, term, `replace`, enabled FROM {$table_name} ORDER BY term ASC" );
	}

	/**
	 * Insert or update terms with a shared replacement and enabled state.
	 *
	 * @param string[] $terms   Normalized terms.
	 * @param string   $replace Replacement string.
	 * @param int      $enabled Enabled flag.
	 * @return int Number of rows affected.
	 */
	public function upsert_terms( array $terms, $replace, $enabled ) {
		global $wpdb;

		if ( empty( $terms ) ) {
			return 0;
		}

		$table_name = $this->get_table_name();
		$replace    = wp_check_invalid_utf8( $replace, true );
		$enabled    = $enabled ? 1 : 0;
		$affected   = 0;

		foreach ( $terms as $term ) {
			$term = $this->to_lowercase( $term );

			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safe.
			$affected += (int) $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Dictionary updates are explicit.
				$wpdb->prepare(
					"INSERT INTO {$table_name} (term, `replace`, enabled)
					VALUES (%s, %s, %d)
					ON DUPLICATE KEY UPDATE `replace` = VALUES(`replace`), enabled = VALUES(enabled)",
					$term,
					$replace,
					$enabled
				)
			);
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		$this->clear_cache();

		return $affected;
	}

	/**
	 * Update existing dictionary entries by ID.
	 *
	 * @param array $updates Updates keyed by ID.
	 * @return int Number of rows updated.
	 */
	public function update_entries( array $updates ) {
		global $wpdb;

		if ( empty( $updates ) ) {
			return 0;
		}

		$table_name = $this->get_table_name();
		$updated    = 0;

		foreach ( $updates as $id => $data ) {
			$id      = (int) $id;
			$replace = isset( $data['replace'] ) ? wp_check_invalid_utf8( $data['replace'], true ) : '';
			$enabled = ! empty( $data['enabled'] ) ? 1 : 0;

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Dictionary updates are explicit.
			$updated += $wpdb->update(
				$table_name,
				array(
					'replace' => $replace,
					'enabled' => $enabled,
				),
				array( 'id' => $id ),
				array( '%s', '%d' ),
				array( '%d' )
			);
		}

		$this->clear_cache();

		return $updated;
	}

	/**
	 * Delete a dictionary entry by ID.
	 *
	 * @param int $id Entry ID.
	 * @return int Rows deleted.
	 */
	public function delete_entry( $id ) {
		global $wpdb;

		$id = (int) $id;
		if ( $id <= 0 ) {
			return 0;
		}

		$table_name = $this->get_table_name();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Dictionary deletions are explicit.
		$deleted = $wpdb->delete( $table_name, array( 'id' => $id ), array( '%d' ) );

		$this->clear_cache();

		return (int) $deleted;
	}

	/**
	 * Clear in-memory caches.
	 *
	 * @return void
	 */
	public function clear_cache() {
		$blog_id = get_current_blog_id();
		unset( self::$enabled_cache[ $blog_id ] );
	}

	/**
	 * Convert to lowercase with UTF-8 support when available.
	 *
	 * @param string $value Input string.
	 * @return string
	 */
	private function to_lowercase( $value ) {
		if ( function_exists( 'mb_strtolower' ) ) {
			return mb_strtolower( $value, 'UTF-8' );
		}

		return strtolower( $value );
	}
}
