<?php
/**
 * 404 
 * @package bluefly
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
		
				<header class="page-header sec-bg">
					<h1 class="page-title opp-text"><?php echo '啊哦，页面没找到。'; ?></h1>
				</header><!-- .page-header -->

				<div class="page-content opp-text">
					<p><?php echo '好像出错了。搜索一下试试？'; ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
