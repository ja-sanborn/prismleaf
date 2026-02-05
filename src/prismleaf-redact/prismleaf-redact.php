<?php
/**
 * Plugin Name: Prismleaf Redaction
 * Plugin URI: https://github.com/ja-sanborn/prismleaf
 * Description: Redacts or replaces sensitive terms at display time without modifying stored content.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: JA Sanborn
 * Author URI: https://github.com/ja-sanborn/
 * License: GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: prismleaf-redact
 *
 * @package prismleaf-redact
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PRISMLEAF_REDACT_VERSION', '1.0.0' );
define( 'PRISMLEAF_REDACT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PRISMLEAF_REDACT_URL', plugin_dir_url( __FILE__ ) );
define( 'PRISMLEAF_REDACT_BASENAME', plugin_basename( __FILE__ ) );

require_once PRISMLEAF_REDACT_PATH . 'includes/class-prismleaf-redact-plugin.php';

Prismleaf_Redact_Plugin::instance();

register_activation_hook( __FILE__, array( 'Prismleaf_Redact_Plugin', 'activate' ) );
