<?php
/**
 * The search template for our theme.
 *
 * This template is used to display search results.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

get_header(); ?>

<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<!-- BEGIN .row -->
	<div class="row">

		<!-- BEGIN .content -->
		<div class="content">

		<?php if ( is_active_sidebar( 'sidebar-1' ) && is_active_sidebar( 'sidebar-left' ) ) { ?>

			<!-- BEGIN .three columns -->
			<div class="three columns">

				<?php get_sidebar( 'left' ); ?>

			<!-- END .three columns -->
			</div>

			<!-- BEGIN .eight columns -->
			<div class="eight columns">

				<!-- BEGIN .postarea -->
				<div class="postarea middle clearfix">

					<h2 class="headline archive-headline">
						<?php printf( __( 'Search: %s', 'givingpress-lite' ), get_search_query() ); ?>
					</h2>

					<?php get_template_part( 'loop', 'archive' ); ?>

				<!-- END .postarea -->
				</div>

			<!-- END .eight columns -->
			</div>

			<!-- BEGIN .five columns -->
			<div class="five columns">

				<div class="sidebar">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</div>

			<!-- END .five columns -->
			</div>

		<?php } elseif ( is_active_sidebar( 'sidebar-left' ) ) { ?>

			<!-- BEGIN .three columns -->
			<div class="three columns">

				<?php get_sidebar( 'left' ); ?>

			<!-- END .three columns -->
			</div>

			<!-- BEGIN .thirteen columns -->
			<div class="thirteen columns">

				<!-- BEGIN .postarea -->
				<div class="postarea right clearfix">

					<h2 class="headline archive-headline">
						<?php printf( __( 'Search: %s', 'givingpress-lite' ), get_search_query() ); ?>
					</h2>

					<?php get_template_part( 'loop', 'archive' ); ?>

				<!-- END .postarea -->
				</div>

			<!-- END .thirteen columns -->
			</div>

		<?php } elseif ( is_active_sidebar( 'sidebar-1' ) ) { ?>

			<!-- BEGIN .eleven columns -->
			<div class="eleven columns">

				<!-- BEGIN .postarea -->
				<div class="postarea clearfix">

					<h2 class="headline archive-headline">
						<?php printf( __( 'Search: %s', 'givingpress-lite' ), get_search_query() ); ?>
					</h2>

					<?php get_template_part( 'loop', 'archive' ); ?>

				<!-- END .postarea -->
				</div>

			<!-- END .eleven columns -->
			</div>

			<!-- BEGIN .five columns -->
			<div class="five columns">

				<div class="sidebar">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</div>

			<!-- END .five columns -->
			</div>

		<?php } else { ?>

			<!-- BEGIN .sixteen columns -->
			<div class="sixteen columns">

				<!-- BEGIN .postarea full -->
				<div class="postarea full clearfix">

					<h2 class="headline archive-headline">
						<?php printf( __( 'Search: %s', 'givingpress-lite' ), get_search_query() ); ?>
					</h2>

					<?php get_template_part( 'loop', 'archive' ); ?>

				<!-- END .postarea full -->
				</div>

			<!-- END .sixteen columns -->
			</div>

		<?php } ?>

		<!-- END .content -->
		</div>

	<!-- END .row -->
	</div>

<!-- END .post class -->
</div>

<?php get_footer(); ?>
