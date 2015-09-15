<?php
/**
 * 一些功能函数
 *
 * @package bluefly
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function bluefly_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		bluefly_rel_post_date());

	$posted_on = sprintf(
		_x( '%s', '日期', 'bluefly' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$category = get_the_category(); 
	if($category){
		$cat = '<a href="' . esc_url(get_category_link($category[0]->term_id )) . '">' . esc_attr($category[0]->cat_name) . '</a>';
	}

	$byline = sprintf(
		_x( '%s', '作者', 'bluefly' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	if ( !is_singular()) {
		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span><span class="cat-link">' . $cat . '</span>';
	} elseif (!get_theme_mod('meta_singles')) {
		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
		if ( 'post' == get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'bluefly' ) );
			if ( $categories_list ) {
				printf( '<span class="cat-links">' . __( '%1$s', 'bluefly' ) . '</span>', $categories_list );
			}
		}		
	}
}


function bluefly_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() && !get_theme_mod('meta_singles') ) {

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'bluefly' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( '标签 %1$s', 'bluefly' ) . '</span>', $tags_list );
		}
	}
}

function bluefly_breadcrumb() {
	if ( !is_single() && !is_category() )
		return;
	$return = '<a href="';
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
		$num = round($diff_time / (60 * 60 * 24 * 30* 365));
		$uni = '年';
	}
	else if ( $diff_time > 60 * 60 * 24 * 31 ) {//月
		$num = round($diff_time / (60 * 60 * 24 * 30));
		$uni = '月';
	}
	else if ( $diff_time > 60 * 60 * 24 ) {//天
		$num = round($diff_time / (60 * 60 * 24));
		$uni = '天';
	}
	else if ( $diff_time > 60 * 60 ) { //小时
		$num = round($diff_time / 3600);
		$uni = '小时';
	}
	else { //分钟
		$num = round($diff_time / 60);
		$uni = '分';
	}
	$return = $before.$num.$uni.$after ;
	return $return;
}

function bluefly_rel_post_date() {
	global $post;
	$post_date_time = mysql2date('j-n-Y H:i:s', $post->post_date, false);
	$current_time = current_time('timestamp');
	$date_today_time = gmdate('j-n-Y H:i:s', $current_time);
	return bluefly_timediff( $post_date_time,$date_today_time  ,'于','以前' ) ;
}

function bluefly_rel_comment_date() {
	global $post , $comment;
	$post_date_time = mysql2date('j-n-Y H:i:s', $post->post_date, false);
	$comment_date_time = mysql2date('j-n-Y H:i:s', $comment->comment_date, false);
	return bluefly_timediff( $post_date_time, $comment_date_time ,'于博文发表','以后' ) ;
}

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
