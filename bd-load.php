<?php
/**
 * Bootstrap file for setting the ABSPATH constant
 * and loading the bd-config.php file. The bd-config.php
 * file will then load the bd-settings.php file, which
 * will then set up the Blasdoise environment.
 *
 * If the bd-config.php file is not found then an error
 * will be displayed asking the visitor to set up the
 * bd-config.php file.
 *
 * Will also search for bd-config.php in Blasdoise' parent
 * directory to allow the Blasdoise directory to remain
 * untouched.
 *
 * @internal This file must be parsable by PHP4.
 *
 * @package Blasdoise
 */

/** Define ABSPATH as this file's directory */
define( 'ABSPATH', dirname(__FILE__) . '/' );

error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

/*
 * If bd-config.php exists in the Blasdoise root, or if it exists in the root and bd-settings.php
 * doesn't, load bd-config.php. The secondary check for bd-settings.php has the added benefit
 * of avoiding cases where the current directory is a nested installation, e.g. / is Blasdoise(a)
 * and /blog/ is Blasdoise(b).
 *
 * If neither set of conditions is true, initiate loading the setup process.
 */
if ( file_exists( ABSPATH . 'bd-config.php') ) {

	/** The config file resides in ABSPATH */
	require_once( ABSPATH . 'bd-config.php' );

} elseif ( file_exists( dirname(ABSPATH) . '/bd-config.php' ) && ! file_exists( dirname(ABSPATH) . '/bd-settings.php' ) ) {

	/** The config file resides one level above ABSPATH but is not part of another install */
	require_once( dirname(ABSPATH) . '/bd-config.php' );

} else {

	// A config file doesn't exist

	define( 'BDINC', 'bd-includes' );
	require_once( ABSPATH . BDINC . '/load.php' );

	// Standardize $_SERVER variables across setups.
	bd_fix_server_vars();

	require_once( ABSPATH . BDINC . '/functions.php' );

	$path = bd_guess_url() . '/bd-admin/setup-config.php';

	/*
	 * We're going to redirect to setup-config.php. While this shouldn't result
	 * in an infinite loop, that's a silly thing to assume, don't you think? If
	 * we're traveling in circles, our last-ditch effort is "Need more help?"
	 */
	if ( false === strpos( $_SERVER['REQUEST_URI'], 'setup-config' ) ) {
		header( 'Location: ' . $path );
		exit;
	}

	define( 'BD_CONTENT_DIR', ABSPATH . 'bd-content' );
	require_once( ABSPATH . BDINC . '/version.php' );

	bd_check_php_mysql_versions();
	bd_load_translations_early();

	// Die with an error message
	$die  = __( "There doesn't seem to be a <code>bd-config.php</code> file. I need this before we can get started." ) . '</p>';
	$die .= '<p>' . __( "You can create a <code>bd-config.php</code> file through a web interface, but this doesn't work for all server setups. The safest way is to manually create the file." ) . '</p>';
	$die .= '<p><a href="' . $path . '" class="button button-large">' . __( "Create a Configuration File" ) . '</a>';

	bd_die( $die, __( 'Blasdoise &rsaquo; Error' ) );
}
