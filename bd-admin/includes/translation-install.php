<?php
/**
 * Blasdoise Translation Install Administration API
 *
 * @package Blasdoise
 * @subpackage Administration
 */


/**
 * Retrieve translations from Blasdoise Translation API.
 *
 * @since 1.0.0
 *
 * @param string       $type Type of translations. Accepts 'plugins', 'themes', 'core'.
 * @param array|object $args Translation API arguments. Optional.
 * @return object|BD_Error On success an object of translations, BD_Error on failure.
 */
function translations_api( $type, $args = null ) {
	include( ABSPATH . BDINC . '/version.php' ); // include an unmodified $bd_version

	if ( ! in_array( $type, array( 'plugins', 'themes', 'core' ) ) ) {
		return	new BD_Error( 'invalid_type', __( 'Invalid translation type.' ) );
	}

	/**
	 * Allows a plugin to override the Blasdoise Translation Install API entirely.
	 *
	 * @since 1.0.0
	 *
	 * @param bool|array  $result The result object. Default false.
	 * @param string      $type   The type of translations being requested.
	 * @param object      $args   Translation API arguments.
	 */
	$res = apply_filters( 'translations_api', false, $type, $args );

	if ( false === $res ) {
		$url = $http_url = 'http://api.blasdoise.com/translation.json';
		if ( $ssl = bd_http_supports( array( 'ssl' ) ) ) {
			$url = set_url_scheme( $url, 'https' );
		}

		$options = array(
			'timeout' => 3,
			'body' => array(
				'bd_version' => $bd_version,
				'locale'     => get_locale(),
				'version'    => $args['version'], // Version of plugin, theme or core
			),
		);

		if ( 'core' !== $type ) {
			$options['body']['slug'] = $args['slug']; // Plugin or theme slug
		}

		$request = bd_remote_post( $url, $options );

		if ( $ssl && is_bd_error( $request ) ) {
			trigger_error( __( 'An unexpected error occurred. Something may be wrong with Blasdoise or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://blasdoise.com/support/">support forums</a>.' ) . ' ' . __( '(Blasdoise could not establish a secure connection to Blasdoise.com. Please contact your server administrator.)' ), headers_sent() || BD_DEBUG ? E_USER_WARNING : E_USER_NOTICE );

			$request = bd_remote_post( $http_url, $options );
		}

		if ( is_bd_error( $request ) ) {
			$res = new BD_Error( 'translations_api_failed', __( 'An unexpected error occurred. Something may be wrong with Blasdoise or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://blasdoise.com/support/">support forums</a>.' ), $request->get_error_message() );
		} else {
			$res = json_decode( bd_remote_retrieve_body( $request ), true );
			if ( ! is_object( $res ) && ! is_array( $res ) ) {
				$res = new BD_Error( 'translations_api_failed', __( 'An unexpected error occurred. Something may be wrong with Blasdoise or this server&#8217;s configuration. If you continue to have problems, please try the <a href="http://blasdoise.com/support/">support forums</a>.' ), bd_remote_retrieve_body( $request ) );
			}
		}
	}

	/**
	 * Filter the Translation Install API response results.
	 *
	 * @since 1.0.0
	 *
	 * @param object|BD_Error $res  Response object or BD_Error.
	 * @param string          $type The type of translations being requested.
	 * @param object          $args Translation API arguments.
	 */
	return apply_filters( 'translations_api_result', $res, $type, $args );
}

/**
 * Get available translations from the Blasdoise API.
 *
 * @since 1.0.0
 *
 * @see translations_api()
 *
 * @return array Array of translations, each an array of data. If the API response results
 *               in an error, an empty array will be returned.
 */
function bd_get_available_translations() {
	if ( ! defined( 'BD_INSTALLING' ) && false !== ( $translations = get_site_transient( 'available_translations' ) ) ) {
		return $translations;
	}

	include( ABSPATH . BDINC . '/version.php' ); // include an unmodified $bd_version

	$api = translations_api( 'core', array( 'version' => $bd_version ) );

	if ( is_bd_error( $api ) || empty( $api['translations'] ) ) {
		return array();
	}

	$translations = array();
	// Key the array with the language code for now.
	foreach ( $api['translations'] as $translation ) {
		$translations[ $translation['language'] ] = $translation;
	}

	if ( ! defined( 'BD_INSTALLING' ) ) {
		set_site_transient( 'available_translations', $translations, 3 * HOUR_IN_SECONDS );
	}

	return $translations;
}

