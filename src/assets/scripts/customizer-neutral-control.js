/**
 * Prismleaf Customizer Neutral Preview Control.
 *
 * @package prismleaf
 */

(function (api, $) {
	if (!api || !$) {
		return;
	}

	if (!window.prismleafNeutralControl) {
		return;
	}

	const preview = window.prismleafPreviewGrid;
	if (!preview) {
		return;
	}

	const requestNeutralPayload = (values, source) =>
		$.post(prismleafNeutralControl.ajaxUrl, {
			action: 'prismleaf_neutral_preview',
			_ajax_nonce: prismleafNeutralControl.nonce,
			light: values.light || '',
			dark: values.dark || '',
			source: source || '',
		});

	const initControl = (control) => {
		const grid = control.container.find('.prismleaf-preview-grid')[0];
		const labels = control.params.neutralLabels || {};
		const lightSettingId = control.params.lightSetting;
		const darkSettingId = control.params.darkSetting;
		const neutralSettingId = control.params.neutralSetting;

		let isUpdating = false;

		const applyPayload = (payload) => {
			if (!payload) {
				return;
			}

			isUpdating = true;

			if (lightSettingId && api(lightSettingId)) {
				api(lightSettingId).set(payload.light || '');
			}

			if (darkSettingId && api(darkSettingId)) {
				api(darkSettingId).set(payload.dark || '');
			}

			if (neutralSettingId && api(neutralSettingId)) {
				api(neutralSettingId).set(payload.json || '');
			}

			isUpdating = false;
			updateGrid();
		};

		const updateGrid = preview.initPayloadControl({
			api,
			grid: {
				api,
				settingId: neutralSettingId,
				container: grid,
				labels,
				classes: {
					rowClass: 'prismleaf-preview-row',
					swatchClass: 'prismleaf-preview-swatch',
					labelClass: 'prismleaf-preview-label',
				},
				bindings: [
					{ id: neutralSettingId },
				],
			},
			isUpdating: () => isUpdating,
			setIsUpdating: (value) => {
				isUpdating = value;
			},
			request: requestNeutralPayload,
			applyPayload,
			payloadWhenEmpty: { light: '', dark: '', json: '' },
			getValues: () => ({
				light: lightSettingId ? api(lightSettingId).get() : '',
				dark: darkSettingId ? api(darkSettingId).get() : '',
			}),
			requestBindings: [
				{ id: lightSettingId, source: 'light' },
				{ id: darkSettingId, source: 'dark' },
			],
		});

		updateGrid();
	};

	api.bind('ready', () => {
		api.control.each((control) => {
			if ('prismleaf_neutral_preview' === control.params.type) {
				initControl(control);
			}
		});
	});
})(window.wp && window.wp.customize, window.jQuery);
