/**
 * Prismleaf Customizer Background Image Control.
 *
 * Handles media selection and composite field behavior.
 *
 * @param {Object}   api   The WordPress Customizer API.
 * @param {Object}   $     The jQuery object.
 * @param {Function} media The WordPress media frame factory.
 */

(function (api, $, media) {
	if (!api || !$ || !media) {
		return;
	}

	const CONTROL_SELECTOR = '.prismleaf-background-image-control';

	const setImageState = ($control, imageId, previewUrl) => {
		const $preview = $control.find('.prismleaf-background-image-preview');
		const $details = $control.find('.prismleaf-background-image-details');
		const $remove = $control.find('.prismleaf-background-image-remove');
		const $select = $control.find('.prismleaf-background-image-select');
		const hasImage = Number(imageId) > 0 && !!previewUrl;

		$preview.removeClass('has-image is-empty');

		if (hasImage) {
			$preview
				.addClass('has-image')
				.html(`<img src="${previewUrl}" alt="" />`);
			$details.prop('hidden', false);
			$remove.prop('hidden', false);
			$select.text('Change image');
			return;
		}

		const $placeholder = $preview.find(
			'.prismleaf-background-image-placeholder'
		);
		$preview.addClass('is-empty').empty().append($placeholder);
		$details.prop('hidden', true);
		$remove.prop('hidden', true);
		$select.text('Select image');
	};

	const setSettingValue = ($el, value) => {
		if (!$el.length) {
			return;
		}

		$el.val(value).trigger('input').trigger('change');
	};

	const applyPresetDefaults = ($control, preset) => {
		const maps = {
			default: {
				size: 'auto',
				repeat: 'repeat',
				attachment: 'scroll',
			},
			fill: {
				size: 'cover',
				repeat: 'no-repeat',
				attachment: 'scroll',
			},
			fit: {
				size: 'contain',
				repeat: 'no-repeat',
				attachment: 'scroll',
			},
			stretch: {
				size: 'stretch',
				repeat: 'no-repeat',
				attachment: 'scroll',
			},
			center: {
				size: 'auto',
				repeat: 'no-repeat',
				attachment: 'scroll',
			},
		};

		const selected = maps[preset] || maps.default;
		const $size = $control.find('.prismleaf-background-image-size');
		const $repeat = $control.find('.prismleaf-background-image-repeat');
		const $attachment = $control.find(
			'.prismleaf-background-image-attachment'
		);

		setSettingValue($size, selected.size);
		setSettingValue($repeat, selected.repeat);
		setSettingValue($attachment, selected.attachment);

		$control
			.find('.prismleaf-background-toggle-input[data-target="repeat"]')
			.prop('checked', selected.repeat === 'repeat');
		$control
			.find(
				'.prismleaf-background-toggle-input[data-target="attachment"]'
			)
			.prop('checked', selected.attachment === 'scroll');
	};

	const syncPositionButtons = ($control) => {
		const x = (
			$control.find('.prismleaf-background-position-x').val() || 'center'
		).toString();
		const y = (
			$control.find('.prismleaf-background-position-y').val() || 'center'
		).toString();

		$control
			.find('.prismleaf-background-position-button')
			.each((_, button) => {
				const $button = $(button);
				const isActive =
					$button.data('positionX') === x &&
					$button.data('positionY') === y;
				$button.attr('aria-pressed', isActive ? 'true' : 'false');
			});
	};

	const bindControl = (controlEl) => {
		const $control = $(controlEl);
		let frame = null;

		const $imageInput = $control.find('.prismleaf-background-image-input');
		const $preview = $control.find('.prismleaf-background-image-preview');
		const initialImageId = Number($imageInput.val() || 0);
		const initialImageUrl = $preview.find('img').attr('src') || '';

		setImageState($control, initialImageId, initialImageUrl);
		syncPositionButtons($control);

		$control.on('click', '.prismleaf-background-image-select', (event) => {
			event.preventDefault();

			if (!frame) {
				frame = media({
					title:
						window.prismleafBackgroundImageControl &&
						window.prismleafBackgroundImageControl.mediaTitle
							? window.prismleafBackgroundImageControl.mediaTitle
							: 'Select Background Image',
					button: {
						text:
							window.prismleafBackgroundImageControl &&
							window.prismleafBackgroundImageControl.mediaButton
								? window.prismleafBackgroundImageControl
										.mediaButton
								: 'Use image',
					},
					multiple: false,
				});

				frame.on('select', () => {
					const selection = frame.state().get('selection').first();
					if (!selection) {
						return;
					}

					const data = selection.toJSON();
					const imageId = Number(data.id || 0);
					const previewUrl =
						(data.sizes &&
							data.sizes.medium &&
							data.sizes.medium.url) ||
						data.url ||
						'';

					setSettingValue(
						$imageInput,
						imageId > 0 ? String(imageId) : ''
					);
					setImageState($control, imageId, previewUrl);
				});
			}

			frame.open();
		});

		$control.on('click', '.prismleaf-background-image-remove', (event) => {
			event.preventDefault();
			setSettingValue($imageInput, '');
			setImageState($control, 0, '');
		});

		$control.on(
			'click',
			'.prismleaf-background-position-button',
			function (event) {
				event.preventDefault();
				const $button = $(this);
				setSettingValue(
					$control.find('.prismleaf-background-position-x'),
					$button.data('positionX')
				);
				setSettingValue(
					$control.find('.prismleaf-background-position-y'),
					$button.data('positionY')
				);
				syncPositionButtons($control);
			}
		);

		$control.on(
			'change',
			'.prismleaf-background-toggle-input',
			function () {
				const $toggle = $(this);
				const target = $toggle.data('target');
				const trueValue = ($toggle.data('true') || '').toString();
				const falseValue = ($toggle.data('false') || '').toString();
				const nextValue = $toggle.is(':checked')
					? trueValue
					: falseValue;
				setSettingValue(
					$control.find(`.prismleaf-background-image-${target}`),
					nextValue
				);
			}
		);

		$control.on(
			'change',
			'.prismleaf-background-image-preset',
			function () {
				applyPresetDefaults($control, ($(this).val() || '').toString());
			}
		);

		$imageInput.on('change', () => {
			const imageId = Number($imageInput.val() || 0);
			const previewUrl = $preview.find('img').attr('src') || '';
			setImageState($control, imageId, previewUrl);
		});
	};

	api.bind('ready', () => {
		$(CONTROL_SELECTOR).each((_, controlEl) => bindControl(controlEl));
	});
})(
	window.wp && window.wp.customize,
	window.jQuery,
	window.wp && window.wp.media
);
