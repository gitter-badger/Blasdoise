<?php
/**
 * Manage link administration actions.
 *
 * This page is accessed by the link management pages and handles the forms and
 * AJAX processes for link actions.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

bd_reset_vars( array( 'action', 'cat_id', 'link_id' ) );

if ( ! current_user_can('manage_links') )
	bd_link_manager_disabled_message();

if ( !empty($_POST['deletebookmarks']) )
	$action = 'deletebookmarks';
if ( !empty($_POST['move']) )
	$action = 'move';
if ( !empty($_POST['linkcheck']) )
	$linkcheck = $_POST['linkcheck'];

$this_file = admin_url('link-manager.php');

switch ($action) {
	case 'deletebookmarks' :
		check_admin_referer('bulk-bookmarks');

		// For each link id (in $linkcheck[]) change category to selected value.
		if (count($linkcheck) == 0) {
			bd_redirect($this_file);
			exit;
		}

		$deleted = 0;
		foreach ($linkcheck as $link_id) {
			$link_id = (int) $link_id;

			if ( bd_delete_link($link_id) )
				$deleted++;
		}

		bd_redirect("$this_file?deleted=$deleted");
		exit;

	case 'move' :
		check_admin_referer('bulk-bookmarks');

		// For each link id (in $linkcheck[]) change category to selected value.
		if (count($linkcheck) == 0) {
			bd_redirect($this_file);
			exit;
		}
		$all_links = join(',', $linkcheck);
		/*
		 * Should now have an array of links we can change:
		 *     $q = $bddb->query("update $bddb->links SET link_category='$category' WHERE link_id IN ($all_links)");
		 */

		bd_redirect($this_file);
		exit;

	case 'add' :
		check_admin_referer('add-bookmark');

		$redir = bd_get_referer();
		if ( add_link() )
			$redir = add_query_arg( 'added', 'true', $redir );

		bd_redirect( $redir );
		exit;

	case 'save' :
		$link_id = (int) $_POST['link_id'];
		check_admin_referer('update-bookmark_' . $link_id);

		edit_link($link_id);

		bd_redirect($this_file);
		exit;

	case 'delete' :
		$link_id = (int) $_GET['link_id'];
		check_admin_referer('delete-bookmark_' . $link_id);

		bd_delete_link($link_id);

		bd_redirect($this_file);
		exit;

	case 'edit' :
		bd_enqueue_script('link');
		bd_enqueue_script('xfn');

		if ( bd_is_mobile() )
			bd_enqueue_script( 'jquery-touch-punch' );

		$parent_file = 'link-manager.php';
		$submenu_file = 'link-manager.php';
		$title = __('Edit Link');

		$link_id = (int) $_GET['link_id'];

		if (!$link = get_link_to_edit($link_id))
			bd_die(__('Link not found.'));

		include( ABSPATH . 'bd-admin/edit-link-form.php' );
		include( ABSPATH . 'bd-admin/admin-footer.php' );
		break;

	default :
		break;
}
