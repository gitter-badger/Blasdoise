<?php
/**
 * Blasdoise Core Ajax Handlers.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

//
// No-privilege Ajax handlers.
//

/**
 * Ajax handler for the Heartbeat API in
 * the no-privilege context.
 *
 * Runs when the user is not logged in.
 *
 * @since 1.0.0
 */
function bd_ajax_nopriv_heartbeat() {
	$response = array();

	// screen_id is the same as $current_screen->id and the JS global 'pagenow'.
	if ( ! empty($_POST['screen_id']) )
		$screen_id = sanitize_key($_POST['screen_id']);
	else
		$screen_id = 'front';

	if ( ! empty($_POST['data']) ) {
		$data = bd_unslash( (array) $_POST['data'] );

		/**
		 * Filter Heartbeat AJAX response in no-privilege environments.
		 *
		 * @since 1.0.0
		 *
		 * @param array|object $response  The no-priv Heartbeat response object or array.
		 * @param array        $data      An array of data passed via $_POST.
		 * @param string       $screen_id The screen id.
		 */
		$response = apply_filters( 'heartbeat_nopriv_received', $response, $data, $screen_id );
	}

	/**
	 * Filter Heartbeat AJAX response when no data is passed.
	 *
	 * @since 1.0.0
	 *
	 * @param array|object $response  The Heartbeat response object or array.
	 * @param string       $screen_id The screen id.
	 */
	$response = apply_filters( 'heartbeat_nopriv_send', $response, $screen_id );

	/**
	 * Fires when Heartbeat ticks in no-privilege environments.
	 *
	 * Allows the transport to be easily replaced with long-polling.
	 *
	 * @since 1.0.0
	 *
	 * @param array|object $response  The no-priv Heartbeat response.
	 * @param string       $screen_id The screen id.
	 */
	do_action( 'heartbeat_nopriv_tick', $response, $screen_id );

	// Send the current time according to the server.
	$response['server_time'] = time();

	bd_send_json($response);
}

//
// GET-based Ajax handlers.
//

/**
 * Ajax handler for fetching a list table.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 */
function bd_ajax_fetch_list() {
	global $bd_list_table;

	$list_class = $_GET['list_args']['class'];
	check_ajax_referer( "fetch-list-$list_class", '_ajax_fetch_list_nonce' );

	$bd_list_table = _get_list_table( $list_class, array( 'screen' => $_GET['list_args']['screen']['id'] ) );
	if ( ! $bd_list_table )
		bd_die( 0 );

	if ( ! $bd_list_table->ajax_user_can() )
		bd_die( -1 );

	$bd_list_table->ajax_response();

	bd_die( 0 );
}

/**
 * Ajax handler for tag search.
 *
 * @since 1.0.0
 */
function bd_ajax_ajax_tag_search() {
	if ( ! isset( $_GET['tax'] ) ) {
		bd_die( 0 );
	}

	$taxonomy = sanitize_key( $_GET['tax'] );
	$tax = get_taxonomy( $taxonomy );
	if ( ! $tax ) {
		bd_die( 0 );
	}

	if ( ! current_user_can( $tax->cap->assign_terms ) ) {
		bd_die( -1 );
	}

	$s = bd_unslash( $_GET['q'] );

	$comma = _x( ',', 'tag delimiter' );
	if ( ',' !== $comma )
		$s = str_replace( $comma, ',', $s );
	if ( false !== strpos( $s, ',' ) ) {
		$s = explode( ',', $s );
		$s = $s[count( $s ) - 1];
	}
	$s = trim( $s );

	/**
	 * Filter the minimum number of characters required to fire a tag search via AJAX.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $characters The minimum number of characters required. Default 2.
	 * @param object $tax        The taxonomy object.
	 * @param string $s          The search term.
	 */
	$term_search_min_chars = (int) apply_filters( 'term_search_min_chars', 2, $tax, $s );

	/*
	 * Require $term_search_min_chars chars for matching (default: 2)
	 * ensure it's a non-negative, non-zero integer.
	 */
	if ( ( $term_search_min_chars == 0 ) || ( strlen( $s ) < $term_search_min_chars ) ){
		bd_die();
	}

	$results = get_terms( $taxonomy, array( 'name__like' => $s, 'fields' => 'names', 'hide_empty' => false ) );

	echo join( $results, "\n" );
	bd_die();
}

/**
 * Ajax handler for compression testing.
 *
 * @since 1.0.0
 */
function bd_ajax_bd_compression_test() {
	if ( !current_user_can( 'manage_options' ) )
		bd_die( -1 );

	if ( ini_get('zlib.output_compression') || 'ob_gzhandler' == ini_get('output_handler') ) {
		update_site_option('can_compress_scripts', 0);
		bd_die( 0 );
	}

	if ( isset($_GET['test']) ) {
		header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header('Content-Type: application/javascript; charset=UTF-8');
		$force_gzip = ( defined('ENFORCE_GZIP') && ENFORCE_GZIP );
		$test_str = '"bdCompressionTest Lorem ipsum dolor sit amet consectetuer mollis sapien urna ut a. Eu nonummy condimentum fringilla tempor pretium platea vel nibh netus Maecenas. Hac molestie amet justo quis pellentesque est ultrices interdum nibh Morbi. Cras mattis pretium Phasellus ante ipsum ipsum ut sociis Suspendisse Lorem. Ante et non molestie. Porta urna Vestibulum egestas id congue nibh eu risus gravida sit. Ac augue auctor Ut et non a elit massa id sodales. Elit eu Nulla at nibh adipiscing mattis lacus mauris at tempus. Netus nibh quis suscipit nec feugiat eget sed lorem et urna. Pellentesque lacus at ut massa consectetuer ligula ut auctor semper Pellentesque. Ut metus massa nibh quam Curabitur molestie nec mauris congue. Volutpat molestie elit justo facilisis neque ac risus Ut nascetur tristique. Vitae sit lorem tellus et quis Phasellus lacus tincidunt nunc Fusce. Pharetra wisi Suspendisse mus sagittis libero lacinia Integer consequat ac Phasellus. Et urna ac cursus tortor aliquam Aliquam amet tellus volutpat Vestibulum. Justo interdum condimentum In augue congue tellus sollicitudin Quisque quis nibh."';

		 if ( 1 == $_GET['test'] ) {
		 	echo $test_str;
		 	bd_die();
		 } elseif ( 2 == $_GET['test'] ) {
			if ( !isset($_SERVER['HTTP_ACCEPT_ENCODING']) )
				bd_die( -1 );
			if ( false !== stripos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') && function_exists('gzdeflate') && ! $force_gzip ) {
				header('Content-Encoding: deflate');
				$out = gzdeflate( $test_str, 1 );
			} elseif ( false !== stripos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && function_exists('gzencode') ) {
				header('Content-Encoding: gzip');
				$out = gzencode( $test_str, 1 );
			} else {
				bd_die( -1 );
			}
			echo $out;
			bd_die();
		} elseif ( 'no' == $_GET['test'] ) {
			update_site_option('can_compress_scripts', 0);
		} elseif ( 'yes' == $_GET['test'] ) {
			update_site_option('can_compress_scripts', 1);
		}
	}

	bd_die( 0 );
}

/**
 * Ajax handler for image editor previews.
 *
 * @since 1.0.0
 */
function bd_ajax_imgedit_preview() {
	$post_id = intval($_GET['postid']);
	if ( empty($post_id) || !current_user_can('edit_post', $post_id) )
		bd_die( -1 );

	check_ajax_referer( "image_editor-$post_id" );

	include_once( ABSPATH . 'bd-admin/includes/image-edit.php' );
	if ( ! stream_preview_image($post_id) )
		bd_die( -1 );

	bd_die();
}

/**
 * Ajax handler for oEmbed caching.
 *
 * @since 1.0.0
 *
 * @global BD_Embed $bd_embed
 */
function bd_ajax_oembed_cache() {
	$GLOBALS['bd_embed']->cache_oembed( $_GET['post'] );
	bd_die( 0 );
}

/**
 * Ajax handler for user autocomplete.
 *
 * @since 1.0.0
 */
function bd_ajax_autocomplete_user() {
	if ( ! is_multisite() || ! current_user_can( 'promote_users' ) || bd_is_large_network( 'users' ) )
		bd_die( -1 );

	/** This filter is documented in bd-admin/user-new.php */
	if ( ! is_super_admin() && ! apply_filters( 'autocomplete_users_for_site_admins', false ) )
		bd_die( -1 );

	$return = array();

	// Check the type of request
	// Current allowed values are `add` and `search`
	if ( isset( $_REQUEST['autocomplete_type'] ) && 'search' === $_REQUEST['autocomplete_type'] ) {
		$type = $_REQUEST['autocomplete_type'];
	} else {
		$type = 'add';
	}

	// Check the desired field for value
	// Current allowed values are `user_email` and `user_login`
	if ( isset( $_REQUEST['autocomplete_field'] ) && 'user_email' === $_REQUEST['autocomplete_field'] ) {
		$field = $_REQUEST['autocomplete_field'];
	} else {
		$field = 'user_login';
	}

	// Exclude current users of this blog
	if ( isset( $_REQUEST['site_id'] ) ) {
		$id = absint( $_REQUEST['site_id'] );
	} else {
		$id = get_current_blog_id();
	}

	$include_blog_users = ( $type == 'search' ? get_users( array( 'blog_id' => $id, 'fields' => 'ID' ) ) : array() );
	$exclude_blog_users = ( $type == 'add' ? get_users( array( 'blog_id' => $id, 'fields' => 'ID' ) ) : array() );

	$users = get_users( array(
		'blog_id' => false,
		'search'  => '*' . $_REQUEST['term'] . '*',
		'include' => $include_blog_users,
		'exclude' => $exclude_blog_users,
		'search_columns' => array( 'user_login', 'user_nicename', 'user_email' ),
	) );

	foreach ( $users as $user ) {
		$return[] = array(
			/* translators: 1: user_login, 2: user_email */
			'label' => sprintf( __( '%1$s (%2$s)' ), $user->user_login, $user->user_email ),
			'value' => $user->$field,
		);
	}

	bd_die( bd_json_encode( $return ) );
}

/**
 * Ajax handler for dashboard widgets.
 *
 * @since 1.0.0
 */
function bd_ajax_dashboard_widgets() {
	require_once ABSPATH . 'bd-admin/includes/dashboard.php';

	$pagenow = $_GET['pagenow'];
	if ( $pagenow === 'dashboard-user' || $pagenow === 'dashboard-network' || $pagenow === 'dashboard' ) {
		set_current_screen( $pagenow );
	}

	switch ( $_GET['widget'] ) {
		case 'dashboard_primary' :
			bd_dashboard_primary();
			break;
	}
	bd_die();
}

/**
 * Ajax handler for Customizer preview logged-in status.
 *
 * @since 1.0.0
 */
function bd_ajax_logged_in() {
	bd_die( 1 );
}

//
// Ajax helpers.
//

/**
 * Sends back current comment total and new page links if they need to be updated.
 *
 * Contrary to normal success AJAX response ("1"), die with time() on success.
 *
 * @since 1.0.0
 *
 * @param int $comment_id
 * @param int $delta
 */
function _bd_ajax_delete_comment_response( $comment_id, $delta = -1 ) {
	$total    = isset( $_POST['_total'] )    ? (int) $_POST['_total']    : 0;
	$per_page = isset( $_POST['_per_page'] ) ? (int) $_POST['_per_page'] : 0;
	$page     = isset( $_POST['_page'] )     ? (int) $_POST['_page']     : 0;
	$url      = isset( $_POST['_url'] )      ? esc_url_raw( $_POST['_url'] ) : '';

	// JS didn't send us everything we need to know. Just die with success message
	if ( !$total || !$per_page || !$page || !$url )
		bd_die( time() );

	$total += $delta;
	if ( $total < 0 )
		$total = 0;

	// Only do the expensive stuff on a page-break, and about 1 other time per page
	if ( 0 == $total % $per_page || 1 == mt_rand( 1, $per_page ) ) {
		$post_id = 0;
		$status = 'total_comments'; // What type of comment count are we looking for?
		$parsed = parse_url( $url );
		if ( isset( $parsed['query'] ) ) {
			parse_str( $parsed['query'], $query_vars );
			if ( !empty( $query_vars['comment_status'] ) )
				$status = $query_vars['comment_status'];
			if ( !empty( $query_vars['p'] ) )
				$post_id = (int) $query_vars['p'];
		}

		$comment_count = bd_count_comments($post_id);

		// We're looking for a known type of comment count.
		if ( isset( $comment_count->$status ) )
			$total = $comment_count->$status;
			// Else use the decremented value from above.
	}

	// The time since the last comment count.
	$time = time();

	$x = new BD_Ajax_Response( array(
		'what' => 'comment',
		// Here for completeness - not used.
		'id' => $comment_id,
		'supplemental' => array(
			'total_items_i18n' => sprintf( _n( '%s item', '%s items', $total ), number_format_i18n( $total ) ),
			'total_pages' => ceil( $total / $per_page ),
			'total_pages_i18n' => number_format_i18n( ceil( $total / $per_page ) ),
			'total' => $total,
			'time' => $time
		)
	) );
	$x->send();
}

