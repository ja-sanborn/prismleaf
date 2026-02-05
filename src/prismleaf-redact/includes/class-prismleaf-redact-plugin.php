<?php
/**
 * Core plugin bootstrap.
 *
 * @package prismleaf-redact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once PRISMLEAF_REDACT_PATH . 'includes/class-prismleaf-redact-dictionary.php';
require_once PRISMLEAF_REDACT_PATH . 'includes/class-prismleaf-redact-redactor.php';
require_once PRISMLEAF_REDACT_PATH . 'includes/class-prismleaf-redact-admin.php';

/**
 * Main plugin controller.
 */
class Prismleaf_Redact_Plugin {
	/**
	 * Singleton instance.
	 *
	 * @var Prismleaf_Redact_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Dictionary manager.
	 *
	 * @var Prismleaf_Redact_Dictionary
	 */
	private $dictionary;

	/**
	 * Redactor.
	 *
	 * @var Prismleaf_Redact_Redactor
	 */
	private $redactor;

	/**
	 * Get the plugin instance.
	 *
	 * @return Prismleaf_Redact_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin activation.
	 *
	 * @param bool $network_wide Network activation flag.
	 * @return void
	 */
	public static function activate( $network_wide ) {
		$dictionary = new Prismleaf_Redact_Dictionary();

		if ( is_multisite() && $network_wide ) {
			$site_ids = get_sites( array( 'fields' => 'ids' ) );
			foreach ( $site_ids as $site_id ) {
				switch_to_blog( (int) $site_id );
				$dictionary->create_table();
				self::ensure_default_settings();
				restore_current_blog();
			}

			return;
		}

		$dictionary->create_table();
		self::ensure_default_settings();
	}

	/**
	 * Ensure default settings are stored.
	 *
	 * @return void
	 */
	private static function ensure_default_settings() {
		$defaults = self::get_default_settings();
		$current  = get_option( 'prismleaf_redact_settings', array() );

		if ( empty( $current ) || ! is_array( $current ) ) {
			add_option( 'prismleaf_redact_settings', $defaults, '', false );
			return;
		}

		$merged = wp_parse_args( $current, $defaults );
		update_option( 'prismleaf_redact_settings', $merged, false );
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->dictionary = new Prismleaf_Redact_Dictionary();
		$this->redactor   = new Prismleaf_Redact_Redactor( $this->dictionary );

		add_action( 'init', array( $this, 'load_textdomain' ) );

		if ( is_admin() ) {
			new Prismleaf_Redact_Admin( $this, $this->dictionary );
		}

		add_filter( 'the_title', array( $this, 'filter_title' ), 10, 2 );
		add_filter( 'the_content', array( $this, 'filter_content' ), 20 );
		add_filter( 'the_excerpt', array( $this, 'filter_excerpt' ), 20 );

		add_filter( 'post_link', array( $this, 'filter_post_link' ), 10, 4 );
		add_filter( 'page_link', array( $this, 'filter_page_link' ), 10, 3 );
		add_filter( 'post_type_link', array( $this, 'filter_post_type_link' ), 10, 4 );
		add_filter( 'attachment_link', array( $this, 'filter_attachment_link' ), 10, 2 );

		add_filter( 'wp_get_canonical_url', array( $this, 'filter_canonical_url' ), 20, 2 );
		add_action( 'template_redirect', array( $this, 'maybe_redirect_original_permalink' ), 1 );
		add_filter( 'pre_handle_404', array( $this, 'maybe_resolve_redacted_permalink' ), 10, 2 );
		add_action( 'parse_request', array( $this, 'maybe_parse_redacted_request' ), 5 );
	}

	/**
	 * Load text domain.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'prismleaf-redact', false, dirname( PRISMLEAF_REDACT_BASENAME ) . '/languages' );
	}

	/**
	 * Get default settings.
	 *
	 * @return array
	 */
	public static function get_default_settings() {
		return array(
			'enabled'          => false,
			'redaction_char'   => '*',
			'redaction_length' => 3,
			'show_first'       => false,
			'show_last'        => false,
		);
	}

