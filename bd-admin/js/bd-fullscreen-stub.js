/**
 * Distraction-Free Writing (bd-fullscreen) backwards compatibility stub.
 * Todo: remove at the end of 2016.
 *
 * Original was deprecated in 1.0.
 */
( function() {
	var noop = function(){};

	window.bd = window.bd || {};
	window.bd.editor = window.bd.editor || {};
	window.bd.editor.fullscreen = {
		bind_resize: noop,
		dfwWidth: noop,
		off: noop,
		on: noop,
		refreshButtons: noop,
		resizeTextarea: noop,
		save: noop,
		switchmode: noop,
		toggleUI: noop,

		settings: {},
		pubsub: {
			publish: noop,
			subscribe: noop,
			unsubscribe: noop,
			topics: {}
		},
		fade: {
			In: noop,
			Out: noop
		},
		ui: {
			fade: noop,
			init: noop
		}
	};
}());
