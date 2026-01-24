/* global jQuery */

(function ($) {
	if (typeof wp === 'undefined' || !wp.customize) {
		return;
	}

	wp.customize.controlConstructor.prismleaf_background_image =
		wp.customize.Control.extend({
			/**
			 * Initialize the control.
			 *
			 * @return {void}
			 */
			ready() {
				const control = this;
				const container = control.container;
				const hiddenInput = container.find(
					'.prismleaf-background-image-input'
				);

				if (!hiddenInput.length) {
					return;
				}

				let frame;
				const preview = container.find(
					'.prismleaf-background-image-preview'
				);
				let previewImg = preview.find('img');
				const details = container.find(
					'.prismleaf-background-image-details'
				);
				const removeButton = container.find(
					'.prismleaf-background-image-remove'
				);
				const repeatValue = container.find(
					'.prismleaf-background-image-repeat'
				);
				const attachmentValue = container.find(
					'.prismleaf-background-image-attachment'
				);
				const positionX = container.find(
					'.prismleaf-background-position-x'
				);
				const positionY = container.find(
					'.prismleaf-background-position-y'
				);
				const positionButtons = container.find(
					'.prismleaf-background-position-button'
				);

				const updatePreviewState = function (hasImage) {
					preview.toggleClass('has-image', Boolean(hasImage));
					preview.toggleClass('is-empty', !hasImage);
				};

				const toggleDetails = function () {
					const hasImage = $.trim(hiddenInput.val());

					if (hasImage) {
						details.removeAttr('hidden');
						removeButton.removeAttr('hidden');
					} else {
						details.attr('hidden', 'hidden');
						removeButton.attr('hidden', 'hidden');
					}
				};

				const updatePositionState = function () {
					const x = positionX.val() || 'center';
					const y = positionY.val() || 'center';

					positionButtons.each(function () {
						const button = $(this);
						const matches =
							button.data('position-x') === x &&
							button.data('position-y') === y;
						button.toggleClass('is-active', matches);
						button.attr('aria-pressed', matches ? 'true' : 'false');
					});
				};

				const updatePreviewImage = function (url) {
					if (url) {
						if (previewImg.length) {
							previewImg.attr('src', url);
						} else {
							preview.html('<img src="' + url + '" alt="" />');
							previewImg = preview.find('img');
						}
						updatePreviewState(true);
					} else if (previewImg.length) {
						previewImg.remove();
						previewImg = $();
						updatePreviewState(false);
					}
				};

				container.on(
					'click',
					'.prismleaf-background-image-select',
					function (event) {
						event.preventDefault();

						if (frame) {
							frame.open();
							return;
						}

						const strings =
							window.prismleafBackgroundImageControl || {};
						frame = wp.media({
							title: strings.mediaTitle || '',
							library: {
								type: 'image',
							},
							button: {
								text: strings.mediaButton || '',
							},
							multiple: false,
						});

						frame.on('select', function () {
							const selection = frame
								.state()
								.get('selection')
								.first();
							const data = selection.toJSON();

							if (!data) {
								return;
							}

							hiddenInput.val(data.id).trigger('change');
							updatePreviewImage(data.url);
							toggleDetails();
						});

						frame.open();
					}
				);

				container.on(
					'click',
					'.prismleaf-background-image-remove',
					function (event) {
						event.preventDefault();

						hiddenInput.val('').trigger('change');
						updatePreviewImage('');
						positionX.val('center').trigger('change');
						positionY.val('center').trigger('change');
						repeatValue.val('no-repeat').trigger('change');
						attachmentValue.val('scroll').trigger('change');
						updatePositionState();
						toggleDetails();
					}
				);

				container.on(
					'click',
					'.prismleaf-background-position-button',
					function (event) {
						event.preventDefault();

						const button = $(this);
						const x = button.data('position-x');
						const y = button.data('position-y');

						positionX.val(x).trigger('change');
						positionY.val(y).trigger('change');
						updatePositionState();
					}
				);

				container.on(
					'change',
					'.prismleaf-background-toggle-input',
					function () {
						const input = $(this);
						const target = input.data('target');
						const value = input.is(':checked')
							? input.data('true')
							: input.data('false');
						const hidden = container.find(
							'.prismleaf-background-image-' + target
						);

						if (hidden.length) {
							hidden.val(value).trigger('change');
						}
					}
				);

				hiddenInput.on('change', function () {
					toggleDetails();
				});

				positionX.on('change', updatePositionState);
				positionY.on('change', updatePositionState);

				updatePositionState();
				toggleDetails();
				updatePreviewState(Boolean($.trim(hiddenInput.val())));
			},
		});
})(jQuery);