	/**
	 * Get settings with defaults.
	 *
	 * @return array
	 */
	public function get_settings() {
		$defaults = self::get_default_settings();
		$current  = get_option( 'prismleaf_redact_settings', array() );

		if ( ! is_array( $current ) ) {
			$current = array();
		}

		return wp_parse_args( $current, $defaults );
	}

	/**
	 * Sanitize settings input.
	 *
	 * @param array $input Raw input.
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$defaults = self::get_default_settings();
		$input    = is_array( $input ) ? $input : array();

		$char = isset( $input['redaction_char'] ) ? wp_check_invalid_utf8( $input['redaction_char'], true ) : $defaults['redaction_char'];
		$char = trim( (string) $char );
		if ( '' === $char || $this->mb_length( $char ) !== 1 ) {
			$char = $defaults['redaction_char'];
		} else {
			$char = $this->mb_substr( $char, 0, 1 );
		}

		$length = isset( $input['redaction_length'] ) ? (int) $input['redaction_length'] : $defaults['redaction_length'];
		$length = max( 1, min( 5, $length ) );

		return array(
			'enabled'          => ! empty( $input['enabled'] ),
			'redaction_char'   => $char,
			'redaction_length' => $length,
			'show_first'       => ! empty( $input['show_first'] ),
			'show_last'        => ! empty( $input['show_last'] ),
		);
	}

	/**
	 * Check if redaction is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		$settings = $this->get_settings();

		return ! empty( $settings['enabled'] );
	}

	/**
	 * Filter post titles.
	 *
	 * @param string $title   Title.
	 * @param int    $post_id Post ID.
	 * @return string
	 */
	public function filter_title( $title, $post_id ) {
		unset( $post_id );

		if ( $this->should_bypass() || ! $this->is_enabled() ) {
			return $title;
		}

		$settings = $this->get_settings();

		return $this->redactor->redact_plain_text( $title, $settings );
	}

	/**
	 * Filter post content.
	 *
	 * @param string $content Content.
	 * @return string
	 */
	public function filter_content( $content ) {
		if ( $this->should_bypass() || ! $this->is_enabled() ) {
			return $content;
		}

		$settings = $this->get_settings();

		return $this->redactor->redact_html( $content, $settings );
	}

	/**
	 * Filter post excerpts.
	 *
	 * @param string $excerpt Excerpt.
	 * @return string
	 */
	public function filter_excerpt( $excerpt ) {
		if ( $this->should_bypass() || ! $this->is_enabled() ) {
			return $excerpt;
		}

		$settings = $this->get_settings();

		return $this->redactor->redact_html( $excerpt, $settings );
	}

	/**
	 * Filter post permalinks.
	 *
	 * @param string  $permalink Permalink.
	 * @param WP_Post $post      Post object.
	 * @param bool    $leavename Leave name.
	 * @param bool    $sample    Sample flag.
	 * @return string
	 */
	public function filter_post_link( $permalink, $post, $leavename = false, $sample = false ) {
		unset( $leavename, $sample );

		return $this->filter_permalink_common( $permalink, $post );
	}

	/**
	 * Filter page permalinks.
	 *
	 * @param string $permalink Permalink.
	 * @param int    $post_id   Post ID.
	 * @param bool   $sample    Sample flag.
	 * @return string
	 */
	public function filter_page_link( $permalink, $post_id, $sample ) {
		unset( $sample );

		return $this->filter_permalink_common( $permalink, get_post( $post_id ) );
	}

	/**
	 * Filter post type permalinks.
	 *
	 * @param string  $permalink Permalink.
	 * @param WP_Post $post      Post object.
	 * @param bool    $leavename Leave name.
	 * @param bool    $sample    Sample flag.
	 * @return string
	 */
	public function filter_post_type_link( $permalink, $post, $leavename, $sample ) {
		unset( $leavename, $sample );

		return $this->filter_permalink_common( $permalink, $post );
	}

	/**
	 * Filter attachment links.
	 *
	 * @param string $permalink Permalink.
	 * @param int    $post_id   Post ID.
	 * @return string
	 */
	public function filter_attachment_link( $permalink, $post_id ) {
		return $this->filter_permalink_common( $permalink, get_post( $post_id ) );
	}

