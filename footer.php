<?php
/**
 * footer.
 */
?>

		</div>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer sec-bg" role="contentinfo">
		<div class="site-info container">
			<?php do_action('bluefly_footer'); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php if ( is_singular() && ( comments_open() || get_comments_number() ) ) { ?>
    <div class="floatcomment secondary_color"><i class="fa fa-comments"></i></div>
<?php } ?>
<div class="sidebar-toggle secondary_color"><i class="fa fa-bars"></i></div>
<div class="floathome secondary_color"><i class="fa fa-home"></i></div>
<div class="floattop secondary_color"><i class="fa fa-chevron-up"></i></div>


<?php wp_footer(); ?>

</body>
</html>
