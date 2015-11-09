/*globals window, document, jQuery, _, Backbone, _bdmejsSettings */

(function ($, _, Backbone) {
	"use strict";

	var BDPlaylistView = Backbone.View.extend({
		initialize : function (options) {
			this.index = 0;
			this.settings = {};
			this.data = options.metadata || $.parseJSON( this.$('script.bd-playlist-script').html() );
			this.playerNode = this.$( this.data.type );

			this.tracks = new Backbone.Collection( this.data.tracks );
			this.current = this.tracks.first();

			if ( 'audio' === this.data.type ) {
				this.currentTemplate = bd.template( 'bd-playlist-current-item' );
				this.currentNode = this.$( '.bd-playlist-current-item' );
			}

			this.renderCurrent();

			if ( this.data.tracklist ) {
				this.itemTemplate = bd.template( 'bd-playlist-item' );
				this.playingClass = 'bd-playlist-playing';
				this.renderTracks();
			}

			this.playerNode.attr( 'src', this.current.get( 'src' ) );

			_.bindAll( this, 'bindPlayer', 'bindResetPlayer', 'setPlayer', 'ended', 'clickTrack' );

			if ( ! _.isUndefined( window._bdmejsSettings ) ) {
				this.settings = _bdmejsSettings;
			}
			this.settings.success = this.bindPlayer;
			this.setPlayer();
		},

		bindPlayer : function (mejs) {
			this.mejs = mejs;
			this.mejs.addEventListener( 'ended', this.ended );
		},

		bindResetPlayer : function (mejs) {
			this.bindPlayer( mejs );
			this.playCurrentSrc();
		},

		setPlayer: function (force) {
			if ( this.player ) {
				this.player.pause();
				this.player.remove();
				this.playerNode = this.$( this.data.type );
			}

			if (force) {
				this.playerNode.attr( 'src', this.current.get( 'src' ) );
				this.settings.success = this.bindResetPlayer;
			}

			/**
			 * This is also our bridge to the outside world
			 */
			this.player = new MediaElementPlayer( this.playerNode.get(0), this.settings );
		},

		playCurrentSrc : function () {
			this.renderCurrent();
			this.mejs.setSrc( this.playerNode.attr( 'src' ) );
			this.mejs.load();
			this.mejs.play();
		},

		renderCurrent : function () {
			var dimensions, defaultImage = 'bd-includes/images/media/video.png';
			if ( 'video' === this.data.type ) {
				if ( this.data.images && this.current.get( 'image' ) && -1 === this.current.get( 'image' ).src.indexOf( defaultImage ) ) {
					this.playerNode.attr( 'poster', this.current.get( 'image' ).src );
				}
				dimensions = this.current.get( 'dimensions' ).resized;
				this.playerNode.attr( dimensions );
			} else {
				if ( ! this.data.images ) {
					this.current.set( 'image', false );
				}
				this.currentNode.html( this.currentTemplate( this.current.toJSON() ) );
			}
		},

		renderTracks : function () {
			var self = this, i = 1, tracklist = $( '<div class="bd-playlist-tracks"></div>' );
			this.tracks.each(function (model) {
				if ( ! self.data.images ) {
					model.set( 'image', false );
				}
				model.set( 'artists', self.data.artists );
				model.set( 'index', self.data.tracknumbers ? i : false );
				tracklist.append( self.itemTemplate( model.toJSON() ) );
				i += 1;
			});
			this.$el.append( tracklist );

			this.$( '.bd-playlist-item' ).eq(0).addClass( this.playingClass );
		},

		events : {
			'click .bd-playlist-item' : 'clickTrack',
			'click .bd-playlist-next' : 'next',
			'click .bd-playlist-prev' : 'prev'
		},

		clickTrack : function (e) {
			e.preventDefault();

			this.index = this.$( '.bd-playlist-item' ).index( e.currentTarget );
			this.setCurrent();
		},

		ended : function () {
			if ( this.index + 1 < this.tracks.length ) {
				this.next();
			} else {
				this.index = 0;
				this.setCurrent();
			}
		},

		next : function () {
			this.index = this.index + 1 >= this.tracks.length ? 0 : this.index + 1;
			this.setCurrent();
		},

		prev : function () {
			this.index = this.index - 1 < 0 ? this.tracks.length - 1 : this.index - 1;
			this.setCurrent();
		},

		loadCurrent : function () {
			var last = this.playerNode.attr( 'src' ) && this.playerNode.attr( 'src' ).split('.').pop(),
				current = this.current.get( 'src' ).split('.').pop();

			this.mejs && this.mejs.pause();

			if ( last !== current ) {
				this.setPlayer( true );
			} else {
				this.playerNode.attr( 'src', this.current.get( 'src' ) );
				this.playCurrentSrc();
			}
		},

		setCurrent : function () {
			this.current = this.tracks.at( this.index );

			if ( this.data.tracklist ) {
				this.$( '.bd-playlist-item' )
					.removeClass( this.playingClass )
					.eq( this.index )
						.addClass( this.playingClass );
			}

			this.loadCurrent();
		}
	});

    $(document).ready(function () {
		$('.bd-playlist').each( function() {
			return new BDPlaylistView({ el: this });
		} );
    });

	window.BDPlaylistView = BDPlaylistView;

}(jQuery, _, Backbone));