	/**
	 * Filter canonical URL.
	 *
	 * @param string  $canonical_url Canonical URL.
	 * @param WP_Post $post          Post object.
	 * @return string
	 */
	public function filter_canonical_url( $canonical_url, $post ) {
		if ( $this->should_bypass() || ! $this->is_enabled() || ! $post instanceof WP_Post ) {
			return $canonical_url;
		}

		if ( is_preview() ) {
			return $canonical_url;
		}

		$raw_permalink = $this->get_unfiltered_permalink( $post->ID );
		if ( ! $raw_permalink ) {
			return $canonical_url;
		}

		return $this->rewrite_permalink( $raw_permalink, $post->ID );
	}

	/**
	 * Redirect requests for original permalinks to rewritten versions.
	 *
	 * @return void
	 */
	public function maybe_redirect_original_permalink() {
		if ( $this->should_bypass() || ! $this->is_enabled() ) {
			return;
		}

		if ( is_preview() || is_feed() ) {
			return;
		}

		global $post, $wp;

		$request_uri   = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		$requested_url = '' !== $request_uri ? home_url( $request_uri ) : home_url( $wp->request );
		$requested_url = esc_url_raw( $requested_url );

		$post_id = 0;
		if ( is_singular() && $post instanceof WP_Post ) {
			$post_id = (int) $post->ID;
		} else {
			$post_id = (int) url_to_postid( $requested_url );
		}

		if ( $post_id <= 0 ) {
			return;
		}

		$original_permalink = $this->get_unfiltered_permalink( $post_id );
		if ( ! $original_permalink ) {
			return;
		}

		$rewritten_permalink = $this->rewrite_permalink( $original_permalink, $post_id );
		if ( $rewritten_permalink === $original_permalink ) {
			return;
		}

		$requested_path = wp_parse_url( $requested_url, PHP_URL_PATH );
		$original_path  = wp_parse_url( $original_permalink, PHP_URL_PATH );
		$rewritten_path = wp_parse_url( $rewritten_permalink, PHP_URL_PATH );

		$requested_path = $requested_path ? untrailingslashit( rawurldecode( $requested_path ) ) : '';
		$original_path  = $original_path ? untrailingslashit( rawurldecode( $original_path ) ) : '';
		$rewritten_path = $rewritten_path ? untrailingslashit( rawurldecode( $rewritten_path ) ) : '';

		if ( $requested_path === $original_path && $requested_path !== $rewritten_path ) {
			wp_safe_redirect( $rewritten_permalink, 301 );
			exit;
		}
	}

	/**
	 * Resolve redacted permalinks that would otherwise 404.
	 *
	 * @param bool     $preempt  Whether to short-circuit default handling.
	 * @param WP_Query $wp_query Query instance.
	 * @return bool
	 */
	public function maybe_resolve_redacted_permalink( $preempt, $wp_query ) {
		if ( $this->should_bypass() || ! $this->is_enabled() ) {
			return $preempt;
		}

		if ( ! $wp_query->is_404() ) {
			return $preempt;
		}

		$post_id = $this->resolve_post_id_from_request();
		if ( $post_id <= 0 ) {
			return $preempt;
		}

		$post = get_post( $post_id );
		if ( ! $post instanceof WP_Post ) {
			return $preempt;
		}

		$original_permalink  = $this->get_unfiltered_permalink( $post_id );
		$rewritten_permalink = $this->rewrite_permalink( $original_permalink, $post_id );

		$request_path = $this->get_request_path();
		$rewrite_path = wp_parse_url( $rewritten_permalink, PHP_URL_PATH );

		if ( $request_path !== $rewrite_path ) {
			return $preempt;
		}

		$wp_query->is_404           = false;
		$wp_query->is_singular      = true;
		$wp_query->queried_object   = $post;
		$wp_query->queried_object_id = $post_id;
		$wp_query->posts            = array( $post );
		$wp_query->post             = $post;
		$wp_query->found_posts      = 1;
		$wp_query->post_count       = 1;
		$wp_query->max_num_pages    = 1;
		$wp_query->set( 'p', $post_id );
		$wp_query->set( 'post_type', $post->post_type );

		status_header( 200 );

		return true;
	}

