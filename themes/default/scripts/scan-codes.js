(function( $, undefined ) {


	$.lvs.register( 'scanCodes' )

		 .require( 'hotKeys' );



	var currentSequence = '',
		timeOut = 400,
		inputIv = null,
		allowedChars = 'abcdefghijklmnopqrstuvwxyABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789-.,_:;/*-+',

		$win = $( window );


	$.pushScanCode = function( code ) {

		var ev = $.Event( 'codeScan' );

		ev.code = code;
		$win.trigger( ev );
	};

	$win.on( 'keypress', function( e ) {

		var keyChar = String.fromCharCode( e.which );

		//Disable automatic code recognition when an element with text-input is focused
		if( $( ':focus' ).is( 'input, textarea' ) )
			return;

		//Disable code recognition when you might be using commands
		if( e.ctrlKey || e.altKey )
			return;

		//Only scan code-like characters
		if( allowedChars.indexOf( keyChar ) === -1 )
			return;

		if( inputIv )
			window.clearInterval( inputIv );

		e.preventDefault();

		currentSequence += keyChar;
		inputIv = window.setTimeout( function() {

			$.pushScanCode( currentSequence );
			currentSequence = '';
		}, timeOut );
	});



	$.hotKeyCode( 220 /* ^ */, function() {

		var code = window.prompt( 'Bitte einen Scan Code eingeben' );
		$.pushScanCode( code );
	});

})( jQuery );
