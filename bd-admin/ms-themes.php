<?php
/**
 * Multisite themes administration panel.
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

require_once( dirname( __FILE__ ) . '/admin.php' );

bd_redirect( network_admin_url('themes.php') );
exit;