	/**
	 * Resolve a post ID from the redacted request path.
	 *
	 * @return int
	 */
	private function resolve_post_id_from_request() {
		$request_path = $this->get_request_path();
		if ( '' === $request_path ) {
			return 0;
		}

		$segments = explode( '/', trim( $request_path, '/' ) );
		if ( empty( $segments ) ) {
			return 0;
		}

		$slug = end( $segments );
		if ( '' === $slug ) {
			return 0;
		}

		$tokens = preg_split( '/[^\p{L}\p{N}]+/u', $slug, -1, PREG_SPLIT_NO_EMPTY );
		if ( empty( $tokens ) ) {
			return 0;
		}

		foreach ( $tokens as $token ) {
			if ( ! ctype_digit( $token ) ) {
				continue;
			}

			$post_id = (int) $token;
			if ( $post_id <= 0 ) {
				continue;
			}

			$post = get_post( $post_id );
			if ( ! $post instanceof WP_Post ) {
				continue;
			}

			return $post_id;
		}

		return 0;
	}

	/**
	 * Get the normalized request path for comparison.
	 *
	 * @return string
	 */
	private function get_request_path() {
		global $wp;

		$request_uri   = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		$requested_url = '' !== $request_uri ? home_url( $request_uri ) : home_url( $wp->request );
		$path          = wp_parse_url( $requested_url, PHP_URL_PATH );

		if ( ! $path ) {
			return '';
		}

		return untrailingslashit( rawurldecode( $path ) );
	}

	/**
	 * Parse redacted requests before WordPress resolves 404s.
	 *
	 * @param WP $wp WordPress environment.
	 * @return void
	 */
	public function maybe_parse_redacted_request( $wp ) {
		if ( $this->should_bypass() || ! $this->is_enabled() ) {
			return;
		}

		$post_id = $this->resolve_post_id_from_request();
		if ( $post_id <= 0 ) {
			return;
		}

		$post = get_post( $post_id );
		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$original_permalink  = $this->get_unfiltered_permalink( $post_id );
		$rewritten_permalink = $this->rewrite_permalink( $original_permalink, $post_id );

		$request_path = $this->get_request_path();
		$rewrite_path = wp_parse_url( $rewritten_permalink, PHP_URL_PATH );
		$rewrite_path = $rewrite_path ? untrailingslashit( rawurldecode( $rewrite_path ) ) : '';

		if ( $request_path !== $rewrite_path ) {
			return;
		}

		$wp->query_vars['p']         = $post_id;
		$wp->query_vars['post_type'] = $post->post_type;
		unset( $wp->query_vars['name'], $wp->query_vars['pagename'], $wp->query_vars['attachment'] );
		unset( $wp->query_vars['error'] );
	}

	/**
	 * Shared permalink filter logic.
	 *
	 * @param string       $permalink Permalink.
	 * @param WP_Post|null $post      Post object.
	 * @return string
	 */
	private function filter_permalink_common( $permalink, $post ) {
		if ( $this->should_bypass() || ! $this->is_enabled() || ! $post instanceof WP_Post ) {
			return $permalink;
		}

		return $this->rewrite_permalink( $permalink, $post->ID );
	}

	/**
	 * Rewrite permalink tokens based on dictionary.
	 *
	 * @param string $permalink Permalink.
	 * @param int    $post_id   Post ID.
	 * @return string
	 */
	private function rewrite_permalink( $permalink, $post_id ) {
		$terms = $this->dictionary->get_enabled_terms();
		if ( empty( $terms ) ) {
			return $permalink;
		}

		$parsed = wp_parse_url( $permalink );
		if ( empty( $parsed['path'] ) ) {
			return $permalink;
		}

		$path         = $parsed['path'];
		$has_trailing = '/' === substr( $path, -1 );
		$trimmed_path = trim( $path, '/' );
		$segments     = '' === $trimmed_path ? array() : explode( '/', $trimmed_path );
		$rewritten    = array();

		foreach ( $segments as $segment ) {
			$rewritten[] = $this->rewrite_slug_segment( $segment, $terms, $post_id );
		}

		$new_path = '/' . implode( '/', $rewritten );
		if ( $has_trailing ) {
			$new_path .= '/';
		}

		$rebuilt = $new_path;
		if ( isset( $parsed['query'] ) ) {
			$rebuilt .= '?' . $parsed['query'];
		}
		if ( isset( $parsed['fragment'] ) ) {
			$rebuilt .= '#' . $parsed['fragment'];
		}

		if ( isset( $parsed['scheme'], $parsed['host'] ) ) {
			$port    = isset( $parsed['port'] ) ? ':' . $parsed['port'] : '';
			$rebuilt = $parsed['scheme'] . '://' . $parsed['host'] . $port . $rebuilt;
		}

		return $rebuilt;
	}

