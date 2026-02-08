/**
 * Prismleaf Menu Enhancements
 *
 * Handles dropdown flyouts and mobile submenu toggles so nested menu
 * items display outside the overflow-constrained header.
 *
 * @package
 */

(function () {
	'use strict';

	const body = document.body;
	if (!body) {
		return;
	}

	const flyout = document.createElement('div');
	flyout.className = 'prismleaf-menu-flyout';
	flyout.setAttribute('role', 'menu');
	flyout.setAttribute('aria-hidden', 'true');
	body.appendChild(flyout);

	let activeItem = null;
	let hideTimer = null;

	const clearHideTimer = () => {
		if (hideTimer) {
			window.clearTimeout(hideTimer);
			hideTimer = null;
		}
	};

	const clearFlyoutOpenState = () => {
		const openItems = flyout.querySelectorAll(
			'.menu-item-has-children.is-open'
		);
		openItems.forEach((item) => {
			item.classList.remove('is-open');
			item.style.removeProperty('z-index');
			const submenu = item.querySelector(':scope > .sub-menu');
			if (submenu) {
				submenu.style.removeProperty('z-index');
			}
		});
	};

	const setFlyoutOpenTrail = (item) => {
		if (!item || !flyout.contains(item)) {
			return;
		}

		clearFlyoutOpenState();

		let current = item;
		const chain = [];
		while (current && flyout.contains(current)) {
			if (current.classList.contains('menu-item-has-children')) {
				chain.push(current);
			}
			current = current.parentElement
				? current.parentElement.closest('.menu-item-has-children')
				: null;
		}

		chain.reverse().forEach((node, depth) => {
			const zIndex = 100 + depth;
			node.classList.add('is-open');
			node.style.zIndex = `${zIndex}`;
			const submenu = node.querySelector(':scope > .sub-menu');
			if (submenu) {
				submenu.style.zIndex = `${zIndex + 1}`;
			}
		});
	};

	const hideFlyout = () => {
		clearHideTimer();
		activeItem = null;
		clearFlyoutOpenState();
		flyout.classList.remove('is-visible');
		setFlyoutMenuType('');
		flyout.setAttribute('aria-hidden', 'true');
		flyout.style.display = 'none';
		flyout.innerHTML = '';
		flyout.removeAttribute('data-prismleaf-flyout-direction');
	};

	const scheduleHide = () => {
		clearHideTimer();
		hideTimer = window.setTimeout(hideFlyout, 750);
	};

	const shouldKeepOpenOnLeave = (event, source) => {
		if (!event || !source) {
			return false;
		}

		const nextTarget = event.relatedTarget;
		if (!nextTarget) {
			return false;
		}

		return (
			source.contains(nextTarget) ||
			flyout.contains(nextTarget) ||
			(activeItem && activeItem.contains(nextTarget))
		);
	};

	const positionFlyout = (trigger) => {
		const rect = trigger.getBoundingClientRect();
		flyout.style.display = 'block';
		flyout.style.visibility = 'hidden';
		const flyoutWidth = flyout.offsetWidth;
		const viewportWidth = document.documentElement.clientWidth;
		let left = rect.left;
		if (left + flyoutWidth > viewportWidth - 12) {
			left = Math.max(12, viewportWidth - flyoutWidth - 12);
		}
		const availableLeft = left;
		const availableRight = viewportWidth - (left + flyoutWidth);
		flyout.dataset.prismleafFlyoutDirection =
			availableRight >= availableLeft ? 'right' : 'left';
		const scrollX =
			window.pageXOffset || document.documentElement.scrollLeft;
		const scrollY =
			window.pageYOffset || document.documentElement.scrollTop;
		flyout.style.left = `${left + scrollX}px`;
		flyout.style.top = `${rect.bottom + scrollY}px`;
		flyout.style.visibility = 'visible';
	};

	const setFlyoutMenuType = (menuType) => {
		if (menuType) {
			flyout.dataset.prismleafMenu = menuType;
		} else {
			flyout.removeAttribute('data-prismleaf-menu');
		}
	};

	const showFlyout = (li) => {
		const trigger = li.querySelector(':scope > a, :scope > button');
		if (!trigger) {
			hideFlyout();
			return;
		}

		const submenu = li.querySelector(':scope > .sub-menu');
		if (!submenu) {
			hideFlyout();
			return;
		}

		const nav = li.closest('nav[data-prismleaf-menu]');
		setFlyoutMenuType(nav ? nav.dataset.prismleafMenu : '');
		flyout.setAttribute('aria-hidden', 'false');

		if (activeItem === li) {
			return;
		}

		activeItem = li;
		flyout.innerHTML = '';
		const clone = submenu.cloneNode(true);
		flyout.appendChild(clone);
		flyout.classList.add('is-visible');
		positionFlyout(trigger);
	};

	const menuSelectors = '.prismleaf-menu-primary, .prismleaf-menu-secondary';
	const navMenus = Array.from(document.querySelectorAll(menuSelectors));

	navMenus.forEach((nav) => {
		if (!nav) {
			return;
		}

		const parentItems = Array.from(
			nav.querySelectorAll('.menu-item-has-children')
		);

		parentItems.forEach((li) => {
			li.addEventListener('pointerenter', () => {
				clearHideTimer();
				showFlyout(li);
			});

			li.addEventListener('focusin', () => {
				clearHideTimer();
				showFlyout(li);
			});

			li.addEventListener('pointerleave', (event) => {
				if (shouldKeepOpenOnLeave(event, li)) {
					return;
				}
				scheduleHide();
			});

			li.addEventListener('focusout', (event) => {
				if (!li.contains(event.relatedTarget)) {
					scheduleHide();
				}
			});
		});

		nav.addEventListener('pointerleave', (event) => {
			if (shouldKeepOpenOnLeave(event, nav)) {
				return;
			}
			scheduleHide();
		});
		nav.addEventListener('pointerenter', clearHideTimer);
	});

	flyout.addEventListener('pointerenter', clearHideTimer);
	flyout.addEventListener('pointerover', (event) => {
		clearHideTimer();
		const listItem = event.target.closest('li');
		if (!listItem || !flyout.contains(listItem)) {
			return;
		}
		let branchItem = null;
		if (listItem.classList.contains('menu-item-has-children')) {
			branchItem = listItem;
		} else if (listItem.parentElement) {
			branchItem = listItem.parentElement.closest(
				'.menu-item-has-children'
			);
		}
		if (branchItem) {
			setFlyoutOpenTrail(branchItem);
		} else {
			clearFlyoutOpenState();
		}
	});
	flyout.addEventListener('focusin', (event) => {
		clearHideTimer();
		const listItem = event.target.closest('li');
		if (!listItem || !flyout.contains(listItem)) {
			return;
		}
		let branchItem = null;
		if (listItem.classList.contains('menu-item-has-children')) {
			branchItem = listItem;
		} else if (listItem.parentElement) {
			branchItem = listItem.parentElement.closest(
				'.menu-item-has-children'
			);
		}
		if (branchItem) {
			setFlyoutOpenTrail(branchItem);
		} else {
			clearFlyoutOpenState();
		}
	});
	flyout.addEventListener('pointerleave', (event) => {
		if (shouldKeepOpenOnLeave(event, flyout)) {
			return;
		}
		scheduleHide();
	});

	document.addEventListener('click', (event) => {
		if (
			activeItem &&
			!activeItem.contains(event.target) &&
			!flyout.contains(event.target)
		) {
			hideFlyout();
		}
	});

	window.addEventListener('scroll', hideFlyout);
	window.addEventListener('resize', hideFlyout);
})();
