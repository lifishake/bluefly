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
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

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


