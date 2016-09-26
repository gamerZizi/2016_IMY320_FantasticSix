<?php
/**
 * This template is used to display the homepage middle section.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

$thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'giving-featured-small' ) : false; ?>

<!-- BEGIN .content -->
<div class="content radius-full shadow">

	<?php if ( has_post_thumbnail() ) { ?>
		<div class="feature-img page-banner" <?php if ( ! empty( $thumb ) ) { ?> style="background-image: url(<?php echo esc_url( $thumb[0] ); ?>);" <?php } ?>>
			<?php the_post_thumbnail( 'giving-featured-small' ); ?>
		</div>
	<?php } ?>

	<!-- BEGIN .information -->
	<div class="information">

		<h2 class="headline text-center"><?php the_title(); ?></h2>

		<?php the_excerpt(); ?>

		<a class="button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Learn More', 'givingpress-lite' ); ?></a>

	<!-- END .information -->
	</div>

<!-- END .content -->
</div>
