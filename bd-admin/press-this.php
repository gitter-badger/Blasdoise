<?php
/**
 * Press This Display and Handler.
 *
 * @package Blasdoise
 * @subpackage Press_This
 */

define('IFRAME_REQUEST' , true);

/** Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( get_post_type_object( 'post' )->cap->create_posts ) )
	bd_die( __( 'Cheatin&#8217; uh?' ), 403 );

/**
 * @global BD_Press_This $bd_press_this
 */
if ( empty( $GLOBALS['bd_press_this'] ) ) {
	include( ABSPATH . 'bd-admin/includes/class-bd-press-this.php' );
}

$GLOBALS['bd_press_this']->html();
