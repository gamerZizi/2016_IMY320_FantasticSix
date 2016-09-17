<?php
/**
 * This page template is used to display a 404 error message.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

get_header(); ?>

<!-- BEGIN .row -->
<div class="row">

	<!-- BEGIN .content -->
	<div class="content">

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>

			<!-- BEGIN .eleven columns -->
			<div class="eleven columns">

			<div class="postarea">
				<h1 class="headline"><?php esc_html_e( 'Not Found, Error 404', 'givingpress-lite' ); ?></h1>
				<p><?php esc_html_e( 'The page you are looking for no longer exists. Perhaps searching can help.', 'givingpress-lite' ); ?></p>
				<div class="no-result-search"><?php get_template_part( 'searchform' ); ?></div>
			</div>

			<!-- END .eleven columns -->
			</div>

			<!-- BEGIN .five columns -->
			<div class="five columns">

				<?php get_sidebar( 'page' ); ?>

			<!-- END .five columns -->
			</div>

		<?php } else { ?>

			<!-- BEGIN .sixteen columns -->
			<div class="sixteen columns">

			<div class="postarea full">
				<h1 class="headline"><?php esc_html_e( 'Not Found, Error 404', 'givingpress-lite' ); ?></h1>
				<p><?php esc_html_e( 'The page you are looking for no longer exists. Perhaps searching can help.', 'givingpress-lite' ); ?></p>
				<div class="no-result-search"><?php get_template_part( 'searchform' ); ?></div>
			</div>

			<!-- END .sixteen columns -->
			</div>

		<?php } ?>

	<!-- END .content -->
	</div>

<!-- END .row -->
</div>

<?php get_footer(); ?>
