/**
 * Prismleaf Customizer Palette Preview Control.
 *
 * @package prismleaf
 */

(function (api) {
	if (!api) {
		return;
	}

	const parseJson = (raw) => {
		if (!raw) {
			return null;
		}

		try {
			return JSON.parse(raw);
		} catch (e) {
			return null;
		}
	};

	const renderGrid = (container, values, labels, classes) => {
		if (!container) {
			return;
		}

		const rowClass = (classes && classes.rowClass) || '';
		const swatchClass = (classes && classes.swatchClass) || '';
		const labelClass = (classes && classes.labelClass) || '';

		container.innerHTML = '';

		Object.keys(labels || {}).forEach((key) => {
			if (!values || !values[key]) {
				return;
			}

			const row = document.createElement('div');
			if (rowClass) {
				row.className = rowClass;
			}

			const swatch = document.createElement('div');
			if (swatchClass) {
				swatch.className = swatchClass;
			}
			swatch.style.backgroundColor = values[key];

			const label = document.createElement('div');
			if (labelClass) {
				label.className = labelClass;
			}
			label.textContent = `${labels[key]} ${values[key]}`;

			row.appendChild(swatch);
			row.appendChild(label);
			container.appendChild(row);
		});
	};

	const bindSetting = (settingId, callback) => {
		if (!settingId || !callback) {
			return;
		}

		api(settingId, (setting) => {
			setting.bind(callback);
		});
	};

	const renderGridFromSetting = (settingId, container, labels, classes) => {
		if (!settingId) {
			if (container) {
				container.hidden = true;
				container.innerHTML = '';
			}
			return;
		}

		const setting = api(settingId);
		const values = setting ? parseJson(setting.get()) : null;

		if (!values) {
			container.hidden = true;
			container.innerHTML = '';
			return;
		}

		container.hidden = false;
		renderGrid(container, values, labels, classes);
	};

	const createGridUpdater = (options) => {
		if (!options) {
			return () => {};
		}

		const {
			settingId,
			container,
			labels,
			classes,
			isActive,
		} = options;

		return () => {
			if (typeof isActive === 'function' && !isActive()) {
				if (container) {
					container.hidden = true;
					container.innerHTML = '';
				}
				return;
			}

			renderGridFromSetting(settingId, container, labels, classes);
		};
	};

	const initGridControl = (options) => {
		if (!options) {
			return () => {};
		}

		const updateGrid = createGridUpdater(options);
		const bindings = options.bindings || [];

		bindings.forEach((binding) => {
			if (!binding) {
				return;
			}
			bindSetting(binding.id, binding.callback || updateGrid);
		});

		updateGrid();
		return updateGrid;
	};

	const initControl = (control) => {
		const grid = control.container.find('.prismleaf-preview-grid')[0];
		const labels = control.params.paletteLabels || {};
		const paletteSettingId = control.params.paletteSetting;

		const updateGrid = initGridControl({
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
