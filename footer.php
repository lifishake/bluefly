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
<div class="floattop opp-text"><i class="fa fa-hand-o-up"></i></div>
<?php if ( is_singular() && ( comments_open() || get_comments_number() ) ) { ?>
<div class="floatcomment opp-text"><i class="fa fa-comments"></i></div>
<?php } ?>
<?php wp_footer(); ?>

</body>
</html>
