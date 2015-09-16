<?php
/**
 * advancedonepage functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package advancedonepage
 */

if ( ! function_exists( 'advancedonepage_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function advancedonepage_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on advancedonepage, use a find and replace
	 * to change 'advancedonepage' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'advancedonepage', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'advancedonepage' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'advancedonepage_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // advancedonepage_setup
add_action( 'after_setup_theme', 'advancedonepage_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function advancedonepage_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'advancedonepage_content_width', 640 );
}
add_action( 'after_setup_theme', 'advancedonepage_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function advancedonepage_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'advancedonepage' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'advancedonepage_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function advancedonepage_scripts() {
	wp_enqueue_style( 'advancedonepage-style', get_stylesheet_uri() );

	wp_enqueue_script( 'advancedonepage-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20120206', true );


}
add_action( 'wp_enqueue_scripts', 'advancedonepage_scripts' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


if(is_admin()) {
	require get_template_directory() . '/inc/admin.php';
}