//
// POST-based Ajax handlers.
//

/**
 * Ajax handler for adding a hierarchical term.
 *
 * @since 1.0.0
 */
function _bd_ajax_add_hierarchical_term() {
	$action = $_POST['action'];
	$taxonomy = get_taxonomy(substr($action, 4));
	check_ajax_referer( $action, '_ajax_nonce-add-' . $taxonomy->name );
	if ( !current_user_can( $taxonomy->cap->edit_terms ) )
		bd_die( -1 );
	$names = explode(',', $_POST['new'.$taxonomy->name]);
	$parent = isset($_POST['new'.$taxonomy->name.'_parent']) ? (int) $_POST['new'.$taxonomy->name.'_parent'] : 0;
	if ( 0 > $parent )
		$parent = 0;
	if ( $taxonomy->name == 'category' )
		$post_category = isset($_POST['post_category']) ? (array) $_POST['post_category'] : array();
	else
		$post_category = ( isset($_POST['tax_input']) && isset($_POST['tax_input'][$taxonomy->name]) ) ? (array) $_POST['tax_input'][$taxonomy->name] : array();
	$checked_categories = array_map( 'absint', (array) $post_category );
	$popular_ids = bd_popular_terms_checklist($taxonomy->name, 0, 10, false);

	foreach ( $names as $cat_name ) {
		$cat_name = trim($cat_name);
		$category_nicename = sanitize_title($cat_name);
		if ( '' === $category_nicename )
			continue;
		if ( !$cat_id = term_exists( $cat_name, $taxonomy->name, $parent ) )
			$cat_id = bd_insert_term( $cat_name, $taxonomy->name, array( 'parent' => $parent ) );
		if ( is_bd_error( $cat_id ) ) {
			continue;
		} elseif ( is_array( $cat_id ) ) {
			$cat_id = $cat_id['term_id'];
		}
		$checked_categories[] = $cat_id;
		if ( $parent ) // Do these all at once in a second
			continue;

		ob_start();

		bd_terms_checklist( 0, array( 'taxonomy' => $taxonomy->name, 'descendants_and_self' => $cat_id, 'selected_cats' => $checked_categories, 'popular_cats' => $popular_ids ));

		$data = ob_get_clean();

		$add = array(
			'what' => $taxonomy->name,
			'id' => $cat_id,
			'data' => str_replace( array("\n", "\t"), '', $data),
			'position' => -1
		);
	}

	if ( $parent ) { // Foncy - replace the parent and all its children
		$parent = get_term( $parent, $taxonomy->name );
		$term_id = $parent->term_id;

		while ( $parent->parent ) { // get the top parent
			$parent = get_term( $parent->parent, $taxonomy->name );
			if ( is_bd_error( $parent ) )
				break;
			$term_id = $parent->term_id;
		}

		ob_start();

		bd_terms_checklist( 0, array('taxonomy' => $taxonomy->name, 'descendants_and_self' => $term_id, 'selected_cats' => $checked_categories, 'popular_cats' => $popular_ids));

		$data = ob_get_clean();

		$add = array(
			'what' => $taxonomy->name,
			'id' => $term_id,
			'data' => str_replace( array("\n", "\t"), '', $data),
			'position' => -1
		);
	}

	ob_start();

	bd_dropdown_categories( array(
		'taxonomy' => $taxonomy->name, 'hide_empty' => 0, 'name' => 'new'.$taxonomy->name.'_parent', 'orderby' => 'name',
		'hierarchical' => 1, 'show_option_none' => '&mdash; '.$taxonomy->labels->parent_item.' &mdash;'
	) );

	$sup = ob_get_clean();

	$add['supplemental'] = array( 'newcat_parent' => $sup );

	$x = new BD_Ajax_Response( $add );
	$x->send();
}

/**
 * Ajax handler for deleting a comment.
 *
 * @since 1.0.0
 */
function bd_ajax_delete_comment() {
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	if ( !$comment = get_comment( $id ) )
		bd_die( time() );
	if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) )
		bd_die( -1 );

	check_ajax_referer( "delete-comment_$id" );
	$status = bd_get_comment_status( $comment->comment_ID );

	$delta = -1;
	if ( isset($_POST['trash']) && 1 == $_POST['trash'] ) {
		if ( 'trash' == $status )
			bd_die( time() );
		$r = bd_trash_comment( $comment->comment_ID );
	} elseif ( isset($_POST['untrash']) && 1 == $_POST['untrash'] ) {
		if ( 'trash' != $status )
			bd_die( time() );
		$r = bd_untrash_comment( $comment->comment_ID );
		if ( ! isset( $_POST['comment_status'] ) || $_POST['comment_status'] != 'trash' ) // undo trash, not in trash
			$delta = 1;
	} elseif ( isset($_POST['spam']) && 1 == $_POST['spam'] ) {
		if ( 'spam' == $status )
			bd_die( time() );
		$r = bd_spam_comment( $comment->comment_ID );
	} elseif ( isset($_POST['unspam']) && 1 == $_POST['unspam'] ) {
		if ( 'spam' != $status )
			bd_die( time() );
		$r = bd_unspam_comment( $comment->comment_ID );
		if ( ! isset( $_POST['comment_status'] ) || $_POST['comment_status'] != 'spam' ) // undo spam, not in spam
			$delta = 1;
	} elseif ( isset($_POST['delete']) && 1 == $_POST['delete'] ) {
		$r = bd_delete_comment( $comment->comment_ID );
	} else {
		bd_die( -1 );
	}

	if ( $r ) // Decide if we need to send back '1' or a more complicated response including page links and comment counts
		_bd_ajax_delete_comment_response( $comment->comment_ID, $delta );
	bd_die( 0 );
}

/**
 * Ajax handler for deleting a tag.
 *
 * @since 1.0.0
 */
function bd_ajax_delete_tag() {
	$tag_id = (int) $_POST['tag_ID'];
	check_ajax_referer( "delete-tag_$tag_id" );

	$taxonomy = !empty($_POST['taxonomy']) ? $_POST['taxonomy'] : 'post_tag';
	$tax = get_taxonomy($taxonomy);

	if ( !current_user_can( $tax->cap->delete_terms ) )
		bd_die( -1 );

	$tag = get_term( $tag_id, $taxonomy );
	if ( !$tag || is_bd_error( $tag ) )
		bd_die( 1 );

	if ( bd_delete_term($tag_id, $taxonomy))
		bd_die( 1 );
	else
		bd_die( 0 );
}

/**
 * Ajax handler for deleting a link.
 *
 * @since 1.0.0
 */
function bd_ajax_delete_link() {
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	check_ajax_referer( "delete-bookmark_$id" );
	if ( !current_user_can( 'manage_links' ) )
		bd_die( -1 );

	$link = get_bookmark( $id );
	if ( !$link || is_bd_error( $link ) )
		bd_die( 1 );

	if ( bd_delete_link( $id ) )
		bd_die( 1 );
	else
		bd_die( 0 );
}

/**
 * Ajax handler for deleting meta.
 *
 * @since 1.0.0
 */
function bd_ajax_delete_meta() {
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	check_ajax_referer( "delete-meta_$id" );
	if ( !$meta = get_metadata_by_mid( 'post', $id ) )
		bd_die( 1 );

	if ( is_protected_meta( $meta->meta_key, 'post' ) || ! current_user_can( 'delete_post_meta',  $meta->post_id, $meta->meta_key ) )
		bd_die( -1 );
	if ( delete_meta( $meta->meta_id ) )
		bd_die( 1 );
	bd_die( 0 );
}

/**
 * Ajax handler for deleting a post.
 *
 * @since 1.0.0
 *
 * @param string $action Action to perform.
 */
function bd_ajax_delete_post( $action ) {
	if ( empty( $action ) )
		$action = 'delete-post';
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	check_ajax_referer( "{$action}_$id" );
	if ( !current_user_can( 'delete_post', $id ) )
		bd_die( -1 );

	if ( !get_post( $id ) )
		bd_die( 1 );

	if ( bd_delete_post( $id ) )
		bd_die( 1 );
	else
		bd_die( 0 );
}

/**
 * Ajax handler for sending a post to the trash.
 *
 * @since 1.0.0
 *
 * @param string $action Action to perform.
 */
function bd_ajax_trash_post( $action ) {
	if ( empty( $action ) )
		$action = 'trash-post';
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	check_ajax_referer( "{$action}_$id" );
	if ( !current_user_can( 'delete_post', $id ) )
		bd_die( -1 );

	if ( !get_post( $id ) )
		bd_die( 1 );

	if ( 'trash-post' == $action )
		$done = bd_trash_post( $id );
	else
		$done = bd_untrash_post( $id );

	if ( $done )
		bd_die( 1 );

	bd_die( 0 );
}

/**
 * Ajax handler to restore a post from the trash.
 *
 * @since 1.0.0
 *
 * @param string $action Action to perform.
 */
function bd_ajax_untrash_post( $action ) {
	if ( empty( $action ) )
		$action = 'untrash-post';
	bd_ajax_trash_post( $action );
}

/**
 * @since 1.0.0
 *
 * @param string $action
 */
function bd_ajax_delete_page( $action ) {
	if ( empty( $action ) )
		$action = 'delete-page';
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	check_ajax_referer( "{$action}_$id" );
	if ( !current_user_can( 'delete_page', $id ) )
		bd_die( -1 );

	if ( ! get_post( $id ) )
		bd_die( 1 );

	if ( bd_delete_post( $id ) )
		bd_die( 1 );
	else
		bd_die( 0 );
}

/**
 * Ajax handler to dim a comment.
 *
 * @since 1.0.0
 */
function bd_ajax_dim_comment() {
	$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;

	if ( !$comment = get_comment( $id ) ) {
		$x = new BD_Ajax_Response( array(
			'what' => 'comment',
			'id' => new BD_Error('invalid_comment', sprintf(__('Comment %d does not exist'), $id))
		) );
		$x->send();
	}

	if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) && ! current_user_can( 'moderate_comments' ) )
		bd_die( -1 );

	$current = bd_get_comment_status( $comment->comment_ID );
	if ( isset( $_POST['new'] ) && $_POST['new'] == $current )
		bd_die( time() );

	check_ajax_referer( "approve-comment_$id" );
	if ( in_array( $current, array( 'unapproved', 'spam' ) ) )
		$result = bd_set_comment_status( $comment->comment_ID, 'approve', true );
	else
		$result = bd_set_comment_status( $comment->comment_ID, 'hold', true );

	if ( is_bd_error($result) ) {
		$x = new BD_Ajax_Response( array(
			'what' => 'comment',
			'id' => $result
		) );
		$x->send();
	}

	// Decide if we need to send back '1' or a more complicated response including page links and comment counts
	_bd_ajax_delete_comment_response( $comment->comment_ID );
	bd_die( 0 );
}

/**
 * Ajax handler for deleting a link category.
 *
 * @since 1.0.0
 *
 * @param string $action Action to perform.
 */
function bd_ajax_add_link_category( $action ) {
	if ( empty( $action ) )
		$action = 'add-link-category';
	check_ajax_referer( $action );
	if ( !current_user_can( 'manage_categories' ) )
		bd_die( -1 );
	$names = explode(',', bd_unslash( $_POST['newcat'] ) );
	$x = new BD_Ajax_Response();
	foreach ( $names as $cat_name ) {
		$cat_name = trim($cat_name);
		$slug = sanitize_title($cat_name);
		if ( '' === $slug )
			continue;
		if ( !$cat_id = term_exists( $cat_name, 'link_category' ) )
			$cat_id = bd_insert_term( $cat_name, 'link_category' );
		if ( is_bd_error( $cat_id ) ) {
			continue;
		} elseif ( is_array( $cat_id ) ) {
			$cat_id = $cat_id['term_id'];
		}
		$cat_name = esc_html( $cat_name );
		$x->add( array(
			'what' => 'link-category',
			'id' => $cat_id,
			'data' => "<li id='link-category-$cat_id'><label for='in-link-category-$cat_id' class='selectit'><input value='" . esc_attr($cat_id) . "' type='checkbox' checked='checked' name='link_category[]' id='in-link-category-$cat_id'/> $cat_name</label></li>",
			'position' => -1
		) );
	}
	$x->send();
}

/**
 * Ajax handler to add a tag.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 */
