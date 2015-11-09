<?php
/**
 * Install plugin administration panel.
 *
 * @package Blasdoise
 * @subpackage Administration
 */
// TODO route this pages via a specific iframe handler instead of the do_action below
if ( !defined( 'IFRAME_REQUEST' ) && isset( $_GET['tab'] ) && ( 'plugin-information' == $_GET['tab'] ) )
	define( 'IFRAME_REQUEST', true );

/**
 * Blasdoise Administration Bootstrap.
 */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can('install_plugins') )
	bd_die(__('You do not have sufficient permissions to install plugins on this site.'));

if ( is_multisite() && ! is_network_admin() ) {
	bd_redirect( network_admin_url( 'plugin-install.php' ) );
	exit();
}

if ( ! empty( $_REQUEST['_bd_http_referer'] ) ) {
	$location = remove_query_arg( '_bd_http_referer', bd_unslash( $_SERVER['REQUEST_URI'] ) );

	if ( ! empty( $_REQUEST['paged'] ) ) {
		$location = add_query_arg( 'paged', (int) $_REQUEST['paged'], $location );
	}

	bd_redirect( $location );
	exit;
}

$title = __( 'Add Plugins' );
$parent_file = 'plugins.php';

bd_enqueue_script( 'plugin-install' );
if ( 'plugin-information' != $tab )
	add_thickbox();

get_current_screen()->add_help_tab( array(
'id'		=> 'overview',
'title'		=> __('Overview'),
'content'	=>
	'<p>' . __('Plugins hook into Blasdoise to extend its functionality with custom features. Plugins are developed independently from the core Blasdoise application by thousands of developers. All plugins in the official Blasdoise Plugin Directory are compatible with the license Blasdoise uses. You can find new plugins to install by searching or browsing the Directory right here in your own Plugins section.') . '</p>'
) );

get_current_screen()->add_help_tab( array(
'id'		=> 'adding-plugins',
'title'		=> __('Adding Plugins'),
'content'	=>
	'<p>' . __('If you know what you&#8217;re looking for, Search is your best bet. The Search screen has options to search the Blasdoise Plugin Directory for a particular Term, Author, or Tag. You can also search the directory by selecting popular tags. Tags in larger type mean more plugins have been labeled with that tag.') . '</p>' .
	'<p>' . __('If you want to install a plugin that you&#8217;ve downloaded elsewhere, click the Upload link in the upper left. You will be prompted to upload the .zip package, and once uploaded, you can activate the new plugin.') . '</p>'
) );

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For support:' ) . '</strong></p>' .
	'<p>' . sprintf( __('<a href="%s" target="_blank">Like Fanspage on Facebook</a>'), 'http://facebook.com/blasdoise' ) . '</p>' .
	'<p>' . sprintf( __('<a href="%s" target="_blank">Follow Us on Twitter</a>'), 'http://twitter.com/blasdoise' ) . '</p>'
);

/**
 * Blasdoise Administration Template Header.
 */
include(ABSPATH . 'bd-admin/admin-header.php');
?>
<div class="wrap">
<h2>
	<?php
		echo esc_html( $title );
	?>
</h2>

<div class="upload-plugin">
	<p class="install-help"><?php _e('If you have a plugin in a .zip format, you may install it by uploading it here.'); ?></p>
	<form method="post" enctype="multipart/form-data" class="bd-upload-form" action="<?php echo self_admin_url('update.php?action=upload-plugin'); ?>">
		<?php bd_nonce_field( 'plugin-upload'); ?>
		<label class="screen-reader-text" for="pluginzip"><?php _e('Plugin zip file'); ?></label>
		<input type="file" id="pluginzip" name="pluginzip" />
		<?php submit_button( __( 'Install Now' ), 'button', 'install-plugin-submit', false ); ?>
	</form>
</div>
</div>
<?php
/**
 * Blasdoise Administration Template Footer.
 */
include(ABSPATH . 'bd-admin/admin-footer.php');
