<?php
/**
 * Install theme administration panel.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/** Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can('install_themes') )
	bd_die( __( 'You do not have sufficient permissions to install themes on this site.' ) );

if ( is_multisite() && ! is_network_admin() ) {
	bd_redirect( network_admin_url( 'theme-install.php' ) );
	exit();
}

$title = __( 'Add Themes' );
$parent_file = 'themes.php';

if ( ! is_network_admin() ) {
	$submenu_file = 'themes.php';
}

bd_localize_script( 'theme', '_bdThemeSettings', array(
	'themes'   => false,
	'settings' => array(
		'isInstall'     => true,
		'canInstall'    => current_user_can( 'install_themes' ),
		'installURI'    => current_user_can( 'install_themes' ) ? self_admin_url( 'theme-install.php' ) : null,
		'adminUrl'      => parse_url( self_admin_url(), PHP_URL_PATH )
	),
	'l10n' => array(
		'addNew' => __( 'Add New Theme' ),
		'search'  => __( 'Search Themes' ),
		'searchPlaceholder' => __( 'Search themes...' ), // placeholder (no ellipsis)
		'upload' => __( 'Upload Theme' ),
		'back'   => __( 'Back' ),
		'error'  => __( 'An unexpected error occurred. Something may be wrong with Blasdoise or this server&#8217;s configuration.' )
	),
) );

bd_enqueue_script( 'theme' );

$help_overview =
	'<p>' . __('You can Upload a theme manually if you have already downloaded its ZIP archive onto your computer (make sure it is from a trusted and original source). You can also do it the old-fashioned way and copy a downloaded theme&#8217;s folder via FTP into your <code>/bd-content/themes</code> directory.') . '</p>';

get_current_screen()->add_help_tab( array(
	'id'      => 'overview',
	'title'   => __('Overview'),
	'content' => $help_overview
) );

$help_installing =
	'<p>' . __('Once you have generated a list of themes, you can preview and install any of them. Click on the thumbnail of the theme you&#8217;re interested in previewing. It will open up in a full-screen Preview page to give you a better idea of how that theme will look.') . '</p>';

get_current_screen()->add_help_tab( array(
	'id'      => 'installing',
	'title'   => __('Previewing and Installing'),
	'content' => $help_installing
) );

get_current_screen()->set_help_sidebar(
    '<p><strong>' . __( 'For support:' ) . '</strong></p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Like Us on Facebook</a>'), 'http://facebook.com/blasdoise' ) . '</p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Follow Us on Twitter</a>'), 'http://twitter.com/blasdoise' ) . '</p>'
);

include(ABSPATH . 'bd-admin/admin-header.php');

?>
<div class="wrap">
	<h2><?php
		echo esc_html( $title );
	?></h2>

	<div class="upload-theme">
		<p class="install-help"><?php _e('If you have a theme in a .zip format, you may install it by uploading it here.'); ?></p>
		<form method="post" enctype="multipart/form-data" class="bd-upload-form" action="<?php echo self_admin_url('update.php?action=upload-theme'); ?>">
			<?php bd_nonce_field( 'theme-upload'); ?>
			<input type="file" name="themezip" />
			<?php submit_button( __( 'Install Now' ), 'button', 'install-theme-submit', false ); ?>
		</form>
	</div>
	
</div>

<?php
include(ABSPATH . 'bd-admin/admin-footer.php');