function bd_ajax_add_tag() {
	global $bd_list_table;

	check_ajax_referer( 'add-tag', '_bdnonce_add-tag' );
	$taxonomy = !empty($_POST['taxonomy']) ? $_POST['taxonomy'] : 'post_tag';
	$tax = get_taxonomy($taxonomy);

	if ( !current_user_can( $tax->cap->edit_terms ) )
		bd_die( -1 );

	$x = new BD_Ajax_Response();

	$tag = bd_insert_term($_POST['tag-name'], $taxonomy, $_POST );

	if ( !$tag || is_bd_error($tag) || (!$tag = get_term( $tag['term_id'], $taxonomy )) ) {
		$message = __('An error has occurred. Please reload the page and try again.');
		if ( is_bd_error($tag) && $tag->get_error_message() )
			$message = $tag->get_error_message();

		$x->add( array(
			'what' => 'taxonomy',
			'data' => new BD_Error('error', $message )
		) );
		$x->send();
	}

	$bd_list_table = _get_list_table( 'BD_Terms_List_Table', array( 'screen' => $_POST['screen'] ) );

	$level = 0;
	if ( is_taxonomy_hierarchical($taxonomy) ) {
		$level = count( get_ancestors( $tag->term_id, $taxonomy, 'taxonomy' ) );
		ob_start();
		$bd_list_table->single_row( $tag, $level );
		$noparents = ob_get_clean();
	}

	ob_start();
	$bd_list_table->single_row( $tag );
	$parents = ob_get_clean();

	$x->add( array(
		'what' => 'taxonomy',
		'supplemental' => compact('parents', 'noparents')
	) );
	$x->add( array(
		'what' => 'term',
		'position' => $level,
		'supplemental' => (array) $tag
	) );
	$x->send();
}

/**
 * Ajax handler for getting a tagcloud.
 *
 * @since 1.0.0
 */
function bd_ajax_get_tagcloud() {
	if ( ! isset( $_POST['tax'] ) ) {
		bd_die( 0 );
	}

	$taxonomy = sanitize_key( $_POST['tax'] );
	$tax = get_taxonomy( $taxonomy );
	if ( ! $tax ) {
		bd_die( 0 );
	}

	if ( ! current_user_can( $tax->cap->assign_terms ) ) {
		bd_die( -1 );
	}

	$tags = get_terms( $taxonomy, array( 'number' => 45, 'orderby' => 'count', 'order' => 'DESC' ) );

	if ( empty( $tags ) )
		bd_die( $tax->labels->not_found );

	if ( is_bd_error( $tags ) )
		bd_die( $tags->get_error_message() );

	foreach ( $tags as $key => $tag ) {
		$tags[ $key ]->link = '#';
		$tags[ $key ]->id = $tag->term_id;
	}

	// We need raw tag names here, so don't filter the output
	$return = bd_generate_tag_cloud( $tags, array('filter' => 0) );

	if ( empty($return) )
		bd_die( 0 );

	echo $return;

	bd_die();
}

/**
 * Ajax handler for getting comments.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 * @global int           $post_id
 *
 * @param string $action Action to perform.
 */
function bd_ajax_get_comments( $action ) {
	global $bd_list_table, $post_id;
	if ( empty( $action ) )
		$action = 'get-comments';

	check_ajax_referer( $action );

	if ( empty( $post_id ) && ! empty( $_REQUEST['p'] ) ) {
		$id = absint( $_REQUEST['p'] );
		if ( ! empty( $id ) )
			$post_id = $id;
	}

	if ( empty( $post_id ) )
		bd_die( -1 );

	$bd_list_table = _get_list_table( 'BD_Post_Comments_List_Table', array( 'screen' => 'edit-comments' ) );

	if ( ! current_user_can( 'edit_post', $post_id ) )
		bd_die( -1 );

	$bd_list_table->prepare_items();

	if ( !$bd_list_table->has_items() )
		bd_die( 1 );

	$x = new BD_Ajax_Response();
	ob_start();
	foreach ( $bd_list_table->items as $comment ) {
		if ( ! current_user_can( 'edit_comment', $comment->comment_ID ) )
			continue;
		get_comment( $comment );
		$bd_list_table->single_row( $comment );
	}
	$comment_list_item = ob_get_clean();

	$x->add( array(
		'what' => 'comments',
		'data' => $comment_list_item
	) );
	$x->send();
}

/**
 * Ajax handler for replying to a comment.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 *
 * @param string $action Action to perform.
 */
function bd_ajax_replyto_comment( $action ) {
	global $bd_list_table;
	if ( empty( $action ) )
		$action = 'replyto-comment';

	check_ajax_referer( $action, '_ajax_nonce-replyto-comment' );

	$comment_post_ID = (int) $_POST['comment_post_ID'];
	$post = get_post( $comment_post_ID );
	if ( ! $post )
		bd_die( -1 );

	if ( !current_user_can( 'edit_post', $comment_post_ID ) )
		bd_die( -1 );

	if ( empty( $post->post_status ) )
		bd_die( 1 );
	elseif ( in_array($post->post_status, array('draft', 'pending', 'trash') ) )
		bd_die( __('ERROR: you are replying to a comment on a draft post.') );

	$user = bd_get_current_user();
	if ( $user->exists() ) {
		$user_ID = $user->ID;
		$comment_author       = bd_slash( $user->display_name );
		$comment_author_email = bd_slash( $user->user_email );
		$comment_author_url   = bd_slash( $user->user_url );
		$comment_content      = trim( $_POST['content'] );
		$comment_type         = isset( $_POST['comment_type'] ) ? trim( $_POST['comment_type'] ) : '';
		if ( current_user_can( 'unfiltered_html' ) ) {
			if ( ! isset( $_POST['_bd_unfiltered_html_comment'] ) )
				$_POST['_bd_unfiltered_html_comment'] = '';

			if ( bd_create_nonce( 'unfiltered-html-comment' ) != $_POST['_bd_unfiltered_html_comment'] ) {
				kses_remove_filters(); // start with a clean slate
				kses_init_filters(); // set up the filters
			}
		}
	} else {
		bd_die( __( 'Sorry, you must be logged in to reply to a comment.' ) );
	}

	if ( '' == $comment_content )
		bd_die( __( 'ERROR: please type a comment.' ) );

	$comment_parent = 0;
	if ( isset( $_POST['comment_ID'] ) )
		$comment_parent = absint( $_POST['comment_ID'] );
	$comment_auto_approved = false;
	$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

	// Automatically approve parent comment.
	if ( !empty($_POST['approve_parent']) ) {
		$parent = get_comment( $comment_parent );

		if ( $parent && $parent->comment_approved === '0' && $parent->comment_post_ID == $comment_post_ID ) {
			if ( bd_set_comment_status( $parent->comment_ID, 'approve' ) )
				$comment_auto_approved = true;
		}
	}

	$comment_id = bd_new_comment( $commentdata );
	$comment = get_comment($comment_id);
	if ( ! $comment ) bd_die( 1 );

	$position = ( isset($_POST['position']) && (int) $_POST['position'] ) ? (int) $_POST['position'] : '-1';

	ob_start();
	if ( isset( $_REQUEST['mode'] ) && 'dashboard' == $_REQUEST['mode'] ) {
		require_once( ABSPATH . 'bd-admin/includes/dashboard.php' );
		_bd_dashboard_recent_comments_row( $comment );
	} else {
		if ( isset( $_REQUEST['mode'] ) && 'single' == $_REQUEST['mode'] ) {
			$bd_list_table = _get_list_table('BD_Post_Comments_List_Table', array( 'screen' => 'edit-comments' ) );
		} else {
			$bd_list_table = _get_list_table('BD_Comments_List_Table', array( 'screen' => 'edit-comments' ) );
		}
		$bd_list_table->single_row( $comment );
	}
	$comment_list_item = ob_get_clean();

	$response =  array(
		'what' => 'comment',
		'id' => $comment->comment_ID,
		'data' => $comment_list_item,
		'position' => $position
	);

	if ( $comment_auto_approved )
		$response['supplemental'] = array( 'parent_approved' => $parent->comment_ID );

	$x = new BD_Ajax_Response();
	$x->add( $response );
	$x->send();
}

/**
 * Ajax handler for editing a comment.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 */
function bd_ajax_edit_comment() {
	global $bd_list_table;

	check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment' );

	$comment_id = (int) $_POST['comment_ID'];
	if ( ! current_user_can( 'edit_comment', $comment_id ) )
		bd_die( -1 );

	if ( '' == $_POST['content'] )
		bd_die( __( 'ERROR: please type a comment.' ) );

	if ( isset( $_POST['status'] ) )
		$_POST['comment_status'] = $_POST['status'];
	edit_comment();

	$position = ( isset($_POST['position']) && (int) $_POST['position']) ? (int) $_POST['position'] : '-1';
	$checkbox = ( isset($_POST['checkbox']) && true == $_POST['checkbox'] ) ? 1 : 0;
	$bd_list_table = _get_list_table( $checkbox ? 'BD_Comments_List_Table' : 'BD_Post_Comments_List_Table', array( 'screen' => 'edit-comments' ) );

	$comment = get_comment( $comment_id );
	if ( empty( $comment->comment_ID ) )
		bd_die( -1 );

	ob_start();
	$bd_list_table->single_row( $comment );
	$comment_list_item = ob_get_clean();

	$x = new BD_Ajax_Response();

	$x->add( array(
		'what' => 'edit_comment',
		'id' => $comment->comment_ID,
		'data' => $comment_list_item,
		'position' => $position
	));

	$x->send();
}

/**
 * Ajax handler for adding a menu item.
 *
 * @since 1.0.0
 */
function bd_ajax_add_menu_item() {
	check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );

	if ( ! current_user_can( 'edit_theme_options' ) )
		bd_die( -1 );

	require_once ABSPATH . 'bd-admin/includes/nav-menu.php';

	// For performance reasons, we omit some object properties from the checklist.
	// The following is a hacky way to restore them when adding non-custom items.

	$menu_items_data = array();
	foreach ( (array) $_POST['menu-item'] as $menu_item_data ) {
		if (
			! empty( $menu_item_data['menu-item-type'] ) &&
			'custom' != $menu_item_data['menu-item-type'] &&
			! empty( $menu_item_data['menu-item-object-id'] )
		) {
			switch( $menu_item_data['menu-item-type'] ) {
				case 'post_type' :
					$_object = get_post( $menu_item_data['menu-item-object-id'] );
				break;

				case 'taxonomy' :
					$_object = get_term( $menu_item_data['menu-item-object-id'], $menu_item_data['menu-item-object'] );
				break;
			}

			$_menu_items = array_map( 'bd_setup_nav_menu_item', array( $_object ) );
			$_menu_item = reset( $_menu_items );

			// Restore the missing menu item properties
			$menu_item_data['menu-item-description'] = $_menu_item->description;
		}

		$menu_items_data[] = $menu_item_data;
	}

	$item_ids = bd_save_nav_menu_items( 0, $menu_items_data );
	if ( is_bd_error( $item_ids ) )
		bd_die( 0 );

	$menu_items = array();

	foreach ( (array) $item_ids as $menu_item_id ) {
		$menu_obj = get_post( $menu_item_id );
		if ( ! empty( $menu_obj->ID ) ) {
			$menu_obj = bd_setup_nav_menu_item( $menu_obj );
			$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
			$menu_items[] = $menu_obj;
		}
	}

	/** This filter is documented in bd-admin/includes/nav-menu.php */
	$walker_class_name = apply_filters( 'bd_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', $_POST['menu'] );

	if ( ! class_exists( $walker_class_name ) )
		bd_die( 0 );

	if ( ! empty( $menu_items ) ) {
		$args = array(
			'after' => '',
			'before' => '',
			'link_after' => '',
			'link_before' => '',
			'walker' => new $walker_class_name,
		);
		echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
	}
	bd_die();
}

/**
 * Ajax handler for adding meta.
 *
 * @since 1.0.0
 */
