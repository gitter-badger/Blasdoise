<?php
/**
 * Blasdoise Administration Bootstrap
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/**
 * In Blasdoise Administration Screens
 *
 * @since 1.0.0
 */
if ( ! defined( 'BD_ADMIN' ) ) {
	define( 'BD_ADMIN', true );
}

if ( ! defined('BD_NETWORK_ADMIN') )
	define('BD_NETWORK_ADMIN', false);

if ( ! defined('BD_USER_ADMIN') )
	define('BD_USER_ADMIN', false);

if ( ! BD_NETWORK_ADMIN && ! BD_USER_ADMIN ) {
	define('BD_BLOG_ADMIN', true);
}

if ( isset($_GET['import']) && !defined('BD_LOAD_IMPORTERS') )
	define('BD_LOAD_IMPORTERS', true);

require_once(dirname(dirname(__FILE__)) . '/bd-load.php');

nocache_headers();

if ( get_option('db_upgraded') ) {
	flush_rewrite_rules();
	update_option( 'db_upgraded',  false );

	/**
	 * Fires on the next page load after a successful DB upgrade.
	 *
	 * @since 1.0.0
	 */
	do_action( 'after_db_upgrade' );
} elseif ( get_option('db_version') != $bd_db_version && empty($_POST) ) {
	if ( !is_multisite() ) {
		bd_redirect( admin_url( 'upgrade.php?_bd_http_referer=' . urlencode( bd_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
		exit;

	/**
	 * Filter whether to attempt to perform the multisite DB upgrade routine.
	 *
	 * In single site, the user would be redirected to bd-admin/upgrade.php.
	 * In multisite, the DB upgrade routine is automatically fired, but only
	 * when this filter returns true.
	 *
	 * If the network is 50 sites or less, it will run every time. Otherwise,
	 * it will throttle itself to reduce load.
	 *
	 * @since 1.0.0
	 *
	 * @param bool true Whether to perform the Multisite upgrade routine. Default true.
	 */
	} elseif ( apply_filters( 'do_mu_upgrade', true ) ) {
		$c = get_blog_count();

		/*
		 * If there are 50 or fewer sites, run every time. Otherwise, throttle to reduce load:
		 * attempt to do no more than threshold value, with some +/- allowed.
		 */
		if ( $c <= 50 || ( $c > 50 && mt_rand( 0, (int)( $c / 50 ) ) == 1 ) ) {
			require_once( ABSPATH . BDINC . '/http.php' );
			$response = bd_remote_get( admin_url( 'upgrade.php?step=1' ), array( 'timeout' => 120, 'httpversion' => '1.1' ) );
			/** This action is documented in bd-admin/network/upgrade.php */
			do_action( 'after_mu_upgrade', $response );
			unset($response);
		}
		unset($c);
	}
}

require_once(ABSPATH . 'bd-admin/includes/admin.php');

auth_redirect();

// Schedule trash collection
if ( !bd_next_scheduled('bd_scheduled_delete') && !defined('BD_INSTALLING') )
	bd_schedule_event(time(), 'daily', 'bd_scheduled_delete');

set_screen_options();

$date_format = get_option('date_format');
$time_format = get_option('time_format');

bd_enqueue_script( 'common' );

/**
 * $pagenow is set in vars.php
 * The remaining variables are imported as globals elsewhere, declared as globals here
 *
 * @global string $pagenow
 * @global array  $bd_importers
 * @global string $hook_suffix
 * @global string $plugin_page
 * @global string $typenow
 * @global string $taxnow
 */
global $pagenow, $bd_importers, $hook_suffix, $plugin_page, $typenow, $taxnow;

$page_hook = null;

$editing = false;

if ( isset($_GET['page']) ) {
	$plugin_page = bd_unslash( $_GET['page'] );
	$plugin_page = plugin_basename($plugin_page);
}

if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) )
	$typenow = $_REQUEST['post_type'];
else
	$typenow = '';

if ( isset( $_REQUEST['taxonomy'] ) && taxonomy_exists( $_REQUEST['taxonomy'] ) )
	$taxnow = $_REQUEST['taxonomy'];
else
	$taxnow = '';

if ( BD_NETWORK_ADMIN )
	require(ABSPATH . 'bd-admin/network/menu.php');
elseif ( BD_USER_ADMIN )
	require(ABSPATH . 'bd-admin/user/menu.php');
else
	require(ABSPATH . 'bd-admin/menu.php');

if ( current_user_can( 'manage_options' ) ) {
	/**
	 * Filter the maximum memory limit available for administration screens.
	 *
	 * This only applies to administrators, who may require more memory for tasks like updates.
	 * Memory limits when processing images (uploaded or edited by users of any role) are
	 * handled separately.
	 *
	 * The BD_MAX_MEMORY_LIMIT constant specifically defines the maximum memory limit available
	 * when in the administration back-end. The default is 256M, or 256 megabytes of memory.
	 *
	 * @since 1.0.0
	 *
	 * @param string 'BD_MAX_MEMORY_LIMIT' The maximum Blasdoise memory limit. Default 256M.
	 */
	@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', BD_MAX_MEMORY_LIMIT ) );
}

/**
 * Fires as an admin screen or script is being initialized.
 *
 * Note, this does not just run on user-facing admin screens.
 * It runs on admin-ajax.php and admin-post.php as well.
 *
 * This is roughly analgous to the more general 'init' hook, which fires earlier.
 *
 * @since 1.0.0
 */
do_action( 'admin_init' );

if ( isset($plugin_page) ) {
	if ( !empty($typenow) )
		$the_parent = $pagenow . '?post_type=' . $typenow;
	else
		$the_parent = $pagenow;
	if ( ! $page_hook = get_plugin_page_hook($plugin_page, $the_parent) ) {
		$page_hook = get_plugin_page_hook($plugin_page, $plugin_page);

		// Backwards compatibility for plugins using add_management_page().
		if ( empty( $page_hook ) && 'edit.php' == $pagenow && '' != get_plugin_page_hook($plugin_page, 'index.php') ) {
			// There could be plugin specific params on the URL, so we need the whole query string
			if ( !empty($_SERVER[ 'QUERY_STRING' ]) )
				$query_string = $_SERVER[ 'QUERY_STRING' ];
			else
				$query_string = 'page=' . $plugin_page;
			bd_redirect( admin_url('index.php?' . $query_string) );
			exit;
		}
	}
	unset($the_parent);
}

$hook_suffix = '';
if ( isset( $page_hook ) ) {
	$hook_suffix = $page_hook;
} elseif ( isset( $plugin_page ) ) {
	$hook_suffix = $plugin_page;
} elseif ( isset( $pagenow ) ) {
	$hook_suffix = $pagenow;
}

set_current_screen();

// Handle plugin admin pages.
if ( isset($plugin_page) ) {
	if ( $page_hook ) {
		/**
		 * Fires before a particular screen is loaded.
		 *
		 * The load-* hook fires in a number of contexts. This hook is for plugin screens
		 * where a callback is provided when the screen is registered.
		 *
		 * The dynamic portion of the hook name, `$page_hook`, refers to a mixture of plugin
		 * page information including:
		 * 1. The page type. If the plugin page is registered as a submenu page, such as for
		 *    Settings, the page type would be 'settings'. Otherwise the type is 'toplevel'.
		 * 2. A separator of '_page_'.
		 * 3. The plugin basename minus the file extension.
		 *
		 * Together, the three parts form the `$page_hook`. Citing the example above,
		 * the hook name used would be 'load-settings_page_pluginbasename'.
		 *
		 * @see get_plugin_page_hook()
		 *
		 * @since 1.0.0
		 */
		do_action( 'load-' . $page_hook );
		if (! isset($_GET['noheader']))
			require_once(ABSPATH . 'bd-admin/admin-header.php');

		/**
		 * Used to call the registered callback for a plugin screen.
		 *
		 * @ignore
		 * @since 1.0.0
		 */
		do_action( $page_hook );
	} else {
		if ( validate_file($plugin_page) )
			bd_die(__('Invalid plugin page'));

		if ( !( file_exists(BD_PLUGIN_DIR . "/$plugin_page") && is_file(BD_PLUGIN_DIR . "/$plugin_page") ) && !( file_exists(BDMU_PLUGIN_DIR . "/$plugin_page") && is_file(BDMU_PLUGIN_DIR . "/$plugin_page") ) )
			bd_die(sprintf(__('Cannot load %s.'), htmlentities($plugin_page)));

		/**
		 * Fires before a particular screen is loaded.
		 *
		 * The load-* hook fires in a number of contexts. This hook is for plugin screens
		 * where the file to load is directly included, rather than the use of a function.
		 *
		 * The dynamic portion of the hook name, `$plugin_page`, refers to the plugin basename.
		 *
		 * @see plugin_basename()
		 *
		 * @since 1.0.0
		 */
		do_action( 'load-' . $plugin_page );

		if ( !isset($_GET['noheader']))
			require_once(ABSPATH . 'bd-admin/admin-header.php');

		if ( file_exists(BDMU_PLUGIN_DIR . "/$plugin_page") )
			include(BDMU_PLUGIN_DIR . "/$plugin_page");
		else
			include(BD_PLUGIN_DIR . "/$plugin_page");
	}

	include(ABSPATH . 'bd-admin/admin-footer.php');

	exit();
} elseif ( isset( $_GET['import'] ) ) {

	$importer = $_GET['import'];

	if ( ! current_user_can('import') )
		bd_die(__('You are not allowed to import.'));

	/**
	 * Whether to filter imported data through kses on import.
	 *
	 * Multisite uses this hook to filter all data through kses by default,
	 * as a super administrator may be assisting an untrusted user.
	 *
	 * @since 1.0.0
	 *
	 * @param bool false Whether to force data to be filtered through kses. Default false.
	 */
	if ( apply_filters( 'force_filtered_html_on_import', false ) ) {
		kses_init_filters();  // Always filter imported data with kses on multisite.
	}

	call_user_func($bd_importers[$importer][2]);

	include(ABSPATH . 'bd-admin/admin-footer.php');

	// Make sure rules are flushed
	flush_rewrite_rules(false);

	exit();
} else {
	/**
	 * Fires before a particular screen is loaded.
	 *
	 * The load-* hook fires in a number of contexts. This hook is for core screens.
	 *
	 * The dynamic portion of the hook name, `$pagenow`, is a global variable
	 * referring to the filename of the current page, such as 'admin.php',
	 * 'post-new.php' etc. A complete hook for the latter would be
	 * 'load-post-new.php'.
	 *
	 * @since 1.0.0
	 */
	do_action( 'load-' . $pagenow );

	/*
	 * The following hooks are fired to ensure backward compatibility.
	 * In all other cases, 'load-' . $pagenow should be used instead.
	 */
	if ( $typenow == 'page' ) {
		if ( $pagenow == 'post-new.php' )
			do_action( 'load-page-new.php' );
		elseif ( $pagenow == 'post.php' )
			do_action( 'load-page.php' );
	}  elseif ( $pagenow == 'edit-tags.php' ) {
		if ( $taxnow == 'category' )
			do_action( 'load-categories.php' );
		elseif ( $taxnow == 'link_category' )
			do_action( 'load-edit-link-categories.php' );
	}
}

if ( ! empty( $_REQUEST['action'] ) ) {
	/**
	 * Fires when an 'action' request variable is sent.
	 *
	 * The dynamic portion of the hook name, `$_REQUEST['action']`,
	 * refers to the action derived from the `GET` or `POST` request.
	 *
	 * @since 1.0.0
	 */
	do_action( 'admin_action_' . $_REQUEST['action'] );
}
