/**
 * Live-update changed settings in real time in the Customizer preview.
 */

( function( $ ) {
	var $style = $( '#fairy-color-scheme-css' ),
		api = bd.customize;

	if ( ! $style.length ) {
		$style = $( 'head' ).append( '<style type="text/css" id="fairy-color-scheme-css" />' )
		                    .find( '#fairy-color-scheme-css' );
	}

	// Site title.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Site tagline.
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Color Scheme CSS.
	api.bind( 'preview-ready', function() {
		api.preview.bind( 'update-color-scheme-css', function( css ) {
			$style.html( css );
		} );
	} );

} )( jQuery );