function bd_ajax_add_meta() {
	check_ajax_referer( 'add-meta', '_ajax_nonce-add-meta' );
	$c = 0;
	$pid = (int) $_POST['post_id'];
	$post = get_post( $pid );

	if ( isset($_POST['metakeyselect']) || isset($_POST['metakeyinput']) ) {
		if ( !current_user_can( 'edit_post', $pid ) )
			bd_die( -1 );
		if ( isset($_POST['metakeyselect']) && '#NONE#' == $_POST['metakeyselect'] && empty($_POST['metakeyinput']) )
			bd_die( 1 );

		// If the post is an autodraft, save the post as a draft and then attempt to save the meta.
		if ( $post->post_status == 'auto-draft' ) {
			$save_POST = $_POST; // Backup $_POST
			$_POST = array(); // Make it empty for edit_post()
			$_POST['action'] = 'draft'; // Warning fix
			$_POST['post_ID'] = $pid;
			$_POST['post_type'] = $post->post_type;
			$_POST['post_status'] = 'draft';
			$now = current_time('timestamp', 1);
			$_POST['post_title'] = sprintf( __( 'Draft created on %1$s at %2$s' ), date( get_option( 'date_format' ), $now ), date( get_option( 'time_format' ), $now ) );

			if ( $pid = edit_post() ) {
				if ( is_bd_error( $pid ) ) {
					$x = new BD_Ajax_Response( array(
						'what' => 'meta',
						'data' => $pid
					) );
					$x->send();
				}
				$_POST = $save_POST; // Now we can restore original $_POST again
				if ( !$mid = add_meta( $pid ) )
					bd_die( __( 'Please provide a custom field value.' ) );
			} else {
				bd_die( 0 );
			}
		} elseif ( ! $mid = add_meta( $pid ) ) {
			bd_die( __( 'Please provide a custom field value.' ) );
		}

		$meta = get_metadata_by_mid( 'post', $mid );
		$pid = (int) $meta->post_id;
		$meta = get_object_vars( $meta );
		$x = new BD_Ajax_Response( array(
			'what' => 'meta',
			'id' => $mid,
			'data' => _list_meta_row( $meta, $c ),
			'position' => 1,
			'supplemental' => array('postid' => $pid)
		) );
	} else { // Update?
		$mid = (int) key( $_POST['meta'] );
		$key = bd_unslash( $_POST['meta'][$mid]['key'] );
		$value = bd_unslash( $_POST['meta'][$mid]['value'] );
		if ( '' == trim($key) )
			bd_die( __( 'Please provide a custom field name.' ) );
		if ( '' == trim($value) )
			bd_die( __( 'Please provide a custom field value.' ) );
		if ( ! $meta = get_metadata_by_mid( 'post', $mid ) )
			bd_die( 0 ); // if meta doesn't exist
		if ( is_protected_meta( $meta->meta_key, 'post' ) || is_protected_meta( $key, 'post' ) ||
			! current_user_can( 'edit_post_meta', $meta->post_id, $meta->meta_key ) ||
			! current_user_can( 'edit_post_meta', $meta->post_id, $key ) )
			bd_die( -1 );
		if ( $meta->meta_value != $value || $meta->meta_key != $key ) {
			if ( !$u = update_metadata_by_mid( 'post', $mid, $value, $key ) )
				bd_die( 0 ); // We know meta exists; we also know it's unchanged (or DB error, in which case there are bigger problems).
		}

		$x = new BD_Ajax_Response( array(
			'what' => 'meta',
			'id' => $mid, 'old_id' => $mid,
			'data' => _list_meta_row( array(
				'meta_key' => $key,
				'meta_value' => $value,
				'meta_id' => $mid
			), $c ),
			'position' => 0,
			'supplemental' => array('postid' => $meta->post_id)
		) );
	}
	$x->send();
}

/**
 * Ajax handler for adding a user.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 *
 * @param string $action Action to perform.
 */
function bd_ajax_add_user( $action ) {
	global $bd_list_table;
	if ( empty( $action ) )
		$action = 'add-user';

	check_ajax_referer( $action );
	if ( ! current_user_can('create_users') )
		bd_die( -1 );
	if ( ! $user_id = edit_user() ) {
		bd_die( 0 );
	} elseif ( is_bd_error( $user_id ) ) {
		$x = new BD_Ajax_Response( array(
			'what' => 'user',
			'id' => $user_id
		) );
		$x->send();
	}
	$user_object = get_userdata( $user_id );

	$bd_list_table = _get_list_table('BD_Users_List_Table');

	$role = current( $user_object->roles );

	$x = new BD_Ajax_Response( array(
		'what' => 'user',
		'id' => $user_id,
		'data' => $bd_list_table->single_row( $user_object, '', $role ),
		'supplemental' => array(
			'show-link' => sprintf(__( 'User <a href="#%s">%s</a> added' ), "user-$user_id", $user_object->user_login),
			'role' => $role,
		)
	) );
	$x->send();
}

/**
 * Ajax handler for closed post boxes.
 *
 * @since 1.0.0
 */
function bd_ajax_closed_postboxes() {
	check_ajax_referer( 'closedpostboxes', 'closedpostboxesnonce' );
	$closed = isset( $_POST['closed'] ) ? explode( ',', $_POST['closed']) : array();
	$closed = array_filter($closed);

	$hidden = isset( $_POST['hidden'] ) ? explode( ',', $_POST['hidden']) : array();
	$hidden = array_filter($hidden);

	$page = isset( $_POST['page'] ) ? $_POST['page'] : '';

	if ( $page != sanitize_key( $page ) )
		bd_die( 0 );

	if ( ! $user = bd_get_current_user() )
		bd_die( -1 );

	if ( is_array($closed) )
		update_user_option($user->ID, "closedpostboxes_$page", $closed, true);

	if ( is_array($hidden) ) {
		$hidden = array_diff( $hidden, array('submitdiv', 'linksubmitdiv', 'manage-menu', 'create-menu') ); // postboxes that are always shown
		update_user_option($user->ID, "metaboxhidden_$page", $hidden, true);
	}

	bd_die( 1 );
}

/**
 * Ajax handler for hidden columns.
 *
 * @since 1.0.0
 */
function bd_ajax_hidden_columns() {
	check_ajax_referer( 'screen-options-nonce', 'screenoptionnonce' );
	$page = isset( $_POST['page'] ) ? $_POST['page'] : '';

	if ( $page != sanitize_key( $page ) )
		bd_die( 0 );

	if ( ! $user = bd_get_current_user() )
		bd_die( -1 );

	$hidden = ! empty( $_POST['hidden'] ) ? explode( ',', $_POST['hidden'] ) : array();
	update_user_option( $user->ID, "manage{$page}columnshidden", $hidden, true );

	bd_die( 1 );
}

/**
 * Ajax handler for updating whether to display the welcome panel.
 *
 * @since 1.0.0
 */
function bd_ajax_update_welcome_panel() {
	check_ajax_referer( 'welcome-panel-nonce', 'welcomepanelnonce' );

	if ( ! current_user_can( 'edit_theme_options' ) )
		bd_die( -1 );

	update_user_meta( get_current_user_id(), 'show_welcome_panel', empty( $_POST['visible'] ) ? 0 : 1 );

	bd_die( 1 );
}

/**
 * Ajax handler for retrieving menu meta boxes.
 *
 * @since 1.0.0
 */
function bd_ajax_menu_get_metabox() {
	if ( ! current_user_can( 'edit_theme_options' ) )
		bd_die( -1 );

	require_once ABSPATH . 'bd-admin/includes/nav-menu.php';

	if ( isset( $_POST['item-type'] ) && 'post_type' == $_POST['item-type'] ) {
		$type = 'posttype';
		$callback = 'bd_nav_menu_item_post_type_meta_box';
		$items = (array) get_post_types( array( 'show_in_nav_menus' => true ), 'object' );
	} elseif ( isset( $_POST['item-type'] ) && 'taxonomy' == $_POST['item-type'] ) {
		$type = 'taxonomy';
		$callback = 'bd_nav_menu_item_taxonomy_meta_box';
		$items = (array) get_taxonomies( array( 'show_ui' => true ), 'object' );
	}

	if ( ! empty( $_POST['item-object'] ) && isset( $items[$_POST['item-object']] ) ) {
		$menus_meta_box_object = $items[ $_POST['item-object'] ];

		/** This filter is documented in bd-admin/includes/nav-menu.php */
		$item = apply_filters( 'nav_menu_meta_box_object', $menus_meta_box_object );
		ob_start();
		call_user_func_array($callback, array(
			null,
			array(
				'id' => 'add-' . $item->name,
				'title' => $item->labels->name,
				'callback' => $callback,
				'args' => $item,
			)
		));

		$markup = ob_get_clean();

		echo bd_json_encode(array(
			'replace-id' => $type . '-' . $item->name,
			'markup' => $markup,
		));
	}

	bd_die();
}

/**
 * Ajax handler for internal linking.
 *
 * @since 1.0.0
 */
function bd_ajax_bd_link_ajax() {
	check_ajax_referer( 'internal-linking', '_ajax_linking_nonce' );

	$args = array();

	if ( isset( $_POST['search'] ) )
		$args['s'] = bd_unslash( $_POST['search'] );
	$args['pagenum'] = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;

	require(ABSPATH . BDINC . '/class-bd-editor.php');
	$results = _BD_Editors::bd_link_query( $args );

	if ( ! isset( $results ) )
		bd_die( 0 );

	echo bd_json_encode( $results );
	echo "\n";

	bd_die();
}

/**
 * Ajax handler for menu locations save.
 *
 * @since 1.0.0
 */
function bd_ajax_menu_locations_save() {
	if ( ! current_user_can( 'edit_theme_options' ) )
		bd_die( -1 );
	check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );
	if ( ! isset( $_POST['menu-locations'] ) )
		bd_die( 0 );
	set_theme_mod( 'nav_menu_locations', array_map( 'absint', $_POST['menu-locations'] ) );
	bd_die( 1 );
}

/**
 * Ajax handler for saving the meta box order.
 *
 * @since 1.0.0
 */
function bd_ajax_meta_box_order() {
	check_ajax_referer( 'meta-box-order' );
	$order = isset( $_POST['order'] ) ? (array) $_POST['order'] : false;
	$page_columns = isset( $_POST['page_columns'] ) ? $_POST['page_columns'] : 'auto';

	if ( $page_columns != 'auto' )
		$page_columns = (int) $page_columns;

	$page = isset( $_POST['page'] ) ? $_POST['page'] : '';

	if ( $page != sanitize_key( $page ) )
		bd_die( 0 );

	if ( ! $user = bd_get_current_user() )
		bd_die( -1 );

	if ( $order )
		update_user_option($user->ID, "meta-box-order_$page", $order, true);

	if ( $page_columns )
		update_user_option($user->ID, "screen_layout_$page", $page_columns, true);

	bd_die( 1 );
}

/**
 * Ajax handler for menu quick searching.
 *
 * @since 1.0.0
 */
function bd_ajax_menu_quick_search() {
	if ( ! current_user_can( 'edit_theme_options' ) )
		bd_die( -1 );

	require_once ABSPATH . 'bd-admin/includes/nav-menu.php';

	_bd_ajax_menu_quick_search( $_POST );

	bd_die();
}

/**
 * Ajax handler to retrieve a permalink.
 *
 * @since 1.0.0
 */
function bd_ajax_get_permalink() {
	check_ajax_referer( 'getpermalink', 'getpermalinknonce' );
	$post_id = isset($_POST['post_id'])? intval($_POST['post_id']) : 0;
	bd_die( add_query_arg( array( 'preview' => 'true' ), get_permalink( $post_id ) ) );
}

/**
 * Ajax handler to retrieve a sample permalink.
 *
 * @since 1.0.0
 */
function bd_ajax_sample_permalink() {
	check_ajax_referer( 'samplepermalink', 'samplepermalinknonce' );
	$post_id = isset($_POST['post_id'])? intval($_POST['post_id']) : 0;
	$title = isset($_POST['new_title'])? $_POST['new_title'] : '';
	$slug = isset($_POST['new_slug'])? $_POST['new_slug'] : null;
	bd_die( get_sample_permalink_html( $post_id, $title, $slug ) );
}

/**
 * Ajax handler for Quick Edit saving a post from a list table.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 */
function bd_ajax_inline_save() {
	global $bd_list_table;

	check_ajax_referer( 'inlineeditnonce', '_inline_edit' );

	if ( ! isset($_POST['post_ID']) || ! ( $post_ID = (int) $_POST['post_ID'] ) )
		bd_die();

	if ( 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_ID ) )
			bd_die( __( 'You are not allowed to edit this page.' ) );
	} else {
		if ( ! current_user_can( 'edit_post', $post_ID ) )
			bd_die( __( 'You are not allowed to edit this post.' ) );
	}

	if ( $last = bd_check_post_lock( $post_ID ) ) {
		$last_user = get_userdata( $last );
		$last_user_name = $last_user ? $last_user->display_name : __( 'Someone' );
		printf( $_POST['post_type'] == 'page' ? __( 'Saving is disabled: %s is currently editing this page.' ) : __( 'Saving is disabled: %s is currently editing this post.' ),	esc_html( $last_user_name ) );
		bd_die();
	}

	$data = &$_POST;

	$post = get_post( $post_ID, ARRAY_A );

	// Since it's coming from the database.
	$post = bd_slash($post);

	$data['content'] = $post['post_content'];
	$data['excerpt'] = $post['post_excerpt'];

	// Rename.
	$data['user_ID'] = get_current_user_id();

	if ( isset($data['post_parent']) )
		$data['parent_id'] = $data['post_parent'];

	// Status.
	if ( isset( $data['keep_private'] ) && 'private' == $data['keep_private'] ) {
		$data['visibility']  = 'private';
		$data['post_status'] = 'private';
	} else {
		$data['post_status'] = $data['_status'];
	}

	if ( empty($data['comment_status']) )
		$data['comment_status'] = 'closed';
	if ( empty($data['ping_status']) )
		$data['ping_status'] = 'closed';

	// Exclude terms from taxonomies that are not supposed to appear in Quick Edit.
	if ( ! empty( $data['tax_input'] ) ) {
		foreach ( $data['tax_input'] as $taxonomy => $terms ) {
			$tax_object = get_taxonomy( $taxonomy );
			/** This filter is documented in bd-admin/includes/class-bd-posts-list-table.php */
			if ( ! apply_filters( 'quick_edit_show_taxonomy', $tax_object->show_in_quick_edit, $taxonomy, $post['post_type'] ) ) {
				unset( $data['tax_input'][ $taxonomy ] );
			}
		}
	}

	// Hack: bd_unique_post_slug() doesn't work for drafts, so we will fake that our post is published.
	if ( ! empty( $data['post_name'] ) && in_array( $post['post_status'], array( 'draft', 'pending' ) ) ) {
		$post['post_status'] = 'publish';
		$data['post_name'] = bd_unique_post_slug( $data['post_name'], $post['ID'], $post['post_status'], $post['post_type'], $post['post_parent'] );
	}

	// Update the post.
	edit_post();

	$bd_list_table = _get_list_table( 'BD_Posts_List_Table', array( 'screen' => $_POST['screen'] ) );

	$level = 0;
	$request_post = array( get_post( $_POST['post_ID'] ) );
	$parent = $request_post[0]->post_parent;

	while ( $parent > 0 ) {
		$parent_post = get_post( $parent );
		$parent = $parent_post->post_parent;
		$level++;
	}

	$bd_list_table->display_rows( array( get_post( $_POST['post_ID'] ) ), $level );

	bd_die();
}

