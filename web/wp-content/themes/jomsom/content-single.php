<?php
/**
 * The template used for displaying post content in single.php
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h2 class="entry-title"><?php the_title(); ?></h2>
		<div class="entry-meta"><?php jomsom_entry_meta(); ?></div>
	</header><!-- .entry-header -->

	<?php
	/**
	 * jomsom_before_post_container hook
	 *
	 * @hooked jomsom_single_content_image - 10
	 */
	do_action( 'jomsom_before_post_container' ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links"><span class="pages">' . __( 'Pages:', 'jomsom' ) . '</span>',
				'after'  => '</div>',
				'link_before' 	=> '<span>',
                'link_after'   	=> '</span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php jomsom_tag_category(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post -->