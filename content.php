<?php
/**
 * 带缩略图的内容
 * @package bluefly
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("view view-fifth"); ?>>
 
	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'hide_index_feat_image' ) != 1 ) ) : ?>
			<?php the_post_thumbnail('bluefly-entry-thumb'); ?>
	<?php else: /*根据不同的format,显示图标*/?>
			<div class="content-bg-<?php echo get_post_format();?>" ></div>
	<?php endif; ?>	
	<div class="mask">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	
		<p class="entry-content">
			<?php the_excerpt(); ?>
		</p><!-- .entry-content -->
	</div><!-- .mask -->
</article><!-- #post-## -->