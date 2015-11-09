<?php
/**
 * Deprecated multisite admin functions from past Blasdoise versions and Blasdoise MU.
 * You shouldn't use these functions and look for the alternatives instead. The functions
 * will be removed in a later version.
 *
 * @package Blasdoise
 * @subpackage Deprecated
 * @since 1.0.0
 */

/**
 * @deprecated 1.0.0
 */
function bdmu_menu() {
	_deprecated_function(__FUNCTION__, '1.0' );
}

/**
  * Determines if the available space defined by the admin has been exceeded by the user.
  *
  * @deprecated 1.0.0
  * @see is_upload_space_available()
 */
function bdmu_checkAvailableSpace() {
	_deprecated_function(__FUNCTION__, '1.0', 'is_upload_space_available()' );

	if ( !is_upload_space_available() )
		bd_die( __('Sorry, you must delete files before you can upload any more.') );
}

/**
 * @deprecated 1.0.0
 */
function mu_options( $options ) {
	_deprecated_function(__FUNCTION__, '1.0' );
	return $options;
}

/**
 * @deprecated 1.0.0
 * @see activate_plugin()
 */
function activate_sitewide_plugin() {
	_deprecated_function(__FUNCTION__, '1.0', 'activate_plugin()' );
	return false;
}

/**
 * @deprecated 1.0.0
 * @see deactivate_sitewide_plugin()
 */
function deactivate_sitewide_plugin( $plugin = false ) {
	_deprecated_function(__FUNCTION__, '1.0', 'deactivate_plugin()' );
}

/**
 * @deprecated 1.0.0
 * @see is_network_only_plugin()
 */
function is_bdmu_sitewide_plugin( $file ) {
	_deprecated_function(__FUNCTION__, '1.0', 'is_network_only_plugin()' );
	return is_network_only_plugin( $file );
}

/**
 * @deprecated 1.0.0
 * @see BD_Theme::get_allowed_on_network()
 */
function get_site_allowed_themes() {
	_deprecated_function( __FUNCTION__, '1.0', 'BD_Theme::get_allowed_on_network()' );
	return array_map( 'intval', BD_Theme::get_allowed_on_network() );
}

/**
 * @deprecated 1.0.0
 * @see BD_Theme::get_allowed_on_site()
 */
function bdmu_get_blog_allowedthemes( $blog_id = 0 ) {
	_deprecated_function( __FUNCTION__, '1.0', 'BD_Theme::get_allowed_on_site()' );
	return array_map( 'intval', BD_Theme::get_allowed_on_site( $blog_id ) );
}

/**
 * @deprecated
 */
function ms_deprecated_blogs_file() {}