<?php
/**
 * @package bluefly
 */
?>
	
<div class="svg-container single-post-svg svg-block">
	<?php bluefly_svg_1(); ?>
</div>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php bluefly_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'post_feat_image' ) != 1 ) ) : ?>
		<div class="single-thumb">
			<?php the_post_thumbnail('bluefly-single-thumb'); ?>
		</div>	
	<?php endif; ?>		

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php bluefly_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
<div class="svg-container single-post-svg single-svg-bottom svg-block">
	<?php bluefly_svg_4(); ?>
</div>