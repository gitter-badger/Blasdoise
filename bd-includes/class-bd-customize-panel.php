<?php
/**
 * Blasdoise Customize Panel classes
 *
 * @package Blasdoise
 * @subpackage Customize
 * @since 1.0.0
 */

/**
 * Customize Panel class.
 *
 * A UI container for sections, managed by the BD_Customize_Manager.
 *
 * @since 1.0.0
 *
 * @see BD_Customize_Manager
 */
class BD_Customize_Panel {

	/**
	 * Incremented with each new class instantiation, then stored in $instance_number.
	 *
	 * Used when sorting two instances whose priorities are equal.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @access protected
	 * @static
	 * @var int
	 */
	protected static $instance_count = 0;

	/**
	 * Order in which this instance was created in relation to other instances.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var int
	 */
	public $instance_number;

	/**
	 * BD_Customize_Manager instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var BD_Customize_Manager
	 */
	public $manager;

	/**
	 * Unique identifier.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $id;

	/**
	 * Priority of the panel, defining the display order of panels and sections.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var integer
	 */
	public $priority = 160;

	/**
	 * Capability required for the panel.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $capability = 'edit_theme_options';

	/**
	 * Theme feature support for the panel.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string|array
	 */
	public $theme_supports = '';

	/**
	 * Title of the panel to show in UI.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $title = '';

	/**
	 * Description to show in the UI.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $description = '';

	/**
	 * Customizer sections for this panel.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $sections;

	/**
	 * Type of this panel.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'default';

	/**
	 * Active callback.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see BD_Customize_Section::active()
	 *
	 * @var callable Callback is called with one argument, the instance of
	 *               {@see BD_Customize_Section}, and returns bool to indicate
	 *               whether the section is active (such as it relates to the URL
	 *               currently being previewed).
	 */
	public $active_callback = '';

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param BD_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      An specific ID for the panel.
	 * @param array                $args    Panel arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
		$this->id = $id;
		if ( empty( $this->active_callback ) ) {
			$this->active_callback = array( $this, 'active_callback' );
		}
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;

