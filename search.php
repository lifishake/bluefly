<?php
/**
 * search结果
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header sec-bg">
				<h1 class="page-title opp-text"><?php printf( '【%s】的搜索结果', '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php while ( have_posts() ) : the_post(); ?>

				<?php /* 使用content-serch */
					get_template_part( 'content', 'search' );
				?>

			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>
			
			<?php /* 使用content-serch */
				get_template_part( 'content', 'none' ); 
			?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
