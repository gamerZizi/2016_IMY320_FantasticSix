<?php
/**
 * The Template for displaying all single posts
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

		<?php
			/**
			 * jomsom_after_post hook
			 *
			 * @hooked jomsom_post_navigation - 10
			 */
			do_action( 'jomsom_after_post' );

			/**
			 * jomsom_comment_section hook
			 *
			 * @hooked jomsom_get_comment_section - 10
			 */
			do_action( 'jomsom_comment_section' );
		?>
	<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>