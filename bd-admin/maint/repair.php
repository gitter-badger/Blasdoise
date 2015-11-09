<?php
/**
 * Database Repair and Optimization Script.
 *
 * @package Blasdoise
 * @subpackage Database
 */
define('BD_REPAIRING', true);

require_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/bd-load.php' );

header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php _e( 'Blasdoise &rsaquo; Database Repair' ); ?></title>
	<?php
	bd_admin_css( 'install', true );
	?>
</head>
<body class="bd-core-ui">
<h1 id="logo"><a href="<?php echo esc_url( __( 'http://blasdoise.com/' ) ); ?>" tabindex="-1"><?php _e( 'Blasdoise' ); ?></a></h1>

<?php

if ( ! defined( 'BD_ALLOW_REPAIR' ) ) {
	echo '<p>' . __( 'To allow use of this page to automatically repair database problems, please add the following line to your <code>bd-config.php</code> file. Once this line is added to your config, reload this page.' ) . "</p><p><code>define('BD_ALLOW_REPAIR', true);</code></p>";

	$default_key     = 'put your unique phrase here';
	$missing_key     = false;
	$duplicated_keys = array();

	foreach ( array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) as $key ) {
		if ( defined( $key ) ) {
			// Check for unique values of each key.
			$duplicated_keys[ constant( $key ) ] = isset( $duplicated_keys[ constant( $key ) ] );
		} else {
			// If a constant is not defined, it's missing.
			$missing_key = true;
		}
	}

	// If at least one key uses the default value, consider it duplicated.
	if ( isset( $duplicated_keys[ $default_key ] ) ) {
		$duplicated_keys[ $default_key ] = true;
	}

	// Weed out all unique, non-default values.
	$duplicated_keys = array_filter( $duplicated_keys );

	if ( $duplicated_keys || $missing_key ) {
		// Translators: 1: bd-config.php; 2: Secret key service URL.
		echo '<p>' . sprintf( __( 'While you are editing your %1$s file, take a moment to make sure you have all 8 keys and that they are unique.' ), '<code>bd-config.php</code>' ) . '</p>';
	}

} elseif ( isset( $_GET['repair'] ) ) {
	$optimize = 2 == $_GET['repair'];
	$okay = true;
	$problems = array();

	$tables = $bddb->tables();

	// Sitecategories may not exist if global terms are disabled.
	$query = $bddb->prepare( "SHOW TABLES LIKE %s", $bddb->esc_like( $bddb->sitecategories ) );
	if ( is_multisite() && ! $bddb->get_var( $query ) ) {
		unset( $tables['sitecategories'] );
	}

	/**
	 * Filter additional database tables to repair.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tables Array of prefixed table names to be repaired.
	 */
	$tables = array_merge( $tables, (array) apply_filters( 'tables_to_repair', array() ) );

	// Loop over the tables, checking and repairing as needed.
	foreach ( $tables as $table ) {
		$check = $bddb->get_row( "CHECK TABLE $table" );

		echo '<p>';
		if ( 'OK' == $check->Msg_text ) {
			/* translators: %s: table name */
			printf( __( 'The %s table is okay.' ), "<code>$table</code>" );
		} else {
			/* translators: 1: table name, 2: error message, */
			printf( __( 'The %1$s table is not okay. It is reporting the following error: %2$s. Blasdoise will attempt to repair this table&hellip;' ) , "<code>$table</code>", "<code>$check->Msg_text</code>" );

			$repair = $bddb->get_row( "REPAIR TABLE $table" );

			echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;';
			if ( 'OK' == $check->Msg_text ) {
				/* translators: %s: table name */
				printf( __( 'Successfully repaired the %s table.' ), "<code>$table</code>" );
			} else {
				/* translators: 1: table name, 2: error message, */
				echo sprintf( __( 'Failed to repair the %1$s table. Error: %2$s' ), "<code>$table</code>", "<code>$check->Msg_text</code>" ) . '<br />';
				$problems[$table] = $check->Msg_text;
				$okay = false;
			}
		}

		if ( $okay && $optimize ) {
			$check = $bddb->get_row( "ANALYZE TABLE $table" );

			echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;';
			if ( 'Table is already up to date' == $check->Msg_text )  {
				/* translators: %s: table name */
				printf( __( 'The %s table is already optimized.' ), "<code>$table</code>" );
			} else {
				$check = $bddb->get_row( "OPTIMIZE TABLE $table" );

				echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;';
				if ( 'OK' == $check->Msg_text || 'Table is already up to date' == $check->Msg_text ) {
					/* translators: %s: table name */
					printf( __( 'Successfully optimized the %s table.' ), "<code>$table</code>" );
				} else {
					/* translators: 1: table name, 2: error message, */
					printf( __( 'Failed to optimize the %1$s table. Error: %2$s' ), "<code>$table</code>", "<code>$check->Msg_text</code>" );
				}
			}
		}
		echo '</p>';
	}

	if ( $problems ) {
		printf( '<p>' . __('Some database problems could not be repaired.') . '</p>' );
		$problem_output = '';
		foreach ( $problems as $table => $problem )
			$problem_output .= "$table: $problem\n";
		echo '<p><textarea name="errors" id="errors" rows="20" cols="60">' . esc_textarea( $problem_output ) . '</textarea></p>';
	} else {
		echo '<p>' . __( 'Repairs complete. Please remove the following line from bd-config.php to prevent this page from being used by unauthorized users.' ) . "</p><p><code>define('BD_ALLOW_REPAIR', true);</code></p>";
	}
} else {
	if ( isset( $_GET['referrer'] ) && 'is_blog_installed' == $_GET['referrer'] )
		echo '<p>' . __( 'One or more database tables are unavailable. To allow Blasdoise to attempt to repair these tables, press the &#8220;Repair Database&#8221; button. Repairing can take a while, so please be patient.' ) . '</p>';
	else
		echo '<p>' . __( 'Blasdoise can automatically look for some common database problems and repair them. Repairing can take a while, so please be patient.' ) . '</p>';
?>
	<p class="step"><a class="button button-large" href="repair.php?repair=1"><?php _e( 'Repair Database' ); ?></a></p>
	<p><?php _e( 'Blasdoise can also attempt to optimize the database. This improves performance in some situations. Repairing and optimizing the database can take a long time and the database will be locked while optimizing.' ); ?></p>
	<p class="step"><a class="button button-large" href="repair.php?repair=2"><?php _e( 'Repair and Optimize Database' ); ?></a></p>
<?php
}
?>
</body>
</html>