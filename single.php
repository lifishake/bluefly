<?php
/**
 * 单页文章
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php the_post_navigation(array('prev_text'=>'<i class="fa fa-arrow-left"></i> %title','next_text'=>'%title <i class="fa fa-arrow-right"></i>','screen_reader_text'=>'文章导航')); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
			<?php do_action('bluefly_after_single_post'); ?>
		<?php endwhile; // end of the loop. ?>
		<?php do_action('bluefly_after_single_post_loop'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
