/* global tinymce */
/**
 * Included for back-compat.
 * The default WindowManager in TinyMCE 4.0 supports three types of dialogs:
 *	- With HTML created from JS.
 *	- With inline HTML (like BDWindowManager).
 *	- Old type iframe based dialogs.
 */
tinymce.BDWindowManager = tinymce.InlineWindowManager = function( editor ) {
	if ( this.bd ) {
		return this;
	}

	this.bd = {};
	this.parent = editor.windowManager;
	this.editor = editor;

	tinymce.extend( this, this.parent );

	this.open = function( args, params ) {
		var $element,
			self = this,
			bd = this.bd;

		if ( ! args.bdDialog ) {
			return this.parent.open.apply( this, arguments );
		} else if ( ! args.id ) {
			return;
		}

		if ( typeof jQuery === 'undefined' || ! jQuery.bd || ! jQuery.bd.bddialog ) {
			// bddialog.js is not loaded
			if ( window.console && window.console.error ) {
				window.console.error('bddialog.js is not loaded. Please set "bddialogs" as dependency for your script when calling bd_enqueue_script(). You may also want to enqueue the "bd-jquery-ui-dialog" stylesheet.');
			}

			return;
		}

		bd.$element = $element = jQuery( '#' + args.id );

		if ( ! $element.length ) {
			return;
		}

		if ( window.console && window.console.log ) {
			window.console.log('tinymce.BDWindowManager is deprecated. Use the default editor.windowManager to open dialogs with inline HTML.');
		}

		bd.features = args;
		bd.params = params;

		// Store selection. Takes a snapshot in the FocusManager of the selection before focus is moved to the dialog.
		editor.nodeChanged();

		// Create the dialog if necessary
		if ( ! $element.data('bddialog') ) {
			$element.bddialog({
				title: args.title,
				width: args.width,
				height: args.height,
				modal: true,
				dialogClass: 'bd-dialog',
				zIndex: 300000
			});
		}

		$element.bddialog('open');

		$element.on( 'bddialogclose', function() {
			if ( self.bd.$element ) {
				self.bd = {};
			}
		});
	};

	this.close = function() {
		if ( ! this.bd.features || ! this.bd.features.bdDialog ) {
			return this.parent.close.apply( this, arguments );
		}

		this.bd.$element.bddialog('close');
	};
};

tinymce.PluginManager.add( 'bddialogs', function( editor ) {
	// Replace window manager
	editor.on( 'init', function() {
		editor.windowManager = new tinymce.BDWindowManager( editor );
	});
});
