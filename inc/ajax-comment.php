<?php 

/**
 * ajax_comment后的回调函数，以及自定义的comment回调显示函数。
 * 自定义comment回调的原因是看原版显示格式不顺眼。
 * 本文件原型来自大发提供的do.php，有大幅度增删改。原始URL：https://fatesinger.com/59
 * @package bluefly
 */
 
//追加回调入口
add_action('wp_ajax_nopriv_ajax_comment', 'ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'ajax_comment_callback');

/**
 * 作用: AJAX提交过程的共同函数。
 * 		在大发原来传参的基础上，针对非诚意留言增加了一个is_grasp的参数以及对应的参数处理。
 * 		其实大发原版也是基于WP的comment的。
 * 来源: 破袜子根据大发的代码修改
 */
function bluefly_newcomment( $is_grasp ) {
	global $wpdb;
    $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
    $post = get_post($comment_post_ID);
    if ( empty($post->comment_status) ) {
        do_action('comment_id_not_found', $comment_post_ID);
        ajax_comment_err('评论状态不正确');
    }
    $status = get_post_status($post);
    $status_obj = get_post_status_object($status);
    if ( !comments_open($comment_post_ID) ) {
        do_action('comment_closed', $comment_post_ID);
        ajax_comment_err('评论已关闭');
    } elseif ( 'trash' == $status ) {
        do_action('comment_on_trash', $comment_post_ID);
        ajax_comment_err('评论状态不正确');
    } elseif ( !$status_obj->public && !$status_obj->private ) {
        do_action('comment_on_draft', $comment_post_ID);
        ajax_comment_err('评论状态不正确');
    } elseif ( post_password_required($comment_post_ID) ) {
        do_action('comment_on_password_protected', $comment_post_ID);
        ajax_comment_err('密码保护中');
    } else {
        do_action('pre_comment_on_post', $comment_post_ID);
    }
    $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
    $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
    $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
	$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
	$comment_type = '';

    $user = wp_get_current_user();
    if ( $user->exists() ) {
        if ( empty( $user->display_name ) )
            $user->display_name=$user->user_login;
        $comment_author       = esc_sql($user->display_name);
        $comment_author_email = esc_sql($user->user_email);
        $comment_author_url   = esc_sql($user->user_url);
        $user_ID              = esc_sql($user->ID);
        if ( current_user_can('unfiltered_html') ) {
            if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
                kses_remove_filters();
                kses_init_filters();
            }
        }
    } else {
        if ( get_option('comment_registration') || 'private' == $status )
            ajax_comment_err('对不起，只有登录用户才能评论。');
    }
	
    /*无诚意留言的判断标志*/
	if ( $is_grasp ) {
		$dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
		if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
		$dupe .= ") LIMIT 1";
		if ( $wpdb->get_var($dupe) ) {
			ajax_comment_err('说过话不能点路过哦！');
		}
		$comment_content      = "私は異議を唱えるできません".' 【'.$comment_author.'】';
		//自定义了一个type.
		$comment_type = 'senseless';
	}
	
    if ( get_option('require_name_email') && !$user->exists() ) {
        if ( 6 > strlen($comment_author_email) || '' == $comment_author )
            ajax_comment_err( '错误：请填写昵称和邮件地址。' );
        elseif ( !is_email($comment_author_email))
            ajax_comment_err( '错误：邮件格式不正确。' );
    }
    if ( '' == $comment_content )
        ajax_comment_err( '错误：请输入您的评论内容。' );
    $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
    if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
    $dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
    if ( $wpdb->get_var($dupe) ) {
        ajax_comment_err('请不要重复评论！');
    }
    if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
        $time_lastcomment = mysql2date('U', $lasttime, false);
        $time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
        $flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
        if ( $flood_die ) {
            ajax_comment_err('评论太频繁了，请稍后再按。');
        }
    }

    $comment_parent = (isset($_POST['comment_parent']) && !$is_grasp) ? absint($_POST['comment_parent']) : 0;
    $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

    $comment_id = wp_new_comment( $commentdata );


    $comment = get_comment($comment_id);
    do_action('set_comment_cookies', $comment, $user);
    $comment_depth = 1;
    $tmp_c = $comment;
    while($tmp_c->comment_parent != 0){
        $comment_depth++;
        $tmp_c = get_comment($tmp_c->comment_parent);
    }
    $GLOBALS['comment'] = $comment;
	//追加了一个email作为返回值。方便回调函数顺利取到头像。
	return $comment_author_email;
}

/**
 * 作用: 无诚意留言的回调显示
 * 来源: 破袜子原创
 */
function bluefly_additional_grasp_show($email) {
	?>
    <li class= "grasp sensele">
	<div class="comment-grasp vcard">
		<div class="comment-author vcard">
			<?php echo '<a class="x7" href="#">'.get_avatar( $email, $size = '32').'</a>';?>
		</div>
    </li>
	<?php
}

/**
 * 作用: 留言的回调显示
 * 来源: 破袜子原创
 */
function bluefly_additional_comment_show($email) {
	?>
    <li <?php comment_class(); ?>>
        <article class="comment-body hentry-bg">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar( $email, $size = '60')?>
                </div>
                <div class="comment-metadata">
                    <span><b class="fn"><?php echo get_comment_author_link(); ?></b>
					</span>
                </div>
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php echo '评论审核中。'; ?></p>
				<?php endif; ?>
            </footer>
            <div class="comment-content <?php echo $vcard_class;?>">
                <?php comment_text(); ?>
            </div>
        </article>		
    </li>
	<?php
}

