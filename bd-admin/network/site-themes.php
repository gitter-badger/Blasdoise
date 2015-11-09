<?php
/**
 * Edit Site Themes Administration Screen
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! is_multisite() )
	bd_die( __( 'Multisite support is not enabled.' ) );

if ( ! current_user_can( 'manage_sites' ) )
	bd_die( __( 'You do not have sufficient permissions to manage themes for this site.' ) );

get_current_screen()->add_help_tab( array(
	'id'      => 'overview',
	'title'   => __('Overview'),
	'content' =>
		'<p>' . __('The menu is for editing information specific to individual sites, particularly if the admin area of a site is unavailable.') . '</p>' .
		'<p>' . __('<strong>Info</strong> - The domain and path are rarely edited as this can cause the site to not work properly. The Registered date and Last Updated date are displayed. Network admins can mark a site as archived, spam, deleted and mature, to remove from public listings or disable.') . '</p>' .
		'<p>' . __('<strong>Users</strong> - This displays the users associated with this site. You can also change their role, reset their password, or remove them from the site. Removing the user from the site does not remove the user from the network.') . '</p>' .
		'<p>' . sprintf( __('<strong>Themes</strong> - This area shows themes that are not already enabled across the network. Enabling a theme in this menu makes it accessible to this site. It does not activate the theme, but allows it to show in the site&#8217;s Appearance menu. To enable a theme for the entire network, see the <a href="%s">Network Themes</a> screen.' ), network_admin_url( 'themes.php' ) ) . '</p>' .
		'<p>' . __('<strong>Settings</strong> - This page shows a list of all settings associated with this site. Some are created by Blasdoise and others are created by plugins you activate. Note that some fields are grayed out and say Serialized Data. You cannot modify these values due to the way the setting is stored in the database.') . '</p>'
) );

get_current_screen()->set_help_sidebar(
    '<p><strong>' . __( 'For support:' ) . '</strong></p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Like Us on Facebook</a>'), 'http://facebook.com/blasdoise' ) . '</p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Follow Us on Twitter</a>'), 'http://twitter.com/blasdoise' ) . '</p>'
);

$bd_list_table = _get_list_table('BD_MS_Themes_List_Table');

$action = $bd_list_table->current_action();

$s = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';

// Clean up request URI from temporary args for screen options/paging uri's to work as expected.
$temp_args = array( 'enabled', 'disabled', 'error' );
$_SERVER['REQUEST_URI'] = remove_query_arg( $temp_args, $_SERVER['REQUEST_URI'] );
$referer = remove_query_arg( $temp_args, bd_get_referer() );

if ( ! empty( $_REQUEST['paged'] ) ) {
	$referer = add_query_arg( 'paged', (int) $_REQUEST['paged'], $referer );
}

$id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

if ( ! $id )
	bd_die( __('Invalid site ID.') );

$bd_list_table->prepare_items();

$details = get_blog_details( $id );
if ( ! $details ) {
	bd_die( __( 'The requested site does not exist.' ) );
}

if ( !can_edit_network( $details->site_id ) )
	bd_die( __( 'You do not have permission to access this page.' ), 403 );

$is_main_site = is_main_site( $id );

if ( $action ) {
	switch_to_blog( $id );
	$allowed_themes = get_option( 'allowedthemes' );

	switch ( $action ) {
		case 'enable':
			check_admin_referer( 'enable-theme_' . $_GET['theme'] );
			$theme = $_GET['theme'];
			$action = 'enabled';
			$n = 1;
			if ( !$allowed_themes )
				$allowed_themes = array( $theme => true );
			else
				$allowed_themes[$theme] = true;
			break;
		case 'disable':
			check_admin_referer( 'disable-theme_' . $_GET['theme'] );
			$theme = $_GET['theme'];
			$action = 'disabled';
			$n = 1;
			if ( !$allowed_themes )
				$allowed_themes = array();
			else
				unset( $allowed_themes[$theme] );
			break;
		case 'enable-selected':
			check_admin_referer( 'bulk-themes' );
			if ( isset( $_POST['checked'] ) ) {
				$themes = (array) $_POST['checked'];
				$action = 'enabled';
				$n = count( $themes );
				foreach( (array) $themes as $theme )
					$allowed_themes[ $theme ] = true;
			} else {
				$action = 'error';
				$n = 'none';
			}
			break;
		case 'disable-selected':
			check_admin_referer( 'bulk-themes' );
			if ( isset( $_POST['checked'] ) ) {
				$themes = (array) $_POST['checked'];
				$action = 'disabled';
				$n = count( $themes );
				foreach( (array) $themes as $theme )
					unset( $allowed_themes[ $theme ] );
			} else {
				$action = 'error';
				$n = 'none';
			}
			break;
	}

	update_option( 'allowedthemes', $allowed_themes );
	restore_current_blog();

	bd_safe_redirect( add_query_arg( array( 'id' => $id, $action => $n ), $referer ) );
	exit;
}

if ( isset( $_GET['action'] ) && 'update-site' == $_GET['action'] ) {
	bd_safe_redirect( $referer );
	exit();
}

add_thickbox();
add_screen_option( 'per_page' );

$title = sprintf( __( 'Edit Site: %s' ), esc_html( $details->blogname ) );

$parent_file = 'sites.php';
$submenu_file = 'sites.php';

require( ABSPATH . 'bd-admin/admin-header.php' ); ?>

<div class="wrap">
<h1 id="edit-site"><?php echo $title; ?></h1>
<p class="edit-site-actions"><a href="<?php echo esc_url( get_home_url( $id, '/' ) ); ?>"><?php _e( 'Visit' ); ?></a> | <a href="<?php echo esc_url( get_admin_url( $id ) ); ?>"><?php _e( 'Dashboard' ); ?></a></p>
<h3 class="nav-tab-wrapper">
<?php
$tabs = array(
	'site-info'     => array( 'label' => __( 'Info' ),     'url' => 'site-info.php'     ),
	'site-users'    => array( 'label' => __( 'Users' ),    'url' => 'site-users.php'    ),
	'site-themes'   => array( 'label' => __( 'Themes' ),   'url' => 'site-themes.php'   ),
	'site-settings' => array( 'label' => __( 'Settings' ), 'url' => 'site-settings.php' ),
);
foreach ( $tabs as $tab_id => $tab ) {
	$class = ( $tab['url'] == $pagenow ) ? ' nav-tab-active' : '';
	echo '<a href="' . $tab['url'] . '?id=' . $id .'" class="nav-tab' . $class . '">' . esc_html( $tab['label'] ) . '</a>';
}
?>
</h3><?php

if ( isset( $_GET['enabled'] ) ) {
	$enabled = absint( $_GET['enabled'] );
	if ( 1 == $enabled ) {
		$message = __( 'Theme enabled.' );
	} else {
		$message = _n( '%s theme enabled.', '%s themes enabled.', $enabled );
	}
	echo '<div id="message" class="updated notice is-dismissible"><p>' . sprintf( $message, number_format_i18n( $enabled ) ) . '</p></div>';
} elseif ( isset( $_GET['disabled'] ) ) {
	$disabled = absint( $_GET['disabled'] );
	if ( 1 == $disabled ) {
		$message = __( 'Theme disabled.' );
	} else {
		$message = _n( '%s theme disabled.', '%s themes disabled.', $disabled );
	}
	echo '<div id="message" class="updated notice is-dismissible"><p>' . sprintf( $message, number_format_i18n( $disabled ) ) . '</p></div>';
} elseif ( isset( $_GET['error'] ) && 'none' == $_GET['error'] ) {
	echo '<div id="message" class="error notice is-dismissible"><p>' . __( 'No theme selected.' ) . '</p></div>';
} ?>

<p><?php _e( 'Network enabled themes are not shown on this screen.' ) ?></p>

<form method="get">
<?php $bd_list_table->search_box( __( 'Search Installed Themes' ), 'theme' ); ?>
<input type="hidden" name="id" value="<?php echo esc_attr( $id ) ?>" />
</form>

<?php $bd_list_table->views(); ?>

<form method="post" action="site-themes.php?action=update-site">
	<input type="hidden" name="id" value="<?php echo esc_attr( $id ) ?>" />

<?php $bd_list_table->display(); ?>

</form>

</div>
<?php include(ABSPATH . 'bd-admin/admin-footer.php'); ?>