/**
 * Ajax handler for quick edit saving for a term.
 *
 * @since 1.0.0
 *
 * @global BD_List_Table $bd_list_table
 */
function bd_ajax_inline_save_tax() {
	global $bd_list_table;

	check_ajax_referer( 'taxinlineeditnonce', '_inline_edit' );

	$taxonomy = sanitize_key( $_POST['taxonomy'] );
	$tax = get_taxonomy( $taxonomy );
	if ( ! $tax )
		bd_die( 0 );

	if ( ! current_user_can( $tax->cap->edit_terms ) )
		bd_die( -1 );

	$bd_list_table = _get_list_table( 'BD_Terms_List_Table', array( 'screen' => 'edit-' . $taxonomy ) );

	if ( ! isset($_POST['tax_ID']) || ! ( $id = (int) $_POST['tax_ID'] ) )
		bd_die( -1 );

	$tag = get_term( $id, $taxonomy );
	$_POST['description'] = $tag->description;

	$updated = bd_update_term($id, $taxonomy, $_POST);
	if ( $updated && !is_bd_error($updated) ) {
		$tag = get_term( $updated['term_id'], $taxonomy );
		if ( !$tag || is_bd_error( $tag ) ) {
			if ( is_bd_error($tag) && $tag->get_error_message() )
				bd_die( $tag->get_error_message() );
			bd_die( __( 'Item not updated.' ) );
		}
	} else {
		if ( is_bd_error($updated) && $updated->get_error_message() )
			bd_die( $updated->get_error_message() );
		bd_die( __( 'Item not updated.' ) );
	}
	$level = 0;
	$parent = $tag->parent;
	while ( $parent > 0 ) {
		$parent_tag = get_term( $parent, $taxonomy );
		$parent = $parent_tag->parent;
		$level++;
	}
	$bd_list_table->single_row( $tag, $level );
	bd_die();
}

/**
 * Ajax handler for querying posts for the Find Posts modal.
 *
 * @see window.findPosts
 *
 * @since 1.0.0
 */
function bd_ajax_find_posts() {
	check_ajax_referer( 'find-posts' );

	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	unset( $post_types['attachment'] );

	$s = bd_unslash( $_POST['ps'] );
	$args = array(
		'post_type' => array_keys( $post_types ),
		'post_status' => 'any',
		'posts_per_page' => 50,
	);
	if ( '' !== $s )
		$args['s'] = $s;

	$posts = get_posts( $args );

	if ( ! $posts ) {
		bd_send_json_error( __( 'No items found.' ) );
	}

	$html = '<table class="widefat"><thead><tr><th class="found-radio"><br /></th><th>'.__('Title').'</th><th class="no-break">'.__('Type').'</th><th class="no-break">'.__('Date').'</th><th class="no-break">'.__('Status').'</th></tr></thead><tbody>';
	$alt = '';
	foreach ( $posts as $post ) {
		$title = trim( $post->post_title ) ? $post->post_title : __( '(no title)' );
		$alt = ( 'alternate' == $alt ) ? '' : 'alternate';

		switch ( $post->post_status ) {
			case 'publish' :
			case 'private' :
				$stat = __('Published');
				break;
			case 'future' :
				$stat = __('Scheduled');
				break;
			case 'pending' :
				$stat = __('Pending Review');
				break;
			case 'draft' :
				$stat = __('Draft');
				break;
		}

		if ( '0000-00-00 00:00:00' == $post->post_date ) {
			$time = '';
		} else {
			/* translators: date format in table columns */
			$time = mysql2date(__('Y/m/d'), $post->post_date);
		}

		$html .= '<tr class="' . trim( 'found-posts ' . $alt ) . '"><td class="found-radio"><input type="radio" id="found-'.$post->ID.'" name="found_post_id" value="' . esc_attr($post->ID) . '"></td>';
		$html .= '<td><label for="found-'.$post->ID.'">' . esc_html( $title ) . '</label></td><td class="no-break">' . esc_html( $post_types[$post->post_type]->labels->singular_name ) . '</td><td class="no-break">'.esc_html( $time ) . '</td><td class="no-break">' . esc_html( $stat ). ' </td></tr>' . "\n\n";
	}

	$html .= '</tbody></table>';

	bd_send_json_success( $html );
}

/**
 * Ajax handler for saving the widgets order.
 *
 * @since 1.0.0
 */
function bd_ajax_widgets_order() {
	check_ajax_referer( 'save-sidebar-widgets', 'savewidgets' );

	if ( !current_user_can('edit_theme_options') )
		bd_die( -1 );

	unset( $_POST['savewidgets'], $_POST['action'] );

	// Save widgets order for all sidebars.
	if ( is_array($_POST['sidebars']) ) {
		$sidebars = array();
		foreach ( $_POST['sidebars'] as $key => $val ) {
			$sb = array();
			if ( !empty($val) ) {
				$val = explode(',', $val);
				foreach ( $val as $k => $v ) {
					if ( strpos($v, 'widget-') === false )
						continue;

					$sb[$k] = substr($v, strpos($v, '_') + 1);
				}
			}
			$sidebars[$key] = $sb;
		}
		bd_set_sidebars_widgets($sidebars);
		bd_die( 1 );
	}

	bd_die( -1 );
}

/**
 * Ajax handler for saving a widget.
 *
 * @since 1.0.0
 *
 * @global array $bd_registered_widgets
 * @global array $bd_registered_widget_controls
 * @global array $bd_registered_widget_updates
 */
function bd_ajax_save_widget() {
	global $bd_registered_widgets, $bd_registered_widget_controls, $bd_registered_widget_updates;

	check_ajax_referer( 'save-sidebar-widgets', 'savewidgets' );

	if ( !current_user_can('edit_theme_options') || !isset($_POST['id_base']) )
		bd_die( -1 );

	unset( $_POST['savewidgets'], $_POST['action'] );

	/**
	 * Fires early when editing the widgets displayed in sidebars.
	 *
	 * @since 1.0.0
	 */
	do_action( 'load-widgets.php' );

	/**
	 * Fires early when editing the widgets displayed in sidebars.
	 *
	 * @since 1.0.0
	 */
	do_action( 'widgets.php' );

	/** This action is documented in bd-admin/widgets.php */
	do_action( 'sidebar_admin_setup' );

	$id_base = $_POST['id_base'];
	$widget_id = $_POST['widget-id'];
	$sidebar_id = $_POST['sidebar'];
	$multi_number = !empty($_POST['multi_number']) ? (int) $_POST['multi_number'] : 0;
	$settings = isset($_POST['widget-' . $id_base]) && is_array($_POST['widget-' . $id_base]) ? $_POST['widget-' . $id_base] : false;
	$error = '<p>' . __('An error has occurred. Please reload the page and try again.') . '</p>';

	$sidebars = bd_get_sidebars_widgets();
	$sidebar = isset($sidebars[$sidebar_id]) ? $sidebars[$sidebar_id] : array();

	// Delete.
	if ( isset($_POST['delete_widget']) && $_POST['delete_widget'] ) {

		if ( !isset($bd_registered_widgets[$widget_id]) )
			bd_die( $error );

		$sidebar = array_diff( $sidebar, array($widget_id) );
		$_POST = array('sidebar' => $sidebar_id, 'widget-' . $id_base => array(), 'the-widget-id' => $widget_id, 'delete_widget' => '1');
	} elseif ( $settings && preg_match( '/__i__|%i%/', key($settings) ) ) {
		if ( !$multi_number )
			bd_die( $error );

		$_POST[ 'widget-' . $id_base ] = array( $multi_number => reset( $settings ) );
		$widget_id = $id_base . '-' . $multi_number;
		$sidebar[] = $widget_id;
	}
	$_POST['widget-id'] = $sidebar;

	foreach ( (array) $bd_registered_widget_updates as $name => $control ) {

		if ( $name == $id_base ) {
			if ( !is_callable( $control['callback'] ) )
				continue;

			ob_start();
				call_user_func_array( $control['callback'], $control['params'] );
			ob_end_clean();
			break;
		}
	}

	if ( isset($_POST['delete_widget']) && $_POST['delete_widget'] ) {
		$sidebars[$sidebar_id] = $sidebar;
		bd_set_sidebars_widgets($sidebars);
		echo "deleted:$widget_id";
		bd_die();
	}

	if ( !empty($_POST['add_new']) )
		bd_die();

	if ( $form = $bd_registered_widget_controls[$widget_id] )
		call_user_func_array( $form['callback'], $form['params'] );

	bd_die();
}

/**
 * Ajax handler for saving a widget.
 *
 * @since 1.0.0
 *
 * @global BD_Customize_Manager $bd_customize
 */
function bd_ajax_update_widget() {
	global $bd_customize;
	$bd_customize->widgets->bd_ajax_update_widget();
}

/**
 * Ajax handler for uploading attachments
 *
 * @since 1.0.0
 */
function bd_ajax_upload_attachment() {
	check_ajax_referer( 'media-form' );
	/*
	 * This function does not use bd_send_json_success() / bd_send_json_error()
	 * as the html4 Plupload handler requires a text/html content-type for older IE.
	 */

	if ( ! current_user_can( 'upload_files' ) ) {
		echo bd_json_encode( array(
			'success' => false,
			'data'    => array(
				'message'  => __( "You don't have permission to upload files." ),
				'filename' => $_FILES['async-upload']['name'],
			)
		) );

		bd_die();
	}

	if ( isset( $_REQUEST['post_id'] ) ) {
		$post_id = $_REQUEST['post_id'];
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			echo bd_json_encode( array(
				'success' => false,
				'data'    => array(
					'message'  => __( "You don't have permission to attach files to this post." ),
					'filename' => $_FILES['async-upload']['name'],
				)
			) );

			bd_die();
		}
	} else {
		$post_id = null;
	}

	$post_data = isset( $_REQUEST['post_data'] ) ? $_REQUEST['post_data'] : array();

	// If the context is custom header or background, make sure the uploaded file is an image.
	if ( isset( $post_data['context'] ) && in_array( $post_data['context'], array( 'custom-header', 'custom-background' ) ) ) {
		$bd_filetype = bd_check_filetype_and_ext( $_FILES['async-upload']['tmp_name'], $_FILES['async-upload']['name'] );
		if ( ! bd_match_mime_types( 'image', $bd_filetype['type'] ) ) {
			echo bd_json_encode( array(
				'success' => false,
				'data'    => array(
					'message'  => __( 'The uploaded file is not a valid image. Please try again.' ),
					'filename' => $_FILES['async-upload']['name'],
				)
			) );

			bd_die();
		}
	}

	$attachment_id = media_handle_upload( 'async-upload', $post_id, $post_data );

	if ( is_bd_error( $attachment_id ) ) {
		echo bd_json_encode( array(
			'success' => false,
			'data'    => array(
				'message'  => $attachment_id->get_error_message(),
				'filename' => $_FILES['async-upload']['name'],
			)
		) );

		bd_die();
	}

	if ( isset( $post_data['context'] ) && isset( $post_data['theme'] ) ) {
		if ( 'custom-background' === $post_data['context'] )
			update_post_meta( $attachment_id, '_bd_attachment_is_custom_background', $post_data['theme'] );

		if ( 'custom-header' === $post_data['context'] )
			update_post_meta( $attachment_id, '_bd_attachment_is_custom_header', $post_data['theme'] );
	}

	if ( ! $attachment = bd_prepare_attachment_for_js( $attachment_id ) )
		bd_die();

	echo bd_json_encode( array(
		'success' => true,
		'data'    => $attachment,
	) );

	bd_die();
}

/**
 * Ajax handler for image editing.
 *
 * @since 1.0.0
 */
