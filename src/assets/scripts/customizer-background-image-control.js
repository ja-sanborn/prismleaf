( function( $ ) {
	if ( typeof wp === 'undefined' || ! wp.customize ) {
		return;
	}

	wp.customize.controlConstructor.prismleaf_background_image = wp.customize.Control.extend( {
		/**
		 * Initialize the control.
		 *
		 * @returns {void}
		 */
		ready: function() {
			var control = this;
			var container = control.container;
			var frame;
			var hiddenInput = container.find( '.prismleaf-background-image-input' );
			var preview = container.find( '.prismleaf-background-image-preview' );
			var previewImg = preview.find( 'img' );
			var details = container.find( '.prismleaf-background-image-details' );
			var removeButton = container.find( '.prismleaf-background-image-remove' );
			var repeatValue = container.find( '.prismleaf-background-image-repeat' );
			var attachmentValue = container.find( '.prismleaf-background-image-attachment' );
			var positionX = container.find( '.prismleaf-background-position-x' );
			var positionY = container.find( '.prismleaf-background-position-y' );
			var positionButtons = container.find( '.prismleaf-background-position-button' );

			if ( ! hiddenInput.length ) {
				return;
			}

			var updatePreviewState = function( hasImage ) {
				preview.toggleClass( 'has-image', Boolean( hasImage ) );
				preview.toggleClass( 'is-empty', ! hasImage );
			};

			var toggleDetails = function() {
				var hasImage = $.trim( hiddenInput.val() );

				if ( hasImage ) {
					details.removeAttr( 'hidden' );
					removeButton.removeAttr( 'hidden' );
				} else {
					details.attr( 'hidden', 'hidden' );
					removeButton.attr( 'hidden', 'hidden' );
				}
			};

			var updatePositionState = function() {
				var x = positionX.val() || 'center';
				var y = positionY.val() || 'center';

				positionButtons.each( function() {
					var button = $( this );
					var matches = button.data( 'position-x' ) === x && button.data( 'position-y' ) === y;
					button.toggleClass( 'is-active', matches );
					button.attr( 'aria-pressed', matches ? 'true' : 'false' );
				} );
			};

			var updatePreviewImage = function( url ) {
				if ( url ) {
					if ( previewImg.length ) {
						previewImg.attr( 'src', url );
					} else {
						preview.html( '<img src="' + url + '" alt="" />' );
						previewImg = preview.find( 'img' );
					}
					updatePreviewState( true );
				} else if ( previewImg.length ) {
					previewImg.remove();
					previewImg = $();
					updatePreviewState( false );
				}
			};

			container.on( 'click', '.prismleaf-background-image-select', function( event ) {
				event.preventDefault();

				if ( frame ) {
					frame.open();
					return;
				}

				var strings = window.prismleafBackgroundImageControl || {};
				frame = wp.media( {
					title: strings.mediaTitle || '',
					library: {
						type: 'image',
					},
					button: {
						text: strings.mediaButton || '',
					},
					multiple: false,
				} );

				frame.on( 'select', function() {
					var selection = frame.state().get( 'selection' ).first();
					var data = selection.toJSON();

					if ( ! data ) {
						return;
					}

				hiddenInput.val( data.id ).trigger( 'change' );
				updatePreviewImage( data.url );
				toggleDetails();
				} );

				frame.open();
			} );

			container.on( 'click', '.prismleaf-background-image-remove', function( event ) {
				event.preventDefault();

				hiddenInput.val( '' ).trigger( 'change' );
				updatePreviewImage( '' );
				positionX.val( 'center' ).trigger( 'change' );
				positionY.val( 'center' ).trigger( 'change' );
				repeatValue.val( 'no-repeat' ).trigger( 'change' );
				attachmentValue.val( 'scroll' ).trigger( 'change' );
				updatePositionState();
				toggleDetails();
			} );

			container.on( 'click', '.prismleaf-background-position-button', function( event ) {
				event.preventDefault();

				var button = $( this );
				var x = button.data( 'position-x' );
				var y = button.data( 'position-y' );

				positionX.val( x ).trigger( 'change' );
				positionY.val( y ).trigger( 'change' );
				updatePositionState();
			} );

			container.on( 'change', '.prismleaf-background-toggle-input', function() {
				var input = $( this );
				var target = input.data( 'target' );
				var value = input.is( ':checked' ) ? input.data( 'true' ) : input.data( 'false' );
				var hidden = container.find( '.prismleaf-background-image-' + target );

				if ( hidden.length ) {
					hidden.val( value ).trigger( 'change' );
				}
			} );

			hiddenInput.on( 'change', function() {
				toggleDetails();
			} );

			positionX.on( 'change', updatePositionState );
			positionY.on( 'change', updatePositionState );

			updatePositionState();
			toggleDetails();
			updatePreviewState( Boolean( $.trim( hiddenInput.val() ) ) );
		},
	} );
} )( jQuery );
