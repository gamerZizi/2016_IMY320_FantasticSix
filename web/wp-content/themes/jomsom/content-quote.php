<?php
/**
 * The default template for displaying quote post format
 *
 * Used for both single and index/archive/search
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */
?>

<article id="post post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * jomsom_before_entry_container hook
	 *
	 * @hooked jomsom_archive_content_image - 10
	 */
	do_action( 'jomsom_before_entry_container' ); ?>

	<div class="text-holder">
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php jomsom_entry_meta(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php
		$options = jomsom_get_theme_options();

		if ( is_search() ) : // Only display Excerpts for Search and if 'full-content' is not selected ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		<?php else : ?>
			<div class="entry-content">
				<div class="blockquote-holder">
					<?php the_content(); ?>
					<?php
						wp_link_pages( array(
							'before' => '<div class="page-links"><span class="pages">' . __( 'Pages:', 'jomsom' ) . '</span>',
							'after'  => '</div>',
							'link_before' 	=> '<span>',
		                    'link_after'   	=> '</span>',
						) );
					?>
				</div><!-- .video-content -->
			</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-footer">
			<?php jomsom_tag_category(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .text-holder -->
</article><!-- #post -->