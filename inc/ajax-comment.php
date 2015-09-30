<?php 
add_action('wp_ajax_nopriv_ajax_comment', 'ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'ajax_comment_callback');

/**
 * 作用: AJAX提交过程的共同函数
 * 来源: 破袜子根据大发的代码修改
 * URI: 
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
    
	if ( $is_grasp ) {
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

    $comment_parent = (isset($_POST['comment_parent']) && $is_grasp) ? absint($_POST['comment_parent']) : 0;
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
	return $comment_author_email;
}

function bluefly_additional_grasp_show($email) {
	?>
    <li class= "grasp-list">
	<article class="comment-body hentry-bg">
	<footer class="comment-meta">
		<div class="comment-author vcard">
			<?php echo get_avatar( $email, $size = '32');?>
		</div>
	</footer>
	</article>
    </li>
	<?php
}

function bluefly_additional_comment_show($email) {
	?>
    <li <?php comment_class(); ?>>
        <article class="comment-body hentry-bg">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar( $email, $size = '60')?>
                    <cite class="fn">
                        <?php echo get_comment_author_link();?>
                    </cite>
                </div>
                <div class="comment-metadata">
                    <?php echo get_comment_date(); ?>
                </div>
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( '评论审核中。' ); ?></p>
				<?php endif; ?>
            </footer>
            <div class="comment-content">
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

function ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

/**
 * 作用: 追加【已阅】按钮
 * 来源: 破袜子原创
 * URL: http://pewae.com
 */
 
 //把按钮通过WP自带的钩子追加到默认的留言模板上。
 //【comment_form_defaults】是“提交留言”按钮，类型是submit。默认加到它的后面。
 add_filter('comment_form_defaults' , 'add_senseless_btn', 40);
function add_senseless_btn( $defaults )
{
	if ( is_page() )
		return $defaults;
	//type定义成button，因为不提倡有两个submit。通过CSS使两个按钮看起来一致。
	$notice = '<input id="grasp" class="submit" type="button" value="无言以对" name="grasp">';
	$defaults['submit_button'] = $defaults['submit_button'].$notice;
	return $defaults;
}

function bluefly_get_grasp_list()
{
	if ( !is_single() )
		return;
	?>
	<ol class="grasp-list">
	<?php
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
	<?php
}

function grasp_lists($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'grasp' ); ?>>
	<div class="comment-grasp vcard">
			<?php echo '<a href="'.get_comment_author_link().'" alt="'.get_comment_author().'" >'. get_avatar( get_comment_author_email(), $args['avatar_size']).'</a>' ; ?>
	</div>

	<?php
}

function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 0==$comment->comment_parent ? 'parent' : '' ); ?>>
	<article id="div-comment-<?php comment_ID(); ?>" class="comment-body hentry-bg">
	<footer class="comment-meta">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php printf( __( '%s <span class="says">says:</span>' ), sprintf( '<b class="fn">%s</b>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author -->

		<div class="comment-metadata">
			<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
				<time datetime="<?php comment_time( 'c' ); ?>">
					<?php  echo bluefly_rel_comment_date(); ?>
				</time>
			</a>
			<?php edit_comment_link( __( '编辑' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .comment-metadata -->

		<?php if ( '0' == $comment->comment_approved ) : ?>
		<p class="comment-awaiting-moderation"><?php _e( '评论审核中。' ); ?></p>
		<?php endif; ?>
	</footer><!-- .comment-meta -->

	<div class="comment-content">
		<?php comment_text(); ?>
	</div><!-- .comment-content -->

	<?php
	comment_reply_link( array_merge( $args, array(
		'add_below' => 'div-comment',
		'depth'     => $depth,
		'max_depth' => $args['max_depth'],
		'before'    => '<div class="reply">',
		'after'     => '</div>'
	) ) );
	?>
</article><!-- .comment-body -->
<?php
}