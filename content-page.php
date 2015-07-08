<?php
/**
 * page
 *
 * @package bluefly
 */
?>

<div class="svg-container single-post-svg svg-block">
	<?php bluefly_svg_1(); ?>
</div>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( __( '编辑', 'bluefly' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
<div class="svg-container single-post-svg single-svg-bottom svg-block">
	<?php bluefly_svg_3(); ?>
</div>
