<?php
/**
 * BackPress Scripts Procedural API
 *
 * @since 1.0.0
 *
 * @package Blasdoise
 * @subpackage BackPress
 */

/**
 * Initialize $bd_scripts if it has not been set.
 *
 * @global BD_Scripts $bd_scripts
 *
 * @since 1.0.0
 *
 * @return BD_Scripts BD_Scripts instance.
 */
function bd_scripts() {
	global $bd_scripts;
	if ( ! ( $bd_scripts instanceof BD_Scripts ) ) {
		$bd_scripts = new BD_Scripts();
	}
	return $bd_scripts;
}

/**
 * Helper function to output a _doing_it_wrong message when applicable.
 *
 * @ignore
 * @since 1.0.0
 *
 * @param string $function Function name.
 */
function _bd_scripts_maybe_doing_it_wrong( $function ) {
	if ( did_action( 'init' ) ) {
		return;
	}

	_doing_it_wrong( $function, sprintf(
		__( 'Scripts and styles should not be registered or enqueued until the %1$s, %2$s, or %3$s hooks.' ),
		'<code>bd_enqueue_scripts</code>',
		'<code>admin_enqueue_scripts</code>',
		'<code>login_enqueue_scripts</code>'
	), '1.0' );
}

/**
 * Print scripts in document head that are in the $handles queue.
 *
 * Called by admin-header.php and bd_header hook. Since it is called by bd_header on every page load,
 * the function does not instantiate the BD_Scripts object unless script names are explicitly passed.
 * Makes use of already-instantiated $bd_scripts global if present. Use provided bd_print_scripts
 * hook to register/enqueue new scripts.
 *
 * @see BD_Scripts::do_items()
 * @global BD_Scripts $bd_scripts The BD_Scripts object for printing scripts.
 *
 * @since 1.0.0
 *
 * @param string|bool|array $handles Optional. Scripts to be printed. Default 'false'.
 * @return array On success, a processed array of BD_Dependencies items; otherwise, an empty array.
 */
function bd_print_scripts( $handles = false ) {
	/**
	 * Fires before scripts in the $handles queue are printed.
	 *
	 * @since 1.0.0
	 */
	do_action( 'bd_print_scripts' );
	if ( '' === $handles ) { // for bd_header
		$handles = false;
	}

	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	global $bd_scripts;
	if ( ! ( $bd_scripts instanceof BD_Scripts ) ) {
		if ( ! $handles ) {
			return array(); // No need to instantiate if nothing is there.
		}
	}

	return bd_scripts()->do_items( $handles );
}

/**
 * Register a new script.
 *
 * Registers a script to be linked later using the bd_enqueue_script() function.
 *
 * @see BD_Dependencies::add(), BD_Dependencies::add_data()
 *
 * @since 1.0.0
 *
 * @param string      $handle    Name of the script. Should be unique.
 * @param string      $src       Path to the script from the Blasdoise root directory. Example: '/js/myscript.js'.
 * @param array       $deps      Optional. An array of registered script handles this script depends on. Set to false if there
 *                               are no dependencies. Default empty array.
 * @param string|bool $ver       Optional. String specifying script version number, if it has one, which is concatenated
 *                               to end of path as a query string. If no version is specified or set to false, a version
 *                               number is automatically added equal to current installed Blasdoise version.
 *                               If set to null, no version is added. Default 'false'. Accepts 'false', 'null', or 'string'.
 * @param bool        $in_footer Optional. Whether to enqueue the script before </head> or before </body>.
 *                               Default 'false'. Accepts 'false' or 'true'.
 * @return bool Whether the script has been registered. True on success, false on failure.
 */
function bd_register_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {
	$bd_scripts = bd_scripts();
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	$registered = $bd_scripts->add( $handle, $src, $deps, $ver );
	if ( $in_footer ) {
		$bd_scripts->add_data( $handle, 'group', 1 );
	}

	return $registered;
}

/**
 * Localize a script.
 *
 * Works only if the script has already been added.
 *
 * Accepts an associative array $l10n and creates a JavaScript object:
 *
 *     "$object_name" = {
 *         key: value,
 *         key: value,
 *         ...
 *     }
 *
 *
 * @see BD_Dependencies::localize()
 * @global BD_Scripts $bd_scripts The BD_Scripts object for printing scripts.
 *
 * @since 1.0.0
 *
 * @todo Documentation cleanup
 *
 * @param string $handle      Script handle the data will be attached to.
 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
 *                            Example: '/[a-zA-Z0-9_]+/'.
 * @param array $l10n         The data itself. The data can be either a single or multi-dimensional array.
 * @return bool True if the script was successfully localized, false otherwise.
 */
