<?php
/**
 * Fairy functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in Blasdoise to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * @package Blasdoise
 * @subpackage Fairy
 * @since Fairy 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Fairy 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

/**
 * Fairy only works in Blasdoise 1.0 or later.
 */
if ( version_compare( $GLOBALS['bd_version'], '1.0-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'fairy_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various Blasdoise features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Fairy 1.0
 */
function fairy_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on fairy, use a find and replace
	 * to change 'fairy' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'fairy', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let Blasdoise manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect Blasdoise to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses bd_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'fairy' ),
		'social'  => __( 'Social Links Menu', 'fairy' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = fairy_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the Blasdoise core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'fairy_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );
}
endif; // fairy_setup
add_action( 'after_setup_theme', 'fairy_setup' );

/**
 * Register widget area.
 *
 * @since Fairy 1.0
 */
function fairy_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'fairy' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'fairy' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'fairy_widgets_init' );

if ( ! function_exists( 'fairy_fonts_url' ) ) :
/**
 * Register Google fonts for Fairy.
 *
 * @since Fairy 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function fairy_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'fairy' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'fairy' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'fairy' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'fairy' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Fairy 1.1
 */
function fairy_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'bd_head', 'fairy_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 *
 * @since Fairy 1.0
 */
function fairy_scripts() {
	// Add custom fonts, used in the main stylesheet.
	bd_enqueue_style( 'fairy-fonts', fairy_fonts_url(), array(), null );

	// Add Iconics, used in the main stylesheet.
	bd_enqueue_style( 'iconics', get_template_directory_uri() . '/iconics/iconics.css', array(), '3.2' );

	// Load our main stylesheet.
	bd_enqueue_style( 'fairy-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	bd_enqueue_style( 'fairy-ie', get_template_directory_uri() . '/css/ie.css', array( 'fairy-style' ), '20141010' );
	bd_style_add_data( 'fairy-ie', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	bd_enqueue_style( 'fairy-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'fairy-style' ), '20141010' );
	bd_style_add_data( 'fairy-ie7', 'conditional', 'lt IE 8' );

	bd_enqueue_script( 'fairy-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		bd_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && bd_attachment_is_image() ) {
		bd_enqueue_script( 'fairy-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20141010' );
	}

	bd_enqueue_script( 'fairy-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );
	bd_localize_script( 'fairy-script', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'fairy' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'fairy' ) . '</span>',
	) );
}
add_action( 'bd_enqueue_scripts', 'fairy_scripts' );

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Fairy 1.0
 *
 * @see bd_add_inline_style()
 */
function fairy_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = bd_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = bd_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); border-top: 0; }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	bd_add_inline_style( 'fairy-style', $css );
}
add_action( 'bd_enqueue_scripts', 'fairy_post_nav_background' );

/**
 * Display descriptions in main navigation.
 *
 * @since Fairy 1.0
 *
 * @param string  $item_output The menu item output.
 * @param BD_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        bd_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function fairy_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'fairy_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Fairy 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function fairy_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'fairy_search_form_modify' );

/**
 * Implement the Custom Header feature.
 *
 * @since Fairy 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Fairy 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Fairy 1.0
 */
require get_template_directory() . '/inc/customizer.php';
