# <img src="prismleaf-logo.png" alt="Prismleaf logo" width="64" height="64" style="vertical-align:middle;margin-right:0.5rem;" /> Prismleaf

Prismleaf ships two companion WordPress projects from a single repository: the Prismleaf Theme, which layers Material Design 3 tokens with classic Customizer controls, and the Prismleaf Redaction Plugin, which masks sensitive words at display time while keeping permalinks healthy.

---

## Table of Contents

- [Project Status](#project-status)
- [Prismleaf Theme](#prismleaf-theme)
  - [Architecture & assets](#architecture--assets)
  - [Styling & Customizer](#styling--customizer)
  - [Navigation & site icon](#navigation--site-icon)
- [Prismleaf Redaction Plugin](#prismleaf-redaction-plugin)
  - [Redactor & dictionary](#redactor--dictionary)
  - [Admin UI](#admin-ui)
  - [URL handling](#url-handling)
- [Getting started](#getting-started)
- [Development notes](#development-notes)
- [License](#license)
---

## Project Status

- **Theme readiness** – Version `1.0206.1345` (see `src/prismleaf/style.css`) declares support for feeds, title tag, thumbnails (plus custom `prismleaf-archive-card` and `prismleaf-featured-image` sizes), HTML5 markup, align-wide/responsive embeds/editor styles, custom logo/background, and three registered nav menus (`primary`, `secondary`, `mobile`). It registers footer, homepage, and sidebar widget areas via `src/prismleaf/inc/core/setup.php` and loads `src/prismleaf/languages/` for localization.
- **Customization surface** – `src/prismleaf/inc/customizer/sections/*` (palette, global, header, footer, widgets, sidebars, content, menu, search) pair with sanitizers in `src/prismleaf/inc/utils/sanitizers.php` and helper getters in `src/prismleaf/inc/utils/theme-mods.php`. `src/prismleaf/inc/utils/apply-styles.php` then turns those theme mods into CSS variables that land in `:root` after the final stylesheet, so palette-linked tokens and layout overrides flow through every asset file.
- **Plugin maturity** – Prismleaf Redaction (`src/prismleaf-redact/prismleaf-redact.php`) is version `1.0.0`. Activation creates the `wp_prismleaf_redact_terms` dictionary, stores default settings (redaction disabled, mask char `*`, length `3`, reveal-first/last toggles off), and loads translations from `src/prismleaf-redact/languages/`.

---
## Prismleaf Theme

### Architecture & assets

- `src/prismleaf/functions.php` bootstraps the theme by requiring `inc/core/constants.php`, defaults, utilities (`data-helpers`, `sanitizers`, `color-helpers`, `theme-mods`, `apply-styles`), Customizer controls/helpers, option sections, assets, and setup routines.
- `inc/core/assets.php` enqueues `style.css` plus modular sheets under `assets/styles/` (`constants`, `typography`, `colors`, `layout`, `frames`, `header`, `footer`, `sidebars`, `content`, `archive-results`, `author-bio`, `menus`, `pagination`, `search-box`, `widgets`, `mobile`, `accessibility`) and scripts under `assets/scripts/` (`theme-switch`, `mobile-menu`, `menu`, Customizer helpers).
- The theme uses template parts under `src/prismleaf/template-parts/` (header/footer, archive/pagination components, search, not-found, author bio, site title/icon, theme switch) to keep layout pieces focused and driven by `prismleaf_get_theme_mod_*` helpers.

### Styling & Customizer

- Palette controls and palette-source controls drive CSS tokens via `inc/utils/apply-styles.php`, which injects palette, framed layout, header/footer/sidebar/content/widget/pagination/author/menu variables into the final stylesheet handle. That keeps tokens centralized and easy to override from the Customizer.
- Menu styling is defined in `src/prismleaf/assets/styles/menus.css`. Custom properties such as `--prismleaf-menu-primary-background-color`, divider toggles, button corners, and indicator gaps make it easy to follow the Customizer options defined in `inc/customizer/sections/menu-options.php`.
- Theme mods for the header (position, icon size/shape, elevation, background, etc.) are sanitized inside `inc/utils/theme-mods.php`, so every template part (header row, site icon, navigation rows) reads clean, predictable values.

### Navigation & site icon

- `template-parts/header-content.php` renders the primary, secondary, and mobile `wp_nav_menu` slots with data attributes (`data-prismleaf-menu`, `data-prismleaf-menu-strip`, `data-prismleaf-menu-divider`, `data-prismleaf-menu-button-corners`) so CSS/JS can style each slot independently. The mobile toggle and overlay come with `aria` attributes and are wired up by `assets/scripts/mobile-menu.js`.
- The dropdown behavior lives in `assets/scripts/menu.js`. When you hover or focus a `.menu-item-has-children` inside the primary/secondary nav, the script clones the relevant `.sub-menu`, places it in a floating `.prismleaf-menu-flyout` outside the header overflow, and tags that flyout with the menu slot (primary/secondary) so the CSS can reuse the same palette tokens. Nested levels display via hover/focus inside the flyout, and the script keeps the popover hidden until the trigger is hovered or focused.
- `assets/styles/menus.css` now ensures arrows sit `var(--prismleaf-menu-indicator-gap)` away from the menu text, removes bullets from every submenu (desktop/mobile/flyout), lets nested flyouts branch left or right as needed, and gives the primary/secondary flyout clones the same palette-driven button styling as the parent nav.
- `template-parts/site-icon.php` respects the header icon size option by fetching the scaled attachment when possible (`wp_get_attachment_image_src`) before falling back to `get_site_icon_url`. The `<img>` also renders with the matching width/height so browsers lay it out at the intended diameter.

---

---
## Prismleaf Redaction Plugin

### Redactor & dictionary

- `src/prismleaf-redact/prismleaf-redact.php` bootstraps the singleton `Prismleaf_Redact_Plugin`, sets up constants, and runs activation hooks that create the `wp_prismleaf_redact_terms` table via `includes/class-prismleaf-redact-dictionary.php` and populate default settings.
- `Prismleaf_Redact_Redactor` (`includes/class-prismleaf-redact-redactor.php`) compiles cacheable regex patterns from enabled dictionary terms. It masks plain text or walks a `DOMDocument` representation of HTML to skip `script`, `style`, `code`, `pre`, `noscript`, and `textarea`, honoring the mask character, length, and optional first/last-character visibility.
- Dictionary entries are normalized (lowercased, stripped of punctuation) before being inserted with `INSERT ... ON DUPLICATE KEY UPDATE`, ensuring consistent lookups and clearing the per-site cache after each change.

### Admin UI

- `Prismleaf_Redact_Admin` (`includes/class-prismleaf-redact-admin.php`) adds a **Redactions** menu page that shows settings (enable toggle, mask char, length, reveal-first/last) and two forms: one to add new terms/replacements and another to edit/delete existing entries. Nonces protect every action, and settings errors provide feedback.

### URL handling

- The plugin hooks into `the_title`, `the_content`, `the_excerpt`, and the permalink filters (`post_link`, `page_link`, `post_type_link`, `attachment_link`) so masked terms replace or hide matched characters while stored content stays untouched.
- Permalink rewrites happen in `Prismleaf_Redact_Plugin::rewrite_permalink()`, which masks slug segments with either a replacement term or the original post ID, rebuilding the path without touching the database. `template_redirect`, `pre_handle_404`, and `parse_request` detect legacy slugs, redirect to their rewritten counterparts, or pretend the rewritten slug was requested so visitors never hit an unexpected 404.

---

## Getting started

1. **Install the theme:** copy `src/prismleaf` into `wp-content/themes/prismleaf`, activate it, and use the Customizer to adjust palettes, headers, menus, and layout tokens described earlier.
2. **Install the plugin:** copy `src/prismleaf-redact` into `wp-content/plugins/prismleaf-redact`, activate it, then open **Redactions** in the admin sidebar to enable masking, configure the mask character/length, and add dictionary terms.
3. **Configure navigation:** register menus for `primary`, `secondary`, and `mobile` via the WP menu screen, and the companion `assets/scripts/menu.js` + `assets/styles/menus.css` will automatically layer dropdown flyouts and mobile toggles that respect the Customizer options.

---

## Development notes

- `src/prismleaf/inc/utils/apply-styles.php` builds the CSS variable map that `style.css` and its dependent files consume. Add new CSS vars through that helper so palette overrides remain centralized.
- Always read theme mods through helper wrappers (`prismleaf_get_theme_mod_*`) to get sanitized, defaulted values, whether you are building header columns, buttons, or the site icon.
- Bundle navigation logic between `assets/scripts/menu.js` (desktop flyouts) and `assets/scripts/mobile-menu.js` (mobile overlay + focus trap) so dropdown behavior stays consistent even when CSS-driven overflow would otherwise clip the flyouts.
- `prismleaf-redact` caches enabled dictionary entries but clears the cache whenever you add, update, or delete terms to keep the redactor fast and accurate.

---

## License

Both the theme and the plugin ship under **GPL-2.0 or later**, matching WordPress.org requirements.
