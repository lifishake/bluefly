<?php
/**
 * search结果
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header sec-bg">
				<h1 class="page-title opp-text"><?php printf( '搜索结果:%s', '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php while ( have_posts() ) : the_post(); ?>

				<?php /* 使用content-serch */
					get_template_part( 'content', 'search' );
				?>

			<?php endwhile; ?>

			<?php the_posts_pagination( array(
						'prev_text'          => '&laquo;',
						'next_text'          => '&raquo;',
						'mid_size'			=> 2,
						'screen_reader_text ' => '文章导航',
					) ); ?>

		<?php else : ?>
			
			<?php /* 使用content-none */
				get_template_part( 'content', 'none' ); 
			?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
