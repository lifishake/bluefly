<?php
/**
 * 单页文章的显示
 * @package bluefly
 */
?>
	
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<?php bluefly_posted_on(); /*符合bootstrap的time信息,相对时间*/
				  echo bluefly_breadcrumb(); /*面包屑导航*/
			?>
		</div><!-- .entry-meta -->
		
	</header><!-- .entry-header -->
	
	<?php if ( has_post_thumbnail() ) : ?>
	
		<div class="single-thumb">
			<?php the_post_thumbnail('bluefly-single-thumb'); ?>
			<?php //日期格式参考: https://codex.wordpress.org/Formatting_Date_and_Time ?>
			<span class="single-thumb-time noselect"><?php the_date('y-m-d h:i a'); /*特色图片上的伪照片日期*/ ?></span>
		</div><!-- .single-thumb -->	
	<?php endif; ?>		

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php bluefly_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
