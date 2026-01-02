(function () {
	const trimValue = (input) => {
		if (!input) {
			return '';
		}
		return (input.value || '').trim();
	};

	const initSearch = (form) => {
		const input = form.querySelector('.prismleaf-search-input');
		const button = form.querySelector('.prismleaf-search-button');
		const isFlyout = '1' === form.dataset.flyout;

		if (!input || !button) {
			return;
		}

		const setOpen = (open) => {
			form.classList.toggle('prismleaf-search-open', open);
			button.setAttribute('aria-expanded', open ? 'true' : 'false');
			input.tabIndex = open ? 0 : -1;
			if (!open) {
				input.blur();
			}
		};

		if (isFlyout) {
			setOpen(false);

			const closeFlyout = () => {
				setOpen(false);
				button.focus();
			};

			const handleOutsideClick = (event) => {
				if (!form.classList.contains('prismleaf-search-open')) {
					return;
				}
				if (form.contains(event.target)) {
					return;
				}
				closeFlyout();
			};

			const handleEscape = (event) => {
				if ('Escape' !== event.key) {
					return;
				}
				if (!form.classList.contains('prismleaf-search-open')) {
					return;
				}
				event.preventDefault();
				closeFlyout();
			};

			button.addEventListener('click', (event) => {
				event.preventDefault();

				const isOpen = form.classList.contains('prismleaf-search-open');

				if (!isOpen) {
					setOpen(true);
					input.focus();
					return;
				}

				const value = trimValue(input);

				if ('' === value) {
					closeFlyout();
					return;
				}

				form.submit();
			});

			form.addEventListener('submit', (event) => {
				const value = trimValue(input);
				if ('' === value) {
					event.preventDefault();
					closeFlyout();
				}
			});

			document.addEventListener('click', handleOutsideClick);
			button.addEventListener('keydown', handleEscape);
			input.addEventListener('keydown', handleEscape);
		} else {
			input.tabIndex = 0;

			const updateButtonState = () => {
				const hasValue = '' !== trimValue(input);
				button.disabled = !hasValue;
			};

			updateButtonState();

			input.addEventListener('input', updateButtonState);
			input.addEventListener('change', updateButtonState);

			form.addEventListener('submit', (event) => {
				const value = trimValue(input);
				if ('' === value) {
					event.preventDefault();
					button.disabled = true;
				}
			});

			button.addEventListener('click', (event) => {
				event.preventDefault();
				const value = trimValue(input);
				if ('' === value) {
					button.disabled = true;
					return;
				}
				form.submit();
			});
		}
	};

	const init = () => {
		const forms = document.querySelectorAll('.prismleaf-search');
		forms.forEach(initSearch);
	};

	if ('loading' !== document.readyState) {
		init();
	} else {
		document.addEventListener('DOMContentLoaded', init);
	}
})();