function bd_ajax_image_editor() {
	$attachment_id = intval($_POST['postid']);
	if ( empty($attachment_id) || !current_user_can('edit_post', $attachment_id) )
		bd_die( -1 );

	check_ajax_referer( "image_editor-$attachment_id" );
	include_once( ABSPATH . 'bd-admin/includes/image-edit.php' );

	$msg = false;
	switch ( $_POST['do'] ) {
		case 'save' :
			$msg = bd_save_image($attachment_id);
			$msg = bd_json_encode($msg);
			bd_die( $msg );
			break;
		case 'scale' :
			$msg = bd_save_image($attachment_id);
			break;
		case 'restore' :
			$msg = bd_restore_image($attachment_id);
			break;
	}

	bd_image_editor($attachment_id, $msg);
	bd_die();
}

/**
 * Ajax handler for setting the featured image.
 *
 * @since 1.0.0
 */
function bd_ajax_set_post_thumbnail() {
	$json = ! empty( $_REQUEST['json'] ); // New-style request

	$post_ID = intval( $_POST['post_id'] );
	if ( ! current_user_can( 'edit_post', $post_ID ) )
		bd_die( -1 );

	$thumbnail_id = intval( $_POST['thumbnail_id'] );

	if ( $json )
		check_ajax_referer( "update-post_$post_ID" );
	else
		check_ajax_referer( "set_post_thumbnail-$post_ID" );

	if ( $thumbnail_id == '-1' ) {
		if ( delete_post_thumbnail( $post_ID ) ) {
			$return = _bd_post_thumbnail_html( null, $post_ID );
			$json ? bd_send_json_success( $return ) : bd_die( $return );
		} else {
			bd_die( 0 );
		}
	}

	if ( set_post_thumbnail( $post_ID, $thumbnail_id ) ) {
		$return = _bd_post_thumbnail_html( $thumbnail_id, $post_ID );
		$json ? bd_send_json_success( $return ) : bd_die( $return );
	}

	bd_die( 0 );
}

/**
 * AJAX handler for setting the featured image for an attachment.
 *
 * @since 1.0.0
 *
 * @see set_post_thumbnail()
 */
function bd_ajax_set_attachment_thumbnail() {
	if ( empty( $_POST['urls'] ) || ! is_array( $_POST['urls'] ) ) {
		bd_send_json_error();
	}

	$thumbnail_id = (int) $_POST['thumbnail_id'];
	if ( empty( $thumbnail_id ) ) {
		bd_send_json_error();
	}

	$post_ids = array();
	// For each URL, try to find its corresponding post ID.
	foreach ( $_POST['urls'] as $url ) {
		$post_id = attachment_url_to_postid( $url );
		if ( ! empty( $post_id ) ) {
			$post_ids[] = $post_id;
		}
	}

	if ( empty( $post_ids ) ) {
		bd_send_json_error();
	}

	$success = 0;
	// For each found attachment, set its thumbnail.
	foreach ( $post_ids as $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			continue;
		}

		if ( set_post_thumbnail( $post_id, $thumbnail_id ) ) {
			$success++;
		}
	}

	if ( 0 === $success ) {
		bd_send_json_error();
	} else {
		bd_send_json_success();
	}

	bd_send_json_error();
}

/**
 * Ajax handler for date formatting.
 *
 * @since 1.0.0
 */
function bd_ajax_date_format() {
	bd_die( date_i18n( sanitize_option( 'date_format', bd_unslash( $_POST['date'] ) ) ) );
}

/**
 * Ajax handler for time formatting.
 *
 * @since 1.0.0
 */
function bd_ajax_time_format() {
	bd_die( date_i18n( sanitize_option( 'time_format', bd_unslash( $_POST['date'] ) ) ) );
}

/**
 * Ajax handler for saving posts from the fullscreen editor.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
function bd_ajax_bd_fullscreen_save_post() {
	$post_id = isset( $_POST['post_ID'] ) ? (int) $_POST['post_ID'] : 0;

	$post = null;

	if ( $post_id )
		$post = get_post( $post_id );

	check_ajax_referer('update-post_' . $post_id, '_bdnonce');

	$post_id = edit_post();

	if ( is_bd_error( $post_id ) ) {
		bd_send_json_error();
	}

	if ( $post ) {
		$last_date = mysql2date( get_option('date_format'), $post->post_modified );
		$last_time = mysql2date( get_option('time_format'), $post->post_modified );
	} else {
		$last_date = date_i18n( get_option('date_format') );
		$last_time = date_i18n( get_option('time_format') );
	}

	if ( $last_id = get_post_meta( $post_id, '_edit_last', true ) ) {
		$last_user = get_userdata( $last_id );
		$last_edited = sprintf( __('Last edited by %1$s on %2$s at %3$s'), esc_html( $last_user->display_name ), $last_date, $last_time );
	} else {
		$last_edited = sprintf( __('Last edited on %1$s at %2$s'), $last_date, $last_time );
	}

	bd_send_json_success( array( 'last_edited' => $last_edited ) );
}

/**
 * Ajax handler for removing a post lock.
 *
 * @since 1.0.0
 */
function bd_ajax_bd_remove_post_lock() {
	if ( empty( $_POST['post_ID'] ) || empty( $_POST['active_post_lock'] ) )
		bd_die( 0 );
	$post_id = (int) $_POST['post_ID'];
	if ( ! $post = get_post( $post_id ) )
		bd_die( 0 );

	check_ajax_referer( 'update-post_' . $post_id );

	if ( ! current_user_can( 'edit_post', $post_id ) )
		bd_die( -1 );

	$active_lock = array_map( 'absint', explode( ':', $_POST['active_post_lock'] ) );
	if ( $active_lock[1] != get_current_user_id() )
		bd_die( 0 );

	/**
	 * Filter the post lock window duration.
	 *
	 * @since 1.0.0
	 *
	 * @param int $interval The interval in seconds the post lock duration
	 *                      should last, plus 5 seconds. Default 150.
	 */
	$new_lock = ( time() - apply_filters( 'bd_check_post_lock_window', 150 ) + 5 ) . ':' . $active_lock[1];
	update_post_meta( $post_id, '_edit_lock', $new_lock, implode( ':', $active_lock ) );
	bd_die( 1 );
}

/**
 * Ajax handler for dismissing a Blasdoise pointer.
 *
 * @since 1.0.0
 */
