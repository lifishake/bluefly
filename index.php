<?php
/**
 * 主模板.分页和找不到对应页的时候调用
 * @package bluefly
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<div id="ob-grid" class="grid-layout">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
					/*其实只有两种。search的时候不显示缩略图*/
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>
			</div>
			<?php /*the_posts_navigation(); */
					if ( get_theme_mod('infinite_index') == 1 ) {
						the_posts_navigation();
					}
					else {
						the_posts_pagination( array(
							'prev_text'          => '&laquo;',
							'next_text'          => '&raquo;',
							'mid_size'			=> 2,
							'screen_reader_text ' => '文章导航',
						) );
					}
			?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
