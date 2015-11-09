<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package Blasdoise
 * @subpackage Fairy
 * @since Fairy 1.0
 */
?>

	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php
				/**
				 * Fires before the Fairy footer text for footer customization.
				 *
				 * @since Fairy 1.0
				 */
				do_action( 'fairy_credits' );
			?>
			<a href="<?php echo esc_url( __( 'http://blasdoise.com/', 'fairy' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'fairy' ), 'Blasdoise' ); ?></a>
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->

</div><!-- .site -->

<?php bd_footer(); ?>

</body>
</html>
