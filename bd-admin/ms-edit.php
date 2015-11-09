<?php
/**
 * Action handler for Multisite administration panels.
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

require_once( dirname( __FILE__ ) . '/admin.php' );

bd_redirect( network_admin_url() );
exit;
