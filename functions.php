<?php
/**
 * bluefly functions and definitions
 *
 * @package bluefly
 */


if ( ! function_exists( 'bluefly_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bluefly_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bluefly, use a find and replace
	 * to change 'bluefly' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bluefly', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Content width
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1040;
	}

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
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('bluefly-entry-thumb', 720);
	add_image_size('bluefly-single-thumb', 1040);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bluefly' ),
		'social'  => __( 'Social', 'bluefly' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'bluefly_custom_background_args', array(
		'default-color' => '1c1c1c',
	) ) );
}
endif; // bluefly_setup
add_action( 'after_setup_theme', 'bluefly_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function bluefly_widgets_init() {
	register_sidebar( array(
		'name'          => __( '侧边栏', 'bluefly' ),
		'id'            => 'sidebar-bluefly',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'bluefly_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bluefly_scripts() {

	wp_enqueue_style( 'bluefly-bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), true );	

	wp_enqueue_style( 'bluefly-style', get_stylesheet_uri() );

	wp_enqueue_style( 'bluefly-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css' );	

	wp_enqueue_script( 'bluefly-slicknav', get_template_directory_uri() . '/js/jquery.slicknav.min.js', array('jquery'), true );	

	wp_enqueue_script( 'bluefly-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), true );	

	//wp_enqueue_script( 'bluefly-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array(), true );		

	wp_enqueue_script( 'bluefly-masonry-init', get_template_directory_uri() . '/js/masonry-init.js', array('jquery-masonry'), true );		

	//wp_enqueue_script( 'bluefly-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'bluefly-infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array(), '20120206', true );

	wp_enqueue_script( 'bluefly-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() ) {
		if (get_option( 'thread_comments' )) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_enqueue_script( 'bluefly-ajax-comment', get_template_directory_uri() . '/js/ajax-comment.js', array(), '20150902' );
		wp_localize_script( 'bluefly-ajax-comment', 'ajaxcomment', array(
        'ajax_url'   => admin_url('admin-ajax.php')
    ) );
	}

}
add_action( 'wp_enqueue_scripts', 'bluefly_scripts' );


/**
 * Change the excerpt length
 */
function bluefly_excerpt_length( $length ) {
	$excerpt = get_theme_mod('exc_lenght', '35');
	return esc_attr($excerpt);
}
add_filter( 'excerpt_length', 'bluefly_excerpt_length', 999 );

/**
 * Hide the excerpt more if the excerpt is set to 0 words
 */
function bluefly_excerpt_more( $more ) {
	$excerpt = get_theme_mod('exc_lenght', '35');
	if ($excerpt == '0') {
    	return '';
	} else {
		return '[...]';
	}
}   
add_filter('excerpt_more', 'bluefly_excerpt_more');

/**
 * Footer credits
 */
function bluefly_footer_credits() {
	echo '<a href="' . esc_url( __( 'http://wordpress.org/', 'bluefly' ) ) . '">';
		printf( __( 'Proudly powered by %s', 'bluefly' ), 'WordPress' );
	echo '</a>';
	echo '<span class="sep"> | </span>';
	printf( __( 'Theme: %2$s by %1$s.', 'bluefly' ), 'FlyFreeMedia', '<a href="http://flyfreemedia.com/themes/bluefly" rel="designer">bluefly</a>' );
}
add_action( 'bluefly_footer', 'bluefly_footer_credits' );

/**
 * Load html5shiv
 */
function bluefly_html5shiv() {
    echo '<!--[if lt IE 9]>' . "\n";
    echo '<script src="' . esc_url( get_template_directory_uri() . '/js/html5shiv.js' ) . '"></script>' . "\n";
    echo '<![endif]-->' . "\n";
}
add_action( 'wp_head', 'bluefly_html5shiv' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Styles
 */
require get_template_directory() . '/inc/styles.php';

/**
 * AJAX-comment
 */
require get_template_directory() . '/inc/ajax-comment.php';