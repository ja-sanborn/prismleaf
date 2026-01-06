(function (api) {
	if (!api) {
		return;
	}

	const STYLE_ID = 'prismleaf-header-palette-preview';

	const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

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

	const rgbToHex = (rgb) => {
		if (!Array.isArray(rgb) || 3 !== rgb.length) {
			return null;
		}

		const [r, g, b] = rgb.map((v) => clamp(Math.round(v), 0, 255));
		return (
			'#' + [r, g, b].map((v) => v.toString(16).padStart(2, '0')).join('')
		);
	};

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
		const adjusted = hslToRgb(hsl);
		return adjusted ? normalizeHex(rgbToHex(adjusted)) : null;
	};

	const relativeLuminance = (hex) => {
		const rgb = hexToRgb(hex);
		if (!rgb) {
			return null;
		}

		const srgb = rgb.map((v) => {
			const c = v / 255;
			return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
		});

		return 0.2126 * srgb[0] + 0.7152 * srgb[1] + 0.0722 * srgb[2];
	};

	const contrastRatio = (hex1, hex2) => {
		const l1 = relativeLuminance(hex1);
		const l2 = relativeLuminance(hex2);
		if (null === l1 || null === l2) {
			return null;
		}

		const lighter = Math.max(l1, l2);
		const darker = Math.min(l1, l2);
		return (lighter + 0.05) / (darker + 0.05);
	};

	const pickOnColor = (hex) => {
		const white = '#ffffff';
		const black = '#000000';
		const cw = contrastRatio(hex, white);
		const cb = contrastRatio(hex, black);
		if (null === cw || null === cb) {
			return black;
		}
		return cw >= cb ? white : black;
	};

	const generatePaletteFromBase = (hex, scheme) => {
		const base = normalizeHex(hex);
		if (!base) {
			return null;
		}

		const isDark = 'dark' === scheme;
		const baseDarkerDelta = isDark ? -0.06 : -0.12;
		const baseDarkestDelta = isDark ? -0.12 : -0.22;
		const baseLighterDelta = isDark ? 0.14 : 0.08;
		const baseLightestDelta = isDark ? 0.22 : 0.16;

		return {
			base: base,
			on_base: pickOnColor(base),
			base_darker: adjustLightness(base, baseDarkerDelta) || base,
			base_darkest: adjustLightness(base, baseDarkestDelta) || base,
			base_lighter: adjustLightness(base, baseLighterDelta) || base,
			base_lightest: adjustLightness(base, baseLightestDelta) || base,
		};
	};

	const rolePaletteMap = (role, scheme) => {
		if (!role) {
			return null;
		}

		const normalized = String(role);
		const schemeKey = 'dark' === scheme ? 'dark' : 'light';
		const isActive = 'active' === scheme;

		const brandRoles = [
			'primary',
			'primary-container',
			'secondary',
			'secondary-container',
			'tertiary',
			'tertiary-container',
		];

		if (brandRoles.includes(normalized)) {
			const base = `--prismleaf-color-${normalized}`;
			const onBase = `--prismleaf-color-on-${normalized}`;
			return {
				base: `var(${base})`,
				on_base: `var(${onBase})`,
				base_darker: `var(${base}-darker)`,
				base_darkest: `var(${base}-darkest)`,
				base_lighter: `var(${base}-lighter)`,
				base_lightest: `var(${base}-lightest)`,
			};
		}

		const neutralMap = {
			surface: isActive
				? '--prismleaf-color-surface'
				: `--prismleaf-color-${schemeKey}-surface`,
			'surface-variant': isActive
				? '--prismleaf-color-surface-variant'
				: `--prismleaf-color-${schemeKey}-surface-variant`,
			foreground: isActive
				? '--prismleaf-color-foreground'
				: `--prismleaf-color-${schemeKey}-foreground`,
			background: isActive
				? '--prismleaf-color-background'
				: `--prismleaf-color-${schemeKey}-background`,
		};

		const base = neutralMap[normalized];
		if (!base) {
			return null;
		}

		let onBase = '';
		if ('surface' === normalized) {
			onBase = isActive
				? '--prismleaf-color-on-surface'
				: `--prismleaf-color-${schemeKey}-on-surface`;
		} else if ('surface-variant' === normalized) {
			onBase = isActive
				? '--prismleaf-color-on-surface-variant'
				: `--prismleaf-color-${schemeKey}-on-surface-variant`;
		} else if ('foreground' === normalized) {
			onBase = isActive
				? '--prismleaf-color-on-foreground'
				: `--prismleaf-color-${schemeKey}-on-foreground`;
		} else {
			onBase = isActive
				? '--prismleaf-color-on-background'
				: `--prismleaf-color-${schemeKey}-on-background`;
		}

		return {
			base: `var(${base})`,
			on_base: `var(${onBase})`,
			base_darker: `var(${base}-darker)`,
			base_darkest: `var(${base}-darkest)`,
			base_lighter: `var(${base}-lighter)`,
			base_lightest: `var(${base}-lightest)`,
		};
	};

	const renderPaletteCss = (varBase, palette) => {
		if (!palette) {
			return '';
		}

		const map = {
			base: varBase,
			on_base: `${varBase}-on`,
			base_darker: `${varBase}-darker`,
			base_darkest: `${varBase}-darkest`,
			base_lighter: `${varBase}-lighter`,
			base_lightest: `${varBase}-lightest`,
		};

		let css = '';
		Object.keys(map).forEach((key) => {
			const value = palette[key];
			if (value) {
				css += `${map[key]}:${value};`;
			}
		});

		return css;
	};

	const getSettingValue = (id) => {
		if (!api.has(id)) {
			return null;
		}
		return api(id).get();
	};

	const buildHeaderRoleCss = () => {
		const bgRole = getSettingValue('prismleaf_header_background_role');
		const fgRole = getSettingValue('prismleaf_header_foreground_role');
		const bgLight = normalizeHex(getSettingValue('prismleaf_global_header_background'));
		const bgDark = normalizeHex(
			getSettingValue('prismleaf_header_background_custom_dark')
		);
		const fgLight = normalizeHex(getSettingValue('prismleaf_global_header_foreground'));
		const fgDark = normalizeHex(
			getSettingValue('prismleaf_header_foreground_custom_dark')
		);

		let rootCss = '';
		let lightCss = '';
		let darkCss = '';

		const applyControl = (roleValue, lightHex, darkHex, varBase) => {
			if ('custom' === roleValue) {
				if (lightHex) {
					const palette = generatePaletteFromBase(lightHex, 'light');
					lightCss += renderPaletteCss(varBase, palette);
				}
				if (darkHex) {
					const palette = generatePaletteFromBase(darkHex, 'dark');
					darkCss += renderPaletteCss(varBase, palette);
				}
				return;
			}

			if (!roleValue) {
				return;
			}

			const activePalette = rolePaletteMap(roleValue, 'active');
			rootCss += renderPaletteCss(varBase, activePalette);
		};

		applyControl(bgRole, bgLight, bgDark, '--prismleaf-region-header-background');
		applyControl(fgRole, fgLight, fgDark, '--prismleaf-region-header-foreground');

		let css = '';
		if (rootCss) {
			css += `:root{${rootCss}}`;
			css += `:root[data-prismleaf-color-scheme="light"]{${rootCss}}`;
			css += `:root[data-prismleaf-color-scheme="dark"]{${rootCss}}`;
			css += `.prismleaf-header-contained .prismleaf-region-content{${rootCss}}`;
			css += `:root[data-prismleaf-color-scheme="light"] .prismleaf-header-contained .prismleaf-region-content{${rootCss}}`;
			css += `:root[data-prismleaf-color-scheme="dark"] .prismleaf-header-contained .prismleaf-region-content{${rootCss}}`;
		}
		if (lightCss) {
			css += `@media (prefers-color-scheme: light){:root{${lightCss}}}`;
			css += `@media (prefers-color-scheme: light){.prismleaf-header-contained .prismleaf-region-content{${lightCss}}}`;
			css += `:root[data-prismleaf-color-scheme="light"]{${lightCss}}`;
			css += `:root[data-prismleaf-color-scheme="light"] .prismleaf-header-contained .prismleaf-region-content{${lightCss}}`;
		}
		if (darkCss) {
			css += `@media (prefers-color-scheme: dark){:root{${darkCss}}}`;
			css += `@media (prefers-color-scheme: dark){.prismleaf-header-contained .prismleaf-region-content{${darkCss}}}`;
			css += `:root[data-prismleaf-color-scheme="dark"]{${darkCss}}`;
			css += `:root[data-prismleaf-color-scheme="dark"] .prismleaf-header-contained .prismleaf-region-content{${darkCss}}`;
		}

		return css;
	};

	const updatePreviewStyle = () => {
		const css = buildHeaderRoleCss();
		let styleEl = document.getElementById(STYLE_ID);

		if (!css) {
			if (styleEl && styleEl.parentNode) {
				styleEl.parentNode.removeChild(styleEl);
			}
			return;
		}

		if (!styleEl) {
			styleEl = document.createElement('style');
			styleEl.id = STYLE_ID;
			document.head.appendChild(styleEl);
		}

		styleEl.textContent = css;
	};

	const bindSetting = (id) => {
		api(id, (setting) => {
			setting.bind(updatePreviewStyle);
		});
	};

	const settingIds = [
		'prismleaf_header_background_role',
		'prismleaf_global_header_background',
		'prismleaf_header_background_custom_dark',
		'prismleaf_header_foreground_role',
		'prismleaf_global_header_foreground',
		'prismleaf_header_foreground_custom_dark',
	];

	const initBindings = () => {
		settingIds.forEach(bindSetting);
		updatePreviewStyle();
	};

	api.bind('ready', initBindings);
	api.bind('preview-ready', initBindings);
	initBindings();
})(window.wp && window.wp.customize);
