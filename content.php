<?php
/**
 * @package bluefly
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("view view-fifth"); ?>>

 
	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'hide_index_feat_image' ) != 1 ) ) : ?>
			<?php the_post_thumbnail('bluefly-entry-thumb'); ?>
	<?php else: ?>
			<img src="<?php echo get_template_directory_uri() . '/images/default_thumb.png'; ?>" alt="nopic"></img>
	<?php endif; ?>	
	<div class="mask">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	
		<p class="entry-content">
			<?php the_excerpt(); ?>
		</p><!-- .entry-content -->
		<a href="<?php the_permalink(); ?>"><?php echo __('继续阅读 &hellip;','bluefly'); ?></a>
	</div><!-- .mask -->
</article><!-- #post-## -->