(function( $, undefined ) {

	$.lvs.register( 'auth' )

		 .require( 'scanCodes' );


	$.hotKey( 'A', function() {

		window.location.href = $.lvs.url( 'index/logout' );
	});

	$( window ).on( 'codeScan', function( e ) {

		if( e.code.match( /^LVS\-AUTH\-([0-9]{6})$/ ) ) {

			$.post( $.lvs.url( 'index/scan-code-login.json' ), { scanCode: e.code }, function( data ) {

				console.log( data );
				if( !data.success )
					window.alert( 
						"Authentifizierung fehlgeschlagen:\n"
					  + data.errors.join( "\n" )
					);
				else {

					window.location.href = $.lvs.url( 'index' );
				}
			});
		}

		if( e.code === 'LVS-CMD-LOGOUT' )
			$.triggerHotKey( 'A' );
	});

})( jQuery );