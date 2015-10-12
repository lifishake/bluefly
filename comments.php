<?php
/**
 * 留言模板
 * @package bluefly
 */

/*如果有密码保护，就不显示评论。*/
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php $comment_arg=array();
			$comment_arg['post_id']=get_the_ID();
			$comment_arg['count']='true';
			$comment_arg['user_id']=0;/*don't count for known users*/
			$comment_arg['type']='comment';
			$counta=get_comments($comment_arg);
			$comment_arg['type']='senseless';
			$countb=get_comments($comment_arg);
			printf('<i class="fa fa-comments"></i> %1$s  <i class="fa fa-eye"></i> %2$s',$counta,$countb);
			?>
		</h2>
		
		<?php if( function_exists('bluefly_get_grasp_list') ) {
			//显示非诚意留言墙
			bluefly_get_grasp_list(); }?>
		<ol class="comment-list">
			<?php
				$arg_list = array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 60,
					'type'=>'comment',
					'callback'=>'mytheme_comment',
				);
				//页面时留言改为倒序
				if ( is_page() ){
					$arg_list['reverse_top_level']=true;
					$arg_list['max_depth'] = 2 ;
					$arg_list['per_page'] = 999 ;
				}
				wp_list_comments( $arg_list );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :  ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php echo( '评论导航' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous sec-bg"><?php previous_comments_link( '<i class="fa fa-arrow-left"></i> 旧评论' ); ?></div>
				<div class="nav-next sec-bg"><?php next_comments_link( '新评论 <i class="fa fa-arrow-right"></i>' ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>
		
	<?php endif; // have_comments() ?>

	<?php
		//评论关闭时的显示
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
			echo '<p class="no-comments">"评论已关闭"</p>';
		}
		//通过修改comment_form的默认参数，来实现隐藏已知cookie。
		//很多默认参数的修改其实只是为了全中文需要。
		//参考资料：https://codex.wordpress.org/Function_Reference/comment_form
		$comment_form_args = array();
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$cookie = esc_attr($commenter['comment_author']);
		$email = esc_attr($commenter['comment_author_email']);
		//追加修改资料部分 setStyleDisplay在ajax-comment.js里定义
		$comment_part = '' ;

		if ( $cookie != "" ) {
			$comment_part.= '<div class="form_row">' ;
			$comment_part.= sprintf('<p> <span class="show-form secondary_color" >%s[编辑]</span>， 欢迎回来 ', $cookie) ;	
			$comment_part.= get_avatar( $email, $size = '24') ;
			$comment_part.= '</p></div>' ;			
		}
		//另一半div标签放在前面的 $comment_field里
		$comment_part.= '<div id="author_info">';
		$fields =  array(
			  'author' =>
				'<p class="comment-form-author"><label for="author">昵称</label> ' .
				( $req ? '<span class="required">（必填）</span>' : '' ) .
				'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
				'" size="30"' . $aria_req . ' /></p>',

			  'email' =>
				'<p class="comment-form-email"><label for="email">邮箱</label> ' .
				( $req ? '<span class="required">（必填，不会泄漏）</span>' : '' ) .
				'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
				'" size="30"' . $aria_req . ' /></p>',

			  'url' =>
				'<p class="comment-form-url"><label for="url">网址</label>' .
				'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
				'" size="30" /></p>',
			);//fields
		$args = array(
			  'title_reply'       => '',
			  'title_reply_to'    => '%s',
			  'cancel_reply_link' => '取消',
			  'label_submit'      => '发表留言',

			  'comment_field' =>  '</div><p class="comment-form-comment"><label for="comment">' . '' .
				'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
				'</textarea></p>',

			  'must_log_in' => '<p class="must-log-in">' .
				sprintf('留言前请先<a href="%s">登录</a>',
				  wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
				) . '</p>',

			  'logged_in_as' => '<p class="logged-in-as">' .
				sprintf(
				  '已登录。 <a href="%s" title="Log out of this account">注销?</a>',
				  wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
				) . '</p>',
				
			  'comment_notes_before' => $comment_part ,				

			  'comment_notes_after' => '<p class="form-allowed-tags">不知该说什么就点【路过】吧！</p>',

			  'fields' => apply_filters( 'comment_form_default_fields', $fields ),
			);
		comment_form($args);
		//先调用,后隐藏
		if ( $cookie != "" ) { ?>
			<script type="text/javascript">ToggleCommentForm();</script>
		<?php }
	?>
	
</div><!-- #comments -->
