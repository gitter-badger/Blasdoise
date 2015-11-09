( function( tinymce ) {
	tinymce.ui.BDLinkPreview = tinymce.ui.Control.extend( {
		url: '#',
		renderHtml: function() {
			return (
				'<div id="' + this._id + '" class="bd-link-preview">' +
					'<a href="' + this.url + '" target="_blank" tabindex="-1">' + this.url + '</a>' +
				'</div>'
			);
		},
		setURL: function( url ) {
			var index, lastIndex;

			if ( this.url !== url ) {
				this.url = url;

				url = window.decodeURIComponent( url );

				url = url.replace( /^(?:https?:)?\/\/(?:www\.)?/, '' );

				if ( ( index = url.indexOf( '?' ) ) !== -1 ) {
					url = url.slice( 0, index );
				}

				if ( ( index = url.indexOf( '#' ) ) !== -1 ) {
					url = url.slice( 0, index );
				}

				url = url.replace( /(?:index)?\.html$/, '' );

				if ( url.charAt( url.length - 1 ) === '/' ) {
					url = url.slice( 0, -1 );
				}

				// If the URL is longer that 40 chars, concatenate the beginning (after the domain) and ending with ...
				if ( url.length > 40 && ( index = url.indexOf( '/' ) ) !== -1 && ( lastIndex = url.lastIndexOf( '/' ) ) !== -1 && lastIndex !== index ) {
					// If the beginning + ending are shorter that 40 chars, show more of the ending
					if ( index + url.length - lastIndex < 40 ) {
						lastIndex =  -( 40 - ( index + 1 ) );
					}

					url = url.slice( 0, index + 1 ) + '\u2026' + url.slice( lastIndex );
				}

				tinymce.$( this.getEl().firstChild ).attr( 'href', this.url ).text( url );
			}
		}
	} );

	tinymce.PluginManager.add( 'bdlink', function( editor ) {
		var toolbar;

		editor.addCommand( 'BD_Link', function() {
			window.bdLink && window.bdLink.open( editor.id );
		});

		// BD default shortcut
		editor.addShortcut( 'Alt+Shift+A', '', 'BD_Link' );
		// The "de-facto standard" shortcut, see #27305
		editor.addShortcut( 'Meta+K', '', 'BD_Link' );

		editor.addButton( 'link', {
			icon: 'link',
			tooltip: 'Insert/edit link',
			cmd: 'BD_Link',
			stateSelector: 'a[href]'
		});

		editor.addButton( 'unlink', {
			icon: 'unlink',
			tooltip: 'Remove link',
			cmd: 'unlink'
		});

		editor.addMenuItem( 'link', {
			icon: 'link',
			text: 'Insert/edit link',
			cmd: 'BD_Link',
			stateSelector: 'a[href]',
			context: 'insert',
			prependToContext: true
		});

		editor.on( 'pastepreprocess', function( event ) {
			var pastedStr = event.content,
				regExp = /^(?:https?:)?\/\/\S+$/i;

			if ( ! editor.selection.isCollapsed() && ! regExp.test( editor.selection.getContent() ) ) {
				pastedStr = pastedStr.replace( /<[^>]+>/g, '' );
				pastedStr = tinymce.trim( pastedStr );

				if ( regExp.test( pastedStr ) ) {
					editor.execCommand( 'mceInsertLink', false, {
						href: editor.dom.decode( pastedStr )
					} );

					event.preventDefault();
				}
			}
		} );

		editor.addButton( 'bd_link_preview', {
			type: 'BDLinkPreview',
			onPostRender: function() {
				var self = this;

				editor.on( 'bdtoolbar', function( event ) {
					var anchor = editor.dom.getParent( event.element, 'a' ),
						$anchor,
						href;

					if ( anchor ) {
						$anchor = editor.$( anchor );
						href = $anchor.attr( 'href' );

						if ( href && ! $anchor.find( 'img' ).length ) {
							self.setURL( href );
							event.element = anchor;
							event.toolbar = toolbar;
						}
					}
				} );
			}
		} );

		editor.addButton( 'bd_link_edit', {
			tooltip: 'Edit ', // trailing space is needed, used for context
			icon: 'basicon basicons-edit',
			cmd: 'BD_Link'
		} );

		editor.addButton( 'bd_link_remove', {
			tooltip: 'Remove',
			icon: 'basicon basicons-no',
			cmd: 'unlink'
		} );

		editor.on( 'preinit', function() {
			if ( editor.bd && editor.bd._createToolbar ) {
				toolbar = editor.bd._createToolbar( [
					'bd_link_preview',
					'bd_link_edit',
					'bd_link_remove'
				], true );
			}
		} );
	} );
} )( window.tinymce );
