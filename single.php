<?php
/**
 * 单页文章
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>
			
			<?php do_action('bluefly_after_single_post'); ?>

			<?php the_post_navigation(array('prev_text'=>'<i class="fa fa-arrow-left"></i> %title','next_text'=>'%title <i class="fa fa-arrow-right"></i>','screen_reader_text'=>'文章导航')); ?>
			
			<?php
				//如果允许评论就加载评论
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
			<?php do_action('bluefly_after_comment'); ?>
		<?php endwhile; // end of the loop. ?>
	
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
