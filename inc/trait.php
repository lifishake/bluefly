<?php
/**
 * 一些功能函数
 * @package bluefly
 */

 /**
 * 作用: 版本信息
 * 来源: Oblique
 */
function bluefly_footer_credits() {
	echo 'Theme: <span class="thirdly_color"><a href="https://github.com/lifishake/bluefly" target="_blank" rel="nofollow">bluefly</a></span> &copy;'.date('Y').' 破袜子';
}
add_action( 'bluefly_footer', 'bluefly_footer_credits' );
 
/**
 * 作用: 显示日期(作者隐藏)
 */
function bluefly_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		bluefly_rel_post_date());

	$posted_on = '<i class="fa fa-calendar thirdly_color"></i> ' . $time_string ;

	$byline = '<span class="author vcard hidden "><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';


	/*个人用户,把作者隐藏掉*/
	echo '<span class="posted-on">' . $posted_on . '</span>'. $byline ;

}

/**
 * 作用: 显示tag
 */
function bluefly_entry_footer() {
	if ( 'post' == get_post_type() ) {
		$tags_list = get_the_tag_list( '', ', ' );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><i class="fa fa-tag thirdly_color"></i> %s</span>', $tags_list );
		}
	}
}

/**
 * 作用: archive标题
 * 来源: 破袜子原创
 */
function bluefly_archive_title() {
	/*为了中文化,放弃wordpress自带的the_archive_title()*/
	$format = '%1$s%2$s: %3$s%4$s';
	$before = '<h1 class="page-title opp-text">';
	$after = '</h1>';
	$part1='';
	$part2='';
	if ( is_category() ) {
		$part1 = '分类';
		$part2 = single_cat_title( '', false );
	}
	else if ( is_tag() ) {
		$part1 = '标签';
		$part2 = single_tag_title( '', false );
	}
	else if ( is_year() ) {
		$part1 = '年';
		$part2 =  get_the_date('Y') ;
	}
	else if ( is_month() ) {
		$part1 = '月';
		$part2 = get_the_date('F Y');
	}
	else if ( is_day() ) {
		$part1 = '日';
		$part2 = get_the_date(get_option('date_format'));
	}
	else{
		$part1 = '归档';
	}
	$out = sprintf($format, $before, $part1, $part2, $after);
	echo $out ;
}

/**
 * 作用: 分类面包屑
 * 来源: 破袜子原创
 */
function bluefly_breadcrumb() {
	if ( !is_single() && !is_category() )
		return;
	$return = '<i class="fa fa-folder-open thirdly_color"></i><a href="';
	$return .= home_url();
	$return .= '">主页</a> &raquo; ';
        
	if (is_category() || (is_single() && ! is_attachment())) {
		$category = get_the_category();
		$catID = $category[0]->cat_ID;
		$return .= get_category_parents($catID, true, ' &raquo; ', false);
	}

	if (is_single()){
		$return .= get_the_title();
	}
		
    return $return;
}

/**
 * 作用: 计算时间差
 * 来源: 破袜子原创
 */
function bluefly_timediff( $from, $to, $before, $after) {
	if ( empty($from) || empty($to) )
		return '';
	if( empty($before) )
		$before = '于';
	if( empty($after) )
		$after = '以前';
	$from_int = strtotime($from) ;
	$to_int = strtotime($to) ;
	$diff_time = abs($to_int - $from_int) ;
	if ( $diff_time > 60 * 60 * 24 * 365 ){//年
		$num = round($diff_time / (60 * 60 * 24 * 365));
		$uni = '年';
	}
	else if ( $diff_time > 60 * 60 * 24 * 31 ) {//月
		$num = round($diff_time / (60 * 60 * 24 * 30));
		$uni = '个月';
	}
	else if ( $diff_time > 60 * 60 * 24 ) {//天
		$num = round($diff_time / (60 * 60 * 24));
		$uni = '天';
	}
	else if ( $diff_time > 60 * 60 ) { //小时
		$num = round($diff_time / 3600);
		$uni = '个小时';
	}
	else { //分钟
		$num = round($diff_time / 60);
		$uni = '分';
	}
	$return = $before.$num.$uni.$after ;
	return $return;
}

/**
 * 作用: post的时间差
 * 来源: 破袜子原创
 */
function bluefly_rel_post_date() {
	global $post;
	$post_date_time = mysql2date('j-n-Y H:i:s', $post->post_date, false);
	$current_time = current_time('timestamp');
	$date_today_time = gmdate('j-n-Y H:i:s', $current_time);
	return bluefly_timediff( $post_date_time, $date_today_time ,'','前' ) ;
}

/**
 * 作用: comment的时间差
 * 来源: 破袜子原创
 */
function bluefly_rel_comment_date() {
	global $post , $comment;
	$post_date_time = mysql2date('j-n-Y H:i:s', $post->post_date, false);
	$comment_date_time = mysql2date('j-n-Y H:i:s', $comment->comment_date, false);
	return bluefly_timediff( $post_date_time, $comment_date_time ,'发文','后' ) ;
}

/**
 * 作用: 计算时间差
 * 来源: 破袜子原创
 */
function bluefly_time_ago( $desc ) {
	global $prime_date;
	global $post;
	if (!isset($prime_date)) {
		$posts = get_posts(array('order'=>'ASC', 'posts_per_page'=>1));
		$prime_date = $posts[0]->post_date_gmt;
	}
	$start_time = mysql2date('j-n-Y H:i:s', $prime_date, false);
	$current_time = current_time('timestamp');
	$today_time = gmdate('j-n-Y H:i:s', $current_time);
	$from_int = strtotime($start_time) ;
	$to_int = strtotime($today_time) ;
	
    $diff = (int) abs( $from_int - $to_int );
	$days = round(diff/(60*60*24));
	return $days.$desc;
  
}

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