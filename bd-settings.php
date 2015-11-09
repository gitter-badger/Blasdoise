<?php
/**
 * Used to set up and fix common variables and include
 * the Blasdoise procedural and class library.
 *
 * Allows for some configuration in bd-config.php (see default-constants.php)
 *
 * @internal This file must be parsable by PHP4.
 *
 * @package Blasdoise
 */

/**
 * Stores the location of the Blasdoise directory of functions, classes, and core content.
 *
 * @since 1.0.0
 */
define( 'BDINC', 'bd-includes' );

// Include files required for initialization.
require( ABSPATH . BDINC . '/load.php' );
require( ABSPATH . BDINC . '/default-constants.php' );

/*
 * These can't be directly globalized in version.php. When updating,
 * we're including version.php from another install and don't want
 * these values to be overridden if already set.
 */
global $bd_version, $bd_db_version, $tinymce_version, $required_php_version, $required_mysql_version;
require( ABSPATH . BDINC . '/version.php' );

// Set initial default constants including BD_MEMORY_LIMIT, BD_MAX_MEMORY_LIMIT, BD_DEBUG, SCRIPT_DEBUG, BD_CONTENT_DIR and BD_CACHE.
bd_initial_constants();

// Check for the required PHP version and for the MySQL extension or a database drop-in.
bd_check_php_mysql_versions();

// Disable magic quotes at runtime. Magic quotes are added using bddb later in bd-settings.php.
@ini_set( 'magic_quotes_runtime', 0 );
@ini_set( 'magic_quotes_sybase',  0 );

// Blasdoise calculates offsets from UTC.
date_default_timezone_set( 'UTC' );

// Turn register_globals off.
bd_unregister_GLOBALS();

// Standardize $_SERVER variables across setups.
bd_fix_server_vars();

// Check if we have received a request due to missing favicon.ico
bd_favicon_request();

// Check if we're in maintenance mode.
bd_maintenance();

// Start loading timer.
timer_start();

// Check if we're in BD_DEBUG mode.
bd_debug_mode();

// For an advanced caching plugin to use. Uses a static drop-in because you would only want one.
if ( BD_CACHE )
	BD_DEBUG ? include( BD_CONTENT_DIR . '/advanced-cache.php' ) : @include( BD_CONTENT_DIR . '/advanced-cache.php' );

// Define BD_LANG_DIR if not set.
bd_set_lang_dir();

// Load early Blasdoise files.
require( ABSPATH . BDINC . '/compat.php' );
require( ABSPATH . BDINC . '/functions.php' );
require( ABSPATH . BDINC . '/class-bd.php' );
require( ABSPATH . BDINC . '/class-bd-error.php' );
require( ABSPATH . BDINC . '/plugin.php' );
require( ABSPATH . BDINC . '/pomo/mo.php' );

// Include the bddb class and, if present, a db.php database drop-in.
require_bd_db();

// Set the database table prefix and the format specifiers for database table columns.
$GLOBALS['table_prefix'] = $table_prefix;
bd_set_bddb_vars();

// Start the Blasdoise object cache, or an external object cache if the drop-in is present.
bd_start_object_cache();

// Attach the default filters.
require( ABSPATH . BDINC . '/default-filters.php' );

// Initialize multisite if enabled.
if ( is_multisite() ) {
	require( ABSPATH . BDINC . '/ms-blogs.php' );
	require( ABSPATH . BDINC . '/ms-settings.php' );
} elseif ( ! defined( 'MULTISITE' ) ) {
	define( 'MULTISITE', false );
}

register_shutdown_function( 'shutdown_action_hook' );

// Stop most of Blasdoise from being loaded if we just want the basics.
if ( SHORTINIT )
	return false;

// Load the L10n library.
require_once( ABSPATH . BDINC . '/l10n.php' );

// Run the installer if Blasdoise is not installed.
bd_not_installed();

