<?php
/**
 * comments.
 *
 * @package bluefly
 */

/*如果有密码保护，就不显示评论。*/
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php echo "评论列表"; ?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php _e( '评论导航', 'bluefly' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( __( '旧评论', 'bluefly' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( '新评论', 'bluefly' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>
		<?php if( function_exists('bluefly_get_grasp_list') ) 
			bluefly_get_grasp_list(); ?>
		<ol class="comment-list">
			<?php
				//$arg_list = array();
				$arg_list = array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 60,
					'type'=>'comment',
					'callback'=>'mytheme_comment',
				);
				if ( is_page() ){
					$arg_list['reverse_top_level']=true;
					$arg['max_depth'] = 2 ;
					$arg['per_page'] = 999 ;
				}
				wp_list_comments( $arg_list );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php _e( '评论导航', 'bluefly' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( __( '旧评论', 'bluefly' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( '新评论', 'bluefly' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>
		
	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( '评论已关闭', 'bluefly' ); ?></p>
	<?php endif; ?>

	<?php
		comment_form(array('comment_notes_after'=>'不知该说什么就点【无言以对】吧！'));
	?>
	
</div><!-- #comments -->
