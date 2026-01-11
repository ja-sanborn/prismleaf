/**
 * Prismleaf Customizer helpers.
 *
 * Shared color and palette utilities.
 *
 * @package prismleaf
 */

(function (window) {
	const clampInt = (value, min, max) => {
		const num = Number.isFinite(value) ? Math.trunc(value) : min;
		return Math.min(Math.max(num, min), max);
	};

	const clampFloat = (value, min, max) => {
		const num = Number.isFinite(value) ? value : min;
		return Math.min(Math.max(num, min), max);
	};

	const sanitizeHexColor = (value) => {
		if (typeof value !== 'string') {
			return '';
		}

		let hex = value.trim().toLowerCase();
		if (!hex) {
			return '';
		}

		if (hex[0] === '#') {
			hex = hex.slice(1);
		}

		if (hex.length === 3) {
			hex = `${hex[0]}${hex[0]}${hex[1]}${hex[1]}${hex[2]}${hex[2]}`;
		}

		if (!/^[0-9a-f]{6}$/.test(hex)) {
			return '';
		}

		return `#${hex}`;
	};

	const hexToRgb = (hex) => {
		const clean = sanitizeHexColor(hex);
		if (!clean) {
			return null;
		}

		const raw = clean.slice(1);
		const r = parseInt(raw.slice(0, 2), 16);
		const g = parseInt(raw.slice(2, 4), 16);
		const b = parseInt(raw.slice(4, 6), 16);

		return [r, g, b];
	};

	const rgbaFromHex = (hex, alpha) => {
		const rgb = hexToRgb(hex);
		if (!rgb) {
			return '';
		}

		const clamped = clampFloat(alpha, 0, 1);
		const normalized = Number.isFinite(clamped) ? clamped : 0;
		return `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${normalized.toFixed(2)})`;
	};

	const isRgbaColor = (value) => {
		if (typeof value !== 'string') {
			return false;
		}

		const match = value.trim().match(/^rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(0|1|0?\.\d+)\s*\)$/);
		if (!match) {
			return false;
		}

		const r = Number(match[1]);
		const g = Number(match[2]);
		const b = Number(match[3]);
		const a = Number(match[4]);

		if (!Number.isFinite(r) || r < 0 || r > 255) {
			return false;
		}
		if (!Number.isFinite(g) || g < 0 || g > 255) {
			return false;
		}
		if (!Number.isFinite(b) || b < 0 || b > 255) {
			return false;
		}
		if (!Number.isFinite(a) || a < 0 || a > 1) {
			return false;
		}

		return true;
	};

	const rgbToHex = (rgb) => {
		if (!Array.isArray(rgb) || rgb.length !== 3) {
			return null;
		}

		const r = clampInt(rgb[0], 0, 255).toString(16).padStart(2, '0');
		const g = clampInt(rgb[1], 0, 255).toString(16).padStart(2, '0');
		const b = clampInt(rgb[2], 0, 255).toString(16).padStart(2, '0');

		return `#${r}${g}${b}`;
	};

	const rgbToHsl = (rgb) => {
		if (!Array.isArray(rgb) || rgb.length !== 3) {
			return null;
		}

		const r = clampInt(rgb[0], 0, 255) / 255;
		const g = clampInt(rgb[1], 0, 255) / 255;
		const b = clampInt(rgb[2], 0, 255) / 255;

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
					break;
			}

			h /= 6;
		}

		return [h, s, l];
	};

	const hslToRgb = (hsl) => {
		if (!Array.isArray(hsl) || hsl.length !== 3) {
			return null;
		}

		const h = clampFloat(hsl[0], 0, 1);
		const s = clampFloat(hsl[1], 0, 1);
		const l = clampFloat(hsl[2], 0, 1);

		if (s === 0) {
			const val = Math.round(l * 255);
			return [val, val, val];
		}

		const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
		const p = 2 * l - q;
		const hueToRgb = (p1, q1, t) => {
			let v = t;
			if (v < 0) {
				v += 1;
			}
			if (v > 1) {
				v -= 1;
			}
			if (v < 1 / 6) {
				return p1 + (q1 - p1) * 6 * v;
			}
			if (v < 1 / 2) {
				return q1;
			}
			if (v < 2 / 3) {
				return p1 + (q1 - p1) * (2 / 3 - v) * 6;
			}
			return p1;
		};

		const r = hueToRgb(p, q, h + 1 / 3);
		const g = hueToRgb(p, q, h);
		const b = hueToRgb(p, q, h - 1 / 3);

		return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
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
		if (l1 === null || l2 === null) {
			return null;
		}

		const lighter = Math.max(l1, l2);
		const darker = Math.min(l1, l2);
		return (lighter + 0.05) / (darker + 0.05);
	};

	const minContrastRatio = (foregroundHex, backgroundHexes) => {
		const foreground = sanitizeHexColor(foregroundHex);
		if (!foreground || !Array.isArray(backgroundHexes) || backgroundHexes.length === 0) {
			return null;
		}

		let minRatio = null;
		backgroundHexes.forEach((hex) => {
			const clean = sanitizeHexColor(hex);
			if (!clean) {
				return;
			}
			const ratio = contrastRatio(foreground, clean);
			if (ratio === null) {
				return;
			}
			if (minRatio === null || ratio < minRatio) {
				minRatio = ratio;
			}
		});

		return minRatio;
	};

	const adjustLightnessSafe = (hex, delta, minL = 0.01, maxL = 0.99) => {
		const rgb = hexToRgb(hex);
		if (!rgb) {
			return null;
		}

		const hsl = rgbToHsl(rgb);
		if (!hsl) {
			return null;
		}

		const min = Math.max(0, minL);
		const max = Math.min(1, maxL);
		hsl[2] = Math.max(min, Math.min(max, hsl[2] + delta));

		const nextRgb = hslToRgb(hsl);
		return nextRgb ? rgbToHex(nextRgb) : null;
	};

	const shiftToLightness = (hex, targetL, minL = 0.01, maxL = 0.99) => {
		const clean = sanitizeHexColor(hex);
		if (!clean) {
			return null;
		}

		const rgb = hexToRgb(clean);
		const hsl = rgb ? rgbToHsl(rgb) : null;
		if (!hsl) {
			return null;
		}

		const target = clampFloat(targetL, 0, 1);
		const min = clampFloat(minL, 0, 1);
		const max = clampFloat(maxL, 0, 1);
		const delta = target - hsl[2];

		return adjustLightnessSafe(clean, delta, min, max);
	};

	const buildLightnessRamp = (startHex, deltas, minL = 0.01, maxL = 0.99) => {
		const start = sanitizeHexColor(startHex);
		if (!start || !Array.isArray(deltas)) {
			return [];
		}

		const min = clampFloat(minL, 0, 1);
		const max = clampFloat(maxL, 0, 1);
		const ramp = [start];
		let current = start;

		for (let i = 0; i < deltas.length; i += 1) {
			const next = adjustLightnessSafe(current, Number(deltas[i]), min, max);
			if (!next) {
				return [];
			}
			ramp.push(next);
			current = next;
		}

		return ramp;
	};

	const pickOnColorFromTones = (referenceHex, backgroundHexes) => {
		const reference = sanitizeHexColor(referenceHex);
		if (!reference || !Array.isArray(backgroundHexes) || backgroundHexes.length === 0) {
			return '';
		}

		const backgrounds = backgroundHexes.map((hex) => sanitizeHexColor(hex)).filter(Boolean);
		if (backgrounds.length === 0) {
			return '';
		}

		const targets = [
			shiftToLightness(reference, 0.9, 0.05, 0.95),
			shiftToLightness(reference, 0.1, 0.05, 0.95),
		];

		let bestCandidate = '';
		let bestRatio = 0;
		let bestDistance = null;

		targets.forEach((candidate) => {
			if (!candidate) {
				return;
			}

			const minRatio = minContrastRatio(candidate, backgrounds);
			if (minRatio === null) {
				return;
			}

			const meetsMin = minRatio >= 4.5;
			const distance = Math.abs(7.0 - minRatio);

			if (meetsMin) {
				if (bestDistance === null || distance < bestDistance) {
					bestCandidate = candidate;
					bestRatio = minRatio;
					bestDistance = distance;
				}
			} else if (bestRatio < 4.5 && minRatio > bestRatio) {
				bestCandidate = candidate;
				bestRatio = minRatio;
				bestDistance = distance;
			}
		});

		return bestCandidate;
	};

	const generatePaletteFromBase = (baseHex) => {
		const base = sanitizeHexColor(baseHex);
		if (!base) {
			return null;
		}

		const rgb = hexToRgb(base);
		const hsl = rgb ? rgbToHsl(rgb) : null;
		if (!hsl) {
			return null;
		}

		let baseL = clampFloat(hsl[2], 0.05, 0.95);
		let baseAdjusted = base;
		if (baseL !== hsl[2]) {
			const shifted = shiftToLightness(base, baseL, 0.05, 0.95);
			if (shifted) {
				baseAdjusted = shifted;
			}
		}

		const lightTarget = 0.95;
		const darkTarget = 0.05;
		const distLight = Math.abs(lightTarget - baseL);
		const distDark = Math.abs(baseL - darkTarget);
		const targetL = distLight >= distDark ? lightTarget : darkTarget;
		const direction = targetL >= baseL ? 1 : -1;

		let step = Math.abs(targetL - baseL) / 9;
		step = clampFloat(step, 0, 0.06);

		const deltas = Array(9).fill(step * direction);
		const ramp = buildLightnessRamp(baseAdjusted, deltas, 0.05, 0.95);
		if (ramp.length !== 10) {
			return null;
		}

		const tones = ramp.slice(0, 5);
		const containerTones = ramp.slice(5, 10);
		const onColor = pickOnColorFromTones(ramp[0], tones);
		const containerOn = pickOnColorFromTones(ramp[5], containerTones);

		const opacityBase = ramp[0];

		return {
			'1': ramp[0],
			'2': ramp[1],
			'3': ramp[2],
			'4': ramp[3],
			'5': ramp[4],
			on: onColor,
			outline: rgbaFromHex(opacityBase, 0.30),
			outline_variant: rgbaFromHex(opacityBase, 0.18),
			on_surface_muted: rgbaFromHex(opacityBase, 0.72),
			disabled_foreground: rgbaFromHex(opacityBase, 0.38),
			disabled_surface: rgbaFromHex(opacityBase, 0.12),
			container_1: ramp[9],
			container_2: ramp[8],
			container_3: ramp[7],
			container_4: ramp[6],
			container_5: ramp[5],
			container_on: containerOn,
		};
	};

	const buildPaletteJsonFromBase = (baseHex) => {
		const palette = generatePaletteFromBase(baseHex);
		if (!palette) {
			return '';
		}

		const keys = [
			'1',
			'2',
			'3',
			'4',
			'5',
			'on',
			'outline',
			'outline_variant',
			'on_surface_muted',
			'disabled_foreground',
			'disabled_surface',
			'container_1',
			'container_2',
			'container_3',
			'container_4',
			'container_5',
			'container_on',
		];

		const clean = {};
		for (let i = 0; i < keys.length; i += 1) {
			const key = keys[i];
			if (key === 'outline'
				|| key === 'outline_variant'
				|| key === 'on_surface_muted'
				|| key === 'disabled_foreground'
				|| key === 'disabled_surface') {
				if (!isRgbaColor(palette[key])) {
					return '';
				}
				clean[key] = palette[key];
				continue;
			}

			const value = sanitizeHexColor(palette[key]);
			if (!value) {
				return '';
			}
			clean[key] = value;
		}

		return JSON.stringify(clean);
	};

	const buildPaletteJsonFromVariables = (source) => {
		if (typeof source !== 'string') {
			return '';
		}

		const slug = source.trim().toLowerCase();
		if (!slug || slug === 'default' || slug === 'custom') {
			return '';
		}

		const expectedKeys = [
			'1',
			'2',
			'3',
			'4',
			'5',
			'on',
			'outline',
			'outline_variant',
			'on_surface_muted',
			'disabled_foreground',
			'disabled_surface',
			'container_1',
			'container_2',
			'container_3',
			'container_4',
			'container_5',
			'container_on',
		];

		const palette = {};
		const setOpacityValues = (prefix) => {
			palette.outline = `${prefix}-outline`;
			palette.outline_variant = `${prefix}-outline-variant`;
			palette.on_surface_muted = `${prefix}-on-surface-muted`;
			palette.disabled_foreground = `${prefix}-disabled-foreground`;
			palette.disabled_surface = `${prefix}-disabled-surface`;
		};

		const setValues = (prefix, containerPrefix) => {
			palette.on = `${prefix}-on`;
			palette['1'] = `${prefix}-1`;
			palette['2'] = `${prefix}-2`;
			palette['3'] = `${prefix}-3`;
			palette['4'] = `${prefix}-4`;
			palette['5'] = `${prefix}-5`;
			palette.container_on = `${containerPrefix}-on`;
			palette.container_1 = `${containerPrefix}-1`;
			palette.container_2 = `${containerPrefix}-2`;
			palette.container_3 = `${containerPrefix}-3`;
			palette.container_4 = `${containerPrefix}-4`;
			palette.container_5 = `${containerPrefix}-5`;
		};

		switch (slug) {
			case 'primary':
			case 'secondary':
			case 'tertiary':
			case 'error':
			case 'warning':
			case 'info':
				setValues(`--prismleaf-color-${slug}`, `--prismleaf-color-${slug}-container`);
				setOpacityValues(`--prismleaf-color-${slug}`);
				break;
			case 'neutral_light':
				setValues('--prismleaf-color-light-surface', '--prismleaf-color-light-surface-container');
				setOpacityValues('--prismleaf-color-light');
				break;
			case 'neutral_dark':
				setValues('--prismleaf-color-dark-surface', '--prismleaf-color-dark-surface-container');
				setOpacityValues('--prismleaf-color-dark');
				break;
			default:
				return '';
		}

		const clean = {};
		for (let i = 0; i < expectedKeys.length; i += 1) {
			const key = expectedKeys[i];
			const value = palette[key];
			if (!value) {
				return '';
			}
			clean[key] = value;
		}

		return JSON.stringify(clean);
	};

	window.PrismleafCustomizerHelpers = {
		clampInt,
		clampFloat,
		sanitizeHexColor,
		hexToRgb,
		rgbToHex,
		rgbToHsl,
		hslToRgb,
		relativeLuminance,
		contrastRatio,
		minContrastRatio,
		adjustLightnessSafe,
		shiftToLightness,
		buildLightnessRamp,
		pickOnColorFromTones,
		generatePaletteFromBase,
		buildPaletteJsonFromBase,
		buildPaletteJsonFromVariables,
	};
})(window);
