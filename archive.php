<?php
/**
 * archive
 * @package bluefly
 */

get_header(); ?>

	<?php	if (!is_category() && !is_date() && !is_tag() ){
		//只支持category/tag/date的archive.其余的重定向到404
		//来源:http://wordpress.stackexchange.com/questions/24891/redirect-restricted-page-to-404
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 ); exit();
	} ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header sec-bg">			
				<?php
					bluefly_archive_title();
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			<div class="archive-meta">
				<?php echo bluefly_breadcrumb();?>
			</div><!-- .archive-meta -->
			<div id="ob-grid" class="grid-layout">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>
			</div><!-- #ob-grid -->
			<?php 
				if ( get_theme_mod('infinite_archive') == 1 ) {
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
