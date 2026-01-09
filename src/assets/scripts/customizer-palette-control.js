/**
 * Prismleaf Customizer Palette Preview Control.
 *
 * @package prismleaf
 */

(function (api) {
	if (!api) {
		return;
	}

	const preview = window.prismleafPreviewGrid;
	if (!preview) {
		return;
	}

	const initControl = (control) => {
		const grid = control.container.find('.prismleaf-preview-grid')[0];
		const labels = control.params.paletteLabels || {};
		const paletteSettingId = control.params.paletteSetting;

		const updateGrid = preview.initGridControl({
			api,
			settingId: paletteSettingId,
			container: grid,
			labels,
			classes: {
				rowClass: 'prismleaf-preview-row',
				swatchClass: 'prismleaf-preview-swatch',
				labelClass: 'prismleaf-preview-label',
			},
			isActive: () => !!control.setting.get(),
			bindings: [
				{ id: paletteSettingId },
			],
		});

		control.setting.bind(updateGrid);

		updateGrid();
	};

	api.bind('ready', () => {
		api.control.each((control) => {
			if ('prismleaf_palette_preview' === control.params.type) {
				initControl(control);
			}
		});
	});
})(window.wp && window.wp.customize);
