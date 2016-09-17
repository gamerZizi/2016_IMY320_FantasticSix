<?php
/**
Template Name: Left Sidebar
 *
 * This template is used to display content featuring a left sidebar.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

get_header(); ?>

<?php $thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'giving-featured-large' ) : false; ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="page-<?php the_ID(); ?>">

	<?php if ( has_post_thumbnail() ) { ?>
		<div class="feature-img page-banner" <?php if ( ! empty( $thumb ) ) { ?> style="background-image: url(<?php echo esc_url( $thumb[0] ); ?>);" <?php } ?>>
			<h1 class="headline img-headline"><?php the_title(); ?></h1>
			<?php the_post_thumbnail( 'giving-featured-large' ); ?>
		</div>
	<?php } ?>

	<!-- BEGIN .row -->
	<div class="row">

		<!-- BEGIN .content -->
		<div class="content">

			<!-- BEGIN .four columns -->
			<div class="four columns">

				<?php get_sidebar( 'left' ); ?>

			<!-- END .four columns -->
			</div>

			<!-- BEGIN .twelve columns -->
			<div class="twelve columns">

				<!-- BEGIN .postarea middle -->
				<div class="postarea middle">

					<?php get_template_part( 'loop', 'page' ); ?>

				<!-- END .postarea middle -->
				</div>

			<!-- END .twelve columns -->
			</div>

		<!-- END .content -->
		</div>

	<!-- END .row -->
	</div>

<!-- END .post class -->
</div>

<?php get_footer(); ?>
