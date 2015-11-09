<?php
/**
 * Blasdoise Administration Template Header
 *
 * @package Blasdoise
 * @subpackage Administration
 */

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
if ( ! defined( 'BD_ADMIN' ) )
	require_once( dirname( __FILE__ ) . '/admin.php' );

/**
 * In case admin-header.php is included in a function.
 *
 * @global string    $title
 * @global string    $hook_suffix
 * @global BD_Screen $current_screen
 * @global BD_Locale $bd_locale
 * @global string    $pagenow
 * @global string    $bd_version
 * @global string    $update_title
 * @global int       $total_update_count
 * @global string    $parent_file
 */
global $title, $hook_suffix, $current_screen, $bd_locale, $pagenow, $bd_version,
	$update_title, $total_update_count, $parent_file;

// Catch plugins that include admin-header.php before admin.php completes.
if ( empty( $current_screen ) )
	set_current_screen();

get_admin_page_title();
$title = esc_html( strip_tags( $title ) );

if ( is_network_admin() )
	$admin_title = sprintf( __( 'Network Admin: %s' ), esc_html( get_current_site()->site_name ) );
elseif ( is_user_admin() )
	$admin_title = sprintf( __( 'User Dashboard: %s' ), esc_html( get_current_site()->site_name ) );
else
	$admin_title = get_bloginfo( 'name' );

if ( $admin_title == $title )
	$admin_title = sprintf( __( '%1$s &#8212; Blasdoise' ), $title );
else
	$admin_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; Blasdoise' ), $title, $admin_title );

/**
 * Filter the title tag content for an admin page.
 *
 * @since 1.0.0
 *
 * @param string $admin_title The page title, with extra context added.
 * @param string $title       The original page title.
 */
$admin_title = apply_filters( 'admin_title', $admin_title, $title );

bd_user_settings();

_bd_admin_html_begin();
?>
<title><?php echo $admin_title; ?></title>
<?php

bd_enqueue_style( 'colors' );
bd_enqueue_style( 'ie' );
bd_enqueue_script('utils');
bd_enqueue_script( 'svg-painter' );

$admin_body_class = preg_replace('/[^a-z0-9_-]+/i', '-', $hook_suffix);
?>
<script type="text/javascript">
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof bdOnload!='function'){bdOnload=func;}else{var oldonload=bdOnload;bdOnload=function(){oldonload();func();}}};
var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>',
	pagenow = '<?php echo $current_screen->id; ?>',
	typenow = '<?php echo $current_screen->post_type; ?>',
	adminpage = '<?php echo $admin_body_class; ?>',
	thousandsSeparator = '<?php echo addslashes( $bd_locale->number_format['thousands_sep'] ); ?>',
	decimalPoint = '<?php echo addslashes( $bd_locale->number_format['decimal_point'] ); ?>',
	isRtl = <?php echo (int) is_rtl(); ?>;
</script>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<?php

/**
 * Enqueue scripts for all admin pages.
 *
 * @since 1.0.0
 *
 * @param string $hook_suffix The current admin page.
 */
do_action( 'admin_enqueue_scripts', $hook_suffix );

/**
 * Fires when styles are printed for a specific admin page based on $hook_suffix.
 *
 * @since 1.0.0
 */
do_action( "admin_print_styles-$hook_suffix" );

/**
 * Fires when styles are printed for all admin pages.
 *
 * @since 1.0.0
 */
do_action( 'admin_print_styles' );

/**
 * Fires when scripts are printed for a specific admin page based on $hook_suffix.
 *
 * @since 1.0.0
 */
do_action( "admin_print_scripts-$hook_suffix" );

/**
 * Fires when scripts are printed for all admin pages.
 *
 * @since 1.0.0
 */
do_action( 'admin_print_scripts' );

/**
 * Fires in head section for a specific admin page.
 *
 * The dynamic portion of the hook, `$hook_suffix`, refers to the hook suffix
 * for the admin page.
 *
 * @since 1.0.0
 */
