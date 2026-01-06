(function (api) {
	if (!api) {
		return;
	}

	/**
	 * Clamp a value between min and max.
	 * @param {number} value Input value.
	 * @param {number} min   Minimum value.
	 * @param {number} max   Maximum value.
	 * @return {number} Clamped value.
	 */
	const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

	/**
	 * Normalize a hex color to full 7-char form (#rrggbb). Returns null when invalid.
	 * @param {string} value Raw hex string.
	 * @return {string|null} Normalized hex or null on failure.
	 */
	const normalizeHex = (value) => {
		if (!value) {
			return null;
		}

		let hex = String(value).trim().toLowerCase();

		if ('' === hex) {
			return null;
		}

		if ('#' === hex.charAt(0)) {
			hex = hex.slice(1);
		}

		if (3 === hex.length) {
			hex = hex
				.split('')
				.map((ch) => ch + ch)
				.join('');
		}

		if (6 !== hex.length || !/^[0-9a-f]{6}$/i.test(hex)) {
			return null;
		}

		return `#${hex}`;
	};

	/**
	 * Convert hex to RGB array.
	 * @param {string} hex Hex color string.
	 * @return {number[]|null} RGB array or null on failure.
	 */
	const hexToRgb = (hex) => {
		const normalized = normalizeHex(hex);
		if (!normalized) {
			return null;
		}

		return [
			parseInt(normalized.slice(1, 3), 16),
			parseInt(normalized.slice(3, 5), 16),
			parseInt(normalized.slice(5, 7), 16),
		];
	};

	/**
	 * Convert RGB array to hex string.
	 * @param {number[]} rgb RGB array.
	 * @return {string|null} Hex string or null on failure.
	 */
	const rgbToHex = (rgb) => {
		if (!Array.isArray(rgb) || 3 !== rgb.length) {
			return null;
		}

		const [r, g, b] = rgb.map((v) => clamp(Math.round(v), 0, 255));
		return (
			'#' + [r, g, b].map((v) => v.toString(16).padStart(2, '0')).join('')
		);
	};

	/**
	 * Convert RGB to HSL (0..1).
	 * @param {number[]} rgb RGB array.
	 * @return {number[]|null} HSL array or null on failure.
	 */
	const rgbToHsl = (rgb) => {
		if (!Array.isArray(rgb) || 3 !== rgb.length) {
			return null;
		}

		const [r, g, b] = rgb.map((v) => clamp(v, 0, 255) / 255);

		const max = Math.max(r, g, b);
		const min = Math.min(r, g, b);
		let h = 0;
		let s = 0;
		const l = (max + min) / 2;

		if (max !== min) {
			const d = max - min;
			s = l > 0.5 ? d / (2 - max - min) : d / (max + min);

			switch (max) {
				case r:
					h = (g - b) / d + (g < b ? 6 : 0);
					break;
				case g:
					h = (b - r) / d + 2;
					break;
				default:
					h = (r - g) / d + 4;
			}

			h /= 6;
		}

		return [h, s, l];
	};

	/**
	 * Convert HSL (0..1) to RGB.
	 * @param {number[]} hsl HSL array.
	 * @return {number[]|null} RGB array or null on failure.
	 */
	const hslToRgb = (hsl) => {
		if (!Array.isArray(hsl) || 3 !== hsl.length) {
			return null;
		}

		let [h, s, l] = hsl;

		h = clamp(h, 0, 1);
		s = clamp(s, 0, 1);
		l = clamp(l, 0, 1);

		if (0 === s) {
			const val = Math.round(l * 255);
			return [val, val, val];
		}

		const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
		const p = 2 * l - q;

		const hueToRgb = (t) => {
			let temp = t;
			if (temp < 0) {
				temp += 1;
			}
			if (temp > 1) {
				temp -= 1;
			}
			if (temp < 1 / 6) {
				return p + (q - p) * 6 * temp;
			}
			if (temp < 1 / 2) {
				return q;
			}
			if (temp < 2 / 3) {
				return p + (q - p) * (2 / 3 - temp) * 6;
			}
			return p;
		};

		const r = hueToRgb(h + 1 / 3);
		const g = hueToRgb(h);
		const b = hueToRgb(h - 1 / 3);

		return [r * 255, g * 255, b * 255];
	};

	/**
	 * Adjust lightness of a hex color in HSL space.
	 * @param {string} hex   Hex color string.
	 * @param {number} delta Range -1..1.
	 * @return {string|null} Adjusted hex or null on failure.
	 */
	const adjustLightness = (hex, delta) => {
		const rgb = hexToRgb(hex);
		if (!rgb) {
			return null;
		}

		const hsl = rgbToHsl(rgb);
		if (!hsl) {
			return null;
		}

		hsl[2] = clamp(hsl[2] + delta, 0, 1);

		const newRgb = hslToRgb(hsl);
		return newRgb ? normalizeHex(rgbToHex(newRgb)) : null;
	};

	/**
	 * Use the light base for dark when no override is provided.
	 * @param {string} lightHex Light hex string.
	 * @return {string|null} Normalized hex or null on failure.
	 */
	const deriveDarkFromLight = (lightHex) => normalizeHex(lightHex);

	/**
	 * Bind a light/dark setting pair for auto-derivation and clearing.
	 * @param {string} lightId Light setting ID.
	 * @param {string} darkId  Dark setting ID.
	 */
	const bindLightDarkPair = (lightId, darkId) => {
		if (!api.has(lightId) || !api.has(darkId)) {
			return;
		}

		const lightSetting = api(lightId);
		const darkSetting = api(darkId);

		let isInternal = false;

		const setInternal = (setting, value) => {
			isInternal = true;
			setting.set(value);
			isInternal = false;
		};

		// When light changes: derive dark or clear both.
		lightSetting.bind((value) => {
			if (isInternal) {
				return;
			}

			const normalized = normalizeHex(value);

			if (!normalized) {
				setInternal(lightSetting, '');
				setInternal(darkSetting, '');
				return;
			}

			const derived = deriveDarkFromLight(normalized) || '';
			setInternal(lightSetting, normalized);
			setInternal(darkSetting, derived);
		});

		// When dark is cleared, re-derive from light if possible.
		darkSetting.bind((value) => {
			if (isInternal) {
				return;
			}

			const normalized = normalizeHex(value);

			if (null !== normalized) {
				// Valid custom dark entered; keep it.
				setInternal(darkSetting, normalized);
				return;
			}

			// Cleared/invalid: re-derive from light or clear if light is empty.
			const normalizedLight = normalizeHex(lightSetting.get());
			if (!normalizedLight) {
				setInternal(darkSetting, '');
				return;
			}

			const derived = deriveDarkFromLight(normalizedLight) || '';
			setInternal(darkSetting, derived);
		});
	};

	/**
	 * Initialize palette role controls.
	 * @param {HTMLElement} container Control container.
	 */
	const initPaletteRoleControl = (container) => {
		if (!container || 'true' === container.dataset.prismleafInit) {
			return;
		}

		container.dataset.prismleafInit = 'true';

		const select = container.querySelector('.prismleaf-palette-role-select');
		const customWrap = container.querySelector('.prismleaf-palette-role-custom');

		if (!select || !customWrap) {
			return;
		}

		const updateVisibility = (value) => {
			const isCustom = 'custom' === value;
			customWrap.style.display = isCustom ? '' : 'none';
		};

		const updateFromSelect = () => updateVisibility(select.value);
		select.addEventListener('change', updateFromSelect);

		const settingId = select.getAttribute('data-customize-setting-link');
		if (settingId && api.has(settingId)) {
			api(settingId).bind(updateVisibility);
		}

		updateFromSelect();

		const inputs = container.querySelectorAll('.prismleaf-palette-role-custom-input');
		if (window.jQuery && window.jQuery.fn && window.jQuery.fn.wpColorPicker) {
			window.jQuery(inputs).each((index, input) => {
				if ('true' === input.dataset.prismleafColorPicker) {
					return;
				}

				const settingId = input.getAttribute('data-customize-setting-link');
				const setting = settingId && api.has(settingId) ? api(settingId) : null;
				let isInternal = false;

				const setSettingValue = (value) => {
					if (!setting || isInternal) {
						return;
					}

					isInternal = true;
					setting.set(value);
					isInternal = false;
				};

				window.jQuery(input).wpColorPicker({
					change: function (event, ui) {
						if (ui && ui.color) {
							setSettingValue(ui.color.toString());
						}
					},
					clear: function () {
						setSettingValue('');
					},
				});

				input.dataset.prismleafColorPicker = 'true';
			});
		}
	};

	/**
	 * Initialize all palette role controls.
	 */
	const initPaletteRoleControls = () => {
		document
			.querySelectorAll('.prismleaf-palette-role-control')
			.forEach(initPaletteRoleControl);
	};

	api.bind('ready', () => {
		// Brand roles.
		const roles = [
			'primary',
			'secondary',
			'tertiary',
			'error',
			'warning',
			'info',
		];
		roles.forEach((role) => {
			bindLightDarkPair(
				`prismleaf_brand_${role}_light`,
				`prismleaf_brand_${role}_dark`
			);
		});

		initPaletteRoleControls();
	});
})(window.wp && window.wp.customize);