/**
 * Output the select form for the language selection on the installation screen.
 *
 * @since 1.0.0
 *
 * @global string $bd_local_package
 *
 * @param array $languages Array of available languages (populated via the Translation API).
 */
function bd_install_language_form( $languages ) {
	global $bd_local_package;

	$installed_languages = get_available_languages();

	echo "<label class='screen-reader-text' for='language'>Select a default language</label>\n";
	echo "<select size='14' name='language' id='language'>\n";
	echo '<option value="" lang="en" selected="selected" data-continue="Continue" data-installed="1">English (United States)</option>';
	echo "\n";

	if ( ! empty( $bd_local_package ) && isset( $languages[ $bd_local_package ] ) ) {
		if ( isset( $languages[ $bd_local_package ] ) ) {
			$language = $languages[ $bd_local_package ];
			printf( '<option value="%s" lang="%s" data-continue="%s"%s>%s</option>' . "\n",
				esc_attr( $language['language'] ),
				esc_attr( current( $language['iso'] ) ),
				esc_attr( $language['strings']['continue'] ),
				in_array( $language['language'], $installed_languages ) ? ' data-installed="1"' : '',
				esc_html( $language['native_name'] ) );

			unset( $languages[ $bd_local_package ] );
		}
	}

	foreach ( $languages as $language ) {
		printf( '<option value="%s" lang="%s" data-continue="%s"%s>%s</option>' . "\n",
			esc_attr( $language['language'] ),
			esc_attr( current( $language['iso'] ) ),
			esc_attr( $language['strings']['continue'] ),
			in_array( $language['language'], $installed_languages ) ? ' data-installed="1"' : '',
			esc_html( $language['native_name'] ) );
	}
	echo "</select>\n";
	echo '<p class="step"><span class="spinner"></span><input id="language-continue" type="submit" class="button button-primary button-large" value="Continue" /></p>';
}

/**
 * Download a language pack.
 *
 * @since 1.0.0
 *
 * @see bd_get_available_translations()
 *
 * @param string $download Language code to download.
 * @return string|bool Returns the language code if successfully downloaded
 *                     (or already installed), or false on failure.
 */
function bd_download_language_pack( $download ) {
	// Check if the translation is already installed.
	if ( in_array( $download, get_available_languages() ) ) {
		return $download;
	}

	if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
		return false;
	}

	// Confirm the translation is one we can download.
	$translations = bd_get_available_translations();
	if ( ! $translations ) {
		return false;
	}
	foreach ( $translations as $translation ) {
		if ( $translation['language'] === $download ) {
			$translation_to_load = true;
			break;
		}
	}

	if ( empty( $translation_to_load ) ) {
		return false;
	}
	$translation = (object) $translation;

	require_once ABSPATH . 'bd-admin/includes/class-bd-upgrader.php';
	$skin = new Automatic_Upgrader_Skin;
	$upgrader = new Language_Pack_Upgrader( $skin );
	$translation->type = 'core';
	$result = $upgrader->upgrade( $translation, array( 'clear_update_cache' => false ) );

	if ( ! $result || is_bd_error( $result ) ) {
		return false;
	}

	return $translation->language;
}

/**
 * Check if Blasdoise has access to the filesystem without asking for
 * credentials.
 *
 * @since 1.0.0
 *
 * @return bool Returns true on success, false on failure.
 */
function bd_can_install_language_pack() {
	if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
		return false;
	}

	require_once ABSPATH . 'bd-admin/includes/class-bd-upgrader.php';
	$skin = new Automatic_Upgrader_Skin;
	$upgrader = new Language_Pack_Upgrader( $skin );
	$upgrader->init();

	$check = $upgrader->fs_connect( array( BD_CONTENT_DIR, BD_LANG_DIR ) );

	if ( ! $check || is_bd_error( $check ) ) {
		return false;
	}

	return true;
}
