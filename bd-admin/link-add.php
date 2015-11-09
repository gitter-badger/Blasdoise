<?php
/**
 * Add Link Administration Screen.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/** Load Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! current_user_can('manage_links') )
	bd_die(__('You do not have sufficient permissions to add links to this site.'));

$title = __('Add New Link');
$parent_file = 'link-manager.php';

bd_reset_vars( array('action', 'cat_id', 'link_id' ) );

bd_enqueue_script('link');
bd_enqueue_script('xfn');

if ( bd_is_mobile() )
	bd_enqueue_script( 'jquery-touch-punch' );

$link = get_default_link_to_edit();
include( ABSPATH . 'bd-admin/edit-link-form.php' );

require( ABSPATH . 'bd-admin/admin-footer.php' );