function bd_ajax_dismiss_bd_pointer() {
	$pointer = $_POST['pointer'];
	if ( $pointer != sanitize_key( $pointer ) )
		bd_die( 0 );

//	check_ajax_referer( 'dismiss-pointer_' . $pointer );

	$dismissed = array_filter( explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_bd_pointers', true ) ) );

	if ( in_array( $pointer, $dismissed ) )
		bd_die( 0 );

	$dismissed[] = $pointer;
	$dismissed = implode( ',', $dismissed );

	update_user_meta( get_current_user_id(), 'dismissed_bd_pointers', $dismissed );
	bd_die( 1 );
}

/**
 * Ajax handler for getting an attachment.
 *
 * @since 1.0.0
 */
function bd_ajax_get_attachment() {
	if ( ! isset( $_REQUEST['id'] ) )
		bd_send_json_error();

	if ( ! $id = absint( $_REQUEST['id'] ) )
		bd_send_json_error();

	if ( ! $post = get_post( $id ) )
		bd_send_json_error();

	if ( 'attachment' != $post->post_type )
		bd_send_json_error();

	if ( ! current_user_can( 'upload_files' ) )
		bd_send_json_error();

	if ( ! $attachment = bd_prepare_attachment_for_js( $id ) )
		bd_send_json_error();

	bd_send_json_success( $attachment );
}

/**
 * Ajax handler for querying attachments.
 *
 * @since 1.0.0
 */
function bd_ajax_query_attachments() {
	if ( ! current_user_can( 'upload_files' ) )
		bd_send_json_error();

	$query = isset( $_REQUEST['query'] ) ? (array) $_REQUEST['query'] : array();
	$keys = array(
		's', 'order', 'orderby', 'posts_per_page', 'paged', 'post_mime_type',
		'post_parent', 'post__in', 'post__not_in', 'year', 'monthnum'
	);
	foreach ( get_taxonomies_for_attachments( 'objects' ) as $t ) {
		if ( $t->query_var && isset( $query[ $t->query_var ] ) ) {
			$keys[] = $t->query_var;
		}
	}

	$query = array_intersect_key( $query, array_flip( $keys ) );
	$query['post_type'] = 'attachment';
	if ( MEDIA_TRASH
		&& ! empty( $_REQUEST['query']['post_status'] )
		&& 'trash' === $_REQUEST['query']['post_status'] ) {
		$query['post_status'] = 'trash';
	} else {
		$query['post_status'] = 'inherit';
	}

	if ( current_user_can( get_post_type_object( 'attachment' )->cap->read_private_posts ) )
		$query['post_status'] .= ',private';

	/**
	 * Filter the arguments passed to BD_Query during an AJAX
	 * call for querying attachments.
	 *
	 * @since 1.0.0
	 *
	 * @see BD_Query::parse_query()
	 *
	 * @param array $query An array of query variables.
	 */
	$query = apply_filters( 'ajax_query_attachments_args', $query );
	$query = new BD_Query( $query );

	$posts = array_map( 'bd_prepare_attachment_for_js', $query->posts );
	$posts = array_filter( $posts );

	bd_send_json_success( $posts );
}

/**
 * Ajax handler for updating attachment attributes.
 *
 * @since 1.0.0
 */
function bd_ajax_save_attachment() {
	if ( ! isset( $_REQUEST['id'] ) || ! isset( $_REQUEST['changes'] ) )
		bd_send_json_error();

	if ( ! $id = absint( $_REQUEST['id'] ) )
		bd_send_json_error();

	check_ajax_referer( 'update-post_' . $id, 'nonce' );

	if ( ! current_user_can( 'edit_post', $id ) )
		bd_send_json_error();

	$changes = $_REQUEST['changes'];
	$post    = get_post( $id, ARRAY_A );

	if ( 'attachment' != $post['post_type'] )
		bd_send_json_error();

	if ( isset( $changes['parent'] ) )
		$post['post_parent'] = $changes['parent'];

	if ( isset( $changes['title'] ) )
		$post['post_title'] = $changes['title'];

	if ( isset( $changes['caption'] ) )
		$post['post_excerpt'] = $changes['caption'];

	if ( isset( $changes['description'] ) )
		$post['post_content'] = $changes['description'];

	if ( MEDIA_TRASH && isset( $changes['status'] ) )
		$post['post_status'] = $changes['status'];

	if ( isset( $changes['alt'] ) ) {
		$alt = bd_unslash( $changes['alt'] );
		if ( $alt != get_post_meta( $id, '_bd_attachment_image_alt', true ) ) {
			$alt = bd_strip_all_tags( $alt, true );
			update_post_meta( $id, '_bd_attachment_image_alt', bd_slash( $alt ) );
		}
	}

	if ( bd_attachment_is( 'audio', $post['ID'] ) ) {
		$changed = false;
		$id3data = bd_get_attachment_metadata( $post['ID'] );
		if ( ! is_array( $id3data ) ) {
			$changed = true;
			$id3data = array();
		}
		foreach ( bd_get_attachment_id3_keys( (object) $post, 'edit' ) as $key => $label ) {
			if ( isset( $changes[ $key ] ) ) {
				$changed = true;
				$id3data[ $key ] = sanitize_text_field( bd_unslash( $changes[ $key ] ) );
			}
		}

		if ( $changed ) {
			bd_update_attachment_metadata( $id, $id3data );
		}
	}

	if ( MEDIA_TRASH && isset( $changes['status'] ) && 'trash' === $changes['status'] ) {
		bd_delete_post( $id );
	} else {
		bd_update_post( $post );
	}

	bd_send_json_success();
}

/**
 * Ajax handler for saving backwards compatible attachment attributes.
 *
 * @since 1.0.0
 */
function bd_ajax_save_attachment_compat() {
	if ( ! isset( $_REQUEST['id'] ) )
		bd_send_json_error();

	if ( ! $id = absint( $_REQUEST['id'] ) )
		bd_send_json_error();

	if ( empty( $_REQUEST['attachments'] ) || empty( $_REQUEST['attachments'][ $id ] ) )
		bd_send_json_error();
	$attachment_data = $_REQUEST['attachments'][ $id ];

	check_ajax_referer( 'update-post_' . $id, 'nonce' );

	if ( ! current_user_can( 'edit_post', $id ) )
		bd_send_json_error();

	$post = get_post( $id, ARRAY_A );

	if ( 'attachment' != $post['post_type'] )
		bd_send_json_error();

	/** This filter is documented in bd-admin/includes/media.php */
	$post = apply_filters( 'attachment_fields_to_save', $post, $attachment_data );

	if ( isset( $post['errors'] ) ) {
		$errors = $post['errors']; // @todo return me and display me!
		unset( $post['errors'] );
	}

	bd_update_post( $post );

	foreach ( get_attachment_taxonomies( $post ) as $taxonomy ) {
		if ( isset( $attachment_data[ $taxonomy ] ) )
			bd_set_object_terms( $id, array_map( 'trim', preg_split( '/,+/', $attachment_data[ $taxonomy ] ) ), $taxonomy, false );
	}

	if ( ! $attachment = bd_prepare_attachment_for_js( $id ) )
		bd_send_json_error();

	bd_send_json_success( $attachment );
}

/**
 * Ajax handler for saving the attachment order.
 *
 * @since 1.0.0
 */
function bd_ajax_save_attachment_order() {
	if ( ! isset( $_REQUEST['post_id'] ) )
		bd_send_json_error();

	if ( ! $post_id = absint( $_REQUEST['post_id'] ) )
		bd_send_json_error();

	if ( empty( $_REQUEST['attachments'] ) )
		bd_send_json_error();

	check_ajax_referer( 'update-post_' . $post_id, 'nonce' );

	$attachments = $_REQUEST['attachments'];

	if ( ! current_user_can( 'edit_post', $post_id ) )
		bd_send_json_error();

	foreach ( $attachments as $attachment_id => $menu_order ) {
		if ( ! current_user_can( 'edit_post', $attachment_id ) )
			continue;
		if ( ! $attachment = get_post( $attachment_id ) )
			continue;
		if ( 'attachment' != $attachment->post_type )
			continue;

		bd_update_post( array( 'ID' => $attachment_id, 'menu_order' => $menu_order ) );
	}

	bd_send_json_success();
}

/**
 * Ajax handler for sending an attachment to the editor.
 *
 * Generates the HTML to send an attachment to the editor.
 * Backwards compatible with the media_send_to_editor filter
 * and the chain of filters that follow.
 *
 * @since 1.0.0
 */
function bd_ajax_send_attachment_to_editor() {
	check_ajax_referer( 'media-send-to-editor', 'nonce' );

	$attachment = bd_unslash( $_POST['attachment'] );

	$id = intval( $attachment['id'] );

	if ( ! $post = get_post( $id ) )
		bd_send_json_error();

	if ( 'attachment' != $post->post_type )
		bd_send_json_error();

	if ( current_user_can( 'edit_post', $id ) ) {
		// If this attachment is unattached, attach it. Primarily a back compat thing.
		if ( 0 == $post->post_parent && $insert_into_post_id = intval( $_POST['post_id'] ) ) {
			bd_update_post( array( 'ID' => $id, 'post_parent' => $insert_into_post_id ) );
		}
	}

	$rel = $url = '';
	$html = isset( $attachment['post_title'] ) ? $attachment['post_title'] : '';
	if ( ! empty( $attachment['url'] ) ) {
		$url = $attachment['url'];
		if ( strpos( $url, 'attachment_id') || get_attachment_link( $id ) == $url )
			$rel = ' rel="attachment bd-att-' . $id . '"';
		$html = '<a href="' . esc_url( $url ) . '"' . $rel . '>' . $html . '</a>';
	}

	remove_filter( 'media_send_to_editor', 'image_media_send_to_editor' );

	if ( 'image' === substr( $post->post_mime_type, 0, 5 ) ) {
		$align = isset( $attachment['align'] ) ? $attachment['align'] : 'none';
		$size = isset( $attachment['image-size'] ) ? $attachment['image-size'] : 'medium';
		$alt = isset( $attachment['image_alt'] ) ? $attachment['image_alt'] : '';

		// No whitespace-only captions.
		$caption = isset( $attachment['post_excerpt'] ) ? $attachment['post_excerpt'] : '';
		if ( '' === trim( $caption ) ) {
			$caption = '';
		}

		$title = ''; // We no longer insert title tags into <img> tags, as they are redundant.
		$html = get_image_send_to_editor( $id, $caption, $title, $align, $url, (bool) $rel, $size, $alt );
	} elseif ( bd_attachment_is( 'video', $post ) || bd_attachment_is( 'audio', $post )  ) {
		$html = stripslashes_deep( $_POST['html'] );
	}

	/** This filter is documented in bd-admin/includes/media.php */
	$html = apply_filters( 'media_send_to_editor', $html, $id, $attachment );

	bd_send_json_success( $html );
}

/**
 * Ajax handler for sending a link to the editor.
 *
 * Generates the HTML to send a non-image embed link to the editor.
 *
 * Backwards compatible with the following filters:
 * - file_send_to_editor_url
 * - audio_send_to_editor_url
 * - video_send_to_editor_url
 *
 * @since 1.0.0
 *
 * @global BD_Post  $post
 * @global BD_Embed $bd_embed
 */
function bd_ajax_send_link_to_editor() {
	global $post, $bd_embed;

	check_ajax_referer( 'media-send-to-editor', 'nonce' );

	if ( ! $src = bd_unslash( $_POST['src'] ) )
		bd_send_json_error();

	if ( ! strpos( $src, '://' ) )
		$src = 'http://' . $src;

	if ( ! $src = esc_url_raw( $src ) )
		bd_send_json_error();

	if ( ! $link_text = trim( bd_unslash( $_POST['link_text'] ) ) )
		$link_text = bd_basename( $src );

	$post = get_post( isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0 );

	// Ping Blasdoise for an embed.
	$check_embed = $bd_embed->run_shortcode( '[embed]'. $src .'[/embed]' );

	// Fallback that Blasdoise creates when no oEmbed was found.
	$fallback = $bd_embed->maybe_make_link( $src );

	if ( $check_embed !== $fallback ) {
		// TinyMCE view for [embed] will parse this
		$html = '[embed]' . $src . '[/embed]';
	} elseif ( $link_text ) {
		$html = '<a href="' . esc_url( $src ) . '">' . $link_text . '</a>';
	} else {
		$html = '';
	}

	// Figure out what filter to run:
	$type = 'file';
	if ( ( $ext = preg_replace( '/^.+?\.([^.]+)$/', '$1', $src ) ) && ( $ext_type = bd_ext2type( $ext ) )
		&& ( 'audio' == $ext_type || 'video' == $ext_type ) )
			$type = $ext_type;

	/** This filter is documented in bd-admin/includes/media.php */
	$html = apply_filters( $type . '_send_to_editor_url', $html, $src, $link_text );

	bd_send_json_success( $html );
}

/**
 * Ajax handler for the Heartbeat API.
 *
 * Runs when the user is logged in.
 *
 * @since 1.0.0
 */
function bd_ajax_heartbeat() {
	if ( empty( $_POST['_nonce'] ) ) {
		bd_send_json_error();
	}

	$response = $data = array();
	$nonce_state = bd_verify_nonce( $_POST['_nonce'], 'heartbeat-nonce' );

	// screen_id is the same as $current_screen->id and the JS global 'pagenow'.
	if ( ! empty( $_POST['screen_id'] ) ) {
		$screen_id = sanitize_key($_POST['screen_id']);
	} else {
		$screen_id = 'front';
	}

	if ( ! empty( $_POST['data'] ) ) {
		$data = bd_unslash( (array) $_POST['data'] );
	}

	if ( 1 !== $nonce_state ) {
		$response = apply_filters( 'bd_refresh_nonces', $response, $data, $screen_id );

		if ( false === $nonce_state ) {
			// User is logged in but nonces have expired.
			$response['nonces_expired'] = true;
			bd_send_json( $response );
		}
	}

	if ( ! empty( $data ) ) {
		/**
		 * Filter the Heartbeat response received.
		 *
		 * @since 1.0.0
		 *
		 * @param array|object $response  The Heartbeat response object or array.
		 * @param array        $data      The $_POST data sent.
		 * @param string       $screen_id The screen id.
		 */
		$response = apply_filters( 'heartbeat_received', $response, $data, $screen_id );
	}

	/**
	 * Filter the Heartbeat response sent.
	 *
	 * @since 1.0.0
	 *
	 * @param array|object $response  The Heartbeat response object or array.
	 * @param string       $screen_id The screen id.
	 */
	$response = apply_filters( 'heartbeat_send', $response, $screen_id );

	/**
	 * Fires when Heartbeat ticks in logged-in environments.
	 *
	 * Allows the transport to be easily replaced with long-polling.
	 *
	 * @since 1.0.0
	 *
	 * @param array|object $response  The Heartbeat response object or array.
	 * @param string       $screen_id The screen id.
	 */
	do_action( 'heartbeat_tick', $response, $screen_id );

	// Send the current time according to the server
	$response['server_time'] = time();

	bd_send_json( $response );
}

/**
 * Ajax handler for getting revision diffs.
 *
 * @since 1.0.0
 */
function bd_ajax_get_revision_diffs() {
	require ABSPATH . 'bd-admin/includes/revision.php';

	if ( ! $post = get_post( (int) $_REQUEST['post_id'] ) )
		bd_send_json_error();

	if ( ! current_user_can( 'read_post', $post->ID ) )
		bd_send_json_error();

	// Really just pre-loading the cache here.
	if ( ! $revisions = bd_get_post_revisions( $post->ID, array( 'check_enabled' => false ) ) )
		bd_send_json_error();

	$return = array();
	@set_time_limit( 0 );

	foreach ( $_REQUEST['compare'] as $compare_key ) {
		list( $compare_from, $compare_to ) = explode( ':', $compare_key ); // from:to

		$return[] = array(
			'id' => $compare_key,
			'fields' => bd_get_revision_ui_diff( $post, $compare_from, $compare_to ),
		);
	}
	bd_send_json_success( $return );
}

/**
 * Ajax handler for auto-saving the selected color scheme for
 * a user's own profile.
 *
 * @since 1.0.0
 *
 * @global array $_bd_admin_css_colors
 */
function bd_ajax_save_user_color_scheme() {
	global $_bd_admin_css_colors;

	check_ajax_referer( 'save-color-scheme', 'nonce' );

	$color_scheme = sanitize_key( $_POST['color_scheme'] );

	if ( ! isset( $_bd_admin_css_colors[ $color_scheme ] ) ) {
		bd_send_json_error();
	}

	$previous_color_scheme = get_user_meta( get_current_user_id(), 'admin_color', true );
	update_user_meta( get_current_user_id(), 'admin_color', $color_scheme );

	bd_send_json_success( array(
		'previousScheme' => 'admin-color-' . $previous_color_scheme,
		'currentScheme'  => 'admin-color-' . $color_scheme
	) );
}

/**
 * Ajax handler for getting themes from themes_api().
 *
 * @since 1.0.0
 *
 * @global array $themes_allowedtags
 * @global array $theme_field_defaults
 */
function bd_ajax_query_themes() {
	global $themes_allowedtags, $theme_field_defaults;

	if ( ! current_user_can( 'install_themes' ) ) {
		bd_send_json_error();
	}

	$args = bd_parse_args( bd_unslash( $_REQUEST['request'] ), array(
		'per_page' => 20,
		'fields'   => $theme_field_defaults
	) );

	$old_filter = isset( $args['browse'] ) ? $args['browse'] : 'search';

	/** This filter is documented in bd-admin/includes/class-bd-theme-install-list-table.php */
	$args = apply_filters( 'install_themes_table_api_args_' . $old_filter, $args );

	$api = themes_api( 'query_themes', $args );

	if ( is_bd_error( $api ) ) {
		bd_send_json_error();
	}

	$update_php = network_admin_url( 'update.php?action=install-theme' );
	foreach ( $api->themes as &$theme ) {
		$theme->install_url = add_query_arg( array(
			'theme'    => $theme->slug,
			'_bdnonce' => bd_create_nonce( 'install-theme_' . $theme->slug )
		), $update_php );

		$theme->name        = bd_kses( $theme->name, $themes_allowedtags );
		$theme->author      = bd_kses( $theme->author, $themes_allowedtags );
		$theme->version     = bd_kses( $theme->version, $themes_allowedtags );
		$theme->description = bd_kses( $theme->description, $themes_allowedtags );
		$theme->num_ratings = sprintf( _n( '(based on %s rating)', '(based on %s ratings)', $theme->num_ratings ), number_format_i18n( $theme->num_ratings ) );
		$theme->preview_url = set_url_scheme( $theme->preview_url );
	}

	bd_send_json_success( $api );
}

/**
 * Apply [embed] AJAX handlers to a string.
 *
 * @since 1.0.0
 *
 * @global BD_Post    $post       Global $post.
 * @global BD_Embed   $bd_embed   Embed API instance.
 * @global BD_Scripts $bd_scripts
 */
function bd_ajax_parse_embed() {
	global $post, $bd_embed;

	if ( ! $post = get_post( (int) $_POST['post_ID'] ) ) {
		bd_send_json_error();
	}

	if ( empty( $_POST['shortcode'] ) || ! current_user_can( 'edit_post', $post->ID ) ) {
		bd_send_json_error();
	}

	$shortcode = bd_unslash( $_POST['shortcode'] );

	preg_match( '/' . get_shortcode_regex() . '/s', $shortcode, $matches );
	$atts = shortcode_parse_atts( $matches[3] );
	if ( ! empty( $matches[5] ) ) {
		$url = $matches[5];
	} elseif ( ! empty( $atts['src'] ) ) {
		$url = $atts['src'];
	} else {
		$url = '';
	}

	$parsed = false;
	setup_postdata( $post );

	$bd_embed->return_false_on_fail = true;

	if ( is_ssl() && 0 === strpos( $url, 'http://' ) ) {
		// Admin is ssl and the user pasted non-ssl URL.
		// Check if the provider supports ssl embeds and use that for the preview.
		$ssl_shortcode = preg_replace( '%^(\\[embed[^\\]]*\\])http://%i', '$1https://', $shortcode );
		$parsed = $bd_embed->run_shortcode( $ssl_shortcode );

		if ( ! $parsed ) {
			$no_ssl_support = true;
		}
	}

	if ( $url && ! $parsed ) {
		$parsed = $bd_embed->run_shortcode( $shortcode );
	}

	if ( ! $parsed ) {
		bd_send_json_error( array(
			'type' => 'not-embeddable',
			'message' => sprintf( __( '%s failed to embed.' ), '<code>' . esc_html( $url ) . '</code>' ),
		) );
	}

	if ( has_shortcode( $parsed, 'audio' ) || has_shortcode( $parsed, 'video' ) ) {
		$styles = '';
		$mce_styles = bdview_media_sandbox_styles();
		foreach ( $mce_styles as $style ) {
			$styles .= sprintf( '<link rel="stylesheet" href="%s"/>', $style );
		}

		$html = do_shortcode( $parsed );

		global $bd_scripts;
		if ( ! empty( $bd_scripts ) ) {
			$bd_scripts->done = array();
		}
		ob_start();
		bd_print_scripts( 'bd-mediaelement' );
		$scripts = ob_get_clean();

		$parsed = $styles . $html . $scripts;
	}


	if ( ! empty( $no_ssl_support ) || ( is_ssl() && ( preg_match( '%<(iframe|script|embed) [^>]*src="http://%', $parsed ) ||
		preg_match( '%<link [^>]*href="http://%', $parsed ) ) ) ) {
		// Admin is ssl and the embed is not. Iframes, scripts, and other "active content" will be blocked.
		bd_send_json_error( array(
			'type' => 'not-ssl',
			'message' => __( 'This preview is unavailable in the editor.' ),
		) );
	}

	bd_send_json_success( array(
		'body' => $parsed,
		'attr' => $bd_embed->last_attr
	) );
}

/**
 * @since 1.0.0
 *
 * @global BD_Post    $post
 * @global BD_Scripts $bd_scripts
 */
function bd_ajax_parse_media_shortcode() {
	global $post, $bd_scripts;

	if ( empty( $_POST['shortcode'] ) ) {
		bd_send_json_error();
	}

	$shortcode = bd_unslash( $_POST['shortcode'] );

	if ( ! empty( $_POST['post_ID'] ) ) {
		$post = get_post( (int) $_POST['post_ID'] );
	}

	// the embed shortcode requires a post
	if ( ! $post || ! current_user_can( 'edit_post', $post->ID ) ) {
		if ( 'embed' === $shortcode ) {
			bd_send_json_error();
		}
	} else {
		setup_postdata( $post );
	}

	$parsed = do_shortcode( $shortcode  );

	if ( empty( $parsed ) ) {
		bd_send_json_error( array(
			'type' => 'no-items',
			'message' => __( 'No items found.' ),
		) );
	}

	$head = '';
	$styles = bdview_media_sandbox_styles();

	foreach ( $styles as $style ) {
		$head .= '<link type="text/css" rel="stylesheet" href="' . $style . '">';
	}

	if ( ! empty( $bd_scripts ) ) {
		$bd_scripts->done = array();
	}

	ob_start();

	echo $parsed;

	if ( 'playlist' === $_REQUEST['type'] ) {
		bd_underscore_playlist_templates();

		bd_print_scripts( 'bd-playlist' );
	} else {
		bd_print_scripts( array( 'froogaloop', 'bd-mediaelement' ) );
	}

	bd_send_json_success( array(
		'head' => $head,
		'body' => ob_get_clean()
	) );
}

/**
 * AJAX handler for destroying multiple open sessions for a user.
 *
 * @since 1.0.0
 */
function bd_ajax_destroy_sessions() {
	$user = get_userdata( (int) $_POST['user_id'] );
	if ( $user ) {
		if ( ! current_user_can( 'edit_user', $user->ID ) ) {
			$user = false;
		} elseif ( ! bd_verify_nonce( $_POST['nonce'], 'update-user_' . $user->ID ) ) {
			$user = false;
		}
	}

	if ( ! $user ) {
		bd_send_json_error( array(
			'message' => __( 'Could not log out user sessions. Please try again.' ),
		) );
	}

	$sessions = BD_Session_Tokens::get_instance( $user->ID );

	if ( $user->ID === get_current_user_id() ) {
		$sessions->destroy_others( bd_get_session_token() );
		$message = __( 'You are now logged out everywhere else.' );
	} else {
		$sessions->destroy_all();
		/* translators: 1: User's display name. */
		$message = sprintf( __( '%s has been logged out.' ), $user->display_name );
	}

	bd_send_json_success( array( 'message' => $message ) );
}


/**
 * AJAX handler for updating a plugin.
 *
 * @since 1.0.0
 *
 * @see Plugin_Upgrader
 */
function bd_ajax_update_plugin() {
	global $bd_filesystem;

	$plugin = urldecode( $_POST['plugin'] );

	$status = array(
		'update'     => 'plugin',
		'plugin'     => $plugin,
		'slug'       => sanitize_key( $_POST['slug'] ),
		'oldVersion' => '',
		'newVersion' => '',
	);

	$plugin_data = get_plugin_data( BD_PLUGIN_DIR . '/' . $plugin );
	if ( $plugin_data['Version'] ) {
		$status['oldVersion'] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
	}

	if ( ! current_user_can( 'update_plugins' ) ) {
		$status['error'] = __( 'You do not have sufficient permissions to update plugins for this site.' );
 		bd_send_json_error( $status );
	}

	check_ajax_referer( 'updates' );

	include_once( ABSPATH . 'bd-admin/includes/class-bd-upgrader.php' );

	bd_update_plugins();

	$skin = new Automatic_Upgrader_Skin();
	$upgrader = new Plugin_Upgrader( $skin );
	$result = $upgrader->bulk_upgrade( array( $plugin ) );

	if ( is_array( $result ) && empty( $result[$plugin] ) && is_bd_error( $skin->result ) ) {
		$result = $skin->result;
	}

	if ( is_array( $result ) && !empty( $result[ $plugin ] ) ) {
		$plugin_update_data = current( $result );

		/*
		 * If the `update_plugins` site transient is empty (e.g. when you update
		 * two plugins in quick succession before the transient repopulates),
		 * this may be the return.
		 *
		 * Preferably something can be done to ensure `update_plugins` isn't empty.
		 * For now, surface some sort of error here.
		 */
		if ( $plugin_update_data === true ) {
 			bd_send_json_error( $status );
		}

		$plugin_data = get_plugins( '/' . $result[ $plugin ]['destination_name'] );
		$plugin_data = reset( $plugin_data );

		if ( $plugin_data['Version'] ) {
			$status['newVersion'] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
		}

		bd_send_json_success( $status );
	} else if ( is_bd_error( $result ) ) {
		$status['error'] = $result->get_error_message();
 		bd_send_json_error( $status );

 	} else if ( is_bool( $result ) && ! $result ) {
		$status['errorCode'] = 'unable_to_connect_to_filesystem';
		$status['error'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );

		// Pass through the error from BD_Filesystem if one was raised
		if ( is_bd_error( $bd_filesystem->errors ) && $bd_filesystem->errors->get_error_code() ) {
			$status['error'] = $bd_filesystem->errors->get_error_message();
		}

		bd_send_json_error( $status );

	}
}

/**
 * AJAX handler for saving a post from Press This.
 *
 * @since 1.0.0
 *
 * @global BD_Press_This $bd_press_this
 */
function bd_ajax_press_this_save_post() {
	if ( empty( $GLOBALS['bd_press_this'] ) ) {
		include( ABSPATH . 'bd-admin/includes/class-bd-press-this.php' );
	}

	$GLOBALS['bd_press_this']->save_post();
}

/**
 * AJAX handler for creating new category from Press This.
 *
 * @since 1.0.0
 *
 * @global BD_Press_This $bd_press_this
 */
function bd_ajax_press_this_add_category() {
	if ( empty( $GLOBALS['bd_press_this'] ) ) {
		include( ABSPATH . 'bd-admin/includes/class-bd-press-this.php' );
	}

	$GLOBALS['bd_press_this']->add_category();
}

/**
 * AJAX handler for cropping an image.
 *
 * @since 1.0.0
 *
 * @global BD_Site_Icon $bd_site_icon
 */
function bd_ajax_crop_image() {
	$attachment_id = absint( $_POST['id'] );

	check_ajax_referer( 'image_editor-' . $attachment_id, 'nonce' );
	if ( ! current_user_can( 'customize' ) ) {
		bd_send_json_error();
	}

	$context = str_replace( '_', '-', $_POST['context'] );
	$data    = array_map( 'absint', $_POST['cropDetails'] );
	$cropped = bd_crop_image( $attachment_id, $data['x1'], $data['y1'], $data['width'], $data['height'], $data['dst_width'], $data['dst_height'] );

	if ( ! $cropped || is_bd_error( $cropped ) ) {
		bd_send_json_error( array( 'message' => __( 'Image could not be processed.' ) ) );
	}

	switch ( $context ) {
		case 'site-icon':
			require_once ABSPATH . '/bd-admin/includes/class-bd-site-icon.php';
			global $bd_site_icon;

			// Skip creating a new attachment if the attachment is a Site Icon.
			if ( get_post_meta( $attachment_id, '_bd_attachment_context', true ) == $context ) {

				// Delete the temporary cropped file, we don't need it.
				bd_delete_file( $cropped );

				// Additional sizes in bd_prepare_attachment_for_js().
				add_filter( 'image_size_names_choose', array( $bd_site_icon, 'additional_sizes' ) );
				break;
			}

			/** This filter is documented in bd-admin/custom-header.php */
			$cropped = apply_filters( 'bd_create_file_in_uploads', $cropped, $attachment_id ); // For replication.
			$object  = $bd_site_icon->create_attachment_object( $cropped, $attachment_id );
			unset( $object['ID'] );

			// Update the attachment.
			add_filter( 'intermediate_image_sizes_advanced', array( $bd_site_icon, 'additional_sizes' ) );
			$attachment_id = $bd_site_icon->insert_attachment( $object, $cropped );
			remove_filter( 'intermediate_image_sizes_advanced', array( $bd_site_icon, 'additional_sizes' ) );

			// Additional sizes in bd_prepare_attachment_for_js().
			add_filter( 'image_size_names_choose', array( $bd_site_icon, 'additional_sizes' ) );
			break;

		default:

			/**
			 * Fires before a cropped image is saved.
			 *
			 * Allows to add filters to modify the way a cropped image is saved.
			 *
			 * @since 1.0.0
			 *
			 * @param string $context       The Customizer control requesting the cropped image.
			 * @param int    $attachment_id The attachment ID of the original image.
			 * @param string $cropped       Path to the cropped image file.
			 */
			do_action( 'bd_ajax_crop_image_pre_save', $context, $attachment_id, $cropped );

			/** This filter is documented in bd-admin/custom-header.php */
			$cropped = apply_filters( 'bd_create_file_in_uploads', $cropped, $attachment_id ); // For replication.

			$parent_url = get_post( $attachment_id )->guid;
			$url        = str_replace( basename( $parent_url ), basename( $cropped ), $parent_url );

			$size       = @getimagesize( $cropped );
			$image_type = ( $size ) ? $size['mime'] : 'image/jpeg';

			$object = array(
				'post_title'     => basename( $cropped ),
				'post_content'   => $url,
				'post_mime_type' => $image_type,
				'guid'           => $url,
				'context'        => $context,
			);

			$attachment_id = bd_insert_attachment( $object, $cropped );
			$metadata = bd_generate_attachment_metadata( $attachment_id, $cropped );

			/**
			 * Filter the cropped image attachment metadata.
			 *
			 * @since 1.0.0
			 *
			 * @see bd_generate_attachment_metadata()
			 *
			 * @param array $metadata Attachment metadata.
			 */
			$metadata = apply_filters( 'bd_ajax_cropped_attachment_metadata', $metadata );
			bd_update_attachment_metadata( $attachment_id, $metadata );

			/**
			 * Filter the attachment ID for a cropped image.
			 *
			 * @since 1.0.0
			 *
			 * @param int    $attachment_id The attachment ID of the cropped image.
			 * @param string $context       The Customizer control requesting the cropped image.
			 */
			$attachment_id = apply_filters( 'bd_ajax_cropped_attachment_id', $attachment_id, $context );
	}

	bd_send_json_success( bd_prepare_attachment_for_js( $attachment_id ) );
}