		$this->sections = array(); // Users cannot customize the $sections array.
	}

	/**
	 * Check whether panel is active to current Customizer preview.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether the panel is active to the current preview.
	 */
	final public function active() {
		$panel = $this;
		$active = call_user_func( $this->active_callback, $this );

		/**
		 * Filter response of BD_Customize_Panel::active().
		 *
		 * @since 1.0.0
		 *
		 * @param bool               $active  Whether the Customizer panel is active.
		 * @param BD_Customize_Panel $panel   {@see BD_Customize_Panel} instance.
		 */
		$active = apply_filters( 'customize_panel_active', $active, $panel );

		return $active;
	}

	/**
	 * Default callback used when invoking {@see BD_Customize_Panel::active()}.
	 *
	 * Subclasses can override this with their specific logic, or they may
	 * provide an 'active_callback' argument to the constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Always true.
	 */
	public function active_callback() {
		return true;
	}

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @since 1.0.0
	 *
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array = bd_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type' ) );
		$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content'] = $this->get_content();
		$array['active'] = $this->active();
		$array['instanceNumber'] = $this->instance_number;
		return $array;
	}

	/**
	 * Checks required user capabilities and whether the theme has the
	 * feature support required by the panel.
	 *
	 * @since 1.0.0
	 *
	 * @return bool False if theme doesn't support the panel or the user doesn't have the capability.
	 */
	final public function check_capabilities() {
		if ( $this->capability && ! call_user_func_array( 'current_user_can', (array) $this->capability ) ) {
			return false;
		}

		if ( $this->theme_supports && ! call_user_func_array( 'current_theme_supports', (array) $this->theme_supports ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the panel's content template for insertion into the Customizer pane.
	 *
	 * @since 1.0.0
	 *
	 * @return string Content for the panel.
	 */
	final public function get_content() {
		ob_start();
		$this->maybe_render();
		return trim( ob_get_clean() );
	}

	/**
	 * Check capabilities and render the panel.
	 *
	 * @since 1.0.0
	 */
	final public function maybe_render() {
		if ( ! $this->check_capabilities() ) {
			return;
		}

		/**
		 * Fires before rendering a Customizer panel.
		 *
		 * @since 1.0.0
		 *
		 * @param BD_Customize_Panel $this BD_Customize_Panel instance.
		 */
		do_action( 'customize_render_panel', $this );

		/**
		 * Fires before rendering a specific Customizer panel.
		 *
		 * The dynamic portion of the hook name, `$this->id`, refers to
		 * the ID of the specific Customizer panel to be rendered.
		 *
		 * @since 1.0.0
		 */
		do_action( "customize_render_panel_{$this->id}" );

		$this->render();
	}

	/**
	 * Render the panel container, and then its contents (via `this->render_content()`) in a subclass.
	 *
	 * Panel containers are now rendered in JS by default, see {@see BD_Customize_Panel::print_template()}.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {}

	/**
	 * Render the panel UI in a subclass.
	 *
	 * Panel contents are now rendered in JS by default, see {@see BD_Customize_Panel::print_template()}.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_content() {}

	/**
	 * Render the panel's JS templates.
	 *
	 * This function is only run for panel types that have been registered with
	 * BD_Customize_Manager::register_panel_type().
	 *
	 * @since 1.0.0
	 *
	 * @see BD_Customize_Manager::register_panel_type()
	 */
	public function print_template() {
		?>
		<script type="text/html" id="tmpl-customize-panel-<?php echo esc_attr( $this->type ); ?>-content">
			<?php $this->content_template(); ?>
		</script>
		<script type="text/html" id="tmpl-customize-panel-<?php echo esc_attr( $this->type ); ?>">
			<?php $this->render_template(); ?>
		</script>
        <?php
	}

	/**
	 * An Underscore (JS) template for rendering this panel's container.
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding BD_Customize_Panel::json().
	 *
	 * @see BD_Customize_Panel::print_template()
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_template() {
		?>
		<li id="accordion-panel-{{ data.id }}" class="accordion-section control-section control-panel control-panel-{{ data.type }}">
			<h3 class="accordion-section-title" tabindex="0">
				{{ data.title }}
				<span class="screen-reader-text"><?php _e( 'Press return or enter to open this panel' ); ?></span>
			</h3>
			<ul class="accordion-sub-container control-panel-content"></ul>
		</li>
		<?php
	}

	/**
	 * An Underscore (JS) template for this panel's content (but not its container).
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding BD_Customize_Panel::json().
	 *
	 * @see BD_Customize_Panel::print_template()
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
			<button class="customize-panel-back" tabindex="-1"><span class="screen-reader-text"><?php _e( 'Back' ); ?></span></button>
			<div class="accordion-section-title">
				<span class="preview-notice"><?php
					/* translators: %s is the site/panel title in the Customizer */
					echo sprintf( __( 'You are customizing %s' ), '<strong class="panel-title">{{ data.title }}</strong>' );
				?></span>
				<button class="customize-help-toggle basicons basicons-editor-help" tabindex="0" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Help' ); ?></span></button>
			</div>
			<# if ( data.description ) { #>
				<div class="description customize-panel-description">
					{{{ data.description }}}
				</div>
			<# } #>
		</li>
		<?php
	}
}

/**
 * Customize Nav Menus Panel Class
 *
 * Needed to add screen options.
 *
 * @since 1.0.0
 *
 * @see BD_Customize_Panel
 */
class BD_Customize_Nav_Menus_Panel extends BD_Customize_Panel {

	/**
	 * Control type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $type = 'nav_menus';

	/**
	 * Render screen options for Menus.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_screen_options() {
		// Essentially adds the screen options.
		add_filter( 'manage_nav-menus_columns', array( $this, 'bd_nav_menu_manage_columns' ) );

		// Display screen options.
		$screen = BD_Screen::get( 'nav-menus.php' );
		$screen->render_screen_options();
	}

	/**
	 * Returns the advanced options for the nav menus page.
	 *
	 * Link title attribute added as it's a relatively advanced concept for new users.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array The advanced menu properties.
	 */
	public function bd_nav_menu_manage_columns() {
		return array(
			'_title'      => __( 'Show advanced menu properties' ),
			'cb'          => '<input type="checkbox" />',
			'link-target' => __( 'Link Target' ),
			'attr-title'  => __( 'Title Attribute' ),
			'css-classes' => __( 'CSS Classes' ),
			'xfn'         => __( 'Link Relationship (XFN)' ),
			'description' => __( 'Description' ),
		);
	}

	/**
	 * An Underscore (JS) template for this panel's content (but not its container).
	 *
	 * Class variables for this panel class are available in the `data` JS object;
	 * export custom variables by overriding BD_Customize_Panel::json().
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @see BD_Customize_Panel::print_template()
	 */
	protected function content_template() {
		?>
		<li class="panel-meta customize-info accordion-section <# if ( ! data.description ) { #> cannot-expand<# } #>">
			<button type="button" class="customize-panel-back" tabindex="-1">
				<span class="screen-reader-text"><?php _e( 'Back' ); ?></span>
			</button>
			<div class="accordion-section-title">
				<span class="preview-notice">
					<?php
					/* Translators: %s is the site/panel title in the Customizer. */
					printf( __( 'You are customizing %s' ), '<strong class="panel-title">{{ data.title }}</strong>' );
					?>
				</span>
				<button type="button" class="customize-help-toggle basicons basicons-editor-help" aria-expanded="false">
					<span class="screen-reader-text"><?php _e( 'Help' ); ?></span>
				</button>
				<button type="button" class="customize-screen-options-toggle" aria-expanded="false">
					<span class="screen-reader-text"><?php _e( 'Menu Options' ); ?></span>
				</button>
			</div>
			<# if ( data.description ) { #>
			<div class="description customize-panel-description">{{{ data.description }}}</div>
			<# } #>
			<?php $this->render_screen_options(); ?>
		</li>
	<?php
	}
}
