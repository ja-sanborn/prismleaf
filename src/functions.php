<?php
/**
 * Prismleaf functions and definitions.
 *
 * This file is intentionally a loader/registrar. Feature logic lives in /inc.
 *
 * @package prismleaf
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core includes.
 */
require_once __DIR__ . '/inc/core/constants.php';
require_once PRISMLEAF_DIR . 'inc/utils/misc.php';
require_once PRISMLEAF_DIR . 'inc/utils/color.php';
require_once PRISMLEAF_DIR . 'inc/customizer/helpers/customizer.php';
require_once PRISMLEAF_DIR . 'inc/styles/css-variables.php';
require_once PRISMLEAF_DIR . 'inc/core/setup.php';
require_once PRISMLEAF_DIR . 'inc/core/assets.php';
require_once PRISMLEAF_DIR . 'inc/customizer/controls/section-header.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/palette-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/layout-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/styling-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/branding-options.php';
require_once PRISMLEAF_DIR . 'inc/components/prismleaf-search-component.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/prismleaf-search-options.php';
require_once PRISMLEAF_DIR . 'inc/widgets/class-prismleaf-search-widget.php';
