<?php
/**
 * 没有文章内容的
 * @package bluefly
 */
?>

<section class="no-results not-found">
	<header class="page-header sec-bg">
		<h1 class="page-title opp-text"><?php echo '没找到'; ?></h1>
	</header><!-- .page-header -->

	<div class="page-content opp-text">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( '准备来一发吗？ <a href="%1$s">点这里</a>。', esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	

		<?php else : ?>

			<p><?php '没找到，换个关键词试试。'; ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
