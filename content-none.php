<?php
/**
 * 没有文章内容的
 * @package bluefly
 */
?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php _e( '没找到', 'bluefly' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( '准备来一发吗？ <a href="%1$s">点这里</a>。', 'bluefly' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	

		<?php else : ?>

			<<p><?php _e( '没找到，换个关键词试试。', 'bluefly' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
