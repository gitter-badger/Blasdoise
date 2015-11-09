<?php
/**
 * @package Blasdoise
 * @subpackage Theme_Compat
 * @deprecated 1.0
 *
 * This file is here for Backwards compatibility with old themes and will be removed in a future version
 *
 */
_deprecated_file( sprintf( __( 'Theme without %1$s' ), basename(__FILE__) ), '1.0', null, sprintf( __('Please include a %1$s template in your theme.'), basename(__FILE__) ) );
?>

<hr />
<div id="footer" role="contentinfo">
<!-- If you'd like to support Blasdoise, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
	<p>
		<?php printf(__('%1$s is proudly powered by %2$s'), get_bloginfo('name'),
		'<a href="http://blasdoise.com/">Blasdoise</a>'); ?>
		<br /><?php printf(__('%1$s and %2$s.'), '<a href="' . get_bloginfo('rss2_url') . '">' . __('Entries (RSS)') . '</a>', '<a href="' . get_bloginfo('comments_rss2_url') . '">' . __('Comments (RSS)') . '</a>'); ?>
		<!-- <?php printf(__('%d queries. %s seconds.'), get_num_queries(), timer_stop(0, 3)); ?> -->
	</p>
</div>
</div>

<?php /* "Just what do you think you're doing Dave?" */ ?>

		<?php bd_footer(); ?>
</body>
</html>
