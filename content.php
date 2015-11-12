<?php
/**
 * 带缩略图的内容
 * @package bluefly
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 
	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'hide_index_feat_image' ) != 1 ) ) : ?>
		<!-- div class="entry-thumb" -->
			<?php 
				$post_image_id = get_post_thumbnail_id($post_to_use->ID);
				if ($post_image_id) {
					$thumbnail = wp_get_attachment_image_src( $post_image_id, 'bluefly-entry-thumb', false);
					if ($thumbnail) (string)$thumbnail = $thumbnail[0];
			} ?>
		<!-- /div -->
	<?php endif; ?>
	<div class="view" <?php if($thumbnail){echo 'style= "background-image:url('.$thumbnail.')" ';} ?>>
	<?php if(!$thumbnail): ?>
	<div class="content-bg-<?php echo get_post_format();?>" ></div>
	<?php endif; ?>
	<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	<p class="entry-content">
		<?php the_excerpt(); ?>
	</p>
	</div><!-- content-block -->
</article><!-- #post-## -->