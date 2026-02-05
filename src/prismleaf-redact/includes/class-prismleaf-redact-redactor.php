<?php
/**
 * Redaction logic for Prismleaf Redaction.
 *
 * @package prismleaf-redact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redaction processor.
 *
 * @since 1.0.0
 */
class Prismleaf_Redact_Redactor {
	/**
	 * Dictionary instance.
	 *
	 * @var Prismleaf_Redact_Dictionary
	 */
	private $dictionary;

	/**
	 * Cached regex pattern.
	 *
	 * @var string|null
	 */
	private $pattern = null;

	/**
	 * Cached term hash for the regex pattern.
	 *
	 * @var string|null
	 */
	private $pattern_hash = null;

	/**
	 * Tags to skip while redacting HTML.
	 *
	 * @var string[]
	 */
	private $skip_tags = array(
		'script',
		'style',
		'textarea',
		'code',
		'pre',
		'noscript',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param Prismleaf_Redact_Dictionary $dictionary Dictionary manager.
	 */
	public function __construct( Prismleaf_Redact_Dictionary $dictionary ) {
		$this->dictionary = $dictionary;
	}

	/**
	 * Redact plain text.
	 *
	 * @since 1.0.0
	 * @param string $text     Text to redact.
	 * @param array  $settings Redaction settings.
	 * @return string
	 */
	public function redact_plain_text( $text, array $settings ) {
		$terms = $this->dictionary->get_enabled_terms();
		if ( empty( $terms ) || '' === $text ) {
			return $text;
		}

		$pattern = $this->get_pattern( array_keys( $terms ) );
		if ( null === $pattern ) {
			return $text;
		}

		return preg_replace_callback(
			$pattern,
			function ( $matches ) use ( $terms, $settings ) {
				$matched  = $matches[1];
				$term_key = $this->to_lowercase( $matched );
				$entry    = isset( $terms[ $term_key ] ) ? $terms[ $term_key ] : null;

				if ( empty( $entry ) ) {
					return $matched;
				}

				$replace = $entry['replace'];
				if ( null !== $replace && '' !== $replace ) {
					return $replace;
				}

				return $this->build_masked_value( $matched, $settings );
			},
			$text
		);
	}

	/**
	 * Redact HTML while preserving tags and attributes.
	 *
	 * @since 1.0.0
	 * @param string $html     HTML string.
	 * @param array  $settings Redaction settings.
	 * @return string
	 */
	public function redact_html( $html, array $settings ) {
		$terms = $this->dictionary->get_enabled_terms();
		if ( empty( $terms ) || '' === $html ) {
			return $html;
		}

		if ( ! class_exists( 'DOMDocument' ) ) {
			return $this->redact_plain_text( $html, $settings );
		}

		$pattern = $this->get_pattern( array_keys( $terms ) );
		if ( null === $pattern ) {
			return $html;
		}

		$document = new DOMDocument( '1.0', 'UTF-8' );
		$previous = libxml_use_internal_errors( true );

		$wrapped = '<div>' . $html . '</div>';
		$document->loadHTML(
			'<?xml encoding="UTF-8">' . $wrapped,
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
		);

		$wrapper = $document->getElementsByTagName( 'div' )->item( 0 );
		if ( $wrapper ) {
			$this->walk_nodes( $wrapper, $pattern, $terms, $settings );
		}

		$output = '';
		if ( $wrapper ) {
			foreach ( $wrapper->childNodes as $child ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$output .= $document->saveHTML( $child );
			}
		}

		libxml_clear_errors();
		libxml_use_internal_errors( $previous );

		return $output;
	}

	/**
	 * Walk DOM nodes and redact text nodes.
	 *
	 * @since 1.0.0
	 * @param DOMNode $node     Node to process.
	 * @param string  $pattern  Regex pattern.
	 * @param array   $terms    Dictionary terms.
	 * @param array   $settings Settings.
	 * @return void
	 */
	private function walk_nodes( DOMNode $node, $pattern, array $terms, array $settings ) {
		if ( XML_TEXT_NODE === $node->nodeType ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$node->nodeValue = preg_replace_callback( // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$pattern,
				function ( $matches ) use ( $terms, $settings ) {
					$matched  = $matches[1];
					$term_key = $this->to_lowercase( $matched );
					$entry    = isset( $terms[ $term_key ] ) ? $terms[ $term_key ] : null;

					if ( empty( $entry ) ) {
						return $matched;
					}

					$replace = $entry['replace'];
					if ( null !== $replace && '' !== $replace ) {
						return $replace;
					}

					return $this->build_masked_value( $matched, $settings );
				},
				$node->nodeValue // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			);

			return;
		}

		if ( XML_ELEMENT_NODE === $node->nodeType ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( in_array( strtolower( $node->nodeName ), $this->skip_tags, true ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				return;
			}

			foreach ( $node->childNodes as $child ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$this->walk_nodes( $child, $pattern, $terms, $settings );
			}
		}
	}

	/**
	 * Build a redaction mask.
	 *
	 * @since 1.0.0
	 * @param string $matched  Matched word.
	 * @param array  $settings Redaction settings.
	 * @return string
	 */
	private function build_masked_value( $matched, array $settings ) {
		$char       = isset( $settings['redaction_char'] ) ? $settings['redaction_char'] : '*';
		$length     = isset( $settings['redaction_length'] ) ? (int) $settings['redaction_length'] : 3;
		$show_first = ! empty( $settings['show_first'] );
		$show_last  = ! empty( $settings['show_last'] );

		$mask        = str_repeat( $char, max( 1, $length ) );
		$word_length = $this->mb_length( $matched );

		$prefix = '';
		$suffix = '';

		if ( $show_first && $word_length > 0 ) {
			$prefix = $this->mb_substr( $matched, 0, 1 );
		}

		if ( $show_last && $word_length > 0 && ( $word_length > 1 || ! $show_first ) ) {
			$suffix = $this->mb_substr( $matched, $word_length - 1, 1 );
		}

		return $prefix . $mask . $suffix;
	}

	/**
	 * Build or reuse the regex pattern.
	 *
	 * @since 1.0.0
	 * @param array $terms Dictionary terms.
	 * @return string|null
	 */
	private function get_pattern( array $terms ) {
		if ( empty( $terms ) ) {
			return null;
		}

		$hash = md5( implode( '|', $terms ) );
		if ( $hash === $this->pattern_hash && null !== $this->pattern ) {
			return $this->pattern;
		}

		$escaped = array();
		foreach ( $terms as $term ) {
			$escaped[] = preg_quote( $term, '/' );
		}

		$pattern = '/(?<![\p{L}\p{N}])(' . implode( '|', $escaped ) . ')(?![\p{L}\p{N}])/iu';

		$this->pattern_hash = $hash;
		$this->pattern      = $pattern;

		return $pattern;
	}

	/**
	 * Lowercase with UTF-8 support.
	 *
	 * @since 1.0.0
	 * @param string $value Input string.
	 * @return string
	 */
	private function to_lowercase( $value ) {
		if ( function_exists( 'mb_strtolower' ) ) {
			return mb_strtolower( $value, 'UTF-8' );
		}

		return strtolower( $value );
	}

	/**
	 * Get string length with UTF-8 support.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
}
