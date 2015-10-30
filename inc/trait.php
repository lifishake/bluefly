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

	$byline = '<span class="author vcard "><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';


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
