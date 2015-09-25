<?php
/**
 * 读取数据库,设定自定义CSS样式
 * @package bluefly
 */

/**
 * 作用: HEX描述的颜色值转成RGBA描述(A作为另外的参数)
 * 来源: Oblique原版
 */
function bluefly_hex2rgba_str($color, $opacity = 1.0) {

        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        $rgb =  array_map('hexdec', $hex);
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';

        return $output;
}

/**
 * 作用: HEX描述的颜色值转成RGB
 * 来源: Oblique原版
 */
function hex2rgb($color) {
	if ($color[0] == '#' ) {
		$color = substr( $color, 1 );
	}
	$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    $rgb =  array_map('hexdec', $hex);
	return $rgb;
}

/**
 * 作用: RGB颜色值转成HSV描述
 * 来源: http://stackoverflow.com/questions/1773698/rgb-to-hsv-in-php
 * 输出的范围0-360, 0-100, 0-100!!
 */
function rgb2hsv(array $rgb)   
{                             
	list($R,$G,$B) = $rgb;
    $R = ($R / 255);
    $G = ($G / 255);
    $B = ($B / 255);

    $maxRGB = max($R, $G, $B);
    $minRGB = min($R, $G, $B);
    $chroma = $maxRGB - $minRGB;

    $computedV = floor(100 * $maxRGB);

    if ($chroma == 0)
        return array(0, 0, $computedV);

    $computedS = floor(100 * ($chroma / $maxRGB));

    if ($R == $minRGB)
        $h = 3 - (($G - $B) / $chroma);
    elseif ($B == $minRGB)
        $h = 1 - (($R - $G) / $chroma);
    else // $G == $minRGB
        $h = 5 - (($B - $R) / $chroma);

    $computedH = floor(60 * $h);

    return array($computedH, $computedS, $computedV);
}

/**
 * 作用: RGB颜色值转成HSV描述
 * 来源: 破袜子由C代码修改
 * 输入的范围0-360, 0-100, 0-100!!
 */
function hsv2rgb(array $hsv) {
	list($H,$S,$V) = $hsv;
	//1
	$H /= 60;
	//2
	$I = floor($H);
	$F = $H - $I;
	$S /= 100;
	$V /= 100;
	//3
	$M = round( $V * (1 - $S) * 255);
	$N = round( $V * (1 - $S * $F) * 255 );
	$K = round( $V * (1 - $S * (1 - $F)) * 255 );
	$V = round( $V * 255) ;
	//4
	switch ($I) {
		case 0:
			list($R,$G,$B) = array($V,$K,$M);
			break;
		case 1:
			list($R,$G,$B) = array($N,$V,$M);
			break;
		case 2:
			list($R,$G,$B) = array($M,$V,$K);
			break;
		case 3:
			list($R,$G,$B) = array($M,$N,$V);
			break;
		case 4:
			list($R,$G,$B) = array($K,$M,$V);
			break;
		case 5:
		case 6: //for when $H=1 is given
			list($R,$G,$B) = array($V,$M,$N);
			break;
	}
	return array($R, $G, $B);
}

/**
 * 作用: 根据HSV取相位角度差为$degree度的颜色
 * 来源: 破袜子原创
 * $degree的范围-360~360
 */
function get_semi_color( $hsv, $degree) {
	$temp = $hsv ;
	$temp[0] = $temp[0] + $degree + 360;
	$temp[0] = $temp[0] % 360 ;
	$rgb = hsv2rgb($temp) ;
	$str = sprintf("#%1$02X%2$02X%3$02X",$rgb[0],$rgb[1],$rgb[2]) ;
	return $str;
}

/**
 * 作用: 取角度差为$degree度的颜色
 * 来源: 破袜子原创
 * $degree的范围 0~359
 */
