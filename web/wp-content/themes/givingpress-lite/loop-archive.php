<?php
/**
 * This template displays the archive loop.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<!-- BEGIN .post class -->
<div <?php post_class( 'archive-holder' ); ?> id="post-<?php the_ID(); ?>">

	<h2 class="headline small"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

	<div class="post-date">
		<p class="align-left"><i class="fa fa-clock-o"></i>
			<?php if ( get_the_modified_time() != get_the_time() ) { ?>
				<?php esc_html_e( 'Updated on', 'givingpress-lite' ); ?> <?php the_modified_date( esc_html__( 'F j, Y', 'givingpress-lite' ) ); ?>
			<?php } else { ?>
				<?php esc_html_e( 'Posted on', 'givingpress-lite' ); ?> <?php the_time( esc_html__( 'F j, Y', 'givingpress-lite' ) ); ?>
			<?php } ?>
			<?php esc_html_e( 'by', 'givingpress-lite' ); ?> <?php esc_url( the_author_posts_link() ); ?>
		</p>
		<p class="align-right"><i class="fa fa-comment"></i> <a href="<?php the_permalink(); ?>#comments"><?php comments_number( esc_html__( 'Leave a Comment', 'givingpress-lite' ), esc_html__( '1 Comment', 'givingpress-lite' ), '% Comments' ); ?></a></p>
	</div>

	<?php if ( has_post_thumbnail() ) { ?>
		<a class="feature-img" href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'givingpress-lite' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_post_thumbnail( 'giving-featured-large' ); ?></a>
	<?php } ?>

	<?php the_excerpt(); ?>

	<?php $tag_list = get_the_tag_list( esc_html__( ',', 'givingpress-lite' ) ); if ( ! empty( $tag_list ) || has_category() ) { ?>

		<!-- BEGIN .post-meta -->
		<div class="post-meta">

			<p><i class="fa fa-bars"></i> <?php esc_html_e( 'Category:', 'givingpress-lite' ); ?> <?php the_category( ', ' ); ?> <?php if ( ! empty( $tag_list ) ) { ?><i class="fa fa-tags"></i> <?php esc_html_e( 'Tags:', 'givingpress-lite' ); ?> <?php the_tags( '' ); ?><?php } ?></p>

		<!-- END .post-meta -->
		</div>

	<?php } ?>

<!-- END .post class -->
</div>

<?php endwhile; ?>

	<?php the_posts_pagination( array(
	    'prev_text' => esc_attr__( '&laquo;', 'givingpress-lite' ),
	    'next_text' => esc_attr__( '&raquo;', 'givingpress-lite' ),
	) ); ?>

<?php else : ?>

	<?php get_template_part( 'content/content', 'none' ); ?>

<?php endif; ?>
