<?php
/**
 * Install plugin network administration panel.
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

if ( isset( $_GET['tab'] ) && ( 'plugin-information' == $_GET['tab'] ) )
	define( 'IFRAME_REQUEST', true );

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! is_multisite() )
	bd_die( __( 'Multisite support is not enabled.' ) );

require( ABSPATH . 'bd-admin/plugin-install.php' );
