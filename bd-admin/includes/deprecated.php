<?php
/**
 * Deprecated admin functions from past Blasdoise versions. You shouldn't use these
 * functions and look for the alternatives instead. The functions will be removed
 * in a later version.
 *
 * @package Blasdoise
 * @subpackage Deprecated
 */

/*
 * Deprecated functions come here to die.
 */

/**
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_editor().
 * @see bd_editor()
 */
function tinymce_include() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_editor()' );

	bd_tiny_mce();
}

/**
 * Unused Admin function.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 *
 */
function documentation_link() {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**
 * Calculates the new dimensions for a downsampled image.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_constrain_dimensions()
 * @see bd_constrain_dimensions()
 *
 * @param int $width Current width of the image
 * @param int $height Current height of the image
 * @param int $wmax Maximum wanted width
 * @param int $hmax Maximum wanted height
 * @return array Shrunk dimensions (width, height).
 */
function bd_shrink_dimensions( $width, $height, $wmax = 128, $hmax = 96 ) {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_constrain_dimensions()' );
	return bd_constrain_dimensions( $width, $height, $wmax, $hmax );
}

/**
 * Calculated the new dimensions for a downsampled image.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_constrain_dimensions()
 * @see bd_constrain_dimensions()
 *
 * @param int $width Current width of the image
 * @param int $height Current height of the image
 * @return array Shrunk dimensions (width, height).
 */
function get_udims( $width, $height ) {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_constrain_dimensions()' );
	return bd_constrain_dimensions( $width, $height, 128, 96 );
}

/**
 * {@internal Missing Short Description}}
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_category_checklist()
 * @see bd_category_checklist()
 *
 * @param int $default
 * @param int $parent
 * @param array $popular_ids
 */
function dropdown_categories( $default = 0, $parent = 0, $popular_ids = array() ) {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_category_checklist()' );
	global $post_ID;
	bd_category_checklist( $post_ID );
}

/**
 * {@internal Missing Short Description}}
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_link_category_checklist()
 * @see bd_link_category_checklist()
 *
 * @param int $default
 */
function dropdown_link_categories( $default = 0 ) {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_link_category_checklist()' );
	global $link_id;
	bd_link_category_checklist( $link_id );
}

/**
 * Get the real filesystem path to a file to edit within the admin.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @uses BD_CONTENT_DIR Full filesystem path to the bd-content directory.
 *
 * @param string $file Filesystem path relative to the bd-content directory.
 * @return string Full filesystem path to edit.
 */
function get_real_file_to_edit( $file ) {
	_deprecated_function( __FUNCTION__, '1.0' );

	return BD_CONTENT_DIR . $file;
}

/**
 * {@internal Missing Short Description}}
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_dropdown_categories()
 * @see bd_dropdown_categories()
 *
 * @param int $currentcat
 * @param int $currentparent
 * @param int $parent
 * @param int $level
 * @param array $categories
 * @return bool|null
 */
function bd_dropdown_cats( $currentcat = 0, $currentparent = 0, $parent = 0, $level = 0, $categories = 0 ) {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_dropdown_categories()' );
	if (!$categories )
		$categories = get_categories( array('hide_empty' => 0) );

	if ( $categories ) {
		foreach ( $categories as $category ) {
			if ( $currentcat != $category->term_id && $parent == $category->parent) {
				$pad = str_repeat( '&#8211; ', $level );
				$category->name = esc_html( $category->name );
				echo "\n\t<option value='$category->term_id'";
				if ( $currentparent == $category->term_id )
					echo " selected='selected'";
				echo ">$pad$category->name</option>";
				bd_dropdown_cats( $currentcat, $currentparent, $category->term_id, $level +1, $categories );
			}
		}
	} else {
		return false;
	}
}

/**
 * Register a setting and its sanitization callback
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use register_setting()
 * @see register_setting()
 *
 * @param string $option_group A settings group name. Should correspond to a whitelisted option key name.
 * 	Default whitelisted option key names include "general," "discussion," and "reading," among others.
 * @param string $option_name The name of an option to sanitize and save.
 * @param callable $sanitize_callback A callback function that sanitizes the option's value.
 */
function add_option_update_handler( $option_group, $option_name, $sanitize_callback = '' ) {
	_deprecated_function( __FUNCTION__, '1.0', 'register_setting()' );
	register_setting( $option_group, $option_name, $sanitize_callback );
}

/**
 * Unregister a setting
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use unregister_setting()
 * @see unregister_setting()
 *
 * @param string $option_group
 * @param string $option_name
 * @param callable $sanitize_callback
 */
function remove_option_update_handler( $option_group, $option_name, $sanitize_callback = '' ) {
	_deprecated_function( __FUNCTION__, '1.0', 'unregister_setting()' );
	unregister_setting( $option_group, $option_name, $sanitize_callback );
}

/**
 * Determines the language to use for CodePress syntax highlighting.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 *
 * @param string $filename
**/
function codepress_get_lang( $filename ) {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**
 * Adds JavaScript required to make CodePress work on the theme/plugin editors.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
**/
function codepress_footer_js() {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**
 * Determine whether to use CodePress.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
**/
function use_codepress() {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**
 * @deprecated 1.0.0
 *
 * @return array List of user IDs.
 */
function get_author_user_ids() {
	_deprecated_function( __FUNCTION__, '1.0', 'get_users()' );

	global $bddb;
	if ( !is_multisite() )
		$level_key = $bddb->get_blog_prefix() . 'user_level';
	else
		$level_key = $bddb->get_blog_prefix() . 'capabilities'; // bdmu site admins don't have user_levels

	return $bddb->get_col( $bddb->prepare("SELECT user_id FROM $bddb->usermeta WHERE meta_key = %s AND meta_value != '0'", $level_key) );
}

/**
 * @deprecated 1.0.0
 *
 * @param int $user_id User ID.
 * @return array|bool List of editable authors. False if no editable users.
 */
function get_editable_authors( $user_id ) {
	_deprecated_function( __FUNCTION__, '1.0', 'get_users()' );

	global $bddb;

	$editable = get_editable_user_ids( $user_id );

	if ( !$editable ) {
		return false;
	} else {
		$editable = join(',', $editable);
		$authors = $bddb->get_results( "SELECT * FROM $bddb->users WHERE ID IN ($editable) ORDER BY display_name" );
	}

	return apply_filters('get_editable_authors', $authors);
}

/**
 * @deprecated 1.0.0
 *
 * @param int $user_id User ID.
 * @param bool $exclude_zeros Optional, default is true. Whether to exclude zeros.
 * @return mixed
 */
function get_editable_user_ids( $user_id, $exclude_zeros = true, $post_type = 'post' ) {
	_deprecated_function( __FUNCTION__, '1.0', 'get_users()' );

	global $bddb;

	if ( ! $user = get_userdata( $user_id ) )
		return array();
	$post_type_obj = get_post_type_object($post_type);

	if ( ! $user->has_cap($post_type_obj->cap->edit_others_posts) ) {
		if ( $user->has_cap($post_type_obj->cap->edit_posts) || ! $exclude_zeros )
			return array($user->ID);
		else
			return array();
	}

	if ( !is_multisite() )
		$level_key = $bddb->get_blog_prefix() . 'user_level';
	else
		$level_key = $bddb->get_blog_prefix() . 'capabilities'; // bdmu site admins don't have user_levels

	$query = $bddb->prepare("SELECT user_id FROM $bddb->usermeta WHERE meta_key = %s", $level_key);
	if ( $exclude_zeros )
		$query .= " AND meta_value != '0'";

	return $bddb->get_col( $query );
}

/**
 * @deprecated 1.0.0
 */
function get_nonauthor_user_ids() {
	_deprecated_function( __FUNCTION__, '1.0', 'get_users()' );

	global $bddb;

	if ( !is_multisite() )
		$level_key = $bddb->get_blog_prefix() . 'user_level';
	else
		$level_key = $bddb->get_blog_prefix() . 'capabilities'; // bdmu site admins don't have user_levels

	return $bddb->get_col( $bddb->prepare("SELECT user_id FROM $bddb->usermeta WHERE meta_key = %s AND meta_value = '0'", $level_key) );
}

if ( !class_exists('BD_User_Search') ) :
/**
 * Blasdoise User Search class.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
class BD_User_Search {

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var mixed
	 */
	var $results;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	var $search_term;

	/**
	 * Page number.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var int
	 */
	var $page;

	/**
	 * Role name that users have.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	var $role;

	/**
	 * Raw page number.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var int|bool
	 */
	var $raw_page;

	/**
	 * Amount of users to display per page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var int
	 */
	var $users_per_page = 50;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var int
	 */
	var $first_user;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var int
	 */
	var $last_user;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	var $query_limit;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	var $query_orderby;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	var $query_from;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	var $query_where;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var int
	 */
	var $total_users_for_query = 0;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool
	 */
	var $too_many_total_users = false;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var BD_Error
	 */
	var $search_errors;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string
	 */
	var $paging_text;

	/**
	 * PHP5 Constructor - Sets up the object properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $search_term Search terms string.
	 * @param int $page Optional. Page ID.
	 * @param string $role Role name.
	 * @return BD_User_Search
	 */
	function __construct( $search_term = '', $page = '', $role = '' ) {
		_deprecated_function( __FUNCTION__, '1.0', 'BD_User_Query' );

		$this->search_term = bd_unslash( $search_term );
		$this->raw_page = ( '' == $page ) ? false : (int) $page;
		$this->page = (int) ( '' == $page ) ? 1 : $page;
		$this->role = $role;

		$this->prepare_query();
		$this->query();
		$this->do_paging();
	}

	/**
	 * PHP4 Constructor - Sets up the object properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $search_term Search terms string.
	 * @param int $page Optional. Page ID.
	 * @param string $role Role name.
	 * @return BD_User_Search
	 */
	public function BD_User_Search( $search_term = '', $page = '', $role = '' ) {
		self::__construct( $search_term, $page, $role );
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function prepare_query() {
		global $bddb;
		$this->first_user = ($this->page - 1) * $this->users_per_page;

		$this->query_limit = $bddb->prepare(" LIMIT %d, %d", $this->first_user, $this->users_per_page);
		$this->query_orderby = ' ORDER BY user_login';

		$search_sql = '';
		if ( $this->search_term ) {
			$searches = array();
			$search_sql = 'AND (';
			foreach ( array('user_login', 'user_nicename', 'user_email', 'user_url', 'display_name') as $col )
				$searches[] = $bddb->prepare( $col . ' LIKE %s', '%' . like_escape($this->search_term) . '%' );
			$search_sql .= implode(' OR ', $searches);
			$search_sql .= ')';
		}

		$this->query_from = " FROM $bddb->users";
		$this->query_where = " WHERE 1=1 $search_sql";

		if ( $this->role ) {
			$this->query_from .= " INNER JOIN $bddb->usermeta ON $bddb->users.ID = $bddb->usermeta.user_id";
			$this->query_where .= $bddb->prepare(" AND $bddb->usermeta.meta_key = '{$bddb->prefix}capabilities' AND $bddb->usermeta.meta_value LIKE %s", '%' . $this->role . '%');
		} elseif ( is_multisite() ) {
			$level_key = $bddb->prefix . 'capabilities'; // bdmu site admins don't have user_levels
			$this->query_from .= ", $bddb->usermeta";
			$this->query_where .= " AND $bddb->users.ID = $bddb->usermeta.user_id AND meta_key = '{$level_key}'";
		}

		do_action_ref_array( 'pre_user_search', array( &$this ) );
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function query() {
		global $bddb;

		$this->results = $bddb->get_col("SELECT DISTINCT($bddb->users.ID)" . $this->query_from . $this->query_where . $this->query_orderby . $this->query_limit);

		if ( $this->results )
			$this->total_users_for_query = $bddb->get_var("SELECT COUNT(DISTINCT($bddb->users.ID))" . $this->query_from . $this->query_where); // no limit
		else
			$this->search_errors = new BD_Error('no_matching_users_found', __('No users found.'));
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function prepare_vars_for_template_usage() {}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function do_paging() {
		if ( $this->total_users_for_query > $this->users_per_page ) { // have to page the results
			$args = array();
			if ( ! empty($this->search_term) )
				$args['usersearch'] = urlencode($this->search_term);
			if ( ! empty($this->role) )
				$args['role'] = urlencode($this->role);

			$this->paging_text = paginate_links( array(
				'total' => ceil($this->total_users_for_query / $this->users_per_page),
				'current' => $this->page,
				'base' => 'users.php?%_%',
				'format' => 'userspage=%#%',
				'add_args' => $args
			) );
			if ( $this->paging_text ) {
				$this->paging_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
					number_format_i18n( ( $this->page - 1 ) * $this->users_per_page + 1 ),
					number_format_i18n( min( $this->page * $this->users_per_page, $this->total_users_for_query ) ),
					number_format_i18n( $this->total_users_for_query ),
					$this->paging_text
				);
			}
		}
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	function get_results() {
		return (array) $this->results;
	}

	/**
	 * Displaying paging text.
	 *
	 * @see do_paging() Builds paging text.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function page_links() {
		echo $this->paging_text;
	}

	/**
	 * Whether paging is enabled.
	 *
	 * @see do_paging() Builds paging text.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool
	 */
	function results_are_paged() {
		if ( $this->paging_text )
			return true;
		return false;
	}

	/**
	 * Whether there are search terms.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool
	 */
	function is_search() {
		if ( $this->search_term )
			return true;
		return false;
	}
}
endif;

/**
 * Retrieve editable posts from other users.
 *
 * @deprecated 1.0.0
 *
 * @param int $user_id User ID to not retrieve posts from.
 * @param string $type Optional, defaults to 'any'. Post type to retrieve, can be 'draft' or 'pending'.
 * @return array List of posts from others.
 */
function get_others_unpublished_posts($user_id, $type='any') {
	_deprecated_function( __FUNCTION__, '1.0' );

	global $bddb;

	$editable = get_editable_user_ids( $user_id );

	if ( in_array($type, array('draft', 'pending')) )
		$type_sql = " post_status = '$type' ";
	else
		$type_sql = " ( post_status = 'draft' OR post_status = 'pending' ) ";

	$dir = ( 'pending' == $type ) ? 'ASC' : 'DESC';

	if ( !$editable ) {
		$other_unpubs = '';
	} else {
		$editable = join(',', $editable);
		$other_unpubs = $bddb->get_results( $bddb->prepare("SELECT ID, post_title, post_author FROM $bddb->posts WHERE post_type = 'post' AND $type_sql AND post_author IN ($editable) AND post_author != %d ORDER BY post_modified $dir", $user_id) );
	}

	return apply_filters('get_others_drafts', $other_unpubs);
}

/**
 * Retrieve drafts from other users.
 *
 * @deprecated 1.0.0
 *
 * @param int $user_id User ID.
 * @return array List of drafts from other users.
 */
function get_others_drafts($user_id) {
	_deprecated_function( __FUNCTION__, '1.0' );

	return get_others_unpublished_posts($user_id, 'draft');
}

/**
 * Retrieve pending review posts from other users.
 *
 * @deprecated 1.0.0
 *
 * @param int $user_id User ID.
 * @return array List of posts with pending review post type from other users.
 */
function get_others_pending($user_id) {
	_deprecated_function( __FUNCTION__, '1.0' );

	return get_others_unpublished_posts($user_id, 'pending');
}

/**
 * Output the QuickPress dashboard widget.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_dashboard_quick_press()
 * @see bd_dashboard_quick_press()
 */
function bd_dashboard_quick_press_output() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_dashboard_quick_press()' );
	bd_dashboard_quick_press();
}

/**
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_editor()
 * @see bd_editor()
 *
 * @staticvar int $num
 */
function bd_tiny_mce( $teeny = false, $settings = false ) {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_editor()' );

	static $num = 1;

	if ( ! class_exists('_BD_Editors' ) )
		require_once( ABSPATH . BDINC . '/class-bd-editor.php' );

	$editor_id = 'content' . $num++;

	$set = array(
		'teeny' => $teeny,
		'tinymce' => $settings ? $settings : true,
		'quicktags' => false
	);

	$set = _BD_Editors::parse_settings($editor_id, $set);
	_BD_Editors::editor_settings($editor_id, $set);
}

/**
 * @deprecated 1.0.0
 * @deprecated Use bd_editor()
 * @see bd_editor()
 */
function bd_preload_dialogs() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_editor()' );
}

/**
 * @deprecated 1.0.0
 * @deprecated Use bd_editor()
 * @see bd_editor()
 */
function bd_print_editor_js() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_editor()' );
}

/**
 * @deprecated 1.0.0
 * @deprecated Use bd_editor()
 * @see bd_editor()
 */
function bd_quicktags() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_editor()' );
}

/**
 * Returns the screen layout options.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use $current_screen->render_screen_layout()
 * @see BD_Screen::render_screen_layout()
 */
function screen_layout( $screen ) {
	_deprecated_function( __FUNCTION__, '1.0', '$current_screen->render_screen_layout()' );

	$current_screen = get_current_screen();

	if ( ! $current_screen )
		return '';

	ob_start();
	$current_screen->render_screen_layout();
	return ob_get_clean();
}

/**
 * Returns the screen's per-page options.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use $current_screen->render_per_page_options()
 * @see BD_Screen::render_per_page_options()
 */
function screen_options( $screen ) {
	_deprecated_function( __FUNCTION__, '1.0', '$current_screen->render_per_page_options()' );

	$current_screen = get_current_screen();

	if ( ! $current_screen )
		return '';

	ob_start();
	$current_screen->render_per_page_options();
	return ob_get_clean();
}

/**
 * Renders the screen's help.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use $current_screen->render_screen_meta()
 * @see BD_Screen::render_screen_meta()
 */
function screen_meta( $screen ) {
	$current_screen = get_current_screen();
	$current_screen->render_screen_meta();
}

/**
 * Favorite actions were deprecated in version 1.0. Use the admin bar instead.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
function favorite_actions() {
	_deprecated_function( __FUNCTION__, '1.0', 'BD_Admin_Bar' );
}

function media_upload_image() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_media_upload_handler()' );
	return bd_media_upload_handler();
}

function media_upload_audio() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_media_upload_handler()' );
	return bd_media_upload_handler();
}

function media_upload_video() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_media_upload_handler()' );
	return bd_media_upload_handler();
}

function media_upload_file() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_media_upload_handler()' );
	return bd_media_upload_handler();
}

function type_url_form_image() {
	_deprecated_function( __FUNCTION__, '1.0', "bd_media_insert_url_form('image')" );
	return bd_media_insert_url_form( 'image' );
}

function type_url_form_audio() {
	_deprecated_function( __FUNCTION__, '1.0', "bd_media_insert_url_form('audio')" );
	return bd_media_insert_url_form( 'audio' );
}

function type_url_form_video() {
	_deprecated_function( __FUNCTION__, '1.0', "bd_media_insert_url_form('video')" );
	return bd_media_insert_url_form( 'video' );
}

function type_url_form_file() {
	_deprecated_function( __FUNCTION__, '1.0', "bd_media_insert_url_form('file')" );
	return bd_media_insert_url_form( 'file' );
}

/**
 * Add contextual help text for a page.
 *
 * Creates an 'Overview' help tab.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use get_current_screen()->add_help_tab()
 * @see BD_Screen
 *
 * @param string    $screen The handle for the screen to add help to. This is usually the hook name returned by the add_*_page() functions.
 * @param string    $help   The content of an 'Overview' help tab.
 */
function add_contextual_help( $screen, $help ) {
	_deprecated_function( __FUNCTION__, '1.0', 'get_current_screen()->add_help_tab()' );

	if ( is_string( $screen ) )
		$screen = convert_to_screen( $screen );

	BD_Screen::add_old_compat_help( $screen, $help );
}

/**
 * Get the allowed themes for the current blog.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use bd_get_themes()
 * @see bd_get_themes()
 *
 * @return array $themes Array of allowed themes.
 */
function get_allowed_themes() {
	_deprecated_function( __FUNCTION__, '1.0', "bd_get_themes( array( 'allowed' => true ) )" );

	$themes = bd_get_themes( array( 'allowed' => true ) );

	$bd_themes = array();
	foreach ( $themes as $theme ) {
		$bd_themes[ $theme->get('Name') ] = $theme;
	}

	return $bd_themes;
}

/**
 * {@internal Missing Short Description}}
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 *
 * @return array
 */
function get_broken_themes() {
	_deprecated_function( __FUNCTION__, '1.0', "bd_get_themes( array( 'errors' => true )" );

	$themes = bd_get_themes( array( 'errors' => true ) );
	$broken = array();
	foreach ( $themes as $theme ) {
		$name = $theme->get('Name');
		$broken[ $name ] = array(
			'Name' => $name,
			'Title' => $name,
			'Description' => $theme->errors()->get_error_message(),
		);
	}
	return $broken;
}

/**
 * {@internal Missing Short Description}}
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 *
 * @return BD_Theme
 */
function current_theme_info() {
	_deprecated_function( __FUNCTION__, '1.0', 'bd_get_theme()' );

	return bd_get_theme();
}

/**
 * This was once used to display an 'Insert into Post' button. Now it is deprecated and stubbed.
 *
 * @deprecated 1.0.0
 */
function _insert_into_post_button( $type ) {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**
 * This was once used to display a media button. Now it is deprecated and stubbed.
 *
 * @deprecated 1.0.0
 */
function _media_button($title, $icon, $type, $id) {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**
 * Get an existing post and format it for editing.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 *
 * @param int $id
 * @return object
 */
function get_post_to_edit( $id ) {
	_deprecated_function( __FUNCTION__, '1.0', 'get_post()' );

	return get_post( $id, OBJECT, 'edit' );
}

/**
 * Get the default page information to use.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use get_default_post_to_edit()
 *
 * @return BD_Post Post object containing all the default post data as attributes
 */
function get_default_page_to_edit() {
	_deprecated_function( __FUNCTION__, '1.0', "get_default_post_to_edit( 'page' )" );

	$page = get_default_post_to_edit();
	$page->post_type = 'page';
	return $page;
}

/**
 * This was once used to create a thumbnail from an Image given a maximum side size.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @deprecated Use image_resize()
 * @see image_resize()
 *
 * @param mixed $file Filename of the original image, Or attachment id.
 * @param int $max_side Maximum length of a single side for the thumbnail.
 * @param mixed $deprecated Never used.
 * @return string Thumbnail path on success, Error string on failure.
 */
function bd_create_thumbnail( $file, $max_side, $deprecated = '' ) {
	_deprecated_function( __FUNCTION__, '1.0', 'image_resize()' );
	return apply_filters( 'bd_create_thumbnail', image_resize( $file, $max_side, $max_side ) );
}

/**
 * This was once used to display a metabox for the nav menu theme locations.
 *
 * Deprecated in favor of a 'Manage Locations' tab added to nav menus management screen.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
function bd_nav_menu_locations_meta_box() {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**
 * This was once used to kick-off the Core Updater.
 *
 * Deprecated in favor of instantating a Core_Upgrader instance directly,
 * and calling the 'upgrade' method.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @see Core_Upgrader
 */
function bd_update_core($current, $feedback = '') {
	_deprecated_function( __FUNCTION__, '1.0', 'new Core_Upgrader();' );

	if ( !empty($feedback) )
		add_filter('update_feedback', $feedback);

	include( ABSPATH . 'bd-admin/includes/class-bd-upgrader.php' );
	$upgrader = new Core_Upgrader();
	return $upgrader->upgrade($current);

}

/**
 * This was once used to kick-off the Plugin Updater.
 *
 * Deprecated in favor of instantating a Plugin_Upgrader instance directly,
 * and calling the 'upgrade' method.
 * Unused since 1.0.0.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @see Plugin_Upgrader
 */
function bd_update_plugin($plugin, $feedback = '') {
	_deprecated_function( __FUNCTION__, '1.0', 'new Plugin_Upgrader();' );

	if ( !empty($feedback) )
		add_filter('update_feedback', $feedback);

	include( ABSPATH . 'bd-admin/includes/class-bd-upgrader.php' );
	$upgrader = new Plugin_Upgrader();
	return $upgrader->upgrade($plugin);
}

/**
 * This was once used to kick-off the Theme Updater.
 *
 * Deprecated in favor of instantating a Theme_Upgrader instance directly,
 * and calling the 'upgrade' method.
 * Unused since 1.0.0.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @see Theme_Upgrader
 */
function bd_update_theme($theme, $feedback = '') {
	_deprecated_function( __FUNCTION__, '1.0', 'new Theme_Upgrader();' );

	if ( !empty($feedback) )
		add_filter('update_feedback', $feedback);

	include( ABSPATH . 'bd-admin/includes/class-bd-upgrader.php' );
	$upgrader = new Theme_Upgrader();
	return $upgrader->upgrade($theme);
}

/**
 * This was once used to display attachment links. Now it is deprecated and stubbed.
 *
 * {@internal Missing Short Description}}
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 *
 * @param int|bool $id
 */
function the_attachment_links( $id = false ) {
	_deprecated_function( __FUNCTION__, '1.0' );
}

/**#@+
 * Displays a screen icon.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
function screen_icon() {
	echo get_screen_icon();
}
function get_screen_icon() {
	return '<!-- Screen icons are no longer used as of Blasdoise 3.8. -->';
}
/**#@-*/

/**#@+
 * Deprecated dashboard widget controls.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
function bd_dashboard_incoming_links_output() {}
function bd_dashboard_secondary_output() {}
/**#@-*/

/**#@+
 * Deprecated dashboard widget controls.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 */
function bd_dashboard_incoming_links() {}
function bd_dashboard_incoming_links_control() {}
function bd_dashboard_plugins() {}
function bd_dashboard_primary_control() {}
function bd_dashboard_recent_comments_control() {}
function bd_dashboard_secondary() {}
function bd_dashboard_secondary_control() {}
/**#@-*/

/**
 * This was once used to move child posts to a new parent.
 *
 * @since 1.0.0
 * @deprecated 1.0.0
 * @access private
 *
 * @param int $old_ID
 * @param int $new_ID
 */
function _relocate_children( $old_ID, $new_ID ) {
	_deprecated_function( __FUNCTION__, '1.0' );
}
