<?php
/**
 * 读取数据库,设定自定义CSS样式
 * @package bluefly
 */

//Converts hex colors to rgba for the menu background color
function bluefly_hex2rgba_str($color, $opacity = 1.0) {

        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        $rgb =  array_map('hexdec', $hex);
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';

        return $output;
}

function hex2rgb($color) {
	if ($color[0] == '#' ) {
		$color = substr( $color, 1 );
	}
	$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    $rgb =  array_map('hexdec', $hex);
	return $rgb;
}

//http://stackoverflow.com/questions/1773698/rgb-to-hsv-in-php
function rgb2hsv(array $rgb)    // RGB values:    0-255, 0-255, 0-255
{                                // HSV values:    0-360, 0-100, 0-100
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

function get_semi_color( $hsv, $degree) {
	$temp = $hsv ;
	$temp[0] = $temp[0] + $degree + 360;
	$temp[0] = $temp[0] % 360 ;
	$rgb = hsv2rgb($temp) ;
	$str = sprintf("#%1$02X%2$02X%3$02X",$rgb[0],$rgb[1],$rgb[2]) ;
	return $str;
}

function get_assistant_color( $primary, $degree ) {
	 $rgb = hex2rgb($primary) ;
	 $hsv = rgb2hsv($rgb) ;
	 $ret = array() ;
	 $ret[0] = get_semi_color( $hsv, $degree) ;
	 $ret[1] = get_semi_color( $hsv,  0 - $degree) ;
	 return $ret;
 }

//Dynamic styles
function bluefly_custom_styles($custom) {

	$custom = '';

    //Site title
    $site_title_size = get_theme_mod( 'site_title_size', '82' );
    if ( get_theme_mod( 'site_title_size' )) {
        $custom .= ".site-title { font-size:" . intval($site_title_size) . "px; }"."\n";
    }
    //Site description
    $site_desc_size = get_theme_mod( 'site_desc_size', '18' );
    if ( get_theme_mod( 'site_desc_size' )) {
        $custom .= ".site-description { font-size:" . intval($site_desc_size) . "px; }"."\n";
    }
    //Menu
    $menu_size = get_theme_mod( 'menu_size', '16' );
    if ( get_theme_mod( 'menu_size' )) {
        $custom .= ".main-navigation li { font-size:" . intval($menu_size) . "px; }"."\n";
    }    	    	
	//H1 size
	$h1_size = get_theme_mod( 'h1_size' );
	if ( get_theme_mod( 'h1_size' )) {
		$custom .= "h1 { font-size:" . intval($h1_size) . "px; }"."\n";
	}
    //H2 size
    $h2_size = get_theme_mod( 'h2_size' );
    if ( get_theme_mod( 'h2_size' )) {
        $custom .= "h2 { font-size:" . intval($h2_size) . "px; }"."\n";
    }
    //H3 size
    $h3_size = get_theme_mod( 'h3_size' );
    if ( get_theme_mod( 'h3_size' )) {
        $custom .= "h3 { font-size:" . intval($h3_size) . "px; }"."\n";
    }

    //Body size
    $body_size = get_theme_mod( 'body_size' );
    if ( get_theme_mod( 'body_size' )) {
        $custom .= "body { font-size:" . intval($body_size) . "px; }"."\n";
    }


	//Header padding
	$branding_padding = get_theme_mod( 'branding_padding', '150' );
	$custom .= ".site-branding { padding:" . intval($branding_padding) . "px 0; }"."\n";
	//Header padding 1024
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
	
	//Footer background
	$footer_background = get_theme_mod( 'footer_background', '#17191B' );
	$custom .= ".site-footer { background-color:" . esc_attr($footer_background) . ";}"."\n";
	
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
	//Entry background
	$entry_bg = get_theme_mod( 'entry_background_color', '#FFFFFF' );
	$custom .= ".hentry { background-color:" . esc_attr($entry_bg) . ";}"."\n";
	$custom .= ".view { border: 8px solid " . esc_attr($entry_bg) . ";"."\n";
	$custom .= "box-shadow: 1px 1px 2px ". esc_attr($background_color). ";}\n";
	
	//Sidebar
	$sidebar_bg = get_theme_mod( 'sidebar_bg', '#17191B' );
	$custom .= ".widget-area { background-color:" . esc_attr($sidebar_bg) . ";}"."\n";
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

	//Output all the styles
	wp_add_inline_style( 'bluefly-style', $custom );	
}
add_action( 'wp_enqueue_scripts', 'bluefly_custom_styles' );