// Load most of Blasdoise.
require( ABSPATH . BDINC . '/class-bd-walker.php' );
require( ABSPATH . BDINC . '/class-bd-ajax-response.php' );
require( ABSPATH . BDINC . '/formatting.php' );
require( ABSPATH . BDINC . '/capabilities.php' );
require( ABSPATH . BDINC . '/query.php' );
require( ABSPATH . BDINC . '/date.php' );
require( ABSPATH . BDINC . '/theme.php' );
require( ABSPATH . BDINC . '/class-bd-theme.php' );
require( ABSPATH . BDINC . '/template.php' );
require( ABSPATH . BDINC . '/user.php' );
require( ABSPATH . BDINC . '/session.php' );
require( ABSPATH . BDINC . '/meta.php' );
require( ABSPATH . BDINC . '/general-template.php' );
require( ABSPATH . BDINC . '/link-template.php' );
require( ABSPATH . BDINC . '/author-template.php' );
require( ABSPATH . BDINC . '/post.php' );
require( ABSPATH . BDINC . '/post-template.php' );
require( ABSPATH . BDINC . '/revision.php' );
require( ABSPATH . BDINC . '/post-formats.php' );
require( ABSPATH . BDINC . '/post-thumbnail-template.php' );
require( ABSPATH . BDINC . '/category.php' );
require( ABSPATH . BDINC . '/category-template.php' );
require( ABSPATH . BDINC . '/comment.php' );
require( ABSPATH . BDINC . '/comment-template.php' );
require( ABSPATH . BDINC . '/rewrite.php' );
require( ABSPATH . BDINC . '/feed.php' );
require( ABSPATH . BDINC . '/bookmark.php' );
require( ABSPATH . BDINC . '/bookmark-template.php' );
require( ABSPATH . BDINC . '/kses.php' );
require( ABSPATH . BDINC . '/cron.php' );
require( ABSPATH . BDINC . '/deprecated.php' );
require( ABSPATH . BDINC . '/script-loader.php' );
require( ABSPATH . BDINC . '/taxonomy.php' );
require( ABSPATH . BDINC . '/update.php' );
require( ABSPATH . BDINC . '/canonical.php' );
require( ABSPATH . BDINC . '/shortcodes.php' );
require( ABSPATH . BDINC . '/class-bd-embed.php' );
require( ABSPATH . BDINC . '/media.php' );
require( ABSPATH . BDINC . '/http.php' );
require( ABSPATH . BDINC . '/class-http.php' );
require( ABSPATH . BDINC . '/widgets.php' );
require( ABSPATH . BDINC . '/nav-menu.php' );
require( ABSPATH . BDINC . '/nav-menu-template.php' );
require( ABSPATH . BDINC . '/admin-bar.php' );

// Load multisite-specific files.
if ( is_multisite() ) {
	require( ABSPATH . BDINC . '/ms-functions.php' );
	require( ABSPATH . BDINC . '/ms-default-filters.php' );
	require( ABSPATH . BDINC . '/ms-deprecated.php' );
}

// Define constants that rely on the API to obtain the default value.
// Define must-use plugin directory constants, which may be overridden in the sunrise.php drop-in.
bd_plugin_directory_constants();

$GLOBALS['bd_plugin_paths'] = array();

// Load must-use plugins.
foreach ( bd_get_mu_plugins() as $mu_plugin ) {
	include_once( $mu_plugin );
}
unset( $mu_plugin );

// Load network activated plugins.
if ( is_multisite() ) {
	foreach( bd_get_active_network_plugins() as $network_plugin ) {
		bd_register_plugin_realpath( $network_plugin );
		include_once( $network_plugin );
	}
	unset( $network_plugin );
}

/**
 * Fires once all must-use and network-activated plugins have loaded.
 *
 * @since 1.0.0
 */
do_action( 'muplugins_loaded' );

if ( is_multisite() )
	ms_cookie_constants(  );

// Define constants after multisite is loaded.
bd_cookie_constants();

// Define and enforce our SSL constants
bd_ssl_constants();

// Create common globals.
require( ABSPATH . BDINC . '/vars.php' );

// Make taxonomies and posts available to plugins and themes.
// @plugin authors: warning: these get registered again on the init hook.
create_initial_taxonomies();
create_initial_post_types();

// Register the default theme directory root
register_theme_directory( get_theme_root() );

// Load active plugins.
foreach ( bd_get_active_and_valid_plugins() as $plugin ) {
	bd_register_plugin_realpath( $plugin );
	include_once( $plugin );
}
unset( $plugin );

// Load pluggable functions.
require( ABSPATH . BDINC . '/pluggable.php' );
require( ABSPATH . BDINC . '/pluggable-deprecated.php' );

