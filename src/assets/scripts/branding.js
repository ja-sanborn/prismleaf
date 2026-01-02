(function () {
	'use strict';

	const CLASS_FORCE_BELOW = 'prismleaf-branding-tagline-force-below';
	const CLASS_OVERFLOW_HIDDEN = 'prismleaf-branding-overflow-hidden';
	const CLASS_TAGLINE_INLINE = 'prismleaf-branding-tagline-inline';
	const CLASS_TAGLINE_BELOW = 'prismleaf-branding-tagline-below';

	function hasOverflow(element) {
		if (!element) {
			return false;
		}

		return element.scrollWidth > element.clientWidth;
	}

	function updateBranding(branding) {
		const text = branding.querySelector('.prismleaf-branding-text');
		const tagline = branding.querySelector('.prismleaf-site-description');

		if (!text || !tagline) {
			return;
		}

		branding.classList.remove(CLASS_FORCE_BELOW);
		branding.classList.remove(CLASS_OVERFLOW_HIDDEN);

		if (
			branding.classList.contains(CLASS_TAGLINE_INLINE) &&
			hasOverflow(text)
		) {
			branding.classList.add(CLASS_FORCE_BELOW);
		}

		if (
			branding.classList.contains(CLASS_TAGLINE_BELOW) ||
			branding.classList.contains(CLASS_FORCE_BELOW)
		) {
			if (hasOverflow(text)) {
				branding.classList.add(CLASS_OVERFLOW_HIDDEN);
			}
		}
	}

	function updateAllBranding() {
		const brandingBlocks = document.querySelectorAll('.prismleaf-branding');

		brandingBlocks.forEach(function (branding) {
			updateBranding(branding);
		});
	}

	function debounce(fn, delay) {
		let timeoutId = null;

		return function () {
			if (timeoutId) {
				window.clearTimeout(timeoutId);
			}

			timeoutId = window.setTimeout(fn, delay);
		};
	}

	document.addEventListener('DOMContentLoaded', updateAllBranding);
	window.addEventListener('load', updateAllBranding);
	window.addEventListener('resize', debounce(updateAllBranding, 150));
})();
