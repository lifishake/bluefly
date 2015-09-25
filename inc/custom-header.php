<?php
/**
 * 自定义header的例子
 * @package bluefly
 */

/**
 * 设置主题对应的参数
 *
 * @uses bluefly_header_style()
 * @uses bluefly_admin_header_style()
 * @uses bluefly_admin_header_image()
 */
function bluefly_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'bluefly_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/images/header.jpg',
		'default-text-color'     => '000000',
		'width'                  => 1920,
		'height'                 => 850,
		'flex-height'            => true,
		'wp-head-callback'       => 'bluefly_header_style',
		'admin-head-callback'    => 'bluefly_admin_header_style',
		'admin-preview-callback' => 'bluefly_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'bluefly_custom_header_setup' );

/**
 * 主题风格的回调函数
 */
function bluefly_header_style() {
	if ( get_header_image() ) {	
		?>
		<style type="text/css">
					.overlay {
					    background: url(<?php echo get_header_image(); ?>) no-repeat;
					    background-position: center top;
					    background-attachment: fixed;
					    background-size: cover;
					}
		</style>
		<?php
	}
}

/**
 * 自定义面板上的风格
 */
function bluefly_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			border: none;
		}
		#headimg h1,
		#desc {
		}
		#headimg h1 {
		}
		#headimg h1 a {
		}
		#desc {
		}
		#headimg img {
		}
	</style>
<?php
}

/**
 * 自定义面板上的缩略图
 */
function bluefly_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php
}