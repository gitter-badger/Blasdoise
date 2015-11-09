<?php
/**
 * Edit user network administration panel.
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! is_multisite() )
	bd_die( __( 'Multisite support is not enabled.' ) );

require( ABSPATH . 'bd-admin/user-edit.php' );
