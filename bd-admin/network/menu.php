<?php
/**
 * Build Network Administration Menu.
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

/* translators: Network menu item */
$menu[2] = array(__('Dashboard'), 'manage_network', 'index.php', '', 'menu-top menu-top-first menu-icon-dashboard', 'menu-dashboard', 'basicons-dashboard');

$submenu['index.php'][0] = array( __( 'Home' ), 'read', 'index.php' );

$update_data = bd_get_update_data();
if ( $update_data['counts']['total'] ) {
	$submenu['index.php'][10] = array( sprintf( __( 'Updates %s' ), "<span class='update-plugins count-{$update_data['counts']['total']}' title='{$update_data['title']}'><span class='update-count'>" . number_format_i18n( $update_data['counts']['total'] ) . "</span></span>" ), 'update_core', 'update-core.php' );
} else {
	$submenu['index.php'][10] = array( __( 'Updates' ), 'update_core', 'update-core.php' );
}

$submenu['index.php'][15] = array( __( 'Upgrade Network' ), 'manage_network', 'upgrade.php' );

$menu[4] = array( '', 'read', 'separator1', '', 'bd-menu-separator' );

/* translators: Sites menu item */
$menu[5] = array(__('Sites'), 'manage_sites', 'sites.php', '', 'menu-top menu-icon-site', 'menu-site', 'basicons-admin-network');
$submenu['sites.php'][5]  = array( __('All Sites'), 'manage_sites', 'sites.php' );
$submenu['sites.php'][10]  = array( _x('Add New', 'site'), 'create_sites', 'site-new.php' );

$menu[10] = array(__('Users'), 'manage_network_users', 'users.php', '', 'menu-top menu-icon-users', 'menu-users', 'basicons-admin-users');
$submenu['users.php'][5]  = array( __('All Users'), 'manage_network_users', 'users.php' );
$submenu['users.php'][10]  = array( _x('Add New', 'user'), 'create_users', 'user-new.php' );

if ( current_user_can( 'update_themes' ) && $update_data['counts']['themes'] ) {
	$menu[15] = array(sprintf( __( 'Themes %s' ), "<span class='update-plugins count-{$update_data['counts']['themes']}'><span class='theme-count'>" . number_format_i18n( $update_data['counts']['themes'] ) . "</span></span>" ), 'manage_network_themes', 'themes.php', '', 'menu-top menu-icon-appearance', 'menu-appearance', 'basicons-admin-appearance' );
} else {
	$menu[15] = array( __( 'Themes' ), 'manage_network_themes', 'themes.php', '', 'menu-top menu-icon-appearance', 'menu-appearance', 'basicons-admin-appearance' );
}
$submenu['themes.php'][5]  = array( __('Installed Themes'), 'manage_network_themes', 'themes.php' );
$submenu['themes.php'][10] = array( _x('Add New', 'theme'), 'install_themes', 'theme-install.php' );
$submenu['themes.php'][15] = array( _x('Editor', 'theme editor'), 'edit_themes', 'theme-editor.php' );

if ( current_user_can( 'update_plugins' ) && $update_data['counts']['plugins'] ) {
	$menu[20] = array( sprintf( __( 'Plugins %s' ), "<span class='update-plugins count-{$update_data['counts']['plugins']}'><span class='plugin-count'>" . number_format_i18n( $update_data['counts']['plugins'] ) . "</span></span>" ), 'manage_network_plugins', 'plugins.php', '', 'menu-top menu-icon-plugins', 'menu-plugins', 'basicons-admin-plugins');
} else {
	$menu[20] = array( __('Plugins'), 'manage_network_plugins', 'plugins.php', '', 'menu-top menu-icon-plugins', 'menu-plugins', 'basicons-admin-plugins' );
}
$submenu['plugins.php'][5]  = array( __('Installed Plugins'), 'manage_network_plugins', 'plugins.php' );
$submenu['plugins.php'][10] = array( _x('Add New', 'plugin'), 'install_plugins', 'plugin-install.php' );
$submenu['plugins.php'][15] = array( _x('Editor', 'plugin editor'), 'edit_plugins', 'plugin-editor.php' );

$menu[25] = array(__('Settings'), 'manage_network_options', 'settings.php', '', 'menu-top menu-icon-settings', 'menu-settings', 'basicons-admin-settings');
if ( defined( 'MULTISITE' ) && defined( 'BD_ALLOW_MULTISITE' ) && BD_ALLOW_MULTISITE ) {
	$submenu['settings.php'][5]  = array( __('Network Settings'), 'manage_network_options', 'settings.php' );
	$submenu['settings.php'][10] = array( __('Network Setup'), 'manage_network_options', 'setup.php' );
}
unset($update_data);

$menu[99] = array( '', 'exist', 'separator-last', '', 'bd-menu-separator' );

require_once(ABSPATH . 'bd-admin/includes/menu.php');