/**
 * 作用: 评论提交后的回调函数，
 * 来源: 大发（bigfa）
 * URI: https://fatesinger.com/59
 */
function ajax_comment_callback(){
	$is_grasp = isset($_POST['grasp']) ? true : false;
	$comment_author_email ;
	if ( false==(bool)$is_grasp ) {
		$comment_author_email = bluefly_newcomment( false );
		bluefly_additional_comment_show($comment_author_email); 
	}
	else {
		$comment_author_email = bluefly_newcomment( true );
		bluefly_additional_grasp_show($comment_author_email); 
	}
    die();
}

/**
 * 作用: ajax效果时，弹出的错误窗口
 * 来源: 大发
 */
function ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

/**
 * 作用: 追加【已阅】按钮
 * 来源: 破袜子原创
 */
 
 //把按钮通过WP自带的钩子追加到默认的留言模板上。
 //【comment_form_defaults】是“提交留言”按钮，类型是submit。默认加到它的后面。
 //add_filter('comment_form_defaults' , 'add_senseless_btn', 40);
function add_senseless_btn( $defaults )
{
	if ( is_page() )
		return $defaults;
	//type定义成button，因为不提倡有两个submit。通过CSS使两个按钮看起来一致。
	$notice = '<input id="grasp" class="submit" type="button" value="路过" name="grasp">';
	$defaults['submit_button'] = $notice.$defaults['submit_button'];
	return $defaults;
}

/**
 * 作用: 无诚意留言列表.调用WP函数
 * 来源: 破袜子原创
 */
function bluefly_get_grasp_list()
{
	if ( !is_single() )
		return;
	?>
	<div class="no-order">
	<div class="assistive-text noselect"><i class="fa fa-arrow-down"></i> 看过但不讲话的人 <i class="fa fa-arrow-down"></i></div>
	<ol class="grasp-list">
	<?php
		//通过指定回调函数grasp_lists达到只显示头像的目的。
		wp_list_comments(array(
					'style'      => 'ol',
					'avatar_size'=> 32,
					'type'=>'senseless',
					'per_page' => 30 ,
					'callback'=>'grasp_lists',
					'reverse_top_level'=>false,
				));
	?>
	</ol>	
	</div><!-- .no-order -->
	<?php
}

/**
 * 作用: 无诚意头像列表.显示部分
 * 来源: 破袜子原创
 */
function grasp_lists($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'grasp' ); ?>>
	<div class="comment-grasp vcard">
			<?php echo '<a class="x7" href="'.get_comment_author_url().'" rel="external nofollow" >'. get_avatar( get_comment_author_email(), $args['avatar_size']).'</a>' ; ?>
	</div>
	<?php
}

/**
 * 作用: 默认comment_list的回调函数。指定该函数的目的一是使格式与ajaxcomment的格式统一，二是做一些CSS细节上的调整。
 * 来源: 破袜子原创
 */
function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 0==$comment->comment_parent ? 'parent' : '' ); ?>>
	<article id="div-comment-<?php comment_ID(); ?>" class="comment-body hentry-bg">
	<footer class="comment-meta">
		<div class="comment-author vcard">
			<?php /*gravatar头像。设成不显示头像后效果待测试*/
				if ( $comment->user_id != 0 )
					$vcard_class = "byposter" ;
				else
					$vcard_class = "visitor" ;
				$ava_arg_detail = array('class'=>$vcard_class);
				echo '<a href="'.get_comment_author_url().'" rel="external nofollow" >'.get_avatar( get_comment_author_email(), $args['avatar_size'],'','', $ava_arg_detail).'</a>'; 
			?>
		</div><!-- .comment-author -->

		<!-- 名字 /于XXX以后 [/回复XXX] [/编辑] -->
		<div class="comment-metadata">
			<span><b class="fn"><?php echo get_comment_author_link(); ?></b>
				<time datetime="<?php comment_time( 'c' ); ?>" class="comment-on">
					<?php  echo bluefly_rel_comment_date(); ?>
				</time>
			<?php $parent_comment_id = $comment->comment_parent ;
				  if ( $parent_comment_id > 0 ) {
					printf( '<span class="towhom"> @ <a href="%1s">%2s</a></span>', esc_url( get_comment_link( $parent_comment_id ) ), get_comment_author($parent_comment_id) );
					} ?>
			<?php edit_comment_link( ' 编辑', '<span class="edit-link">', '</span>' ); ?>
			<?php
			comment_reply_link( array_merge( $args, array(
				'add_below' => 'div-comment',
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'reply_text'=> '@TA',
				'before'	=> '<em class="reply">',
				'after'	=> '</em>',
			) ) );
			?>
			</span>
		</div><!-- .comment-metadata -->

		<?php if ( '0' == $comment->comment_approved ) : ?>
		<p class="comment-awaiting-moderation"><?php echo '评论审核中。'; ?></p>
		<?php endif; ?>
	</footer><!-- .comment-meta -->

	<div class="comment-content <?php echo $vcard_class;?>">
		<?php comment_text(); ?>
	</div><!-- .comment-content -->

</article><!-- .comment-body -->
<?php
}