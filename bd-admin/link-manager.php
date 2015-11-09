<?php
/**
 * Link Management Administration Screen.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );
if ( ! current_user_can( 'manage_links' ) )
	bd_die( __( 'You do not have sufficient permissions to edit the links for this site.' ) );

$bd_list_table = _get_list_table('BD_Links_List_Table');

// Handle bulk deletes
$doaction = $bd_list_table->current_action();

if ( $doaction && isset( $_REQUEST['linkcheck'] ) ) {
	check_admin_referer( 'bulk-bookmarks' );

	if ( 'delete' == $doaction ) {
		$bulklinks = (array) $_REQUEST['linkcheck'];
		foreach ( $bulklinks as $link_id ) {
			$link_id = (int) $link_id;

			bd_delete_link( $link_id );
		}

		bd_redirect( add_query_arg('deleted', count( $bulklinks ), admin_url( 'link-manager.php' ) ) );
		exit;
	}
} elseif ( ! empty( $_GET['_bd_http_referer'] ) ) {
	 bd_redirect( remove_query_arg( array( '_bd_http_referer', '_bdnonce' ), bd_unslash( $_SERVER['REQUEST_URI'] ) ) );
	 exit;
}

$bd_list_table->prepare_items();

$title = __('Links');
$this_file = $parent_file = 'link-manager.php';

get_current_screen()->add_help_tab( array(
'id'		=> 'overview',
'title'		=> __('Overview'),
'content'	=>
	'<p>' . sprintf(__('You can add links here to be displayed on your site, usually using <a href="%s">Widgets</a>. By default, links to several sites in the Blasdoise community are included as examples.'), 'widgets.php') . '</p>' .
    '<p>' . __('Links may be separated into Link Categories; these are different than the categories used on your posts.') . '</p>' .
    '<p>' . __('You can customize the display of this screen using the Screen Options tab and/or the dropdown filters above the links table.') . '</p>'
) );
get_current_screen()->add_help_tab( array(
'id'		=> 'deleting-links',
'title'		=> __('Deleting Links'),
'content'	=>
    '<p>' . __('If you delete a link, it will be removed permanently, as Links do not have a Trash function yet.') . '</p>'
) );

get_current_screen()->set_help_sidebar(
    '<p><strong>' . __( 'For support:' ) . '</strong></p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Like Us on Facebook</a>'), 'http://facebook.com/blasdoise' ) . '</p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Follow Us on Twitter</a>'), 'http://twitter.com/blasdoise' ) . '</p>'
);

include_once( ABSPATH . 'bd-admin/admin-header.php' );

if ( ! current_user_can('manage_links') )
	bd_die(__("You do not have sufficient permissions to edit the links for this site."));

?>

<div class="wrap nosubsub">
<h1><?php echo esc_html( $title ); ?> <a href="link-add.php" class="page-title-action"><?php echo esc_html_x('Add New', 'link'); ?></a> <?php
if ( !empty($_REQUEST['s']) )
	printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( bd_unslash($_REQUEST['s']) ) ); ?>
</h1>

<?php
if ( isset($_REQUEST['deleted']) ) {
	echo '<div id="message" class="updated notice is-dismissible"><p>';
	$deleted = (int) $_REQUEST['deleted'];
	printf(_n('%s link deleted.', '%s links deleted', $deleted), $deleted);
	echo '</p></div>';
	$_SERVER['REQUEST_URI'] = remove_query_arg(array('deleted'), $_SERVER['REQUEST_URI']);
}
?>

<form id="posts-filter" method="get">

<?php $bd_list_table->search_box( __( 'Search Links' ), 'link' ); ?>

<?php $bd_list_table->display(); ?>

<div id="ajax-response"></div>
</form>

</div>

<?php
include( ABSPATH . 'bd-admin/admin-footer.php' );
