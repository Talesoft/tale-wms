(function( $, undefined ) {

	$.lvs = {

		options: null,
		features: [],

		register: function( name ) {

			if( !this.options )
				throw new Error( "Failed to register module: Please pass the LVS options object to $.lvs.options first" );

			this.features.push( name );

			return this;
		},

		require: function( name ) {

			if( this.features.indexOf( name ) === -1 )
				throw new Error( "Library requires feature " + name );
		},

		url: function( subUrl ) {

			var url = this.options.baseUrl;

			if( subUrl )
				url += '/' + subUrl.replace( /^\/*/, '' );

			return url;
		}
	};

	//jQuery extensions
	$.fn.disable = function() {

		var $targets = $( this ).find( 'a, input, select, textarea, button' );

		if( $( this ).is( 'a, input, select, textarea, button' ) )
			$targets = $targets.add( $( this ) );

		return $targets.each( function() {

			var $el = $( this );

			if( $el.is( 'a' ) ) {

				$el.data( 'href', $el.attr( 'href' ) )
				   .removeAttr( 'href' );
			}

			$( this ).addClass( 'disabled' )
					 .attr( 'disabled', 'disabled' );
		});
	};

	$.fn.enable = function() {

		var $targets = $( this ).find( 'a, input, select, textarea, button' );

		if( $( this ).is( 'a, input, select, textarea, button' ) )
			$targets = $targets.add( $( this ) );

		return $targets.each( function() {

			var $el = $( this );

			if( $el.is( 'a' ) ) {

				$el.attr( 'href', $el.data( 'href' ) );
			}

			$( this ).removeClass( 'disabled' )
					 .removeAttr( 'disabled' );
		});
	};

})( jQuery );