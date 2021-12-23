<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>

<?php if (is_single()): ?>
	<script>
		window.onload = function() {
			document.querySelectorAll('pre').forEach((block) => {
				hljs.highlightBlock(block);
			});
		};
	</script>
<?php endif; ?>

</body>
</html>
