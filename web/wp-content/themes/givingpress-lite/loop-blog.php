<?php
/**
 * This template displays the blog loop.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?>

<?php $wp_query = new WP_Query( array( 'paged' => $paged, 'suppress_filters' => 0 ) ); ?>
<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

<?php $thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'giving-featured-medium' ) : false; ?>

<!-- BEGIN .blog-holder -->
<div class="blog-holder shadow radius-full">

	<?php if ( has_post_thumbnail() ) { ?>
		<a class="feature-img post-banner" <?php if ( ! empty( $thumb ) ) { ?> style="background-image: url(<?php echo esc_url( $thumb[0] ); ?>);" <?php } ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'givingpress-lite' ), the_title_attribute( 'echo=0' ) ) ); ?>">
			<?php the_post_thumbnail( 'giving-featured-medium' ); ?>
		</a>
	<?php } ?>

	<!-- BEGIN .postarea -->
	<div class="postarea">

		<!-- BEGIN .post class -->
		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

			<div class="post-date">
				<p><i class="fa fa-clock-o"></i> <?php givingpress_lite_posted_on(); ?> <span class="align-right"><i class="fa fa-comment"></i> <a class="scroll" href="<?php the_permalink(); ?>#comments"><?php comments_number( esc_html__( 'Leave a Comment', 'givingpress-lite' ), esc_html__( '1 Comment', 'givingpress-lite' ), esc_html__( '% Comments', 'givingpress-lite' ) ); ?></a></span></p>
			</div>

			<h2 class="headline"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<!-- BEGIN .article -->
			<div class="article">

				<?php the_content( esc_attr__( 'Read More', 'givingpress-lite' ) ); ?>

			<!-- END .article -->
			</div>

		<!-- END .post class -->
		</div>

	<!-- END .postarea -->
	</div>

<!-- END .blog-holder -->
</div>

<?php endwhile; ?>

	<?php if ( $wp_query->max_num_pages > 1 ) { ?>

		<?php the_posts_pagination( array(
		    'prev_text' => esc_attr__( '&laquo;', 'givingpress-lite' ),
		    'next_text' => esc_attr__( '&raquo;', 'givingpress-lite' ),
		) ); ?>

	<?php } ?>

<?php else : ?>

<!-- BEGIN .blog-holder -->
<div class="blog-holder shadow radius-full">

	<!-- BEGIN .postarea -->
	<div class="postarea">

		<?php get_template_part( 'content/content', 'none' ); ?>

	<!-- END .postarea -->
	</div>

<!-- END .blog-holder -->
</div>

<?php endif; ?>
