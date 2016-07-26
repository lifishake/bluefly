<?php
/**
 * 读取数据库,设定自定义CSS样式
 * @package bluefly
 */

/**
 * 作用: 追加临时CSS，所有方案由自定义得到
 * 来源: Oblique原版，破袜子修改
 */
function bluefly_custom_styles($custom) {

    $custom = '';

    //主标题
    $site_title_size = get_theme_mod( 'site_title_size', '82' );
    if ( get_theme_mod( 'site_title_size' )) {
        $custom .= ".site-title { font-size:" . intval($site_title_size) . "px; }"."\n";
    }
    $title_family = get_theme_mod( 'site_title_font_family', '' );
    if ( '' != $title_family ) {
        $style_path = get_template_directory_uri();
        $custom .= "@font-face { font-family: '" . $title_family ."'; \n";
        $custom .= "src: url('".$style_path."/fonts/" . $title_family .".eot'); \n"; /*IE 9.0*/
        $custom .= "src: url('".$style_path."/fonts/" . $title_family .".eot?#iefix') format('embedded-opentype'), \n"; /*IE -6-8*/
        $custom .= "url('".$style_path."/fonts/" . $title_family .".woff2') format('woff2'), \n";/*少数支持且快*/
        $custom .= "url('".$style_path."/fonts/" . $title_family .".woff') format('woff'), \n";/*大多数*/
        $custom .= "url('".$style_path."/fonts/" . $title_family .".ttf') format('ttf'), \n" ; /* Safari, Android, iOS */
        $custom .= "url('".$style_path."/fonts/" . $title_family .".svg') format('svg'); \n";
        $custom .= "font-weight: normal; \n font-style: normal; }\n";
        $custom .= ".site-title { font-family:" . $title_family ."; }"."\n";
        $custom .= ".site-description { font-family:" . $title_family ."; }"."\n";
    }
    //副标题
    $site_desc_size = get_theme_mod( 'site_desc_size', '18' );
    if ( get_theme_mod( 'site_desc_size' )) {
        $custom .= ".site-description { font-size:" . intval($site_desc_size) . "px; }"."\n";
    }
    //菜单
    $menu_size = get_theme_mod( 'menu_size', '16' );
    if ( get_theme_mod( 'menu_size' )) {
        $custom .= ".main-navigation li { font-size:" . intval($menu_size) . "px; }"."\n";
    }               
    //H1
    $h1_size = get_theme_mod( 'h1_size' );
    if ( get_theme_mod( 'h1_size' )) {
        $custom .= "h1 { font-size:" . intval($h1_size) . "px; }"."\n";
    }
    //H2
    $h2_size = get_theme_mod( 'h2_size' );
    if ( get_theme_mod( 'h2_size' )) {
        $custom .= "h2 { font-size:" . intval($h2_size) . "px; }"."\n";
    }
    //H3
    $h3_size = get_theme_mod( 'h3_size' );
    if ( get_theme_mod( 'h3_size' )) {
        $custom .= "h3 { font-size:" . intval($h3_size) . "px; }"."\n";
    }

    //头部图片高度
    $branding_padding = get_theme_mod( 'branding_padding', '150' );
    $custom .= ".site-branding { padding:" . intval($branding_padding) . "px 0; }"."\n";
    //头部图片高度1024
    $branding_padding_1024 = get_theme_mod( 'branding_padding_1024', '100' );
    $custom .= "@media only screen and (max-width: 1024px) { .site-branding { padding:" . intval($branding_padding_1024) . "px 0; } }"."\n";    
    //Logo size
    $logo_size = get_theme_mod( 'logo_size', '200' );
    $custom .= ".site-logo { max-width:" . intval($logo_size) . "px; }"."\n";
    
    //取得各种颜色
    $primary_color = get_theme_mod( 'primary_color', '#23B6B6' );//(1)
    $background_color = '#'.get_background_color();//(2)
    $entry_bg = get_theme_mod( 'entry_bg', '#FFFFFF' );//(3)
    $opp_text = $entry_bg;
    $site_title = get_theme_mod( 'site_title_color', '#f9f9f9' );//(3)
    $site_desc = get_theme_mod( 'site_desc_color', '#dddddd' );//(3)
    $sidebar_color = get_theme_mod( 'sidebar_color', '#f9f9f9' );//(3)
    $entry_titles = get_theme_mod( 'entry_titles', '#000' );//(4)
    $body_text = get_theme_mod( 'body_text_color', '#50545C' ); //(4)
    $entry_meta = get_theme_mod( 'entry_meta', '#9d9d9d' );//(5)
    $second_bg = get_theme_mod( 'second_bg', '#17191B' );//(6)
    
    //Secondary,thirdly
    if ( 1 == get_theme_mod('ignore_calc_color') ) {
        $secondary_color = get_theme_mod( 'secondary_color', '#B524B5' );//(7)
        $thirdly_color = get_theme_mod( 'thirdly_color', '#B5B524' );//(8)
    }
    else {
        $steps = intval(get_theme_mod( 'color_phase_steps', '135' ));
        $ass = get_assistant_color( $primary_color, $steps ) ;
        $secondary_color = esc_attr($ass[0]);//(7)
        $thirdly_color = esc_attr($ass[1]);//(8)
    }

    //正文文字颜色    
    $rgba   = bluefly_hex2rgba_str($body_text_color, 0.3);
    $shadow = bluefly_hex2rgba_str($body_text, 0.3);
    $overlay = bluefly_hex2rgba_str($entry_bg, 0.6);
    $custom .= "body { color:" . esc_attr($body_text) . ";\n";  
    $custom .= "background-color:". esc_attr($background_color). ";\n";
    $custom .= "text-shadow: 1px 0px 1px ". esc_attr($shadow). ";}\n";
    $custom .= ".towhom a { color:" . esc_attr($body_text) . ";}\n";
    $custom .= "h1,h2,h3,h4,h5,h6 { color:" . esc_attr($body_text) . ";}\n";
    $custom .= ".view h2, .view p, .view a.info { color:" . esc_attr($body_text) . ";}\n";
    $custom .= ".search-field { color:" . esc_attr($body_text) . ";}\n";
    $custom .= ".view::after {background-color:".$overlay."; }\n";
    
    $custom .= "blockquote::before { color:" . esc_attr($background_color) . ";}\n";
    
    //Site title    
    $custom .= ".site-title a, .site-title a:hover { color:" . esc_attr($site_title) . ";}\n";  
    //Site desc 
    $custom .= ".site-description { color:" . esc_attr($site_desc) . ";}\n";
    
    //Entry titles  
    $custom .= ".entry-title, .entry-title a { color:" . esc_attr($entry_titles) . ";}\n";
    
    //Entry meta    
    $custom .= ".entry-meta a, .entry-meta a, .entry-footer, .entry-footer a, .comment-metadata a { color:" . esc_attr($entry_meta) . ";}\n";
    $custom .= "a:hover { color: ". esc_attr($entry_meta). ";}\n";
    $custom .= ".site-footer,.site-footer a { color: ". esc_attr($entry_meta). ";}\n";
    
    //文章背景色 
    $custom .= ".hentry { background-color:" . esc_attr($entry_bg) . ";}\n";
    $custom .= ".view { border: 5px solid " . esc_attr($entry_bg) . ";\n";
    $custom .= "box-shadow: 1px 0px 1px ". esc_attr($entry_bg). ";}\n";
    $custom .= ".hentry-bg { background-color:" . esc_attr($entry_bg) . ";}\n";
	$rgba   = bluefly_hex2rgba_str($background_color, 0.6);
	$custom .= ".entry-content ol , .entry-content ul { background-image: linear-gradient( to right ," . esc_attr($rgba) . "," . esc_attr($entry_bg) . "); }\n";

    //反选文字的颜色,与文章背景色相同. 
    $custom .= ".opp-text { color:" . esc_attr($opp_text) . ";}\n";
    $custom .= ".site-main .comment-navigation a,.site-main .posts-navigation a,.site-main .post-navigation a,.site-main .pagination a  { color:" . esc_attr($opp_text) . ";}\n";
    
    //带a带hover的直接加不上,只能单写
    $custom .= ".post-meta, post-meta a { color:" . esc_attr($opp_text) . ";}\n";
    $custom .= ".wp-caption-text { color:" . esc_attr($opp_text) . ";}\n";
    $custom .= ".slicknav_nav { color:" . esc_attr($opp_text) . ";}\n";
    $custom .= ".slicknav_brand { color:" . esc_attr($opp_text) . ";}\n";
    $custom .= ".comment-respond .comment-reply-title { color:" . esc_attr($opp_text) . ";}\n";
    $custom .= "button, .button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"]{color: " . esc_attr($opp_text) . "}\n";
    
    //第二背景色
    $custom .= ".sec-bg { background-color:" . esc_attr($second_bg) . ";}\n";
    $custom .= 'button:hover,.button:hover,input[type="button"]:hover,input[type="reset"]:hover,input[type="submit"]:hover,input[id="grasp"]:hover { background-color: '. esc_attr($second_bg) . ";}\n";
    $custom .= ".comment-navigation .nav-previous,.posts-navigation .nav-previous,.post-navigation .nav-previous,.comment-navigation .nav-next,.posts-navigation .nav-next,.post-navigation .nav-next,.pagination .page-numbers  { background-color:" . esc_attr($second_bg) . ";}\n";
    $custom .= ".form_row img{ border: 1px solid " . esc_attr($second_bg) . ";}\n";
    $custom .= ".widget-area, .widget-area a { color:" . esc_attr($sidebar_color) . ";}\n";
    $custom .= ".view [class*=\"content-bg-\"]::before { color:" . esc_attr($second_bg) . ";}\n";
    
    //主颜色(主要是链接和高亮) 
    $custom .= "a { color:" . esc_attr($primary_color) . ";}" . "\n";
    $custom .= ".archive-meta a:hover, .entry-meta a:hover, .view p:hover, .entry-title a:hover, .social-navigation li a:hover, a { color:" . esc_attr($primary_color) . "}\n";
    $custom .= ".nav-previous:hover, .nav-next:hover, button, .button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"] { background-color:" . esc_attr($primary_color) . "}\n";
    $custom .= ".page-numbers.current { color: ". esc_attr($opp_text). "; background-color: ".esc_attr($primary_color)."; }\n";
    $rgba   = bluefly_hex2rgba_str($primary_color, 0.3);
    $custom .= ".bluefly-entry-thumb:after { background-color:" . esc_attr($rgba) . ";}" . "\n";
    $custom .= ".view h2 { background-color:" . esc_attr($rgba) . ";}" . "\n";
    $custom .= ".assistive-text { color:" . esc_attr($primary_color) . ";}" . "\n";
    $custom .= "blockquote { border-left: 5px solid " . esc_attr($primary_color) . ";" . "\n";
    $custom .= "background-color: " . esc_attr($rgba) . ";}" . "\n";
    $custom .= ".slicknav_nav li { border-color:" . esc_attr($primary_color) . ";}" . "\n";
	$custom .= ".entry-content ol , .entry-content ul { color:" . esc_attr($primary_color) . ";}\n";
	$custom .= ".entry-content ol , .entry-content ul { border-left: 3px solid " . esc_attr($primary_color) . ";}\n";
    
    $custom .= ".social-navigation li a::before { color:". $secondary_color. ";}\n" ;
    $custom .= ".secondary_color { color:". $secondary_color. ";}\n" ;
    $custom .= ".widget-area a:hover { color:" . esc_attr($secondary_color) . "}\n";
    $custom .= "del::after { background-color:" . esc_attr($thirdly_color) . "}\n";
    $custom .= ".thirdly_color, .thirdly_color a { color:". $thirdly_color. ";}\n" ;
    $custom .= ".sticky::before { color: ". esc_attr($thirdly_color). ";}\n";
    $custom .= ".comments-title { border-bottom: 1px solid ". esc_attr($rgba). ";}\n";
    $custom .= ".grasp-list { border-bottom: 1px solid ". esc_attr($rgba). ";}\n";
    $custom .= ".comment-metadata { border-bottom: 1px solid ". $rgba. ";}\n";
    $custom .= 'input[id="grasp"]{background-color:'.$shadow.'}\n;';
    $custom .= 'button:focus,input[type="button"]:focus,input[type="reset"]:focus,input[type="submit"]:focus,input[id="grasp"]:focus
