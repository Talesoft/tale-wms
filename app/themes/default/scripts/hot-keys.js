(function( $ ) {

	$.lvs.register( 'hotKeys' );

	var $win = $( window ),
		hotKeys = {},
		currentKeys = {},

		alt = false,
		ctrl = false,
		shift = false;

	$win.on( 'keydown', function( e ) {

		currentKeys[ e.which ] = true;

		var keyChar = String.fromCharCode( e.which );

		if( ctrl && ( keyChar in hotKeys ) )
			e.preventDefault();

		alt = e.altKey;
		ctrl = e.ctrlKey;
		shift = e.shiftKey;
	});

	$win.on( 'keyup', function( e ) {

		if( ( e.which in currentKeys ) )
			delete currentKeys[ e.which ];

		var keyChar = String.fromCharCode( e.which );

		if( ctrl )
			$.triggerHotKey.call( this, keyChar );

		alt = e.altKey;
		ctrl = e.ctrlKey;
		shift = e.shiftKey;
	});

	$.triggerHotKey = function( keyChar ) {

		if( ( keyChar in hotKeys ) ) {

			hotKeys[ keyChar ].call( this );
		}
	};

	$.triggerHotKeyCode = function( keyCode ) {

		$.triggerHotKey( String.fromCharCode( keyCode ) );
	};

	$.hotKey = function( keyChar, action ) {

		hotKeys[ keyChar ] = action;
	};

	$.hotKeyCode = function( keyCode, action ) {

		$.hotKey( String.fromCharCode( keyCode ), action );
	}

})( jQuery );