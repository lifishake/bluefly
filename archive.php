<?php
/**
 * archive
 * @package bluefly
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header sec-bg">			
				<?php
					the_archive_title( '<h1 class="page-title opp-text">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			<div class="archive-meta">
				<?php echo bluefly_breadcrumb();?>
			</div>
			<div id="ob-grid" class="grid-layout">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>
			<?php 
				if ( get_theme_mod('infinite_archive') == 1 ) {
					the_posts_navigation();
				}
				else {
					the_posts_pagination( array(
						'prev_text'          => '&laquo',
						'next_text'          => '&raquo',
						'mid_size'			=> 2,
						'screen_reader_text ' => '文章导航',
					) );
				}
			?>
			</div>		

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
