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
			<?php bluefly_posted_on(); ?>
			<?php echo bluefly_breadcrumb(); /*自定义面包屑*/ ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="single-thumb">
			<?php the_post_thumbnail('bluefly-single-thumb'); ?>
		</div>	
	<?php endif; ?>		

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php bluefly_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
