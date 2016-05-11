<?php
/**
 * 不对外公开的函数，不影响正常使用
 * @package bluefly
 */
 wp_enqueue_style( 'inline-widget-style', get_template_directory_uri() . '/inc/personal.css', array(), true );
/**
 * 作用: 显示版权条
 * 来源: 自产
 * URL:  
 */
function my_licence()
{
	if ( ! is_singular() || is_page() )	{
		return ;
	}
	if ( has_tag('zhuanzai') || has_category('zhaichaohedaolian') )
		return '' ;
	$belt= '<div id = "lv" class = "license" >本作品由<a href="http://pewae.com" title="attributionURL">大致</a>创作，采用<a title="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/cn/deed.zh">署名-非商业性使用-禁止演绎</a>授权条款授权。转载请勿作修改并注明原始链接。</div>' ;
	if ( function_exists('apip_get_social') ){
		$belt.= apip_get_social() ;
	}
	echo $belt;
}
add_action('bluefly_after_single_post', 'my_licence') ;

/**
 * 作用: 推荐内容
 * 来源: 自产
 * URL:  
 */
function my_inline_content() {
	if ( ! is_singular() || is_page() )	{
		return ;
	}
	$belt = '<div class = "sidebar-inline" >' ;	
	if ( function_exists('apip_related_post') ) {
		$belt .= '<div id = "belt1" class="belt-widgets">' ;
		$belt .= '<h3 class = "sidebar-inline-title">相关文章</h3>' ;
		$belt .= apip_related_post() ;
		$belt .= '</div>';
	}
	if ( function_exists('apip_sameday_post') && !wp_is_mobile() ) {
		$belt .= '<div id = "belt2" class="belt-widgets">' ;
		$belt .= '<h3 class = "sidebar-inline-title">历史上的今天</h3>' ;
		$belt .= apip_sameday_post() ;
		$belt .= '</div>';
	}
	$belt.='</div>';
	echo $belt;
}
add_action('bluefly_after_comment', 'my_inline_content') ;

add_action( 'wp_enqueue_scripts', 'bluefly_personal_style',20 );
function bluefly_personal_style($custom) {
	if ( !is_singular() )
		return;
	$custom = '';
	$background_color = '#'.get_background_color();//(2)
	$primary_color = get_theme_mod( 'primary_color', '#23B6B6' );
	$entry_meta = get_theme_mod( 'entry_meta', '#9d9d9d' );//(5)
	$second_bg = get_theme_mod( 'second_bg', '#17191B' );//(6)
	$secondary_color = get_theme_mod( 'secondary_color', '#B524B5' );
	$thirdly_color = get_theme_mod( 'thirdly_color', '#B5B524' );//(8)
	$rgba_bg_60 = bluefly_hex2rgba_str($second_bg, 0.6);
	$rgba_primary_30 = bluefly_hex2rgba_str($primary_color, 0.3);
	$custom .= ".sidebar-inline { background-color: ".$rgba_bg_60.";\n";
	$custom .= " border-color: ".$rgba_primary_30.";}\n";
	$custom .= ".license { color:".$secondary_color.";}\n";
	$custom .= "#sharebar { background-color: ".$rgba_bg_60." !important ;}\n";
	$custom .= "#sharebar span { color: ".$entry_meta." !important ;}\n";
	$custom .= ".sidebar-inline a, .sidebar-inline a hover { color:".$secondary_color.";}\n";
	wp_add_inline_style( 'inline-widget-style', $custom );
}
