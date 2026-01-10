/**
 * Prismleaf Customizer Preview
 *
 * Live preview refresh bindings for Customizer settings.
 *
 * @package prismleaf
 */

(function (api) {
	if (!api) {
		return;
	}

	const bindRefreshSetting = (id) => {
		api(id, (setting) => {
			setting.bind(() => {
				api.previewer.refresh();
			});
		});
	};

	const initBindings = () => {
	};

	api.bind('ready', initBindings);
})(window.wp && window.wp.customize);
