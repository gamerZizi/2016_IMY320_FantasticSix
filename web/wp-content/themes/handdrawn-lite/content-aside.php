<?php

?>
    <article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="article-intro">
        <?php handdrawn_article_intro(); ?>
        </header><!-- end .article-intro -->
        <div class="row handy">
            <div class="article-container large-12 columns">
                <div class="article-content">
                    <?php if( ! is_single() ) {
                        the_excerpt();
                    } else {
                        the_content();
                    }
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