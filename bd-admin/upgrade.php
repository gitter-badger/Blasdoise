<?php
/**
 * Upgrade Blasdoise Page.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/**
 * We are upgrading Blasdoise.
 *
 * @since 1.0.0
 * @var bool
 */
define( 'BD_INSTALLING', true );

/** Load Blasdoise Bootstrap */
require( dirname( dirname( __FILE__ ) ) . '/bd-load.php' );

nocache_headers();

timer_start();
require_once( ABSPATH . 'bd-admin/includes/upgrade.php' );

delete_site_transient('update_core');

if ( isset( $_GET['step'] ) )
	$step = $_GET['step'];
else
	$step = 0;

// Do it. No output.
if ( 'upgrade_db' === $step ) {
	bd_upgrade();
	die( '0' );
}

/**
 * @global string $bd_version
 * @global string $required_php_version
 * @global string $required_mysql_version
 * @global bddb   $bddb
 */
global $bd_version, $required_php_version, $required_mysql_version;

$step = (int) $step;

$php_version    = phpversion();
$mysql_version  = $bddb->db_version();
$php_compat     = version_compare( $php_version, $required_php_version, '>=' );
if ( file_exists( BD_CONTENT_DIR . '/db.php' ) && empty( $bddb->is_mysql ) )
	$mysql_compat = true;
else
	$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );

@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php echo get_option( 'blog_charset' ); ?>" />
	<title><?php _e( 'Blasdoise &rsaquo; Update' ); ?></title>
	<?php
	bd_admin_css( 'install', true );
	bd_admin_css( 'ie', true );
	?>
</head>
<body class="bd-core-ui">
<h1 id="logo"><a href="<?php echo esc_url( __( 'http://blasdoise.com/' ) ); ?>" tabindex="-1"><?php _e( 'Blasdoise' ); ?></a></h1>

<?php if ( get_option( 'db_version' ) == $bd_db_version || !is_blog_installed() ) : ?>

<h2><?php _e( 'No Update Required' ); ?></h2>
<p><?php _e( 'Your Blasdoise database is already up-to-date!' ); ?></p>
<p class="step"><a class="button button-large" href="<?php echo get_option( 'home' ); ?>/"><?php _e( 'Continue' ); ?></a></p>

<?php elseif ( !$php_compat || !$mysql_compat ) :
	if ( !$mysql_compat && !$php_compat )
		printf( __('You cannot update because Blasdoise %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.'), $bd_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version );
	elseif ( !$php_compat )
		printf( __('You cannot update because Blasdoise %1$s requires PHP version %2$s or higher. You are running version %3$s.'), $bd_version, $required_php_version, $php_version );
	elseif ( !$mysql_compat )
		printf( __('You cannot update because Blasdoise %1$s requires MySQL version %2$s or higher. You are running version %3$s.'), $bd_version, $required_mysql_version, $mysql_version );
?>
<?php else :
switch ( $step ) :
	case 0:
		$goback = bd_get_referer();
		if ( $goback ) {
			$goback = esc_url_raw( $goback );
			$goback = urlencode( $goback );
		}
?>
<h2><?php _e( 'Database Update Required' ); ?></h2>
<p><?php _e( 'Blasdoise has been updated! Before we send you on your way, we have to update your database to the newest version.' ); ?></p>
<p><?php _e( 'The update process may take a little while, so please be patient.' ); ?></p>
<p class="step"><a class="button button-large" href="upgrade.php?step=1&amp;backto=<?php echo $goback; ?>"><?php _e( 'Update Blasdoise Database' ); ?></a></p>
<?php
		break;
	case 1:
		bd_upgrade();

			$backto = !empty($_GET['backto']) ? bd_unslash( urldecode( $_GET['backto'] ) ) : __get_option( 'home' ) . '/';
			$backto = esc_url( $backto );
			$backto = bd_validate_redirect($backto, __get_option( 'home' ) . '/');
?>
<h2><?php _e( 'Update Complete' ); ?></h2>
	<p><?php _e( 'Your Blasdoise database has been successfully updated!' ); ?></p>
	<p class="step"><a class="button button-large" href="<?php echo $backto; ?>"><?php _e( 'Continue' ); ?></a></p>

<!--
<pre>
<?php printf( __( '%s queries' ), $bddb->num_queries ); ?>

<?php printf( __( '%s seconds' ), timer_stop( 0 ) ); ?>
</pre>
-->

<?php
		break;
endswitch;
endif;
?>
</body>
</html>