do_action( "admin_head-$hook_suffix" );

/**
 * Fires in head section for all admin pages.
 *
 * @since 1.0.0
 */
do_action( 'admin_head' );

if ( get_user_setting('mfold') == 'f' )
	$admin_body_class .= ' folded';

if ( !get_user_setting('unfold') )
	$admin_body_class .= ' auto-fold';

if ( is_admin_bar_showing() )
	$admin_body_class .= ' admin-bar';

if ( is_rtl() )
	$admin_body_class .= ' rtl';

if ( $current_screen->post_type )
	$admin_body_class .= ' post-type-' . $current_screen->post_type;

if ( $current_screen->taxonomy )
	$admin_body_class .= ' taxonomy-' . $current_screen->taxonomy;

$admin_body_class .= ' branch-' . str_replace( array( '.', ',' ), '-', floatval( $bd_version ) );
$admin_body_class .= ' version-' . str_replace( '.', '-', preg_replace( '/^([.0-9]+).*/', '$1', $bd_version ) );
$admin_body_class .= ' admin-color-' . sanitize_html_class( get_user_option( 'admin_color' ), 'fresh' );
$admin_body_class .= ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );

if ( bd_is_mobile() )
	$admin_body_class .= ' mobile';

if ( is_multisite() )
	$admin_body_class .= ' multisite';

if ( is_network_admin() )
	$admin_body_class .= ' network-admin';

$admin_body_class .= ' no-customize-support no-svg';

?>
</head>
<?php
/**
 * Filter the CSS classes for the body tag in the admin.
 *
 * This filter differs from the {@see 'post_class'} and {@see 'body_class'} filters
 * in two important ways:
 *
 * 1. `$classes` is a space-separated string of class names instead of an array.
 * 2. Not all core admin classes are filterable, notably: bd-admin, bd-core-ui,
 *    and no-js cannot be removed.
 *
 * @since 1.0.0
 *
 * @param string $classes Space-separated list of CSS classes.
 */
$admin_body_classes = apply_filters( 'admin_body_class', '' );
?>
<body class="bd-admin bd-core-ui no-js <?php echo $admin_body_classes . ' ' . $admin_body_class; ?>">
<script type="text/javascript">
	document.body.className = document.body.className.replace('no-js','js');
</script>

<?php
// Make sure the customize body classes are correct as early as possible.
if ( current_user_can( 'customize' ) ) {
	bd_customize_support_script();
}
?>

<div id="bdwrap">
<?php require(ABSPATH . 'bd-admin/menu-header.php'); ?>
<div id="bdcontent">

<?php
/**
 * Fires at the beginning of the content section in an admin page.
 *
 * @since 1.0.0
 */
do_action( 'in_admin_header' );
?>

<div id="bdbody" role="main">
<?php
unset($title_class, $blog_name, $total_update_count, $update_title);

$current_screen->set_parentage( $parent_file );

?>

<div id="bdbody-content" aria-label="<?php esc_attr_e('Main content'); ?>" tabindex="0">
<?php

$current_screen->render_screen_meta();

if ( is_network_admin() ) {
	/**
	 * Print network admin screen notices.
	 *
	 * @since 1.0.0
	 */
	do_action( 'network_admin_notices' );
} elseif ( is_user_admin() ) {
	/**
	 * Print user admin screen notices.
	 *
	 * @since 1.0.0
	 */
	do_action( 'user_admin_notices' );
} else {
	/**
	 * Print admin screen notices.
	 *
	 * @since 1.0.0
	 */
	do_action( 'admin_notices' );
}

/**
 * Print generic admin screen notices.
 *
 * @since 1.0.0
 */
do_action( 'all_admin_notices' );

if ( $parent_file == 'options-general.php' )
	require(ABSPATH . 'bd-admin/options-head.php');