button:active,input[type="button"]:active,input[type="reset"]:active,input[type="submit"]:active,input[id="grasp"]:active  { border-color: '. esc_attr($thirdly_color). ";}\n";
    $custom .= ".comment-author .avatar { border: 5px solid ". esc_attr($background_color). ";\n";
    $custom .= " box-shadow: inset 1px 1px 2px ". esc_attr($thirdly_color). ";}\n";
    $custom .= ".comment-author .byposter { border: 5px dotted ". esc_attr($second_bg). ";\n";
    $custom .= " box-shadow: inset 1px 1px 2px ". esc_attr($background_color). ";}\n";
    $shadow = bluefly_hex2rgba_str($primary_color, 0.3);
    $custom .= ".comment-content.byposter p { text-shadow: 1px 0px 1px ". $shadow. ";}\n";
	$shadow = bluefly_hex2rgba_str($thirdly_color, 0.8);
	$custom .= ".entry-content ol , .entry-content ul { text-shadow: 2px 0px 3px ". $shadow. ";}\n";
    $custom .= "a.comment-reply-link:hover { color: ". esc_attr($secondary_color). ";}\n";
    $custom .= ".view:hover {border: 5px solid ".$primary_color.";\n box-shadow: 1px 1px 2px ". esc_attr($primary_color). ";}\n";
    
    //底部烟雾效果
    $rgba   = bluefly_hex2rgba_str($background_color, 0);
    $custom .= "body.not-touch-device:after { background: -moz-linear-gradient(top, ". esc_attr($rgba). " 0%, ".$background_color." 100%);\n";
    $custom .= "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ".esc_attr($rgba)."), color-stop(100%, ".$background_color."));\n";
    $custom .= "background: -webkit-linear-gradient(top, ".esc_attr($rgba)." 0%, ".$background_color." 100%);\n";
    $custom .= "background: -o-linear-gradient(top, ".esc_attr($rgba)." 0%, ".$background_color." 100%);\n";
    $custom .= "background: -ms-linear-gradient(top, ".esc_attr($rgba)." 0%, ".$background_color." 100%);\n";
    $custom .= "background: linear-gradient(to bottom, ".esc_attr($rgba)." 0%, ".$background_color." 100%);\n";
    $custom .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".esc_attr($background_color)."', endColorstr='".$background_color."',GradientType=0 );}\n";
    
    //Output all the styles
    wp_add_inline_style( 'bluefly-style', $custom );    
}
add_action( 'wp_enqueue_scripts', 'bluefly_custom_styles' );