	/**
	 * Rewrite a slug segment using dictionary terms.
	 *
	 * @param string $segment Segment.
	 * @param array  $terms   Dictionary terms.
	 * @param int    $post_id Post ID.
	 * @return string
	 */
	private function rewrite_slug_segment( $segment, array $terms, $post_id ) {
		$parts = preg_split( '/([^\p{L}\p{N}]+)/u', $segment, -1, PREG_SPLIT_DELIM_CAPTURE );
		if ( empty( $parts ) ) {
			return $segment;
		}

		foreach ( $parts as $index => $part ) {
			if ( 0 !== $index % 2 ) {
				continue;
			}

			if ( '' === $part ) {
				continue;
			}

			$term_key = $this->resolve_term_key( $part, $terms );
			if ( null === $term_key || ! isset( $terms[ $term_key ] ) ) {
				continue;
			}

			$replace = $terms[ $term_key ]['replace'];
			if ( null !== $replace && '' !== $replace ) {
				$replacement     = sanitize_title( $replace );
				$parts[ $index ] = '' !== $replacement ? $replacement : (string) $post_id;
				continue;
			}

			$parts[ $index ] = (string) $post_id;
		}

		return implode( '', $parts );
	}

	/**
	 * Resolve a slug token to a dictionary term key.
	 *
	 * @param string $part  Slug token.
	 * @param array  $terms Dictionary terms.
	 * @return string|null
	 */
	private function resolve_term_key( $part, array $terms ) {
		$term_key = $this->to_lowercase( $part );
		if ( isset( $terms[ $term_key ] ) ) {
			return $term_key;
		}

		// Handle possessive slugs where apostrophes are stripped (e.g., jason's -> jasons).
		if ( 's' === substr( $term_key, -1 ) && strlen( $term_key ) > 1 ) {
			$base = substr( $term_key, 0, -1 );
			if ( isset( $terms[ $base ] ) ) {
				return $base;
			}
		}

		return null;
	}

	/**
	 * Determine whether to bypass redaction logic.
	 *
	 * @return bool
	 */
	private function should_bypass() {
		if ( is_admin() ) {
			return true;
		}

		if ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) {
			return true;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the unfiltered permalink for a post.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	private function get_unfiltered_permalink( $post_id ) {
		$filters = array(
			'post_link'       => array( 'filter_post_link', 10, 4 ),
			'page_link'       => array( 'filter_page_link', 10, 3 ),
			'post_type_link'  => array( 'filter_post_type_link', 10, 4 ),
			'attachment_link' => array( 'filter_attachment_link', 10, 2 ),
		);

		foreach ( $filters as $hook => $callback ) {
			remove_filter( $hook, array( $this, $callback[0] ), $callback[1] );
		}

		$permalink = get_permalink( $post_id );

		foreach ( $filters as $hook => $callback ) {
			add_filter( $hook, array( $this, $callback[0] ), $callback[1], $callback[2] );
		}

		return $permalink;
	}

	/**
	 * Get string length with UTF-8 support.
	 *
	 * @param string $value Input string.
	 * @return int
	 */
	private function mb_length( $value ) {
		if ( function_exists( 'mb_strlen' ) ) {
			return (int) mb_strlen( $value, 'UTF-8' );
		}

		return (int) strlen( $value );
	}

	/**
	 * Substring with UTF-8 support.
	 *
	 * @param string $value  Input string.
	 * @param int    $start  Start position.
	 * @param int    $length Length.
	 * @return string
	 */
	private function mb_substr( $value, $start, $length ) {
		if ( function_exists( 'mb_substr' ) ) {
			return mb_substr( $value, $start, $length, 'UTF-8' );
		}

		return substr( $value, $start, $length );
	}

	/**
	 * Lowercase with UTF-8 support.
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