function bd_localize_script( $handle, $object_name, $l10n ) {
	global $bd_scripts;
	if ( ! ( $bd_scripts instanceof BD_Scripts ) ) {
		_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );
		return false;
	}

	return $bd_scripts->localize( $handle, $object_name, $l10n );
}

/**
 * Remove a registered script.
 *
 * Note: there are intentional safeguards in place to prevent critical admin scripts,
 * such as jQuery core, from being unregistered.
 *
 * @see BD_Dependencies::remove()
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the script to be removed.
 */
function bd_deregister_script( $handle ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	/**
	 * Do not allow accidental or negligent de-registering of critical scripts in the admin.
	 * Show minimal remorse if the correct hook is used.
	 */
	$current_filter = current_filter();
	if ( ( is_admin() && 'admin_enqueue_scripts' !== $current_filter ) ||
		( 'bd-login.php' === $GLOBALS['pagenow'] && 'login_enqueue_scripts' !== $current_filter )
	) {
		$no = array(
			'jquery', 'jquery-core', 'jquery-migrate', 'jquery-ui-core', 'jquery-ui-accordion',
			'jquery-ui-autocomplete', 'jquery-ui-button', 'jquery-ui-datepicker', 'jquery-ui-dialog',
			'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-menu', 'jquery-ui-mouse',
			'jquery-ui-position', 'jquery-ui-progressbar', 'jquery-ui-resizable', 'jquery-ui-selectable',
			'jquery-ui-slider', 'jquery-ui-sortable', 'jquery-ui-spinner', 'jquery-ui-tabs',
			'jquery-ui-tooltip', 'jquery-ui-widget', 'underscore', 'backbone',
		);

		if ( in_array( $handle, $no ) ) {
			$message = sprintf( __( 'Do not deregister the %1$s script in the administration area. To target the frontend theme, use the %2$s hook.' ),
				"<code>$handle</code>", '<code>bd_enqueue_scripts</code>' );
			_doing_it_wrong( __FUNCTION__, $message, '1.0' );
			return;
		}
	}

	bd_scripts()->remove( $handle );
}

/**
 * Enqueue a script.
 *
 * Registers the script if $src provided (does NOT overwrite), and enqueues it.
 *
 * @see BD_Dependencies::add(), BD_Dependencies::add_data(), BD_Dependencies::enqueue()
 *
 * @since 1.0.0
 *
 * @param string      $handle    Name of the script.
 * @param string|bool $src       Path to the script from the root directory of Blasdoise. Example: '/js/myscript.js'.
 * @param array       $deps      An array of registered handles this script depends on. Default empty array.
 * @param string|bool $ver       Optional. String specifying the script version number, if it has one. This parameter
 *                               is used to ensure that the correct version is sent to the client regardless of caching,
 *                               and so should be included if a version number is available and makes sense for the script.
 * @param bool        $in_footer Optional. Whether to enqueue the script before </head> or before </body>.
 *                               Default 'false'. Accepts 'false' or 'true'.
 */
function bd_enqueue_script( $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {
	$bd_scripts = bd_scripts();

	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );


	if ( $src || $in_footer ) {
		$_handle = explode( '?', $handle );

		if ( $src ) {
			$bd_scripts->add( $_handle[0], $src, $deps, $ver );
		}

		if ( $in_footer ) {
			$bd_scripts->add_data( $_handle[0], 'group', 1 );
		}
	}

	$bd_scripts->enqueue( $handle );
}

/**
 * Remove a previously enqueued script.
 *
 * @see BD_Dependencies::dequeue()
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the script to be removed.
 */
function bd_dequeue_script( $handle ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	bd_scripts()->dequeue( $handle );
}

/**
 * Check whether a script has been added to the queue.
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the script.
 * @param string $list   Optional. Status of the script to check. Default 'enqueued'.
 *                       Accepts 'enqueued', 'registered', 'queue', 'to_do', and 'done'.
 * @return bool Whether the script script is queued.
 */
function bd_script_is( $handle, $list = 'enqueued' ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	return (bool) bd_scripts()->query( $handle, $list );
}

/**
 * Add metadata to a script.
 *
 * Works only if the script has already been added.
 *
 * Possible values for $key and $value:
 * 'conditional' string Comments for IE 6, lte IE 7, etc.
 *
 * @since 1.0.0
 *
 * @see BD_Dependency::add_data()
 *
 * @param string $handle Name of the script.
 * @param string $key    Name of data point for which we're storing a value.
 * @param mixed  $value  String containing the data to be added.
 * @return bool True on success, false on failure.
 */
function bd_script_add_data( $handle, $key, $value ){
	return bd_scripts()->add_data( $handle, $key, $value );
}
