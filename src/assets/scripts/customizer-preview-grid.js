/**
 * Prismleaf Customizer preview grid helpers.
 *
 * @package prismleaf
 */

(function (win) {
	if (!win) {
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

	const bindSetting = (api, settingId, callback) => {
		if (!api || !settingId || !callback) {
			return;
		}

		api(settingId, (setting) => {
			setting.bind(callback);
		});
	};

	const renderGridFromSetting = (api, settingId, container, labels, classes) => {
		if (!api || !settingId) {
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
			api,
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

			renderGridFromSetting(api, settingId, container, labels, classes);
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
			bindSetting(options.api, binding.id, binding.callback || updateGrid);
		});

		updateGrid();
		return updateGrid;
	};

	const requestAndApplyPayload = (options) => {
		if (!options) {
			return;
		}

		const {
			isUpdating,
			setIsUpdating,
			request,
			applyPayload,
			payloadWhenEmpty,
			getValues,
			source,
		} = options;

		if (isUpdating && typeof isUpdating === 'function' && isUpdating()) {
			return;
		}

		if (typeof getValues !== 'function') {
			return;
		}

		const values = getValues() || {};
		const hasAnyValue = Object.keys(values).some((key) => values[key]);

		if (!hasAnyValue) {
			if (typeof applyPayload === 'function') {
				applyPayload(payloadWhenEmpty || {});
			}
			return;
		}

		if (typeof request !== 'function') {
			return;
		}

		if (typeof setIsUpdating === 'function') {
			setIsUpdating(true);
		}

		request(values, source)
			.done((response) => {
				if (response && response.success && typeof applyPayload === 'function') {
					applyPayload(response.data);
				}
			})
			.always(() => {
				if (typeof setIsUpdating === 'function') {
					setIsUpdating(false);
				}
			});
	};

	const initPayloadControl = (options) => {
		if (!options) {
			return () => {};
		}

		const updateGrid = initGridControl(options.grid || {});
		const requestUpdate = (source) =>
			requestAndApplyPayload({
				isUpdating: options.isUpdating,
				setIsUpdating: options.setIsUpdating,
				request: options.request,
				applyPayload: options.applyPayload,
				payloadWhenEmpty: options.payloadWhenEmpty,
				getValues: options.getValues,
				source,
			});

		if (Array.isArray(options.requestBindings)) {
			options.requestBindings.forEach((binding) => {
				if (!binding) {
					return;
				}
				bindSetting(options.api, binding.id, () => requestUpdate(binding.source));
			});
		}

		return updateGrid;
	};

	win.prismleafPreviewGrid = {
		parseJson,
		renderGrid,
		renderGridFromSetting,
		bindSetting,
		createGridUpdater,
		initGridControl,
		requestAndApplyPayload,
		initPayloadControl,
	};
})(window);
