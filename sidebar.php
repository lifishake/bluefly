<?php
/**
 * 侧边栏
 */
?>

<div id="secondary" class="widget-area" role="complementary">



	<nav id="site-navigation" class="main-navigation" role="navigation">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
	</nav><!-- #site-navigation -->
	<nav class="sidebar-nav"></nav>



	<?php if ( is_active_sidebar( 'sidebar-bluefly' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-bluefly' ); ?>
	<?php endif; ?>

</div><!-- #secondary -->
