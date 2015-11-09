<?php
/**
 * Update/Install Plugin/Theme network administration panel.
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'update-selected', 'activate-plugin', 'update-selected-themes' ) ) )
	define( 'IFRAME_REQUEST', true );

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! is_multisite() )
	bd_die( __( 'Multisite support is not enabled.' ) );

require( ABSPATH . 'bd-admin/update.php' );
