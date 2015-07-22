<?php
/**
 * archive
 *
 * @package bluefly
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<div class="svg-container svg-block page-header-svg">
				<?php bluefly_svg_1(); ?>
			</div>
			<header class="page-header">			
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			<div class="svg-container svg-block page-header-svg">
				<?php echo bluefly_svg_3(); ?>
			</div>				

			<div id="ob-grid" class="grid-layout">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>
			<?php the_posts_navigation(); ?>
			</div>		

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
