<?php
/**
 * Fairy back compat functionality
 *
 * Prevents Fairy from running on Blasdoise versions prior to 1.0,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 1.0.
 *
 * @package Blasdoise
 * @subpackage Fairy
 * @since Fairy 1.0
 */

/**
 * Prevent switching to Fairy on old versions of Blasdoise.
 *
 * Switches to the default theme.
 *
 * @since Fairy 1.0
 */
function fairy_switch_theme() {
	switch_theme( BD_DEFAULT_THEME, BD_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'fairy_upgrade_notice' );
}
add_action( 'after_switch_theme', 'fairy_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Fairy on Blasdoise versions prior to 1.0.
 *
 * @since Fairy 1.0
 */
function fairy_upgrade_notice() {
	$message = sprintf( __( 'Fairy requires at least Blasdoise version 1.0. You are running version %s. Please upgrade and try again.', 'fairy' ), $GLOBALS['bd_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on Blasdoise versions prior to 1.0.
 *
 * @since Fairy 1.0
 */
function fairy_customize() {
	bd_die( sprintf( __( 'Fairy requires at least Blasdoise version 1.0. You are running version %s. Please upgrade and try again.', 'fairy' ), $GLOBALS['bd_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'fairy_customize' );

/**
 * Prevent the Theme Preview from being loaded on Blasdoise versions prior to 1.0.
 *
 * @since Fairy 1.0
 */
function fairy_preview() {
	if ( isset( $_GET['preview'] ) ) {
		bd_die( sprintf( __( 'Fairy requires at least Blasdoise version 1.0. You are running version %s. Please upgrade and try again.', 'fairy' ), $GLOBALS['bd_version'] ) );
	}
}
add_action( 'template_redirect', 'fairy_preview' );
