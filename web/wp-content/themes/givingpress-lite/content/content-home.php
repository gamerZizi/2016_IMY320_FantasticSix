<?php
/**
 * This template is displays the home content.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?>

<?php if ( get_theme_mod( 'givingpress_lite_donation_tagline', 'Donations Are Welcome' ) && '' != get_theme_mod( 'givingpress_lite_donation_tagline', 'Donations Are Welcome' ) ) { ?>

<!-- BEGIN .featured-donation -->
<section class="featured-donation shadow">

	<!-- BEGIN .row -->
	<div class="row">

		<!-- BEGIN .content -->
		<div class="content donation">

			<!-- BEGIN .holder -->
			<div class="holder">

				<?php if ( get_theme_mod( 'givingpress_lite_donation_link', '#' ) && '' != get_theme_mod( 'givingpress_lite_donation_link', '#' ) ) { ?>

				<div class="twelve columns">
					<h2><?php echo get_theme_mod( 'givingpress_lite_donation_tagline', 'Donations Are Welcome' ); ?></h2>
					<p class="description"><?php echo get_theme_mod( 'givingpress_lite_donation_description', 'Enter a brief message about accepting donations for your cause. Edit the content in this section within the WordPress Customizer.' ); ?></p>
				</div>
				

				<div class="four columns vertical-center">
					<div class="align-right">
						<a class="button large" href="<?php echo get_theme_mod( 'givingpress_lite_donation_link', '#' ); ?>"><span class="btn-holder"><?php echo get_theme_mod( 'givingpress_lite_donation_link_text', 'Donate' ); ?></span></a>
					</div>
				</div>

				<?php } else { ?>

				<div class="text-center">
					<h2><?php echo get_theme_mod( 'givingpress_lite_donation_tagline', 'Donations Are Welcome' ); ?></h2>
					<p class="description"><?php echo get_theme_mod( 'givingpress_lite_donation_description', 'Enter a brief message about accepting donations for your cause. Edit the content in this section within the WordPress Customizer.' ); ?></p>
				</div>

				<?php } ?>

			<!-- BEGIN .holder -->
			</div>

		<!-- END .content -->
		</div>

	<!-- END .row -->
	</div>

<!-- END .featured-donation -->
</section>

<?php } ?>

<!-- Featured Pages Small Section -->
<?php if ( get_theme_mod( 'givingpress_lite_page_one' ) && get_theme_mod( 'givingpress_lite_page_two' ) && get_theme_mod( 'givingpress_lite_page_three' ) ) { ?>

<!-- BEGIN .featured-pages -->
<section class="featured-pages">

	<!-- BEGIN .row -->
	<div class="row">

		<!-- BEGIN .content -->
		<div class="content no-bg">

			<div class="holder third">
				<?php $recent = new WP_Query( 'page_id='.get_theme_mod( 'givingpress_lite_page_one' ) );
				while ( $recent->have_posts() ) : $recent->the_post(); ?>
									<?php get_template_part( 'content/home-page', 'small' ); ?>
								<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<div class="holder third">
				<?php $recent = new WP_Query( 'page_id='.get_theme_mod( 'givingpress_lite_page_two' ) );
				while ( $recent->have_posts() ) : $recent->the_post(); ?>
									<?php get_template_part( 'content/home-page', 'small' ); ?>
								<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

			<div class="holder third">
				<?php $recent = new WP_Query( 'page_id='.get_theme_mod( 'givingpress_lite_page_three' ) );
				while ( $recent->have_posts() ) : $recent->the_post(); ?>
									<?php get_template_part( 'content/home-page', 'small' ); ?>
								<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

		<!-- END .content -->
		</div>

	<!-- END .row -->
	</div>

<!-- END .featured-pages -->
</section>

<?php } ?>

<!-- Featured Page Wide Section -->
<?php if ( get_theme_mod( 'givingpress_lite_page_four' ) ) { ?>

	<?php $recent = new WP_Query( 'page_id='.get_theme_mod( 'givingpress_lite_page_four' ) );
	while ( $recent->have_posts() ) : $recent->the_post(); ?>
		<?php $thumb = ( get_the_post_thumbnail() ) ? wp_get_attachment_image_src( get_post_thumbnail_id(), 'giving-featured-large' ) : false; ?>
		<?php $has_content = get_the_content(); ?>

		<!-- BEGIN .featured-page -->
		<section class="featured-page background-scroll"<?php if ( has_post_thumbnail() ) { ?> style="background-image: url(<?php echo esc_url( $thumb[0] ); ?>);"<?php } ?>>

			<!-- BEGIN .row -->
			<div class="row">

				<?php get_template_part( 'content/home-page', 'wide' ); ?>
				<?php if ( has_post_thumbnail() ) { ?><span class="img-shade"></span><?php } ?>

			<!-- END .row -->
			</div>

		<!-- END .featured-page -->
		</section>

		<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>

<?php } ?>

<?php if ( '' == get_theme_mod( 'givingpress_lite_page_one' ) && '' == get_theme_mod( 'givingpress_lite_page_four' ) || '' == get_theme_mod( 'givingpress_lite_page_two' ) && '' == get_theme_mod( 'givingpress_lite_page_four' ) || '' == get_theme_mod( 'givingpress_lite_page_three' ) && '' == get_theme_mod( 'givingpress_lite_page_four' ) ) { ?>

<!-- BEGIN .set-options -->
<section class="set-options">

	<!-- BEGIN .row -->
	<div class="row">

		<!-- BEGIN .content -->
		<div class="content not-set radius-full">

			<!-- BEGIN .postarea -->
			<div class="postarea full">

				<?php get_template_part( 'content/content', 'none' ); ?>

			<!-- END .postarea -->
			</div>

		<!-- END .content -->
		</div>

	<!-- END .row -->
	</div>

<!-- END .set-options -->
</section>

<?php } ?>
