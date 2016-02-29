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
	add_image_size('bluefly-entry-thumb', 9999,320,true);
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
 * 作用: 替换content中的图片链接,为lazyload做准备
 */
function bluefly_lazyload_filter( $content )
{
    $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
    $dom = new DOMDocument();
    @$dom->loadHTML($content);

    foreach ($dom->getElementsByTagName('img') as $node) {  
        $oldsrc = $node->getAttribute('src');
        $node->setAttribute("data-original", $oldsrc );
		$node->setAttribute("class","lazy");
        $newsrc = get_template_directory_uri().'/images/blank.gif';
        $node->setAttribute("src", $newsrc);
    }
    $newHtml = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
    return $newHtml;
}
add_filter( 'the_content', 'bluefly_lazyload_filter',200 );
add_filter( 'post_thumbnail_html', 'bluefly_lazyload_filter',200 );


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
	
	wp_enqueue_script( 'jquery-masonry' );
	
	wp_enqueue_script( 'bluefly-infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array(), '20120206', true );
	wp_enqueue_script( 'bluefly-lazyload', get_template_directory_uri() . '/js/jquery.lazyload.min.js', array(), '1.9.7', true );
	wp_enqueue_script( 'bluefly-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), true );
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
 * 语言声明优化
 */
 function bluefly_chinese( $output, $doctype ) {
	  $output = "lang=\"zh-CN\"";
	  return $output;
 }
add_filter('language_attributes', 'bluefly_chinese', 11); 

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
 * 自定义头部用
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * 特色函数
 */
require get_template_directory() . '/inc/trait.php';

/**
 * 追加的面板功能
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * 自定义风格的实现
 */
require get_template_directory() . '/inc/styles.php';

/**
 * AJAX-comment
 */
require get_template_directory() . '/inc/ajax-comment.php';

/**
 * 私有功能，不上传。没什么特别的，就是些网站描述什么的。
 */
 if (file_exists(get_template_directory() .'/inc/personal.php')) {
	require get_template_directory() . '/inc/personal.php';
 }