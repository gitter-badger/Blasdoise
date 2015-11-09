<?php
/**
 * BackPress Styles Procedural API
 *
 * @since 1.0.0
 *
 * @package Blasdoise
 * @subpackage BackPress
 */

/**
 * Initialize $bd_styles if it has not been set.
 *
 * @global BD_Styles $bd_styles
 *
 * @since 1.0.0
 *
 * @return BD_Styles BD_Styles instance.
 */
function bd_styles() {
	global $bd_styles;
	if ( ! ( $bd_styles instanceof BD_Styles ) ) {
		$bd_styles = new BD_Styles();
	}
	return $bd_styles;
}

/**
 * Display styles that are in the $handles queue.
 *
 * Passing an empty array to $handles prints the queue,
 * passing an array with one string prints that style,
 * and passing an array of strings prints those styles.
 *
 * @global BD_Styles $bd_styles The BD_Styles object for printing styles.
 *
 * @since 1.0.0
 *
 * @param string|bool|array $handles Styles to be printed. Default 'false'.
 * @return array On success, a processed array of BD_Dependencies items; otherwise, an empty array.
 */
function bd_print_styles( $handles = false ) {
	if ( '' === $handles ) { // for bd_header
		$handles = false;
	}
	/**
	 * Fires before styles in the $handles queue are printed.
	 *
	 * @since 1.0.0
	 */
	if ( ! $handles ) {
		do_action( 'bd_print_styles' );
	}

	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	global $bd_styles;
	if ( ! ( $bd_styles instanceof BD_Styles ) ) {
		if ( ! $handles ) {
			return array(); // No need to instantiate if nothing is there.
		}
	}

	return bd_styles()->do_items( $handles );
}

/**
 * Add extra CSS styles to a registered stylesheet.
 *
 * Styles will only be added if the stylesheet in already in the queue.
 * Accepts a string $data containing the CSS. If two or more CSS code blocks
 * are added to the same stylesheet $handle, they will be printed in the order
 * they were added, i.e. the latter added styles can redeclare the previous.
 *
 * @see BD_Styles::add_inline_style()
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the stylesheet to add the extra styles to. Must be lowercase.
 * @param string $data   String containing the CSS styles to be added.
 * @return bool True on success, false on failure.
 */
function bd_add_inline_style( $handle, $data ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	if ( false !== stripos( $data, '</style>' ) ) {
		_doing_it_wrong( __FUNCTION__, __( 'Do not pass style tags to bd_add_inline_style().' ), '1.0' );
		$data = trim( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $data ) );
	}

	return bd_styles()->add_inline_style( $handle, $data );
}

/**
 * Register a CSS stylesheet.
 *
 * @see BD_Dependencies::add()
 *
 * @since 1.0.0
 *
 * @param string      $handle Name of the stylesheet.
 * @param string|bool $src    Path to the stylesheet from the Blasdoise root directory. Example: '/css/mystyle.css'.
 * @param array       $deps   An array of registered style handles this stylesheet depends on. Default empty array.
 * @param string|bool $ver    String specifying the stylesheet version number. Used to ensure that the correct version
 *                            is sent to the client regardless of caching. Default 'false'. Accepts 'false', 'null', or 'string'.
 * @param string      $media  Optional. The media for which this stylesheet has been defined.
 *                            Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print',
 *                            'screen', 'tty', or 'tv'.
 * @return bool Whether the style has been registered. True on success, false on failure.
 */
function bd_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	return bd_styles()->add( $handle, $src, $deps, $ver, $media );
}

/**
 * Remove a registered stylesheet.
 *
 * @see BD_Dependencies::remove()
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the stylesheet to be removed.
 */
function bd_deregister_style( $handle ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	bd_styles()->remove( $handle );
}

/**
 * Enqueue a CSS stylesheet.
 *
 * Registers the style if source provided (does NOT overwrite) and enqueues.
 *
 * @see BD_Dependencies::add(), BD_Dependencies::enqueue()
 *
 * @since 1.0.0
 *
 * @param string      $handle Name of the stylesheet.
 * @param string|bool $src    Path to the stylesheet from the root directory of Blasdoise. Example: '/css/mystyle.css'.
 * @param array       $deps   An array of registered style handles this stylesheet depends on. Default empty array.
 * @param string|bool $ver    String specifying the stylesheet version number, if it has one. This parameter is used
 *                            to ensure that the correct version is sent to the client regardless of caching, and so
 *                            should be included if a version number is available and makes sense for the stylesheet.
 * @param string      $media  Optional. The media for which this stylesheet has been defined.
 *                            Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print',
 *                            'screen', 'tty', or 'tv'.
 */
function bd_enqueue_style( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	$bd_styles = bd_styles();

	if ( $src ) {
		$_handle = explode('?', $handle);
		$bd_styles->add( $_handle[0], $src, $deps, $ver, $media );
	}
	$bd_styles->enqueue( $handle );
}

/**
 * Remove a previously enqueued CSS stylesheet.
 *
 * @see BD_Dependencies::dequeue()
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the stylesheet to be removed.
 */
function bd_dequeue_style( $handle ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	bd_styles()->dequeue( $handle );
}

/**
 * Check whether a CSS stylesheet has been added to the queue.
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the stylesheet.
 * @param string $list   Optional. Status of the stylesheet to check. Default 'enqueued'.
 *                       Accepts 'enqueued', 'registered', 'queue', 'to_do', and 'done'.
 * @return bool Whether style is queued.
 */
function bd_style_is( $handle, $list = 'enqueued' ) {
	_bd_scripts_maybe_doing_it_wrong( __FUNCTION__ );

	return (bool) bd_styles()->query( $handle, $list );
}

/**
 * Add metadata to a CSS stylesheet.
 *
 * Works only if the stylesheet has already been added.
 *
 * Possible values for $key and $value:
 * 'conditional' string      Comments for IE 6, lte IE 7 etc.
 * 'rtl'         bool|string To declare an RTL stylesheet.
 * 'suffix'      string      Optional suffix, used in combination with RTL.
 * 'alt'         bool        For rel="alternate stylesheet".
 * 'title'       string      For preferred/alternate stylesheets.
 *
 * @see BD_Dependency::add_data()
 *
 * @since 1.0.0
 *
 * @param string $handle Name of the stylesheet.
 * @param string $key    Name of data point for which we're storing a value.
 *                       Accepts 'conditional', 'rtl' and 'suffix', 'alt' and 'title'.
 * @param mixed  $value  String containing the CSS data to be added.
 * @return bool True on success, false on failure.
 */
function bd_style_add_data( $handle, $key, $value ) {
	return bd_styles()->add_data( $handle, $key, $value );
}
