/**
 * Prismleaf Customizer Preview
 *
 * Live preview refresh bindings for Customizer settings.
 *
 * @param {wp.customize.API} api
 * @package
 */

(function (api) {
	if (!api) {
		return;
	}

	const initBindings = () => {};

	api.bind('ready', initBindings);
})(window.wp && window.wp.customize);
