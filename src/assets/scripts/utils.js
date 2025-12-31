/* global wp */
( function( api ) {
	if ( ! api ) {
		return;
	}

	const INTERNAL_FLAG = {};

	/**
	 * Clamp a value between min and max.
	 * @param {number} value
	 * @param {number} min
	 * @param {number} max
	 * @return {number}
	 */
	const clamp = ( value, min, max ) => Math.min( Math.max( value, min ), max );

	/**
	 * Normalize a hex color to full 7-char form (#rrggbb). Returns null when invalid.
	 * @param {string} value
	 * @return {string|null}
	 */
	const normalizeHex = ( value ) => {
		if ( ! value ) {
			return null;
		}

		let hex = String( value ).trim().toLowerCase();

		if ( '' === hex ) {
			return null;
		}

		if ( '#' === hex.charAt( 0 ) ) {
			hex = hex.slice( 1 );
		}

		if ( 3 === hex.length ) {
			hex = hex
				.split( '' )
				.map( ( ch ) => ch + ch )
				.join( '' );
		}

		if ( 6 !== hex.length || ! /^[0-9a-f]{6}$/i.test( hex ) ) {
			return null;
		}

		return `#${ hex }`;
	};

	/**
	 * Convert hex to RGB array.
	 * @param {string} hex
	 * @return {number[]|null}
	 */
	const hexToRgb = ( hex ) => {
		const normalized = normalizeHex( hex );
		if ( ! normalized ) {
			return null;
		}

		return [
			parseInt( normalized.slice( 1, 3 ), 16 ),
			parseInt( normalized.slice( 3, 5 ), 16 ),
			parseInt( normalized.slice( 5, 7 ), 16 ),
		];
	};

	/**
	 * Convert RGB array to hex string.
	 * @param {number[]} rgb
	 * @return {string|null}
	 */
	const rgbToHex = ( rgb ) => {
		if ( ! Array.isArray( rgb ) || 3 !== rgb.length ) {
			return null;
		}

		const [ r, g, b ] = rgb.map( ( v ) => clamp( Math.round( v ), 0, 255 ) );
		return (
			'#' +
			[ r, g, b ]
				.map( ( v ) => v.toString( 16 ).padStart( 2, '0' ) )
				.join( '' )
		);
	};

	/**
	 * Convert RGB to HSL (0..1).
	 * @param {number[]} rgb
	 * @return {number[]|null}
	 */
	const rgbToHsl = ( rgb ) => {
		if ( ! Array.isArray( rgb ) || 3 !== rgb.length ) {
			return null;
		}

		let [ r, g, b ] = rgb.map( ( v ) => clamp( v, 0, 255 ) / 255 );

		const max = Math.max( r, g, b );
		const min = Math.min( r, g, b );
		let h = 0;
		let s = 0;
		const l = ( max + min ) / 2;

		if ( max !== min ) {
			const d = max - min;
			s = l > 0.5 ? d / ( 2 - max - min ) : d / ( max + min );

			switch ( max ) {
				case r:
					h = ( g - b ) / d + ( g < b ? 6 : 0 );
					break;
				case g:
					h = ( b - r ) / d + 2;
					break;
				default:
					h = ( r - g ) / d + 4;
			}

			h /= 6;
		}

		return [ h, s, l ];
	};

	/**
	 * Convert HSL (0..1) to RGB.
	 * @param {number[]} hsl
	 * @return {number[]|null}
	 */
	const hslToRgb = ( hsl ) => {
		if ( ! Array.isArray( hsl ) || 3 !== hsl.length ) {
			return null;
		}

		let [ h, s, l ] = hsl;

		h = clamp( h, 0, 1 );
		s = clamp( s, 0, 1 );
		l = clamp( l, 0, 1 );

		if ( 0 === s ) {
			const val = Math.round( l * 255 );
			return [ val, val, val ];
		}

		const q = l < 0.5 ? l * ( 1 + s ) : l + s - l * s;
		const p = 2 * l - q;

		const hueToRgb = ( t ) => {
			let temp = t;
			if ( temp < 0 ) {
				temp += 1;
			}
			if ( temp > 1 ) {
				temp -= 1;
			}
			if ( temp < 1 / 6 ) {
				return p + ( q - p ) * 6 * temp;
			}
			if ( temp < 1 / 2 ) {
				return q;
			}
			if ( temp < 2 / 3 ) {
				return p + ( q - p ) * ( 2 / 3 - temp ) * 6;
			}
			return p;
		};

		const r = hueToRgb( h + 1 / 3 );
		const g = hueToRgb( h );
		const b = hueToRgb( h - 1 / 3 );

		return [ r * 255, g * 255, b * 255 ];
	};

	/**
	 * Adjust lightness of a hex color in HSL space.
	 * @param {string} hex
	 * @param {number} delta Range -1..1
	 * @return {string|null}
	 */
	const adjustLightness = ( hex, delta ) => {
		const rgb = hexToRgb( hex );
		if ( ! rgb ) {
			return null;
		}

		const hsl = rgbToHsl( rgb );
		if ( ! hsl ) {
			return null;
		}

		hsl[ 2 ] = clamp( hsl[ 2 ] + delta, 0, 1 );

		const newRgb = hslToRgb( hsl );
		return newRgb ? normalizeHex( rgbToHex( newRgb ) ) : null;
	};

	/**
	 * Derive dark base from a light base hex.
	 * Matches prismleaf_derive_dark_base_from_light PHP.
	 * @param {string} lightHex
	 * @return {string|null}
	 */
	const deriveDarkFromLight = ( lightHex ) => adjustLightness( lightHex, -0.3 );

	/**
	 * Bind a light/dark setting pair for auto-derivation and clearing.
	 * @param {string} lightId
	 * @param {string} darkId
	 */
	const bindLightDarkPair = ( lightId, darkId ) => {
		if ( ! api.has( lightId ) || ! api.has( darkId ) ) {
			return;
		}

		const lightSetting = api( lightId );
		const darkSetting = api( darkId );

		let isInternal = false;

		const setInternal = ( setting, value ) => {
			isInternal = true;
			setting.set( value );
			isInternal = false;
		};

		// When light changes: derive dark or clear both.
		lightSetting.bind( ( value ) => {
			if ( isInternal ) {
				return;
			}

			const normalized = normalizeHex( value );

			if ( ! normalized ) {
				setInternal( lightSetting, '' );
				setInternal( darkSetting, '' );
				return;
			}

			const derived = deriveDarkFromLight( normalized ) || '';
			setInternal( lightSetting, normalized );
			setInternal( darkSetting, derived );
		} );

		// When dark is cleared, re-derive from light if possible.
		darkSetting.bind( ( value ) => {
			if ( isInternal ) {
				return;
			}

			const normalized = normalizeHex( value );

			if ( null !== normalized ) {
				// Valid custom dark entered; keep it.
				setInternal( darkSetting, normalized );
				return;
			}

			// Cleared/invalid: re-derive from light or clear if light is empty.
			const normalizedLight = normalizeHex( lightSetting.get() );
			if ( ! normalizedLight ) {
				setInternal( darkSetting, '' );
				return;
			}

			const derived = deriveDarkFromLight( normalizedLight ) || '';
			setInternal( darkSetting, derived );
		} );
	};

	api.bind( 'ready', () => {
		// Brand roles.
		const roles = [ 'primary', 'secondary', 'tertiary', 'error', 'warning', 'info' ];
		roles.forEach( ( role ) => {
			bindLightDarkPair(
				`prismleaf_brand_${ role }_light`,
				`prismleaf_brand_${ role }_dark`
			);
		} );

		// Site metadata title/tagline.
		bindLightDarkPair(
			'prismleaf_site_metadata_title_color_light',
			'prismleaf_site_metadata_title_color_dark'
		);
		bindLightDarkPair(
			'prismleaf_site_metadata_tagline_color_light',
			'prismleaf_site_metadata_tagline_color_dark'
		);
	} );
} )( window.wp && window.wp.customize );
