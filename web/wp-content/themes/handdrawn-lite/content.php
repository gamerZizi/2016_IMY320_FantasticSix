<?php

?>
    <article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="article-intro">
        <?php
        /**
         * Detect plugin
         */
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        
        // check for plugin using plugin name
        if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) and is_plugin_active( 'handdrawn-lite-features/handdrawn-lite-features.php' ) ) { 
            $clip_mask = get_field('clip_mask');
            $round_title = get_field('round_title');
            if($clip_mask == 'circle') {
                if( ! $round_title ){
                    handdrawn_article_intro_circle_clip();
                } else {
                    handdrawn_article_intro_circle_clip_textpath();
                }
            } else if($clip_mask == 'cloud') {
                if( ! $round_title ){
                    handdrawn_article_intro_cloud_clip();
                } else {
                    handdrawn_article_intro_cloud_clip_textpath();
                }
            } else if($clip_mask == 'flower') {
                if( ! $round_title ){
                    handdrawn_article_intro_flower_clip();
                } else {
                    handdrawn_article_intro_flower_clip_textpath();
                }
            } else {
                handdrawn_article_intro();
            }         
        }  else {
                    handdrawn_article_intro();
        }  ?>
        </header><!-- end .article-intro -->
        <div class="row">
            <div class="article-container large-12 columns">
                <div class="article-content">
                    <?php if( ! is_single() ) {
                        the_excerpt();
                    } else {
                        the_content(); ?>
                        <?php if ( current_user_can( 'edit_posts' ) ) { ?>
                            <p class="after-content">
                                <span class="handy edit"> <?php edit_post_link( __( 'Edit', 'handdrawn-lite' )); ?></span>
                            </p>
                        <?php }?>
                        
            
                    <?php }
                    wp_link_pages( array(
                        'before'      => '<div class="page-links handy "><span class="page-links-title">' . __( 'Pages:', 'handdrawn-lite' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        ) );
                    ?>
                </div>
            </div><!-- end .article-container -->
        </div><!-- end .row -->
    </article>