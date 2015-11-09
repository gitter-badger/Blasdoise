window.bd = window.bd || {};

( function ( bd, $ ) {
	'use strict';

	var $containerPolite,
		$containerAssertive,
		role;

	/**
	 * Update the ARIA live notification area text node.
	 *
	 * @since 1.0.0
	 *
	 * @param {String} message  The message to be announced by Assistive Technologies.
	 * @param {String} ariaLive Optional. The politeness level for aria-live. Possible values:
	 *                          polite or assertive. Default polite.
	 */
	function speak( message, ariaLive ) {
		// Clear previous messages to allow repeated strings being read out.
		clear();

		if ( $containerAssertive && 'assertive' === ariaLive ) {
			$containerAssertive.text( message );
		} else if ( $containerPolite ) {
			$containerPolite.text( message );
		}
	}

	/**
	 * Build the live regions markup.
	 *
	 * @since 1.0.0
	 *
	 * @param {String} ariaLive Optional. Value for the 'aria-live' attribute, default 'polite'.
	 *
	 * @return {Object} $container The ARIA live region jQuery object.
	 */
	function addContainer( ariaLive ) {
		ariaLive = ariaLive || 'polite';
		role = 'assertive' === ariaLive ? 'alert' : 'status';

		var $container = $( '<div>', {
			'id': 'bd-a11y-speak-' + ariaLive,
			'role': role,
			'aria-live': ariaLive,
			'aria-relevant': 'additions text',
			'aria-atomic': 'true',
			'class': 'screen-reader-text bd-a11y-speak-region'
		});

		$( document.body ).append( $container );
		return $container;
	}

	/**
	 * Clear the live regions.
	 *
	 * @since 1.0.0
	 */
	function clear() {
		$( '.bd-a11y-speak-region' ).text( '' );
	}

	/**
	 * Initialize bd.a11y and define ARIA live notification area.
	 *
	 * @since 1.0.0
	 */
	$( document ).ready( function() {
		$containerPolite = $( '#bd-a11y-speak-polite' );
		$containerAssertive = $( '#bd-a11y-speak-assertive' );

		if ( ! $containerPolite.length ) {
			$containerPolite = addContainer( 'polite' );
		}

		if ( ! $containerAssertive.length ) {
			$containerAssertive = addContainer( 'assertive' );
		}
	});

	bd.a11y = bd.a11y || {};
	bd.a11y.speak = speak;

}( window.bd, window.jQuery ));
