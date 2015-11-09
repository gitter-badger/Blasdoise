<?php
/**
 * Build User Administration Menu.
 *
 * @package Blasdoise
 * @subpackage Administration
 * @since 1.0.0
 */

$menu[2] = array(__('Dashboard'), 'exist', 'index.php', '', 'menu-top menu-top-first menu-icon-dashboard', 'menu-dashboard', 'basicons-dashboard');

$menu[4] = array( '', 'exist', 'separator1', '', 'bd-menu-separator' );

$menu[70] = array( __('Profile'), 'exist', 'profile.php', '', 'menu-top menu-icon-users', 'menu-users', 'basicons-admin-users' );

$menu[99] = array( '', 'exist', 'separator-last', '', 'bd-menu-separator' );

$_bd_real_parent_file['users.php'] = 'profile.php';
$compat = array();
$submenu = array();

require_once(ABSPATH . 'bd-admin/includes/menu.php');
