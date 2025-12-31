# Prismleaf

Prismleaf is a modern **classic WordPress theme** focused on clarity, accessibility, and long-term maintainability. It is designed for developers and site builders who value clean architecture, deliberate UX decisions, and WordPress core conventions—without reliance on the block editor.

Prismleaf emphasizes a clear separation between **content**, **presentation**, and **interaction**, making it suitable for sites that need to grow and adapt without accumulating technical or conceptual debt.

---

## Core Principles

- **Classic-first architecture**  
  Built using traditional WordPress templates, template parts, widgets, and the Customizer.

- **Separation of concerns**  
  WordPress core manages *content and values*; Prismleaf controls *presentation and behavior*.

- **Accessibility as a baseline**  
  Keyboard navigation, visible focus indicators, semantic markup, and ARIA where appropriate are built in from the start.

- **Progressive enhancement**  
  Features work without JavaScript and are enhanced with small, focused scripts when available.

- **Design-system driven**  
  Reusable components, semantic color roles, and consistent interaction patterns across the theme.

---

## Theme Structure

Prismleaf follows a clear and predictable structure:

- `functions.php`  
  Theme bootstrap and includes.

- `/inc/`  
  Core PHP architecture:
  - **core** – theme setup, constants, and asset loading  
  - **components** – reusable render-only components  
  - **widgets** – classic multi-instance widgets  
  - **customizer** – presentation-focused Customizer sections and helpers  

- `/template-parts/`  
  Composable layout and component views.

- `/assets/`  
  Front-end assets:
  - `styles/` – global and component-scoped CSS  
  - `scripts/` – small vanilla JavaScript modules  
  - `images/` – SVG icons and theme imagery  

This structure supports extensibility without coupling layout, logic, and styling.

---

## Customizer Philosophy

Prismleaf intentionally avoids duplicating WordPress core responsibilities.

- **WordPress core** defines values such as:
  - Site Title
  - Tagline
  - Site Icon

- **Prismleaf Customizer sections** control:
  - Visibility
  - Layout
  - Styling
  - Interaction behavior

This approach reduces confusion while giving precise control over how core information is displayed.

---

## Components

Prismleaf components are built to be:

- Reusable
- Accessible
- Context-independent
- Single-responsibility

### Example: Prismleaf Search

The Prismleaf Search control is implemented once and reused across the theme:

- As a template part (Customizer-controlled)
- As a classic widget (instance-controlled)

Features include:
- Optional flyout behavior
- Keyboard and screen reader support
- RTL-aware layout
- Designed focus indicators
- SVG icon masking using theme color roles

---

## Styling & Design System

- Semantic color roles (primary, secondary, tertiary, status colors)
- Support for light and dark themes via CSS custom properties
- Component-scoped styles that remain overridable
- Focus indicators that are intentional, visible, and WCAG-compliant

---

## JavaScript Approach

- Vanilla JavaScript only
- No framework or jQuery dependency
- Small, targeted scripts per feature
- Safe for multiple instances per page
- Enhances behavior without breaking baseline functionality

---

## Accessibility

Accessibility is treated as a foundational requirement:

- Proper labeling of form controls
- Icon-only buttons with accessible names
- Keyboard navigation fully supported
- Clear focus states
- ARIA used only where semantic HTML is insufficient

---

## Intended Audience

Prismleaf is suited for:

- Developers who prefer classic WordPress architecture
- Designers who care about consistency and clarity
- Sites that value longevity over trends
- Projects that need control without unnecessary complexity

---

## License

GPL-2.0 or later, in accordance with WordPress theme guidelines.
