(function () {
	const doc = document.documentElement;
	const storageKey = 'prismleaf-color-scheme';
	const prefersDark = window.matchMedia
		? window.matchMedia('(prefers-color-scheme: dark)')
		: null;

	const data = window.PrismleafThemeSwitch || {};
	const forceLight = !!data.forceLight;
	const initial = forceLight ? 'light' : data.initialState || 'auto';
	const wrapper = document.querySelector('.prismleaf-theme-switch');
	const button = wrapper
		? wrapper.querySelector('.prismleaf-theme-switch-button')
		: null;
	const live = wrapper
		? wrapper.querySelector('.prismleaf-theme-switch-live')
		: null;

	if (!wrapper || !button) {
		return;
	}

	let state = initial;

	const setAriaLabel = (nextState, includeHint = true) => {
		const labels = {
			auto: data.labelAuto || 'Color scheme: Auto',
			light: data.labelLight || 'Color scheme: Light',
			dark: data.labelDark || 'Color scheme: Dark',
		};

		const nextMap = {
			auto: data.hintNextLight || 'Next: Light',
			light: data.hintNextDark || 'Next: Dark',
			dark: data.hintNextAuto || 'Next: Auto',
		};

		const label = labels[nextState] || labels.auto;
		const hint = includeHint ? nextMap[nextState] || '' : '';
		button.setAttribute('aria-label', hint ? `${label}. ${hint}` : label);
		button.setAttribute('title', hint ? `${label}. ${hint}` : label);
		let pressedState = 'false';
		if ('auto' === nextState) {
			pressedState = 'mixed';
		} else if ('dark' === nextState) {
			pressedState = 'true';
		}
		button.setAttribute('aria-pressed', pressedState);
		if (live) {
			live.textContent = label;
		}
	};

	const setIcon = (nextState) => {
		const iconEl = wrapper.querySelector('.prismleaf-theme-switch-icon');
		if (iconEl) {
			iconEl.textContent = '';
			iconEl.setAttribute('data-state', nextState);
		}
	};

	const persist = (value) => {
		try {
			window.localStorage.setItem(storageKey, value);
		} catch (e) {
			// Ignore storage errors.
		}
	};

	const readPersisted = () => {
		if (forceLight) {
			return 'light';
		}
		try {
			const saved = window.localStorage.getItem(storageKey);
			return saved || 'auto';
		} catch (e) {
			return 'auto';
		}
	};

	const applyScheme = (scheme, includeHint = true) => {
		let effective = scheme;
		if ('auto' === scheme) {
			effective = prefersDark && prefersDark.matches ? 'dark' : 'light';
		}

		doc.setAttribute('data-prismleaf-color-scheme', effective);
		wrapper.setAttribute('data-state', scheme);
		state = scheme;
		setAriaLabel(scheme, includeHint);
		setIcon(scheme);
		if (!forceLight) {
			persist(scheme);
		}
	};

	const cycleState = () => {
		if (forceLight) {
			return;
		}

		let next = 'auto';
		if ('auto' === state) {
			next = 'light';
		} else if ('light' === state) {
			next = 'dark';
		} else {
			next = 'auto';
		}

		applyScheme(next);
	};

	if (forceLight) {
		applyScheme('light', false);
		button.setAttribute('disabled', 'disabled');
		return;
	}

	const starting = readPersisted();
	applyScheme(starting);

	button.addEventListener('click', cycleState);

	if (prefersDark && prefersDark.addEventListener) {
		prefersDark.addEventListener('change', () => {
			if ('auto' === state) {
				applyScheme('auto');
			}
		});
	}
})();
