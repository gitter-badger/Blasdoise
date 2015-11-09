<?php
/**
 * Loads the Blasdoise environment and template.
 *
 * @package Blasdoise
 */

if ( !isset($bd_did_header) ) {

	$bd_did_header = true;

	require_once( dirname(__FILE__) . '/bd-load.php' );

	bd();

	require_once( ABSPATH . BDINC . '/template-loader.php' );

}
