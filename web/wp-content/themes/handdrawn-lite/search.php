<?php
/**
 *
 *  The template for displaying search pages.
 */

    get_header();
?>
    <div class="row">
        <div id="content" class="medium-12 large-8 <?php if ( ! is_active_sidebar( 'sidebar-1' ) ) { ?>large-centered <?php } ?>columns" role="main">
            <header class="handy">
                <h2><?php printf( __( 'Search Results for: %s', 'handdrawn-lite' ), get_search_query() ); ?></h2>
            </header>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
				get_template_part( 'content', get_post_format() ); 
            endwhile; ?>
            <div class="clear"></div>
            <?php $pagination = get_the_posts_pagination();
			if ($pagination){ ?>
				<div class="pagination">
					<?php previous_posts_link( '<span class="previous handy">' . __( "Prev", "handdrawn-lite" ) . '</span>' ) ?>
					<?php next_posts_link( '<span class="next handy">' . __( "Next", "handdrawn-lite" ) . '</span>' ); ?>
				</div>
			<?php } ?>
            
            <?php // If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );
		endif;
	    ?>
            
        </div>
        <!-- End Main Column -->
		<?php get_sidebar(); ?>
        <?php get_footer(); ?>