<?php
/**
 * Includes all of the Blasdoise Administration API files.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

if ( ! defined('BD_ADMIN') ) {
	/*
	 * This file is being included from a file other than bd-admin/admin.php, so
	 * some setup was skipped. Make sure the admin message catalog is loaded since
	 * load_default_textdomain() will not have done so in this context.
	 */
	load_textdomain( 'default', BD_LANG_DIR . '/admin-' . get_locale() . '.mo' );
}

/** Blasdoise Administration Hooks */
require_once(ABSPATH . 'bd-admin/includes/admin-filters.php');

/** Blasdoise Bookmark Administration API */
require_once(ABSPATH . 'bd-admin/includes/bookmark.php');

/** Blasdoise Comment Administration API */
require_once(ABSPATH . 'bd-admin/includes/comment.php');

/** Blasdoise Administration File API */
require_once(ABSPATH . 'bd-admin/includes/file.php');

/** Blasdoise Image Administration API */
require_once(ABSPATH . 'bd-admin/includes/image.php');

/** Blasdoise Media Administration API */
require_once(ABSPATH . 'bd-admin/includes/media.php');

/** Blasdoise Misc Administration API */
require_once(ABSPATH . 'bd-admin/includes/misc.php');

/** Blasdoise Plugin Administration API */
require_once(ABSPATH . 'bd-admin/includes/plugin.php');

/** Blasdoise Post Administration API */
require_once(ABSPATH . 'bd-admin/includes/post.php');

/** Blasdoise Administration Screen API */
require_once(ABSPATH . 'bd-admin/includes/screen.php');

/** Blasdoise Taxonomy Administration API */
require_once(ABSPATH . 'bd-admin/includes/taxonomy.php');

/** Blasdoise Template Administration API */
require_once(ABSPATH . 'bd-admin/includes/template.php');

/** Blasdoise List Table Administration API and base class */
require_once(ABSPATH . 'bd-admin/includes/class-bd-list-table.php');
require_once(ABSPATH . 'bd-admin/includes/list-table.php');

/** Blasdoise Theme Administration API */
require_once(ABSPATH . 'bd-admin/includes/theme.php');

/** Blasdoise User Administration API */
require_once(ABSPATH . 'bd-admin/includes/user.php');

/** Blasdoise Site Icon API */
require_once(ABSPATH . 'bd-admin/includes/class-bd-site-icon.php');

/** Blasdoise Update Administration API */
require_once(ABSPATH . 'bd-admin/includes/update.php');

/** Blasdoise Deprecated Administration API */
require_once(ABSPATH . 'bd-admin/includes/deprecated.php');

/** Blasdoise Multisite support API */
if ( is_multisite() ) {
	require_once(ABSPATH . 'bd-admin/includes/ms-admin-filters.php');
	require_once(ABSPATH . 'bd-admin/includes/ms.php');
	require_once(ABSPATH . 'bd-admin/includes/ms-deprecated.php');
}
