(function () {
	'use strict';

	const toggle = document.querySelector(
		'[data-prismleaf-mobile-menu-toggle]'
	);
	const overlay = document.querySelector(
		'[data-prismleaf-mobile-menu-overlay]'
	);
	const dialog = document.querySelector(
		'[data-prismleaf-mobile-menu-dialog]'
	);

	if (!toggle || !overlay || !dialog) {
		return;
	}

	const doc = dialog.ownerDocument;
	let isOpen = false;
	let lastFocused = null;

	function getFocusableElements() {
		return Array.prototype.slice
			.call(
				dialog.querySelectorAll(
					'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
				)
			)
			.filter(function (element) {
				return element.offsetParent !== null;
			});
	}

	function setOpenState(open) {
		isOpen = open;
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (toggle.dataset) {
			const label = open
				? toggle.dataset.labelClose
				: toggle.dataset.labelOpen;
			if (label) {
				toggle.setAttribute('aria-label', label);
			}
		}
		overlay.hidden = !open;
		overlay.setAttribute('aria-hidden', open ? 'false' : 'true');

		if (open) {
			doc.body.classList.add('prismleaf-mobile-menu-open');
			dialog.focus();
		} else {
			doc.body.classList.remove('prismleaf-mobile-menu-open');
			if (lastFocused && typeof lastFocused.focus === 'function') {
				lastFocused.focus();
			}
		}
	}

	function openMenu() {
		if (isOpen) {
			return;
		}

		lastFocused = doc.activeElement;
		setOpenState(true);

		const focusables = getFocusableElements();
		if (focusables.length > 0) {
			focusables[0].focus();
		}
	}

	function closeMenu() {
		if (!isOpen) {
			return;
		}

		setOpenState(false);
	}

	toggle.addEventListener('click', function () {
		if (isOpen) {
			closeMenu();
		} else {
			openMenu();
		}
	});

	overlay.addEventListener('click', function (event) {
		if (event.target === overlay) {
			closeMenu();
		}
	});

	dialog.addEventListener('click', function (event) {
		const link = event.target.closest('a');
		if (link) {
			closeMenu();
		}
	});

	doc.addEventListener('keydown', function (event) {
		if (!isOpen) {
			return;
		}

		if (event.key === 'Escape') {
			event.preventDefault();
			closeMenu();
			return;
		}

		if (event.key !== 'Tab') {
			return;
		}

		const focusables = getFocusableElements();
		if (focusables.length === 0) {
			event.preventDefault();
			return;
		}

		const first = focusables[0];
		const last = focusables[focusables.length - 1];

		if (event.shiftKey) {
			if (doc.activeElement === first) {
				event.preventDefault();
				last.focus();
			}
		} else if (doc.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	});
})();
