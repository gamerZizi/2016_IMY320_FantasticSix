<?php
    get_header();
?>
    <div class="row">
        <div id="content" class="medium-12 large-8 <?php if ( ! is_active_sidebar( 'sidebar-1' ) ) { ?>large-centered <?php } ?>columns" role="main">
            <?php if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post(); 
            
                        get_template_part( 'content', get_post_format() ); ?>
                    <!-- Comments section -->
                    <?php // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || get_comments_number() ) :
                                comments_template();
                        endif; ?>
                    <!-- end Comments section -->
                    <div class="prev-next-post handy">
                        <?php the_post_navigation( array(
                            'next_text' => '<p class="next-post"><span class="meta-nav" aria-hidden="true">' . __( 'Next Post: ', 'handdrawn-lite' ) . '</span>' .
                            '<span class="screen-reader-text">' . __( 'Next post:', 'handdrawn-lite' ) . '</span> ' .
                            '<span class="post-title">%title</span></p>',
                            'prev_text' => '<p class="previous-post"><span class="meta-nav" aria-hidden="true">' . __( ' Previous Post: ', 'handdrawn-lite' ) . '</span> ' .
                            '<span class="screen-reader-text">' . __( 'Previous post:', 'handdrawn-lite' ) . '</span> ' .
                            '<span class="post-title">%title</span></p>',
                        ) ); ?>
                    </div>
                    <?php
                    } // end while 
            } // end if ?>           
        </div><!-- End #content -->

    
        <?php get_sidebar(); ?>
        <?php get_footer(); ?>
