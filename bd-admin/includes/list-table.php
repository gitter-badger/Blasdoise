<?php
/**
 * Helper functions for displaying a list of items in an ajaxified HTML table.
 *
 * @package Blasdoise
 * @subpackage List_Table
 * @since 1.0.0
 */

/**
 * Fetch an instance of a BD_List_Table class.
 *
 * @access private
 * @since 1.0.0
 *
 * @global string $hook_suffix
 *
 * @param string $class The type of the list table, which is the class name.
 * @param array $args Optional. Arguments to pass to the class. Accepts 'screen'.
 * @return object|bool Object on success, false if the class does not exist.
 */
function _get_list_table( $class, $args = array() ) {
	$core_classes = array(
		//Site Admin
		'BD_Posts_List_Table' => 'posts',
		'BD_Media_List_Table' => 'media',
		'BD_Terms_List_Table' => 'terms',
		'BD_Users_List_Table' => 'users',
		'BD_Comments_List_Table' => 'comments',
		'BD_Post_Comments_List_Table' => 'comments',
		'BD_Links_List_Table' => 'links',
		'BD_Plugin_Install_List_Table' => 'plugin-install',
		'BD_Themes_List_Table' => 'themes',
		'BD_Theme_Install_List_Table' => array( 'themes', 'theme-install' ),
		'BD_Plugins_List_Table' => 'plugins',
		// Network Admin
		'BD_MS_Sites_List_Table' => 'ms-sites',
		'BD_MS_Users_List_Table' => 'ms-users',
		'BD_MS_Themes_List_Table' => 'ms-themes',
	);

	if ( isset( $core_classes[ $class ] ) ) {
		foreach ( (array) $core_classes[ $class ] as $required )
			require_once( ABSPATH . 'bd-admin/includes/class-bd-' . $required . '-list-table.php' );

		if ( isset( $args['screen'] ) )
			$args['screen'] = convert_to_screen( $args['screen'] );
		elseif ( isset( $GLOBALS['hook_suffix'] ) )
			$args['screen'] = get_current_screen();
		else
			$args['screen'] = null;

		return new $class( $args );
	}

	return false;
}

/**
 * Register column headers for a particular screen.
 *
 * @since 1.0.0
 *
 * @param string $screen The handle for the screen to add help to. This is usually the hook name returned by the add_*_page() functions.
 * @param array $columns An array of columns with column IDs as the keys and translated column names as the values
 * @see get_column_headers(), print_column_headers(), get_hidden_columns()
 */
function register_column_headers($screen, $columns) {
	$bd_list_table = new _BD_List_Table_Compat($screen, $columns);
}

/**
 * Prints column headers for a particular screen.
 *
 * @since 1.0.0
 */
function print_column_headers($screen, $id = true) {
	$bd_list_table = new _BD_List_Table_Compat($screen);

	$bd_list_table->print_column_headers($id);
}

/**
 * Helper class to be used only by back compat functions
 *
 * @since 1.0.0
 */
class _BD_List_Table_Compat extends BD_List_Table {
	public $_screen;
	public $_columns;

	public function __construct( $screen, $columns = array() ) {
		if ( is_string( $screen ) )
			$screen = convert_to_screen( $screen );

		$this->_screen = $screen;

		if ( !empty( $columns ) ) {
			$this->_columns = $columns;
			add_filter( 'manage_' . $screen->id . '_columns', array( $this, 'get_columns' ), 0 );
		}
	}

	/**
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_column_info() {
		$columns = get_column_headers( $this->_screen );
		$hidden = get_hidden_columns( $this->_screen );
		$sortable = array();

		return array( $columns, $hidden, $sortable );
	}

	/**
	 * @access public
	 *
	 * @return array
	 */
	public function get_columns() {
		return $this->_columns;
	}
}
