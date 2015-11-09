/* global tb_remove */
window.bd = window.bd || {};

(function( $, bd, pagenow ) {
	bd.updates = {};

	/**
	 * User nonce for ajax calls.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	bd.updates.ajaxNonce = window._bdUpdatesSettings.ajax_nonce;

	/**
	 * Localized strings.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	bd.updates.l10n = window._bdUpdatesSettings.l10n;

	/**
	 * Whether filesystem credentials need to be requested from the user.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	bd.updates.shouldRequestFilesystemCredentials = null;

	/**
	 * Filesystem credentials to be packaged along with the request.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	bd.updates.filesystemCredentials = {
		ftp: {
			host: null,
			username: null,
			password: null,
			connectionType: null
		},
		ssh: {
			publicKey: null,
			privateKey: null
		}
	};

	/**
	 * Flag if we're waiting for an update to complete.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	bd.updates.updateLock = false;

	/**
	 * * Flag if we've done an update successfully.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	bd.updates.updateDoneSuccessfully = false;

	/**
	 * If the user tries to update a plugin while an update is
	 * already happening, it can be placed in this queue to perform later.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	bd.updates.updateQueue = [];

	/**
	 * Store a jQuery reference to return focus to when exiting the request credentials modal.
	 *
	 * @since 1.0.0
	 *
	 * @var jQuery object
	 */
	bd.updates.$elToReturnFocusToFromCredentialsModal = null;

	/**
	 * Decrement update counts throughout the various menus.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} updateType
	 */
	bd.updates.decrementCount = function( upgradeType ) {
		var count,
			pluginCount,
			$adminBarUpdateCount = $( '#bd-admin-bar-updates .ab-label' ),
			$dashboardNavMenuUpdateCount = $( 'a[href="update-core.php"] .update-plugins' ),
			$pluginsMenuItem = $( '#menu-plugins' );


		count = $adminBarUpdateCount.text();
		count = parseInt( count, 10 ) - 1;
		if ( count < 0 || isNaN( count ) ) {
			return;
		}
		$( '#bd-admin-bar-updates .ab-item' ).removeAttr( 'title' );
		$adminBarUpdateCount.text( count );


		$dashboardNavMenuUpdateCount.each( function( index, elem ) {
			elem.className = elem.className.replace( /count-\d+/, 'count-' + count );
		} );
		$dashboardNavMenuUpdateCount.removeAttr( 'title' );
		$dashboardNavMenuUpdateCount.find( '.update-count' ).text( count );

		if ( 'plugin' === upgradeType ) {
			pluginCount = $pluginsMenuItem.find( '.plugin-count' ).eq(0).text();
			pluginCount = parseInt( pluginCount, 10 ) - 1;
			if ( pluginCount < 0 || isNaN( pluginCount ) ) {
				return;
			}
			$pluginsMenuItem.find( '.plugin-count' ).text( pluginCount );
			$pluginsMenuItem.find( '.update-plugins' ).each( function( index, elem ) {
				elem.className = elem.className.replace( /count-\d+/, 'count-' + pluginCount );
			} );

			if (pluginCount > 0 ) {
				$( '.subsubsub .upgrade .count' ).text( '(' + pluginCount + ')' );
			} else {
				$( '.subsubsub .upgrade' ).remove();
			}
		}
	};

	/**
	 * Send an Ajax request to the server to update a plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} plugin
	 * @param {string} slug
	 */
	bd.updates.updatePlugin = function( plugin, slug ) {
		var $message, name;
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$message = $( '[data-slug="' + slug + '"]' ).next().find( '.update-message' );
		} else if ( 'plugin-install' === pagenow ) {
			$message = $( '.plugin-card-' + slug ).find( '.update-now' );
			name = $message.data( 'name' );
			$message.attr( 'aria-label', bd.updates.l10n.updatingLabel.replace( '%s', name ) );
		}

		$message.addClass( 'updating-message' );
		if ( $message.html() !== bd.updates.l10n.updating ){
			$message.data( 'originaltext', $message.html() );
		}

		$message.text( bd.updates.l10n.updating );
		bd.a11y.speak( bd.updates.l10n.updatingMsg );

		if ( bd.updates.updateLock ) {
			bd.updates.updateQueue.push( {
				type: 'update-plugin',
				data: {
					plugin: plugin,
					slug: slug
				}
			} );
			return;
		}

		bd.updates.updateLock = true;

		var data = {
			_ajax_nonce:     bd.updates.ajaxNonce,
			plugin:          plugin,
			slug:            slug,
			username:        bd.updates.filesystemCredentials.ftp.username,
			password:        bd.updates.filesystemCredentials.ftp.password,
			hostname:        bd.updates.filesystemCredentials.ftp.hostname,
			connection_type: bd.updates.filesystemCredentials.ftp.connectionType,
			public_key:      bd.updates.filesystemCredentials.ssh.publicKey,
			private_key:     bd.updates.filesystemCredentials.ssh.privateKey
		};

		bd.ajax.post( 'update-plugin', data )
			.done( bd.updates.updateSuccess )
			.fail( bd.updates.updateError );
	};

	/**
	 * On a successful plugin update, update the UI with the result.
	 *
	 * @since 1.0.0
	 *
	 * @param {object} response
	 */
	bd.updates.updateSuccess = function( response ) {
		var $updateMessage, name, $pluginRow, newText;
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$pluginRow = $( '[data-slug="' + response.slug + '"]' ).first();
			$updateMessage = $pluginRow.next().find( '.update-message' );
			$pluginRow.addClass( 'updated' ).removeClass( 'update' );

			// Update the version number in the row.
			newText = $pluginRow.find('.plugin-version-author-uri').html().replace( response.oldVersion, response.newVersion );
			$pluginRow.find('.plugin-version-author-uri').html( newText );

			// Add updated class to update message parent tr
			$pluginRow.next().addClass( 'updated' );
		} else if ( 'plugin-install' === pagenow ) {
			$updateMessage = $( '.plugin-card-' + response.slug ).find( '.update-now' );
			$updateMessage.addClass( 'button-disabled' );
			name = $updateMessage.data( 'name' );
			$updateMessage.attr( 'aria-label', bd.updates.l10n.updatedLabel.replace( '%s', name ) );
		}

		$updateMessage.removeClass( 'updating-message' ).addClass( 'updated-message' );
		$updateMessage.text( bd.updates.l10n.updated );
		bd.a11y.speak( bd.updates.l10n.updatedMsg );

		bd.updates.decrementCount( 'plugin' );

		bd.updates.updateDoneSuccessfully = true;

		/*
		 * The lock can be released since the update was successful,
		 * and any other updates can commence.
		 */
		bd.updates.updateLock = false;

		$(document).trigger( 'bd-plugin-update-success', response );

		bd.updates.queueChecker();
	};


	/**
	 * On a plugin update error, update the UI appropriately.
	 *
	 * @since 1.0.0
	 *
	 * @param {object} response
	 */
	bd.updates.updateError = function( response ) {
		var $message, name;
		bd.updates.updateDoneSuccessfully = false;
		if ( response.errorCode && response.errorCode == 'unable_to_connect_to_filesystem' && bd.updates.shouldRequestFilesystemCredentials ) {
			bd.updates.credentialError( response, 'update-plugin' );
			return;
		}
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$message = $( '[data-slug="' + response.slug + '"]' ).next().find( '.update-message' );
		} else if ( 'plugin-install' === pagenow ) {
			$message = $( '.plugin-card-' + response.slug ).find( '.update-now' );

			name = $message.data( 'name' );
			$message.attr( 'aria-label', bd.updates.l10n.updateFailedLabel.replace( '%s', name ) );
		}
		$message.removeClass( 'updating-message' );
		$message.html( bd.updates.l10n.updateFailed.replace( '%s', response.error ) );
		bd.a11y.speak( bd.updates.l10n.updateFailed );

		/*
		 * The lock can be released since this failure was
		 * after the credentials form.
		 */
		bd.updates.updateLock = false;

		$(document).trigger( 'bd-plugin-update-error', response );

		bd.updates.queueChecker();
	};

	/**
	 * Show an error message in the request for credentials form.
	 *
	 * @param {string} message
	 * @since 1.0.0
	 */
	bd.updates.showErrorInCredentialsForm = function( message ) {
		var $modal = $( '.notification-dialog' );

		// Remove any existing error.
		$modal.find( '.error' ).remove();

		$modal.find( 'h3' ).after( '<div class="error">' + message + '</div>' );
	};

	/**
	 * Events that need to happen when there is a credential error
	 *
	 * @since 1.0.0
	 */
	bd.updates.credentialError = function( response, type ) {
		bd.updates.updateQueue.push( {
			'type': type,
			'data': {
				// Not cool that we're depending on response for this data.
				// This would feel more whole in a view all tied together.
				plugin: response.plugin,
				slug: response.slug
			}
		} );
		bd.updates.showErrorInCredentialsForm( response.error );
		bd.updates.requestFilesystemCredentials();
	};

	/**
	 * If an update job has been placed in the queue, queueChecker pulls it out and runs it.
	 *
	 * @since 1.0.0
	 */
	bd.updates.queueChecker = function() {
		if ( bd.updates.updateLock || bd.updates.updateQueue.length <= 0 ) {
			return;
		}

		var job = bd.updates.updateQueue.shift();

		bd.updates.updatePlugin( job.data.plugin, job.data.slug );
	};


	/**
	 * Request the users filesystem credentials if we don't have them already.
	 *
	 * @since 1.0.0
	 */
	bd.updates.requestFilesystemCredentials = function( event ) {
		if ( bd.updates.updateDoneSuccessfully === false ) {
			/*
			 * For the plugin install screen, return the focus to the install button
			 * after exiting the credentials request modal.
			 */
			if ( 'plugin-install' === pagenow && event ) {
				bd.updates.$elToReturnFocusToFromCredentialsModal = $( event.target );
			}

			bd.updates.updateLock = true;

			bd.updates.requestForCredentialsModalOpen();
		}
	};

	/**
	 * Keydown handler for the request for credentials modal.
	 *
	 * Close the modal when the escape key is pressed.
	 * Constrain keyboard navigation to inside the modal.
	 *
	 * @since 1.0.0
	 */
	bd.updates.keydown = function( event ) {
		if ( 27 === event.keyCode ) {
			bd.updates.requestForCredentialsModalCancel();
		} else if ( 9 === event.keyCode ) {
			// #upgrade button must always be the last focusable element in the dialog.
			if ( event.target.id === 'upgrade' && ! event.shiftKey ) {
				$( '#hostname' ).focus();
				event.preventDefault();
			} else if ( event.target.id === 'hostname' && event.shiftKey ) {
				$( '#upgrade' ).focus();
				event.preventDefault();
			}
		}
	};

	/**
	 * Open the request for credentials modal.
	 *
	 * @since 1.0.0
	 */
	bd.updates.requestForCredentialsModalOpen = function() {
		var $modal = $( '#request-filesystem-credentials-dialog' );
		$( 'body' ).addClass( 'modal-open' );
		$modal.show();

		$modal.find( 'input:enabled:first' ).focus();
		$modal.keydown( bd.updates.keydown );
	};

	/**
	 * Close the request for credentials modal.
	 *
	 * @since 1.0.0
	 */
	bd.updates.requestForCredentialsModalClose = function() {
		$( '#request-filesystem-credentials-dialog' ).hide();
		$( 'body' ).removeClass( 'modal-open' );
		bd.updates.$elToReturnFocusToFromCredentialsModal.focus();
	};

	/**
	 * The steps that need to happen when the modal is canceled out
	 *
	 * @since 1.0.0
	 */
	bd.updates.requestForCredentialsModalCancel = function() {
		// no updateLock and no updateQueue means we already have cleared things up
		var slug, $message;

		if( bd.updates.updateLock === false && bd.updates.updateQueue.length === 0 ){
			return;
		}

		slug = bd.updates.updateQueue[0].data.slug,

		// remove the lock, and clear the queue
		bd.updates.updateLock = false;
		bd.updates.updateQueue = [];

		bd.updates.requestForCredentialsModalClose();
		if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
			$message = $( '[data-slug="' + slug + '"]' ).next().find( '.update-message' );
		} else if ( 'plugin-install' === pagenow ) {
			$message = $( '.plugin-card-' + slug ).find( '.update-now' );
		}

		$message.removeClass( 'updating-message' );
		$message.html( $message.data( 'originaltext' ) );
		bd.a11y.speak( bd.updates.l10n.updateCancel );
	};
	/**
	 * Potentially add an AYS to a user attempting to leave the page
	 *
	 * If an update is on-going and a user attempts to leave the page,
	 * open an "Are you sure?" alert.
	 *
	 * @since 1.0.0
	 */

	bd.updates.beforeunload = function() {
		if ( bd.updates.updateLock ) {
			return bd.updates.l10n.beforeunload;
		}
	};


	$( document ).ready( function() {
		/*
		 * Check whether a user needs to submit filesystem credentials based on whether
		 * the form was output on the page server-side.
		 *
		 * @see {bd_print_request_filesystem_credentials_modal() in PHP}
		 */
		bd.updates.shouldRequestFilesystemCredentials = ( $( '#request-filesystem-credentials-dialog' ).length <= 0 ) ? false : true;

		// File system credentials form submit noop-er / handler.
		$( '#request-filesystem-credentials-dialog form' ).on( 'submit', function() {
			// Persist the credentials input by the user for the duration of the page load.
			bd.updates.filesystemCredentials.ftp.hostname = $('#hostname').val();
			bd.updates.filesystemCredentials.ftp.username = $('#username').val();
			bd.updates.filesystemCredentials.ftp.password = $('#password').val();
			bd.updates.filesystemCredentials.ftp.connectionType = $('input[name="connection_type"]:checked').val();
			bd.updates.filesystemCredentials.ssh.publicKey = $('#public_key').val();
			bd.updates.filesystemCredentials.ssh.privateKey = $('#private_key').val();

			bd.updates.requestForCredentialsModalClose();

			// Unlock and invoke the queue.
			bd.updates.updateLock = false;
			bd.updates.queueChecker();

			return false;
		});

		// Close the request credentials modal when
		$( '#request-filesystem-credentials-dialog [data-js-action="close"], .notification-dialog-background' ).on( 'click', function() {
			bd.updates.requestForCredentialsModalCancel();
		});

		// Hide SSH fields when not selected
		$( '#request-filesystem-credentials-dialog input[name="connection_type"]' ).on( 'change', function() {
			$( this ).parents( 'form' ).find( '#private_key, #public_key' ).parents( 'label' ).toggle( ( 'ssh' == $( this ).val() ) );
		}).change();

		// Click handler for plugin updates in List Table view.
		$( '.plugin-update-tr' ).on( 'click', '.update-link', function( e ) {
			e.preventDefault();
			if ( bd.updates.shouldRequestFilesystemCredentials && ! bd.updates.updateLock ) {
				bd.updates.requestFilesystemCredentials( e );
			}
			var updateRow = $( e.target ).parents( '.plugin-update-tr' );
			// Return the user to the input box of the plugin's table row after closing the modal.
			bd.updates.$elToReturnFocusToFromCredentialsModal = $( '#' + updateRow.data( 'slug' ) ).find( '.check-column input' );
			bd.updates.updatePlugin( updateRow.data( 'plugin' ), updateRow.data( 'slug' ) );
		} );

		$( '.plugin-card' ).on( 'click', '.update-now', function( e ) {
			e.preventDefault();
			var $button = $( e.target );

			if ( bd.updates.shouldRequestFilesystemCredentials && ! bd.updates.updateLock ) {
				bd.updates.requestFilesystemCredentials( e );
			}

			bd.updates.updatePlugin( $button.data( 'plugin' ), $button.data( 'slug' ) );
		} );

		$( '#plugin_update_from_iframe' ).on( 'click' , function( e ) {
			var target,	data;

			target = window.parent == window ? null : window.parent,
			$.support.postMessage = !! window.postMessage;

			if ( $.support.postMessage === false || target === null || window.parent.location.pathname.indexOf( 'update-core.php' ) !== -1 )
				return;

			e.preventDefault();

			data = {
				'action' : 'updatePlugin',
				'slug'	 : $(this).data('slug')
			};

			target.postMessage( JSON.stringify( data ), window.location.origin );
		});

	} );

	$( window ).on( 'message', function( e ) {
		var event = e.originalEvent,
			message,
			loc = document.location,
			expectedOrigin = loc.protocol + '//' + loc.hostname;

		if ( event.origin !== expectedOrigin ) {
			return;
		}

		message = $.parseJSON( event.data );

		if ( typeof message.action === 'undefined' ) {
			return;
		}

		switch (message.action){
			case 'decrementUpdateCount' :
				bd.updates.decrementCount( message.upgradeType );
				break;
			case 'updatePlugin' :
				tb_remove();
				if ( 'plugins' === pagenow || 'plugins-network' === pagenow ) {
					// Return the user to the input box of the plugin's table row after closing the modal.
					$( '#' + message.slug ).find( '.check-column input' ).focus();
					// trigger the update
					$( '.plugin-update-tr[data-slug="' + message.slug + '"]' ).find( '.update-link' ).trigger( 'click' );
				} else if ( 'plugin-install' === pagenow ) {
					$( '.plugin-card-' + message.slug ).find( 'h4 a' ).focus();
					$( '.plugin-card-' + message.slug ).find( '[data-slug="' + message.slug + '"]' ).trigger( 'click' );
				}
				break;
		}

	} );

	$( window ).on( 'beforeunload', bd.updates.beforeunload );

})( jQuery, window.bd, window.pagenow, window.ajaxurl );
