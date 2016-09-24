<?php
/**
 * The template part for displaying a message that posts cannot be found
 */
?>

	
	<article>
        <header class="article-intro">
			<h2><?php _e( 'Nothing Found', 'handdrawn-lite' ); ?></h2>
        </header><!-- end .article-intro -->
        <div class="row">
            <div class="article-container large-12 columns">
                <div class="article-content">
                    <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		
					<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'handdrawn-lite' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		
				<?php elseif ( is_search() ) : ?>
		
					<p><?php _e( 'Sorry, but nothing matched your search terms.', 'handdrawn-lite' ); ?></p>
					
		
				<?php else : ?>
		
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. ', 'handdrawn-lite' ); ?></p>
					
				<?php endif; ?>
                </div>
            </div><!-- end .article-container -->
        </div><!-- end .row -->
    </article>
