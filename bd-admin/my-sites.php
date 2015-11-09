<?php
/**
 * My Sites dashboard.
 *
 * @package Blasdoise
 * @subpackage Multisite
 * @since 1.0.0
 */

require_once( dirname( __FILE__ ) . '/admin.php' );

if ( !is_multisite() )
	bd_die( __( 'Multisite support is not enabled.' ) );

if ( ! current_user_can('read') )
	bd_die( __( 'You do not have sufficient permissions to access this page.' ) );

$action = isset( $_POST['action'] ) ? $_POST['action'] : 'splash';

$blogs = get_blogs_of_user( $current_user->ID );

$updated = false;
if ( 'updateblogsettings' == $action && isset( $_POST['primary_blog'] ) ) {
	check_admin_referer( 'update-my-sites' );

	$blog = get_blog_details( (int) $_POST['primary_blog'] );
	if ( $blog && isset( $blog->domain ) ) {
		update_user_option( $current_user->ID, 'primary_blog', (int) $_POST['primary_blog'], true );
		$updated = true;
	} else {
		bd_die( __( 'The primary site you chose does not exist.' ) );
	}
}

$title = __( 'My Sites' );
$parent_file = 'index.php';

get_current_screen()->add_help_tab( array(
	'id'      => 'overview',
	'title'   => __('Overview'),
	'content' =>
		'<p>' . __('This screen shows an individual user all of their sites in this network, and also allows that user to set a primary site. They can use the links under each site to visit either the frontend or the dashboard for that site.') . '</p>'
) );

get_current_screen()->set_help_sidebar(
    '<p><strong>' . __( 'For support:' ) . '</strong></p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Like Us on Facebook</a>'), 'http://facebook.com/blasdoise' ) . '</p>' .
    '<p>' . sprintf( __('<a href="%s" target="_blank">Follow Us on Twitter</a>'), 'http://twitter.com/blasdoise' ) . '</p>'
);

require_once( ABSPATH . 'bd-admin/admin-header.php' );

if ( $updated ) { ?>
	<div id="message" class="updated notice is-dismissible"><p><strong><?php _e( 'Settings saved.' ); ?></strong></p></div>
<?php } ?>

<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>
<?php
if ( empty( $blogs ) ) :
	echo '<p>';
	_e( 'You must be a member of at least one site to use this page.' );
	echo '</p>';
else :
?>
<form id="myblogs" method="post">
	<?php
	choose_primary_blog();
	/**
	 * Fires before the sites list on the My Sites screen.
	 *
	 * @since 1.0.0
	 */
	do_action( 'myblogs_allblogs_options' );
	?>
	<br clear="all" />
	<ul class="my-sites striped">
	<?php
	/**
	 * Enable the Global Settings section on the My Sites screen.
	 *
	 * By default, the Global Settings section is hidden. Passing a non-empty
	 * string to this filter will enable the section, and allow new settings
	 * to be added, either globally or for specific sites.
	 *
	 * @since 1.0.0
	 *
	 * @param string $settings_html The settings HTML markup. Default empty.
	 * @param object $context       Context of the setting (global or site-specific). Default 'global'.
	 */
	$settings_html = apply_filters( 'myblogs_options', '', 'global' );
	if ( $settings_html != '' ) {
		echo '<h3>' . __( 'Global Settings' ) . '</h3>';
		echo $settings_html;
	}
	reset( $blogs );

	foreach ( $blogs as $user_blog ) {
		echo "<li>";
		echo "<h3>{$user_blog->blogname}</h3>";
		/**
		 * Filter the row links displayed for each site on the My Sites screen.
		 *
		 * @since 1.0.0
		 *
		 * @param string $string    The HTML site link markup.
		 * @param object $user_blog An object containing the site data.
		 */
		echo "<p class='my-sites-actions'>" . apply_filters( 'myblogs_blog_actions', "<a href='" . esc_url( get_home_url( $user_blog->userblog_id ) ). "'>" . __( 'Visit' ) . "</a> | <a href='" . esc_url( get_admin_url( $user_blog->userblog_id ) ) . "'>" . __( 'Dashboard' ) . "</a>", $user_blog ) . "</p>";
		/** This filter is documented in bd-admin/my-sites.php */
		echo apply_filters( 'myblogs_options', '', $user_blog );
		echo "</li>";
	}?>
	</ul>
	<?php
	if ( count( $blogs ) > 1 || has_action( 'myblogs_allblogs_options' ) || has_filter( 'myblogs_options' ) ) {
		?><input type="hidden" name="action" value="updateblogsettings" /><?php
		bd_nonce_field( 'update-my-sites' );
		submit_button();
	}
	?>
	</form>
<?php endif; ?>
	</div>
<?php
include( ABSPATH . 'bd-admin/admin-footer.php' );
