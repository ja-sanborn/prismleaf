(function () {
	const STATES = [ 'auto', 'light', 'dark' ];
	const BUTTON_SELECTOR = '[data-prismleaf-theme-switch]';
	const button = document.querySelector( BUTTON_SELECTOR );

	if ( ! button || button.disabled ) {
		return;
	}

	const htmlElement = document.documentElement;
	const strings = window.prismleafThemeSwitchStrings || {};
	const labels = strings.labels || {};
	const actions = strings.actions || {};
	const storageKey = typeof strings.storageKey === 'string' && strings.storageKey ? strings.storageKey : 'prismleaf_theme_switch_mode';

	let storageAvailable = false;
	let storedMode = null;

	try {
		const testKey = `${ storageKey }:prismleaf`;
		window.localStorage.setItem( testKey, '1' );
		window.localStorage.removeItem( testKey );
		storageAvailable = true;
		storedMode = window.localStorage.getItem( storageKey );
	} catch ( error ) {
		storageAvailable = false;
	}

	let currentMode = STATES.includes( storedMode ) ? storedMode : 'auto';

	applyState( currentMode );

	button.addEventListener( 'click', () => {
		const nextIndex = ( STATES.indexOf( currentMode ) + 1 ) % STATES.length;
		currentMode = STATES[ nextIndex ];
		applyState( currentMode );
		storeMode( currentMode );
	} );

	function applyState( mode ) {
		if ( 'auto' === mode ) {
			htmlElement.removeAttribute( 'data-prismleaf-color-scheme' );
		} else {
			htmlElement.setAttribute( 'data-prismleaf-color-scheme', mode );
		}

		button.setAttribute( 'data-prismleaf-theme-switch-state', mode );

		const labelText = composeLabel( mode );
		if ( labelText ) {
			button.setAttribute( 'aria-label', labelText );
			button.setAttribute( 'title', labelText );
		}
	}

	function composeLabel( mode ) {
		const labelPart = labels[ mode ] || 'Theme mode';
		const actionPart = actions[ mode ] || 'Toggle appearance';
		return `${ labelPart }. ${ actionPart }.`;
	}

	function storeMode( mode ) {
		if ( ! storageAvailable ) {
			return;
		}

		if ( 'auto' === mode ) {
			window.localStorage.removeItem( storageKey );
			return;
		}

		window.localStorage.setItem( storageKey, mode );
	}
})();
