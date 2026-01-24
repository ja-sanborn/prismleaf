/**
 * Prismleaf Mobile Menu Switch
 *
 * Handles the hamburger toggle, focus trapping, and overlay display.
 *
 * @package
 */

(function () {
	'use strict';

	const toggle = document.querySelector(
		'[data-prismleaf-mobile-menu-toggle]'
	);
	const overlay = document.querySelector('[data-prismleaf-mobile-menu]');

	if (!toggle || !overlay) {
		return;
	}

	const mobileMenuPanel = overlay.querySelector('.prismleaf-menu-mobile');
	const mobileMenuLinks = Array.from(
		overlay.querySelectorAll(
			'.prismleaf-menu-mobile .prismleaf-menu-items > li > a'
		)
	);

	const focusableSelector =
		'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';
	let firstFocusable = null;
	let lastFocusable = null;
	let isOpen = false;

	const getFocusableElements = () => {
		const all = Array.from(
			overlay.querySelectorAll(focusableSelector)
		).filter(
			(element) =>
				element.offsetWidth > 0 ||
				element.offsetHeight > 0 ||
				element.getClientRects().length > 0
		);
		firstFocusable = all[0] || null;
		lastFocusable = all[all.length - 1] || null;
	};

	const trapFocus = (event) => {
		if ('Tab' !== event.key || !isOpen) {
			return;
		}

		if (!firstFocusable || !lastFocusable) {
			event.preventDefault();
			overlay.focus();
			return;
		}

		const doc = overlay.ownerDocument || document;
		const activeElement = doc.activeElement;

		if (event.shiftKey) {
			if (activeElement === firstFocusable || activeElement === overlay) {
				event.preventDefault();
				lastFocusable.focus();
			}
		} else if (activeElement === lastFocusable) {
			event.preventDefault();
			firstFocusable.focus();
		}
	};

	const handleKeydown = (event) => {
		if ('Escape' === event.key) {
			closeMenu();
			return;
		}

		trapFocus(event);
	};

	const handleOutsideClick = (event) => {
		if (toggle.contains(event.target)) {
			return;
		}

		const isInsidePanel =
			mobileMenuPanel && mobileMenuPanel.contains(event.target);

		if (!isInsidePanel) {
			closeMenu();
			return;
		}

		const clickedMenuLink = mobileMenuLinks.some((link) =>
			link.contains(event.target)
		);

		if (!clickedMenuLink) {
			closeMenu();
		}
	};

	const openMenu = () => {
		if (isOpen) {
			return;
		}

		isOpen = true;
		overlay.classList.add('is-open');
		toggle.classList.add('is-open');
		toggle.setAttribute('aria-expanded', 'true');
		overlay.setAttribute('aria-hidden', 'false');
		overlay.focus();
		getFocusableElements();
		document.addEventListener('click', handleOutsideClick);
		document.addEventListener('keydown', handleKeydown);
	};

	const closeMenu = () => {
		if (!isOpen) {
			return;
		}

		isOpen = false;
		overlay.classList.remove('is-open');
		toggle.classList.remove('is-open');
		toggle.setAttribute('aria-expanded', 'false');
		overlay.setAttribute('aria-hidden', 'true');
		document.removeEventListener('click', handleOutsideClick);
		document.removeEventListener('keydown', handleKeydown);
		toggle.focus();
	};

	toggle.addEventListener('click', (event) => {
		event.preventDefault();
		if (isOpen) {
			closeMenu();
		} else {
			openMenu();
		}
	});
})();
