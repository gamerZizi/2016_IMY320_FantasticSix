<?php
/**
Template Name: Blog
 *
 * This template is used to display blog posts.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

get_header(); ?>

<?php $thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'giving-featured-large' ) : false; ?>

<?php if ( has_post_thumbnail() ) { ?>
	<div class="feature-img page-banner" <?php if ( ! empty( $thumb ) ) { ?> style="background-image: url(<?php echo esc_url( $thumb[0] ); ?>);" <?php } ?>>
		<h1 class="headline img-headline"><?php the_title(); ?></h1>
		<?php the_post_thumbnail( 'giving-featured-large' ); ?>
	</div>
<?php } ?>

<!-- BEGIN .row -->
<div class="row content-row">

	<!-- BEGIN .content -->
	<div class="content no-bg clearfix">

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>

			<!-- BEGIN .eleven columns -->
			<div class="eleven columns">

				<?php get_template_part( 'loop', 'blog' ); ?>

			<!-- END .eleven columns -->
			</div>

			<!-- BEGIN .five columns -->
			<div class="five columns">

				<?php get_sidebar( 'blog' ); ?>

			<!-- END .five columns -->
			</div>

		<?php } else { ?>

			<!-- BEGIN .sixteen columns -->
			<div class="sixteen columns">

				<?php get_template_part( 'loop', 'blog' ); ?>

			<!-- END .sixteen columns -->
			</div>

		<?php } ?>

	<!-- END .content -->
	</div>

<!-- END .row -->
</div>

<?php get_footer(); ?>
