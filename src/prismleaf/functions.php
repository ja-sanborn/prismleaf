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
require_once PRISMLEAF_DIR . 'inc/core/defaults.php';
require_once PRISMLEAF_DIR . 'inc/utils/data-helpers.php';
require_once PRISMLEAF_DIR . 'inc/utils/sanitizers.php';
require_once PRISMLEAF_DIR . 'inc/utils/color-helpers.php';
require_once PRISMLEAF_DIR . 'inc/utils/theme-mods.php';
require_once PRISMLEAF_DIR . 'inc/utils/apply-styles.php';
require_once PRISMLEAF_DIR . 'inc/customizer/controls/class-prismleaf-customize-section-header-control.php';
require_once PRISMLEAF_DIR . 'inc/customizer/controls/class-prismleaf-customize-palette-preview-control.php';
require_once PRISMLEAF_DIR . 'inc/customizer/controls/class-prismleaf-customize-palette-source-control.php';
require_once PRISMLEAF_DIR . 'inc/customizer/controls/class-prismleaf-customize-background-image-control.php';
require_once PRISMLEAF_DIR . 'inc/customizer/helpers/labels.php';
require_once PRISMLEAF_DIR . 'inc/customizer/helpers/customizer.php';
require_once PRISMLEAF_DIR . 'inc/customizer/helpers/active-callbacks.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/palette-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/global-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/header-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/footer-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/widget-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/sidebar-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/content-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/menu-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/search-options.php';
require_once PRISMLEAF_DIR . 'inc/customizer/sections/comment-options.php';
require_once PRISMLEAF_DIR . 'inc/core/assets.php';
require_once PRISMLEAF_DIR . 'inc/core/setup.php';