function get_assistant_color( $primary, $degree ) {
	 $rgb = hex2rgb($primary) ;
	 $hsv = rgb2hsv($rgb) ;
	 $ret = array() ;
	 $ret[0] = get_semi_color( $hsv, $degree) ;
	 $ret[1] = get_semi_color( $hsv,  0 - $degree) ;
	 return $ret;
 }

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

    //正文字体大小
    $body_size = get_theme_mod( 'body_size' );
    if ( get_theme_mod( 'body_size' )) {
        $custom .= "body { font-size:" . intval($body_size) . "px; }"."\n";
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

	//Primary color
	$primary_color = get_theme_mod( 'primary_color', '#23B6B6' );
	if ( $primary_color != '#23B6B6' ) {
	$custom .= ".entry-meta a:hover, .entry-title a:hover, .widget-area a:hover, .social-navigation li a:hover, a { color:" . esc_attr($primary_color) . "}"."\n";
	$custom .= ".nav-previous:hover, .nav-next:hover, button, .button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"] { background-color:" . esc_attr($primary_color) . "}"."\n";
	$rgba 	= bluefly_hex2rgba_str($primary_color, 0.3);
	$custom .= ".bluefly-entry-thumb:after { background-color:" . esc_attr($rgba) . ";}" . "\n";
	}
	
	//Body
	$body_text = get_theme_mod( 'body_text_color', '#50545C' );
	$custom .= "body { color:" . esc_attr($body_text) . ";"."\n";
	$background_color = get_theme_mod( 'background_color', '#E5E5E5' );
	$custom .= "background-color:". esc_attr($background_color). ";}\n";
	//Site title
	$site_title = get_theme_mod( 'site_title_color', '#f9f9f9' );
	$custom .= ".site-title a, .site-title a:hover { color:" . esc_attr($site_title) . ";}"."\n";
	//Site desc
	$site_desc = get_theme_mod( 'site_desc_color', '#dddddd' );
	$custom .= ".site-description { color:" . esc_attr($site_desc) . ";}"."\n";
	//Entry titles
	$entry_titles = get_theme_mod( 'entry_titles', '#000' );
	$custom .= ".entry-title, .entry-title a { color:" . esc_attr($entry_titles) . ";}"."\n";
	//Entry meta
	$entry_meta = get_theme_mod( 'entry_meta', '#9d9d9d' );
	$custom .= ".entry-meta, .entry-meta a, .entry-footer, .entry-footer a { color:" . esc_attr($entry_meta) . ";}"."\n";	
	//文章背景色
	$hentry_bg = get_theme_mod( 'hentry_bg', '#FFFFFF' );
	$custom .= ".hentry { background-color:" . esc_attr($hentry_bg) . ";}"."\n";
	$custom .= ".view { border: 8px solid " . esc_attr($hentry_bg) . ";"."\n";
	$custom .= "box-shadow: 1px 1px 2px ". esc_attr($background_color). ";}\n";
	$custom .= ".hentry-bg { background-color:" . esc_attr($hentry_bg) . ";}"."\n";
	////这个用固定模板的,加不上自定义风格
	$custom .= ".comment-reply-link { background-color:" . esc_attr($hentry_bg) . ";}"."\n";
	//反选文字的颜色,与文章背景色相同.
	$opp_text = $hentry_bg;
	$custom .= ".opp-text { color:" . esc_attr($opp_text) . ";}"."\n";
	//带a带hover的直接加不上,只能单写
	$custom .= ".post-meta, post-meta a { color:" . esc_attr($opp_text) . ";}"."\n";
	$custom .= ".wp-caption-text { color:" . esc_attr($opp_text) . ";}"."\n";
	$custom .= ".slicknav_nav { color:" . esc_attr($opp_text) . ";}"."\n";
	$custom .= ".slicknav_brand { color:" . esc_attr($opp_text) . ";}"."\n";
	$custom .= ".view h2, .view p, .view a.info { color:" . esc_attr($opp_text) . ";}"."\n";
	$custom .= ".comment-respond .comment-reply-title { color:" . esc_attr($opp_text) . ";}"."\n";
	
	//第二背景色
	$second_bg = get_theme_mod( 'second_bg', '#17191B' );
	$custom .= ".sec-bg { background-color:" . esc_attr($second_bg) . ";}"."\n";
	$custom .= 'button:hover,.button:hover,input[type="button"]:hover,input[type="reset"]:hover,input[type="submit"]:hover,input[type="grasp"]:hover { background-color: '. esc_attr($second_bg) . ";}"."\n";
	
	$sidebar_color = get_theme_mod( 'sidebar_color', '#f9f9f9' );
	$custom .= ".widget-area, .widget-area a { color:" . esc_attr($sidebar_color) . ";}"."\n";
	
	//Secondary,thirdly
	if ( 1 == get_theme_mod('ignore_calc_color') ) {
		$secondary_color = get_theme_mod( 'secondary_color', '#B524B5' );
		$thirdly_color = get_theme_mod( 'thirdly_color', '#B5B524' );
	}
	else {
		$steps = intval(get_theme_mod( 'color_phase_steps', '120' ));
		$ass = get_assistant_color( $primary_color, $steps ) ;
		$secondary_color = esc_attr($ass[0]);
		$thirdly_color = esc_attr($ass[1]);
	}
	$custom .= ".social-navigation li a::before { color:". $secondary_color. ";}"."\n" ;
	$custom .= ".secondary_color { color:". $secondary_color. ";}"."\n" ;
	$custom .= ".thirdly_color, .thirdly_color a { color:". $thirdly_color. ";}"."\n" ;
	$custom .= ".sticky { box-shadow: 1px 1px 2px ". esc_attr($thirdly_color). ";}\n";
	$custom .= ".view:hover {border: 8px solid ".$second_bg.";\n box-shadow: 1px 1px 2px ". esc_attr($secondary_color). ";}\n";
	//Output all the styles
	wp_add_inline_style( 'bluefly-style', $custom );	
}
add_action( 'wp_enqueue_scripts', 'bluefly_custom_styles' );