// Set internal encoding.
bd_set_internal_encoding();

// Run bd_cache_postload() if object cache is enabled and the function exists.
if ( BD_CACHE && function_exists( 'bd_cache_postload' ) )
	bd_cache_postload();

/**
 * Fires once activated plugins have loaded.
 *
 * Pluggable functions are also available at this point in the loading order.
 *
 * @since 1.0.0
 */
do_action( 'plugins_loaded' );

// Define constants which affect functionality if not already defined.
bd_functionality_constants();

// Add magic quotes and set up $_REQUEST ( $_GET + $_POST )
bd_magic_quotes();

/**
 * Fires when comment cookies are sanitized.
 *
 * @since 1.0.0
 */
do_action( 'sanitize_comment_cookies' );

/**
 * Blasdoise Query object
 * @global object $bd_the_query
 * @since 1.0.0
 */
$GLOBALS['bd_the_query'] = new BD_Query();

/**
 * Holds the reference to @see $bd_the_query
 * Use this global for Blasdoise queries
 * @global object $bd_query
 * @since 1.0.0
 */
$GLOBALS['bd_query'] = $GLOBALS['bd_the_query'];

/**
 * Holds the Blasdoise Rewrite object for creating pretty URLs
 * @global object $bd_rewrite
 * @since 1.0.0
 */
$GLOBALS['bd_rewrite'] = new BD_Rewrite();

/**
 * Blasdoise Object
 * @global object $bd
 * @since 1.0.0
 */
$GLOBALS['bd'] = new BD();

/**
 * Blasdoise Widget Factory Object
 * @global object $bd_widget_factory
 * @since 1.0.0
 */
$GLOBALS['bd_widget_factory'] = new BD_Widget_Factory();

/**
 * Blasdoise User Roles
 * @global object $bd_roles
 * @since 1.0.0
 */
$GLOBALS['bd_roles'] = new BD_Roles();

/**
 * Fires before the theme is loaded.
 *
 * @since 1.0.0
 */
do_action( 'setup_theme' );

// Define the template related constants.
bd_templating_constants(  );

// Load the default text localization domain.
load_default_textdomain();

$locale = get_locale();
$locale_file = BD_LANG_DIR . "/$locale.php";
if ( ( 0 === validate_file( $locale ) ) && is_readable( $locale_file ) )
	require( $locale_file );
unset( $locale_file );

// Pull in locale data after loading text domain.
require_once( ABSPATH . BDINC . '/locale.php' );

/**
 * Blasdoise Locale object for loading locale domain date and various strings.
 * @global object $bd_locale
 * @since 1.0.0
 */
$GLOBALS['bd_locale'] = new BD_Locale();

// Load the functions for the active theme, for both parent and child theme if applicable.
if ( ! defined( 'BD_INSTALLING' ) || 'bd-activate.php' === $pagenow ) {
	if ( TEMPLATEPATH !== STYLESHEETPATH && file_exists( STYLESHEETPATH . '/functions.php' ) )
		include( STYLESHEETPATH . '/functions.php' );
	if ( file_exists( TEMPLATEPATH . '/functions.php' ) )
		include( TEMPLATEPATH . '/functions.php' );
}

/**
 * Fires after the theme is loaded.
 *
 * @since 1.0.0
 */
do_action( 'after_setup_theme' );

// Set up current user.
$GLOBALS['bd']->init();

/**
 * Fires after Blasdoise has finished loading but before any headers are sent.
 *
 * Most of BD is loaded at this stage, and the user is authenticated. BD continues
 * to load on the init hook that follows (e.g. widgets), and many plugins instantiate
 * themselves on it for all sorts of reasons (e.g. they need a user, a taxonomy, etc.).
 *
 * If you wish to plug an action once BD is loaded, use the bd_loaded hook below.
 *
 * @since 1.0.0
 */
do_action( 'init' );

// Check site status
if ( is_multisite() ) {
	if ( true !== ( $file = ms_site_check() ) ) {
		require( $file );
		die();
	}
	unset($file);
}

/**
 * This hook is fired once BD, all plugins, and the theme are fully loaded and instantiated.
 *
 * AJAX requests should use bd-admin/admin-ajax.php. admin-ajax.php can handle requests for
 * users not logged in.
 *
 * @since 1.0.0
 */
do_action( 'bd_loaded' );
