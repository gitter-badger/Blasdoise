<?php
/**
 * User Profile Administration Screen.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/**
 * This is a profile page.
 *
 * @since 1.0.0
 * @var bool
 */
define('IS_PROFILE_PAGE', true);

/** Load User Editing Page */
require_once( dirname( __FILE__ ) . '/user-edit.php' );
