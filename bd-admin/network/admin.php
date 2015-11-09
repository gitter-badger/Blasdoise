<?php
/**
 * Blasdoise Network Administration Bootstrap
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

define( 'BD_NETWORK_ADMIN', true );

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( dirname( __FILE__ ) ) . '/admin.php' );

if ( ! is_multisite() )
	bd_die( __( 'Multisite support is not enabled.' ) );

$redirect_network_admin_request = 0 !== strcasecmp( $current_blog->domain, $current_site->domain ) || 0 !== strcasecmp( $current_blog->path, $current_site->path );

/**
 * Filter whether to redirect the request to the Network Admin.
 *
 * @since 1.0.0
 *
 * @param bool $redirect_network_admin_request Whether the request should be redirected.
 */
$redirect_network_admin_request = apply_filters( 'redirect_network_admin_request', $redirect_network_admin_request );
if ( $redirect_network_admin_request ) {
	bd_redirect( network_admin_url() );
	exit;
}
unset( $redirect_network_admin_request );
