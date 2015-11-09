<?php
/**
 * Front to the Blasdoise application. This file doesn't do anything, but loads
 * bd-blog-header.php which does and tells Blasdoise to load the theme.
 *
 * @package Blasdoise
 */

/**
 * Tells Blasdoise to load the Blasdoise theme and output it.
 *
 * @var bool
 */
define('BD_USE_THEMES', true);

/** Loads the Blasdoise Environment and Template */
require( dirname( __FILE__ ) . '/bd-blog-header.php' );
