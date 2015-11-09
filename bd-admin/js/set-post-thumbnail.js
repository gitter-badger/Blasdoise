/* global setPostThumbnailL10n, ajaxurl, post_id, alert */
/* exported BDSetAsThumbnail */

function BDSetAsThumbnail( id, nonce ) {
	var $link = jQuery('a#bd-post-thumbnail-' + id);

	$link.text( setPostThumbnailL10n.saving );
	jQuery.post(ajaxurl, {
		action: 'set-post-thumbnail', post_id: post_id, thumbnail_id: id, _ajax_nonce: nonce, cookie: encodeURIComponent( document.cookie )
	}, function(str){
		var win = window.dialogArguments || opener || parent || top;
		$link.text( setPostThumbnailL10n.setThumbnail );
		if ( str == '0' ) {
			alert( setPostThumbnailL10n.error );
		} else {
			jQuery('a.bd-post-thumbnail').show();
			$link.text( setPostThumbnailL10n.done );
			$link.fadeOut( 2000 );
			win.BDSetThumbnailID(id);
			win.BDSetThumbnailHTML(str);
		}
	}
	);
}
