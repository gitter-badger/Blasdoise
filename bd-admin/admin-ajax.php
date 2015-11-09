<?php
/**
 * Blasdoise AJAX Process Execution.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/**
 * Executing AJAX process.
 *
 * @since 1.0.0
 */
define( 'DOING_AJAX', true );
if ( ! defined( 'BD_ADMIN' ) ) {
	define( 'BD_ADMIN', true );
}

/** Load Blasdoise Bootstrap */
require_once( dirname( dirname( __FILE__ ) ) . '/bd-load.php' );

/** Allow for cross-domain requests (from the frontend). */
send_origin_headers();

// Require an action parameter
if ( empty( $_REQUEST['action'] ) )
	die( '0' );

/** Load Blasdoise Administration APIs */
require_once( ABSPATH . 'bd-admin/includes/admin.php' );

/** Load Ajax Handlers for Blasdoise Core */
require_once( ABSPATH . 'bd-admin/includes/ajax-actions.php' );

@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );

send_nosniff_header();
nocache_headers();

/** This action is documented in bd-admin/admin.php */
do_action( 'admin_init' );

$core_actions_get = array(
	'fetch-list', 'ajax-tag-search', 'bd-compression-test', 'imgedit-preview', 'oembed-cache',
	'autocomplete-user', 'dashboard-widgets', 'logged-in',
);

$core_actions_post = array(
	'oembed-cache', 'image-editor', 'delete-comment', 'delete-tag', 'delete-link',
	'delete-meta', 'delete-post', 'trash-post', 'untrash-post', 'delete-page', 'dim-comment',
	'add-link-category', 'add-tag', 'get-tagcloud', 'get-comments', 'replyto-comment',
	'edit-comment', 'add-menu-item', 'add-meta', 'add-user', 'closed-postboxes',
	'hidden-columns', 'update-welcome-panel', 'menu-get-metabox', 'bd-link-ajax',
	'menu-locations-save', 'menu-quick-search', 'meta-box-order', 'get-permalink',
	'sample-permalink', 'inline-save', 'inline-save-tax', 'find_posts', 'widgets-order',
	'save-widget', 'set-post-thumbnail', 'date_format', 'time_format',
	'bd-remove-post-lock', 'dismiss-bd-pointer', 'upload-attachment', 'get-attachment',
	'query-attachments', 'save-attachment', 'save-attachment-compat', 'send-link-to-editor',
	'send-attachment-to-editor', 'save-attachment-order', 'heartbeat', 'get-revision-diffs',
	'save-user-color-scheme', 'update-widget', 'query-themes', 'parse-embed', 'set-attachment-thumbnail',
	'parse-media-shortcode', 'destroy-sessions', 'install-plugin', 'update-plugin', 'press-this-save-post',
	'press-this-add-category', 'crop-image',
);

// Deprecated
$core_actions_post[] = 'bd-fullscreen-save-post';

// Register core Ajax calls.
if ( ! empty( $_GET['action'] ) && in_array( $_GET['action'], $core_actions_get ) )
	add_action( 'bd_ajax_' . $_GET['action'], 'bd_ajax_' . str_replace( '-', '_', $_GET['action'] ), 1 );

if ( ! empty( $_POST['action'] ) && in_array( $_POST['action'], $core_actions_post ) )
	add_action( 'bd_ajax_' . $_POST['action'], 'bd_ajax_' . str_replace( '-', '_', $_POST['action'] ), 1 );

add_action( 'bd_ajax_nopriv_heartbeat', 'bd_ajax_nopriv_heartbeat', 1 );

if ( is_user_logged_in() ) {
	/**
	 * Fires authenticated AJAX actions for logged-in users.
	 *
	 * The dynamic portion of the hook name, `$_REQUEST['action']`,
	 * refers to the name of the AJAX action callback being fired.
	 *
	 * @since 1.0.0
	 */
	do_action( 'bd_ajax_' . $_REQUEST['action'] );
} else {
	/**
	 * Fires non-authenticated AJAX actions for logged-out users.
	 *
	 * The dynamic portion of the hook name, `$_REQUEST['action']`,
	 * refers to the name of the AJAX action callback being fired.
	 *
	 * @since 1.0.0
	 */
	do_action( 'bd_ajax_nopriv_' . $_REQUEST['action'] );
}
// Default status
die( '0' );
