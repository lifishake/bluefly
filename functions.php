<?php
/**
 * bluefly 函数和定义
 *
 * @package bluefly
 */


if ( ! function_exists( 'bluefly_setup' ) ) :
/**
 * 安装
 */
function bluefly_setup() {

	/*
	 * 取语言包。虽然我没有语言包。
	 */
	//load_theme_textdomain( 'bluefly', get_template_directory() . '/languages' );

	// feed链接
	add_theme_support( 'automatic-feed-links' );

	// 屏宽
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1040;
	}

	/*
	 * 标题优化
	 */
	add_theme_support( 'title-tag' );

	/*
	 * 缩略图
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('bluefly-entry-thumb', 720);
	add_image_size('bluefly-single-thumb', 1040);

	// 支持的菜单.主菜单和社会化
	register_nav_menus( array(
		'primary' => 'Primary Menu',
		'social'  => 'Social',
	) );

	/*
	 * HTML5风格
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'caption',
	) );

	/*
	 * 文章格式
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// 自定义背景
	add_theme_support( 'custom-background', array(
		'default-color' => '1c1c1c',
	) );
}
endif; // bluefly_setup
add_action( 'after_setup_theme', 'bluefly_setup' );

/**
 * 作用: 注册小工具
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function bluefly_widgets_init() {
	register_sidebar( array(
		'name'          => '侧边栏',
		'id'            => 'sidebar-bluefly',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'bluefly_widgets_init' );

/**
 * 作用: 加载js和样式表
 */
function bluefly_scripts() {

	wp_enqueue_style( 'bluefly-bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), true );	

	wp_enqueue_style( 'bluefly-style', get_stylesheet_uri() );

	wp_enqueue_script( 'bluefly-slicknav', get_template_directory_uri() . '/js/jquery.slicknav.min.js', array('jquery'), true );	

	wp_enqueue_script( 'bluefly-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), true );	

	wp_enqueue_script( 'bluefly-masonry-init', get_template_directory_uri() . '/js/masonry-init.js', array('jquery-masonry'), true );		

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
 * 作用: 版本信息
 * 来源: Oblique
 */
function bluefly_footer_credits() {
	echo 'Theme: <span class="thirdly_color"><a href="https://github.com/lifishake/bluefly" target="_blank" rel="nofollow">bluefly</a></span> &copy'.date('Y').' 破袜子';
}
add_action( 'bluefly_footer', 'bluefly_footer_credits' );

/**
 * 菜单用
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
require get_template_directory() . '/inc/addi.php';

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