/**
 * Prismleaf Customizer Palette Source Control.
 *
 * @package prismleaf
 */

(function (api) {
	if (!api) {
		return;
	}

	const $ = window.jQuery;
	const helpers = window.PrismleafCustomizerHelpers || {};

	const setColorPickerValue = (input, value) => {
		if (!input || !$ || !$.fn || !$.fn.wpColorPicker) {
			if (input) {
				input.value = value;
			}
			return;
		}

		$(input).wpColorPicker('color', value);
	};

	const togglePreview = (control, show) => {
		const preview = control.container.find('.prismleaf-palette-source-preview');
		if (preview.length) {
			preview.toggle(!!show);
		}
	};

	const initControl = (control) => {
		const sourceSetting = control.settings && control.settings.source ? control.settings.source : null;
		const baseSetting = control.settings && control.settings.base ? control.settings.base : null;
		const paletteSetting = control.settings && control.settings.palette ? control.settings.palette : null;

		if (!sourceSetting || !baseSetting || !paletteSetting) {
			return;
		}

		const input = control.container.find('.prismleaf-palette-preview-input')[0];

		const updateFromSource = () => {
			const source = sourceSetting.get() || 'default';
			const isCustom = source === 'custom';

			togglePreview(control, isCustom);

			if (!isCustom) {
				if (baseSetting.get() !== '') {
					baseSetting.set('');
				}
				setColorPickerValue(input, '');
			}

			if (source === 'default') {
				paletteSetting.set('');
				return;
			}

			if (isCustom) {
				if (!baseSetting.get()) {
					paletteSetting.set('');
				}
				return;
			}

			if (typeof helpers.buildPaletteJsonFromVariables === 'function') {
				paletteSetting.set(helpers.buildPaletteJsonFromVariables(source));
			}
		};

		sourceSetting.bind(updateFromSource);
		updateFromSource();
	};

	api.bind('ready', () => {
		api.control.each((control) => {
			if ('prismleaf_palette_source' === control.params.type) {
				initControl(control);
			}
		});
	});
})(window.wp && window.wp.customize);
