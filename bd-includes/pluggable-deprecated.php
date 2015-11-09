<?php
/**
 * Deprecated pluggable functions from past Blasdoise versions. You shouldn't use these
 * functions and look for the alternatives instead. The functions will be removed in a
 * later version.
 *
 * Deprecated warnings are also thrown if one of these functions is being defined by a plugin.
 *
 * @package Blasdoise
 * @subpackage Deprecated
 * @see pluggable.php
 */

/*
 * Deprecated functions come here to die.
 */

if ( !function_exists('set_current_user') ) :
/**
 * Changes the current user by ID or name.
 *
 * Set $id to null and specify a name if you do not know a user's ID.
 *
 * @since 1.0.0
 * @see bd_set_current_user() An alias of bd_set_current_user()
 * @deprecated 1.0.0
 * @deprecated Use bd_set_current_user()
 *
 * @param int|null $id User ID.
 * @param string $name Optional. The user's username
 * @return BD_User returns bd_set_current_user()
 */
function set_current_user($id, $name = '') {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_set_current_user()' );
	return bd_set_current_user($id, $name);
}
endif;

if ( !function_exists('get_userdatabylogin') ) :
/**
 * Retrieve user info by login name.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use get_user_by('login')
 *
 * @param string $user_login User's username
 * @return bool|object False on failure, User DB row object
 */
function get_userdatabylogin($user_login) {
	_deprecated_function( __FUNCTION__, '1.0', "get_user_by('login')" );
	return get_user_by('login', $user_login);
}
endif;

if ( !function_exists('get_user_by_email') ) :
/**
 * Retrieve user info by email.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use get_user_by('email')
 *
 * @param string $email User's email address
 * @return bool|object False on failure, User DB row object
 */
function get_user_by_email($email) {
	_deprecated_function( __FUNCTION__, '1.0', "get_user_by('email')" );
	return get_user_by('email', $email);
}
endif;

if ( !function_exists('bd_setcookie') ) :
/**
 * Sets a cookie for a user who just logged in. This function is deprecated.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_set_auth_cookie()
 * @see bd_set_auth_cookie()
 *
 * @param string $username The user's username
 * @param string $password Optional. The user's password
 * @param bool $already_md5 Optional. Whether the password has already been through MD5
 * @param string $home Optional. Will be used instead of COOKIEPATH if set
 * @param string $siteurl Optional. Will be used instead of SITECOOKIEPATH if set
 * @param bool $remember Optional. Remember that the user is logged in
 */
function bd_setcookie($username, $password = '', $already_md5 = false, $home = '', $siteurl = '', $remember = false) {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_set_auth_cookie()' );
	$user = get_user_by('login', $username);
	bd_set_auth_cookie($user->ID, $remember);
}
else :
	_deprecated_function( 'bd_setcookie', '1.0', 'bd_set_auth_cookie()' );
endif;

if ( !function_exists('bd_clearcookie') ) :
/**
 * Clears the authentication cookie, logging the user out. This function is deprecated.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_clear_auth_cookie()
 * @see bd_clear_auth_cookie()
 */
function bd_clearcookie() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_clear_auth_cookie()' );
	bd_clear_auth_cookie();
}
else :
	_deprecated_function( 'bd_clearcookie', '1.0', 'bd_clear_auth_cookie()' );
endif;

if ( !function_exists('bd_get_cookie_login') ):
/**
 * Gets the user cookie login. This function is deprecated.
 *
 * This function is deprecated and should no longer be extended as it won't be
 * used anywhere in Blasdoise. Also, plugins shouldn't use it either.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated No alternative
 *
 * @return bool Always returns false
 */
function bd_get_cookie_login() {
	_deprecated_function( __FUNCTION__, '1.0' );
	return false;
}
else :
	_deprecated_function( 'bd_get_cookie_login', '1.0' );
endif;

if ( !function_exists('bd_login') ) :
/**
 * Checks a users login information and logs them in if it checks out. This function is deprecated.
 *
 * Use the global $error to get the reason why the login failed. If the username
 * is blank, no error will be set, so assume blank username on that case.
 *
 * Plugins extending this function should also provide the global $error and set
 * what the error is, so that those checking the global for why there was a
 * failure can utilize it later.
 *
 * @since 1.2.2
 * @deprecated Use bd_signon()
 * @global string $error Error when false is returned
 *
 * @param string $username   User's username
 * @param string $password   User's password
 * @param string $deprecated Not used
 * @return bool False on login failure, true on successful check
 */
function bd_login($username, $password, $deprecated = '') {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_signon()' );
	global $error;

	$user = bd_authenticate($username, $password);

	if ( ! is_bd_error($user) )
		return true;

	$error = $user->get_error_message();
	return false;
}
else :
	_deprecated_function( 'bd_login', '1.0', 'bd_signon()' );
endif;

/**
 * Blasdoise AtomPub API implementation.
 *
 * Originally stored in bd-app.php, and later bd-includes/class-bd-atom-server.php.
 * It is kept here in case a plugin directly referred to the class.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
if ( ! class_exists( 'bd_atom_server' ) ) {
	class bd_atom_server {
		public function __call( $name, $arguments ) {
			_deprecated_function( __CLASS__ . '::' . $name, '1.0', 'the Atom Publishing Protocol plugin' );
		}

		public static function __callStatic( $name, $arguments ) {
			_deprecated_function( __CLASS__ . '::' . $name, '1.0', 'the Atom Publishing Protocol plugin' );
		}
	}
}