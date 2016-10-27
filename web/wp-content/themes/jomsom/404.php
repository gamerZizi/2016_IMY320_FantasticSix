<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<?php if ( is_active_sidebar( 'sidebar-notfound' ) ) :

						dynamic_sidebar( 'sidebar-notfound' );

					else : ?>
					<header class="page-header">
						<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'jomsom' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content">
						<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'jomsom' ); ?></p>

						<?php get_search_form(); ?>

					</div><!-- .page-content -->
				<?php endif; ?>
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>