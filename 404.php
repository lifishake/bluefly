<?php
/**
 * 404
 *
 * @package bluefly
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<div class="svg-container svg-block page-header-svg">
					<?php bluefly_svg_1(); ?>
				</div>				
				<header class="page-header">
					<h1 class="page-title"><?php _e( '啊哦，页面没找到。', 'bluefly' ); ?></h1>
				</header><!-- .page-header -->
				<div class="svg-container svg-block page-header-svg">
					<?php echo bluefly_svg_3(); ?>
				</div>	

				<div class="page-content">
					<p><?php _e( '好像出错了。搜索一下试试？', 'bluefly' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
