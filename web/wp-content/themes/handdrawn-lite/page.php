<?php
    get_header();
?>
    <div class="row">
        <div id="content" class="medium-12 large-8 <?php if ( ! is_active_sidebar( 'sidebar-1' ) ) { ?>large-centered <?php } ?>columns" role="main">
            <?php if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post(); 
            
                        get_template_part( 'content', 'page' ); ?>
                    <!-- Comments section -->
                    <?php // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || get_comments_number() ) :
                                comments_template();
                        endif; ?>
                    <!-- end Comments section -->
                    <?php
                    } // end while 
            } // end if ?>   
            
        </div>
        <!-- End Main Column -->
        <?php get_sidebar(); ?>
        <?php get_footer(); ?>